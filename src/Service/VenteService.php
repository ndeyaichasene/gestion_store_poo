<?php

require_once dirname(__DIR__) . "/core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Vente.php";
require_once dirname(__DIR__) . "/Model/Entity/LigneVente.php";
require_once dirname(__DIR__) . "/Model/Entity/Produit.php";
require_once dirname(__DIR__) . "/Model/Entity/Client.php";
require_once dirname(__DIR__) . "/Model/Entity/Dette.php";
require_once dirname(__DIR__) . "/Repository/ProduitRepository.php";
require_once dirname(__DIR__) . "/Repository/ClientRepository.php";

class VenteService
{
    private PDO $pdo;
    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private array $panier = [];

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
        $this->produitRepository = new ProduitRepository($this->pdo);
        $this->clientRepository = new ClientRepository($this->pdo);
    }

   
    
     // Ajoute un article au panier ou incrémente sa quantité
    
    public function ajouterArticle(int $produitId, int $quantite = 1): void
    {
        if ($quantite <= 0) {
            throw new InvalidArgumentException("La quantité à ajouter doit être supérieure à zéro.");
        }

        $produit = $this->produitRepository->getProduitById($produitId);
        if (!$produit) {
            throw new Exception("Produit introuvable avec l'ID : " . $produitId);
        }

        $quantiteActuelleDansPanier = isset($this->panier[$produitId]) ? $this->panier[$produitId]['quantite'] : 0;
        $quantiteTotaleDemandee = $quantiteActuelleDansPanier + $quantite;

        if (!$produit->isDisponible($quantiteTotaleDemandee)) {
            throw new Exception(
                "Stock insuffisant pour le produit '{$produit->getLibelle()}'. " .
                "Stock disponible : {$produit->getQteStock()}, Quantité demandée : {$quantiteTotaleDemandee}."
            );
        }

        if (isset($this->panier[$produitId])) {
            $this->panier[$produitId]['quantite'] = $quantiteTotaleDemandee;
            $this->panier[$produitId]['sous_total'] = $quantiteTotaleDemandee * $this->panier[$produitId]['prix_unitaire'];
        } else {
            $this->panier[$produitId] = [
                'produit'       => $produit,
                'quantite'      => $quantite,
                'prix_unitaire' => $produit->getPrixVente(),
                'sous_total'    => $quantite * $produit->getPrixVente()
            ];
        }
    }

    
    //Modifie la quantité d'un article présent dans le panier
    
    public function modifierQuantite(int $produitId, int $nouvelleQuantite): void
    {
        if (!isset($this->panier[$produitId])) {
            throw new Exception("Le produit avec l'ID {$produitId} n'est pas présent dans le panier.");
        }

        if ($nouvelleQuantite <= 0) {
            $this->supprimerArticle($produitId);
            return;
        }

        $produit = $this->produitRepository->getProduitById($produitId);
        if (!$produit || !$produit->isDisponible($nouvelleQuantite)) {
            $stockDispo = $produit ? $produit->getQteStock() : 0;
            throw new Exception("Stock insuffisant pour définir la quantité à {$nouvelleQuantite}. Stock disponible : {$stockDispo}.");
        }

        $this->panier[$produitId]['quantite'] = $nouvelleQuantite;
        $this->panier[$produitId]['sous_total'] = $nouvelleQuantite * $this->panier[$produitId]['prix_unitaire'];
    }

    
    //Retire un article du panier
    
    public function supprimerArticle(int $produitId): void
    {
        unset($this->panier[$produitId]);
    }

    
    //Réinitialise le panier
    
    public function viderPanier(): void
    {
        $this->panier = [];
    }

    
    //Retourne les lignes du panier en cours
    
    public function getPanier(): array
    {
        return $this->panier;
    }

    
    //Calcule le montant total du panier
    
    public function calculerTotalPanier(): float
    {
        $total = 0.0;
        foreach ($this->panier as $ligne) {
            $total += $ligne['sous_total'];
        }
        return $total;
    }

    
    //Retourne le nombre total d'articles dans le panier
    public function getNombreArticles(): int
    {
        $totalArticles = 0;
        foreach ($this->panier as $ligne) {
            $totalArticles += $ligne['quantite'];
        }
        return $totalArticles;
    }


    
    //Valide et enregistre la vente sous transaction SQL atomique
     
    public function validerVente(int $clientId, int $utilisateurId, float $montantVerse, ?string $reference = null): Vente
    {
        // 1. Vérification du panier
        if (empty($this->panier)) {
            throw new Exception("Impossible de valider la vente : le panier est vide.");
        }

        $montantTotal = $this->calculerTotalPanier();
        if ($montantVerse < 0) {
            throw new InvalidArgumentException("Le montant versé ne peut pas être négatif.");
        }

        // 2. Vérification du client
        $client = $this->clientRepository->getClientById($clientId);
        if (!$client) {
            throw new Exception("Client introuvable avec l'ID : " . $clientId);
        }

        $montantRestant = max(0.0, $montantTotal - $montantVerse);

        // 3. Contrôle de la limite de crédit si vente à crédit ou avance
        if ($montantRestant > 0) {
            if (!$client->peutPrendreCredit($montantRestant)) {
                $creditDispo = $client->getCreditDisponible();
                throw new Exception(
                    "Validation impossible : Limite de crédit dépassée pour le client {$client->getNomComplet()}. " .
                    "Plafond restant : " . number_format($creditDispo, 2) . " FCFA, Montant à créditer : " . number_format($montantRestant, 2) . " FCFA."
                );
            }
        }

        // 4. Début de la Transaction SQL
        $this->pdo->beginTransaction();

        try {
            // A. Détermination du statut
            $statut = ($montantVerse >= $montantTotal)
                ? 'COMPTANT'
                : (($montantVerse > 0) ? 'AVANCE' : 'CREDIT_TOTAL');

            // B. Insertion initiale de l'en-tête de vente
            $refTemporaire = $reference ?? ('TMP-' . uniqid());

            $sqlVente = "INSERT INTO ventes (reference, montant_total, montant_verse, statut, client_id, utilisateur_id) 
                         VALUES (:reference, :montant_total, :montant_verse, :statut, :client_id, :utilisateur_id)";
            
            $stmtVente = $this->pdo->prepare($sqlVente);
            $stmtVente->execute([
                ':reference'      => $refTemporaire,
                ':montant_total'  => $montantTotal,
                ':montant_verse'  => $montantVerse,
                ':statut'         => $statut,
                ':client_id'      => $clientId,
                ':utilisateur_id' => $utilisateurId
            ]);

            $venteId = (int) $this->pdo->lastInsertId();

            // C. Définition et mise à jour de la référence finale : #CMD-{id}
            $refVente = $reference ?? $this->genererReferenceVente($venteId);

            if ($refVente !== $refTemporaire) {
                $stmtUpdateRef = $this->pdo->prepare("UPDATE ventes SET reference = :reference WHERE id = :id");
                $stmtUpdateRef->execute([
                    ':reference' => $refVente,
                    ':id'        => $venteId
                ]);
            }

            // D. Traitement de chaque ligne du panier
            $sqlLigne = "INSERT INTO lignes_vente (quantite, prix_unitaire, vente_id, produit_id) 
                         VALUES (:quantite, :prix_unitaire, :vente_id, :produit_id)";
            $stmtLigne = $this->pdo->prepare($sqlLigne);

            $sqlStock = "UPDATE produits 
                         SET qte_stock = qte_stock - :quantite 
                         WHERE id = :produit_id AND qte_stock >= :quantite";
            $stmtStock = $this->pdo->prepare($sqlStock);

            foreach ($this->panier as $produitId => $ligne) {
                // 1. Insertion ligne de vente
                $stmtLigne->execute([
                    ':quantite'      => $ligne['quantite'],
                    ':prix_unitaire' => $ligne['prix_unitaire'],
                    ':vente_id'      => $venteId,
                    ':produit_id'    => $produitId
                ]);

                // 2. Décrémentation atomique du stock avec vérification
                $stmtStock->execute([
                    ':quantite'   => $ligne['quantite'],
                    ':produit_id' => $produitId
                ]);

                if ($stmtStock->rowCount() === 0) {
                    throw new Exception("Rupture de stock détectée lors de l'enregistrement pour le produit ID {$produitId}.");
                }
            }

            // E. Gestion de la dette si impayé
            if ($montantRestant > 0) {
                // 1. Insertion de la dette
                $sqlDette = "INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, vente_id, client_id) 
                             VALUES (:montant_initial, :montant_paye, :montant_restant, :statut, :vente_id, :client_id)";
                $stmtDette = $this->pdo->prepare($sqlDette);
                $stmtDette->execute([
                    ':montant_initial' => $montantRestant,
                    ':montant_paye'    => 0.0,
                    ':montant_restant' => $montantRestant,
                    ':statut'          => 'EN_COURS',
                    ':vente_id'        => $venteId,
                    ':client_id'       => $clientId
                ]);

                // 2. Mise à jour du solde de dette du client
                $sqlClientDette = "UPDATE clients 
                                   SET solde_dette = solde_dette + :montant_dette 
                                   WHERE id = :client_id";
                $stmtClientDette = $this->pdo->prepare($sqlClientDette);
                $stmtClientDette->execute([
                    ':montant_dette' => $montantRestant,
                    ':client_id'     => $clientId
                ]);
            }

            // F. Validation de la transaction
            $this->pdo->commit();

            // G. Réinitialisation du panier
            $this->viderPanier();

            // H. Retour de l'objet Vente
            return new Vente(
                $venteId,
                $refVente,
                $montantTotal,
                $montantVerse,
                $statut,
                new DateTime(),
                $client,
                null
            );

        } catch (Throwable $e) {
            // Annulation complète en cas d'échec
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new Exception("Échec de la transaction de vente : " . $e->getMessage(), 0, $e);
        }
    }

    
    //Génère une référence pour la vente basée sur l'identifiant auto-incrémenté (#CMD-ID)
    
    private function genererReferenceVente(int $id): string
    {
        return '#CMD-' . $id;
    }
}
