<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Core/SessionManager.php";
require_once dirname(__DIR__) . "/Repository/ProduitRepository.php";
require_once dirname(__DIR__) . "/Repository/ClientRepository.php";
require_once dirname(__DIR__) . "/Repository/VenteRepository.php";
require_once dirname(__DIR__) . "/Service/VenteService.php";

class PosController
{
    private PDO $pdo;
    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private VenteRepository $venteRepository;
    private VenteService $venteService;

    public function __construct()
    {
        SessionManager::start();

        $this->pdo = Database::connexionDB();

        $this->produitRepository = new ProduitRepository($this->pdo);
        $this->clientRepository = new ClientRepository($this->pdo);
        $this->venteRepository = new VenteRepository($this->pdo);
        $this->venteService = new VenteService($this->pdo);

        if (!SessionManager::has('pos_cart')) {
            SessionManager::set('pos_cart', []);
        }
    }

    public function index(): void
    {
        // Produits
        $produits = $this->produitRepository->getAllProduit();

        // Clients
        $clients = $this->clientRepository->getAllClient();

        // Ventes
        $ventes = $this->venteRepository->getAllVente();

        // Lignes des ventes
        $ventesAvecLignes = [];

        foreach ($ventes as $vente) {
            $lignes = $this->venteRepository->getLignesVente(
                $vente->getId()
            );

            $ventesAvecLignes[] = [
                'vente' => $vente,
                'lignes' => $lignes
            ];
        }

        // Panier
        $panier = SessionManager::get('pos_cart', []);

        $totalPanier = 0;

        foreach ($panier as $article) {
            $totalPanier += $article['sous_total'];
        }

        // Statistiques
        $stats = $this->venteRepository->getPosStats();

        // Messages
        $success = SessionManager::get('flash_success');
        $error = SessionManager::get('flash_error');

        SessionManager::unset('flash_success');
        SessionManager::unset('flash_error');

        // Vue
        require dirname(__DIR__, 2) . "/views/pos/index.php";
    }


    public function addToCart(): void
    {
        try {

            $produitId = (int) $_POST['produit_id'];
            $quantite = (int) $_POST['quantite'];

            // Récupérer le produit
            $produit = $this->produitRepository->getProduitById($produitId);

            if (!$produit) {
                throw new Exception("Produit introuvable.");
            }

            // Récupérer le panier
            $panier = SessionManager::get('pos_cart', []);

            // Quantité déjà présente
            $ancienneQuantite = $panier[$produitId]['quantite'] ?? 0;

            $nouvelleQuantite = $ancienneQuantite + $quantite;

            // Vérifier le stock
            if ($nouvelleQuantite > $produit->getQteStock()) {
                throw new Exception("Stock insuffisant.");
            }

            // Ajouter au panier
            $panier[$produitId] = [
                'id' => $produit->getId(),
                'libelle' => $produit->getLibelle(),
                'prix_unitaire' => $produit->getPrixVente(),
                'quantite' => $nouvelleQuantite,
                'sous_total' => $nouvelleQuantite * $produit->getPrixVente()
            ];

            // Sauvegarder le panier
            SessionManager::set('pos_cart', $panier);

            SessionManager::set(
                'flash_success',
                "Produit ajouté au panier."
            );

        } catch (Throwable $e) {

            SessionManager::set(
                'flash_error',
                $e->getMessage()
            );
        }

        header("Location: ?view=pos");
        exit;
    }


    public function addVente(): void
    {
        try {

            $clientId = (int) $_POST['client_id'];
            $montantVerse = (float) $_POST['montant_verse'];

            $panier = SessionManager::get('pos_cart', []);

            if ($clientId <= 0) {
                throw new Exception("Veuillez sélectionner un client.");
            }

            if (empty($panier)) {
                throw new Exception("Le panier est vide.");
            }

            // Donner les articles au service
            $this->venteService->viderPanier();

            foreach ($panier as $produitId => $article) {

                $this->venteService->ajouterArticle(
                    (int) $produitId,
                    (int) $article['quantite']
                );
            }

            // Créer la vente
            $vente = $this->venteService->validerVente(
                $clientId,
                2,
                $montantVerse
            );

            // Vider le panier
            SessionManager::set('pos_cart', []);

            SessionManager::set(
                'flash_success',
                "Vente enregistrée avec succès."
            );

        } catch (Throwable $e) {

            SessionManager::set(
                'flash_error',
                $e->getMessage()
            );
        }

        header("Location: ?view=pos");
        exit;
    }
}