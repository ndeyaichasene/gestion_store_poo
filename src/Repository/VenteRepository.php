<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Vente.php";
require_once dirname(__DIR__) . "/Model/Entity/LigneVente.php";
require_once dirname(__DIR__) . "/Model/Entity/Client.php";
require_once dirname(__DIR__) . "/Model/Entity/Utilisateur.php";
require_once dirname(__DIR__) . "/Model/Entity/Produit.php";

class VenteRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
    }

  
    public function getAllVente(): array
    {
        $sql = "SELECT v.*, 
                       c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, 
                       c.adresse AS client_adresse, c.solde_dette AS client_solde_dette, c.limite_credit AS client_limite_credit,
                       u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
                FROM ventes v
                INNER JOIN clients c ON v.client_id = c.id
                INNER JOIN utilisateurs u ON v.utilisateur_id = u.id
                ORDER BY v.id DESC";

        $results = Database::executeQuery($this->pdo, $sql, [], false);
        $ventes = [];

        if (empty($results)) {
            return $ventes;
        }

        foreach ($results as $data) {
            $ventes[] = $this->transformerEnObjetVente($data);
        }

        return $ventes;
    }

    
    public function getVenteById(int $id): ?Vente
    {
        $sql = "SELECT v.*, 
                       c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, 
                       c.adresse AS client_adresse, c.solde_dette AS client_solde_dette, c.limite_credit AS client_limite_credit,
                       u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
                FROM ventes v
                INNER JOIN clients c ON v.client_id = c.id
                INNER JOIN utilisateurs u ON v.utilisateur_id = u.id
                WHERE v.id = :id";

        $vente = Database::executeQuery($this->pdo, $sql, [':id' => $id]);

        return $vente ? $this->transformerEnObjetVente($vente) : null;
    }

  
    public function getLignesVente(int $venteId): array
    {
        $sql = "SELECT lv.*, p.code AS produit_code, p.libelle AS produit_libelle, p.prix_vente AS produit_prix_vente
                FROM lignes_vente lv
                INNER JOIN produits p ON lv.produit_id = p.id
                WHERE lv.vente_id = :vente_id
                ORDER BY lv.id ASC";

        $results = Database::executeQuery($this->pdo, $sql, [':vente_id' => $venteId], false);
        $lignes = [];

        if (empty($results)) {
            return $lignes;
        }

        foreach ($results as $ligneProduitVendu) {
            $produit = new Produit();
            $produit->setId((int) $ligneProduitVendu['produit_id']);
            $produit->setCode($ligneProduitVendu['produit_code']);
            $produit->setLibelle($ligneProduitVendu['produit_libelle']);
            $produit->setPrixVente((float) $ligneProduitVendu['produit_prix_vente']);

            $ligne = new LigneVente();
            $ligne->setId((int) $ligneProduitVendu['id']);
            $ligne->setQuantite((int) $ligneProduitVendu['quantite']);
            $ligne->setPrixUnitaire((float) $ligneProduitVendu['prix_unitaire']);
            $ligne->setProduit($produit);

            $lignes[] = $ligne;
        }

        return $lignes;
    }

   
    public function getStats(): array
    {
        $sql = "SELECT 
                    COALESCE(SUM(montant_verse), 0) AS ca_encaisse_net,
                    (SELECT COALESCE(SUM(solde_dette), 0) FROM clients) AS encours_client_total,
                    COUNT(*) AS total_commandes
                FROM ventes";

        $res = Database::executeQuery($this->pdo, $sql);

        return [
            'ca_encaisse_net'     => (float) ($res['ca_encaisse_net'] ?? 0),
            'encours_client_total' => (float) ($res['encours_client_total'] ?? 0),
            'total_commandes'     => (int) ($res['total_commandes'] ?? 0)
        ];
    }

    public function getPosStats(): array
    {
        return $this->getStats();
    }

   
    public function transformerEnObjetVente(array $data): Vente
    {
        $client = new Client();
        $client->setId((int) $data['client_id']);
        $client->setNom($data['client_nom'] ?? '');
        $client->setPrenom($data['client_prenom'] ?? '');
        $client->setTelephone($data['client_telephone'] ?? '');
        $client->setAdresse($data['client_adresse'] ?? '');
        $client->setSoldeDette((float) ($data['client_solde_dette'] ?? 0));
        $client->setLimiteCredit((float) ($data['client_limite_credit'] ?? 0));

        $user = new Utilisateur();
        $user->setId((int) $data['utilisateur_id']);
        $user->setNom($data['user_nom'] ?? '');
        $user->setPrenom($data['user_prenom'] ?? '');
        $user->setEmail($data['user_email'] ?? '');

        $dateCreation = !empty($data['date_creation']) ? new DateTime($data['date_creation']) : new DateTime();

        $vente = new Vente();
        $vente->setId((int) $data['id']);
        $vente->setReference($data['reference']);
        $vente->setMontantTotal((float) $data['montant_total']);
        $vente->setMontantVerse((float) $data['montant_verse']);
        $vente->setStatut($data['statut']);
        $vente->setDateCreation($dateCreation);
        $vente->setClient($client);
        $vente->setUtilisateur($user);

        return $vente;
    }
}
