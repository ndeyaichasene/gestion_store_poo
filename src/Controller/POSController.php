<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Core/SessionManager.php";
require_once dirname(__DIR__) . "/Repository/ProduitRepository.php";
require_once dirname(__DIR__) . "/Repository/ClientRepository.php";
require_once dirname(__DIR__) . "/Repository/VenteRepository.php";
require_once dirname(__DIR__) . "/Service/VenteService.php";

class POSController
{
    private PDO $pdo;
    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private VenteRepository $venteRepository;
    private VenteService $venteService;

    public function __construct(?PDO $pdo = null)
    {
        SessionManager::start();

        $this->pdo = $pdo ?? Database::connexionDB();
        $this->produitRepository = new ProduitRepository($this->pdo);
        $this->clientRepository = new ClientRepository($this->pdo);
        $this->venteRepository = new VenteRepository($this->pdo);
        $this->venteService = new VenteService($this->pdo);

        // Initialisation du panier en session s'il n'existe pas
        if (!SessionManager::has('pos_cart') || !is_array(SessionManager::get('pos_cart'))) {
            SessionManager::set('pos_cart', []);
        }
    }

    
    public function index(): void
    {
        $action = $_POST['action'] ?? $_GET['action'] ?? null;

        
        if ($action) {
            switch ($action) {
                case 'add_to_cart':
                    $this->ajouterAuPanier();
                    break;
                case 'remove_from_cart':
                    $this->supprimerDuPanier();
                    break;
                case 'clear_cart':
                    $this->viderLePanier();
                    break;
                case 'create_order':
                    $this->validerLaVente();
                    break;
            }
        }

        // Récupération des données pour l'affichage
        $produits = $this->produitRepository->getAllProduit();
        $clients = $this->clientRepository->getAllClient();
        $ventes = $this->venteRepository->getAllVente();

        $ventesAvecLignes = [];
        foreach ($ventes as $vente) {
            $lignes = $this->venteRepository->getLignesVente($vente->getId());
            $ventesAvecLignes[] = [
                'vente'  => $vente,
                'lignes' => $lignes
            ];
        }

        // Panier courant en session
        $panier = SessionManager::get('pos_cart', []);
        $totalPanier = 0.0;
        foreach ($panier as $item) {
            $totalPanier += $item['sous_total'];
        }

        // Statistiques
        $stats = $this->venteRepository->getPosStats();

        // Messages Flash
        $success = SessionManager::get('flash_success');
        $error = SessionManager::get('flash_error');
        SessionManager::unset('flash_success');
        SessionManager::unset('flash_error');

        // Inclusion de la vue POS
        require dirname(__DIR__, 2) . "/views/pos/index.php";
    }

  
    private function ajouterAuPanier(): void
    {
        try {
            $clientId = (int) ($_POST['client_id'] ?? 0);
            if ($clientId > 0) {
                SessionManager::set('selected_client_id', $clientId);
            }

            $produitId = (int) ($_POST['produit_id'] ?? 0);
            $quantite = (int) ($_POST['quantite'] ?? 1);

            if ($produitId <= 0 || $quantite <= 0) {
                throw new Exception("Veuillez sélectionner un produit et une quantité valide.");
            }

            $produit = $this->produitRepository->getProduitById($produitId);
            if (!$produit) {
                throw new Exception("Produit introuvable.");
            }

            $panier = SessionManager::get('pos_cart', []);
            $qteActuelle = $panier[$produitId]['quantite'] ?? 0;
            $qteTotale = $qteActuelle + $quantite;

            if ($qteTotale > $produit->getQteStock()) {
                throw new Exception("Stock insuffisant pour '{$produit->getLibelle()}'. Stock disponible : {$produit->getQteStock()}.");
            }

            $panier[$produitId] = [
                'id'            => $produit->getId(),
                'libelle'       => $produit->getLibelle(),
                'prix_unitaire' => $produit->getPrixVente(),
                'quantite'      => $qteTotale,
                'sous_total'    => $qteTotale * $produit->getPrixVente()
            ];

            SessionManager::set('pos_cart', $panier);
            SessionManager::set('flash_success', "Article '{$produit->getLibelle()}' ajouté au panier.");

        } catch (Throwable $e) {
            SessionManager::set('flash_error', $e->getMessage());
        }

        header("Location: ?view=pos");
        exit;
    }

   
    private function supprimerDuPanier(): void
    {
        $produitId = (int) ($_GET['produit_id'] ?? 0);
        $panier = SessionManager::get('pos_cart', []);

        if (isset($panier[$produitId])) {
            unset($panier[$produitId]);
            SessionManager::set('pos_cart', $panier);
            SessionManager::set('flash_success', "Article retiré du panier.");
        }

        header("Location: ?view=pos");
        exit;
    }


    private function viderLePanier(): void
    {
        SessionManager::set('pos_cart', []);
        SessionManager::set('flash_success', "Panier réinitialisé.");

        header("Location: ?view=pos");
        exit;
    }

  
    private function validerLaVente(): void
    {
        try {
            $clientId = (int) ($_POST['client_id'] ?? SessionManager::get('selected_client_id', 0));
            $montantVerse = (float) ($_POST['montant_verse'] ?? 0.0);
            $utilisateurId = SessionManager::get('user_id', 2);
            $panier = SessionManager::get('pos_cart', []);

            if ($clientId <= 0) {
                throw new Exception("Veuillez sélectionner un client acheteur.");
            }

            if (empty($panier)) {
                throw new Exception("Votre panier est vide. Veuillez ajouter au moins un article.");
            }

            // Alimentation du service métier avec les lignes du panier en session
            $this->venteService->viderPanier();
            foreach ($panier as $produitId => $item) {
                $this->venteService->ajouterArticle((int)$produitId, (int)$item['quantite']);
            }

            // Validation de la vente
            $vente = $this->venteService->validerVente($clientId, $utilisateurId, $montantVerse);

            // Vider le panier après succès et réinitialiser le client sélectionné
            SessionManager::set('pos_cart', []);
            SessionManager::unset('selected_client_id');
            SessionManager::set('flash_success', "Vente {$vente->getReference()} validée avec succès ! Montant total : " . number_format($vente->getMontantTotal(), 0, ',', ' ') . " FCFA.");

        } catch (Throwable $e) {
            SessionManager::set('flash_error', "Erreur lors de la vente : " . $e->getMessage());
        }

        header("Location: ?view=pos");
        exit;
    }
}
