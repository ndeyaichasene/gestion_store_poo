<?php

require_once dirname(__DIR__) . "/core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Produit.php";

class ProduitRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
    }

  
    public function getAllProduit(): array
    {
        $sql = "SELECT * FROM produits ORDER BY id ASC";
        $results = Database::executeQuery($this->pdo, $sql, [], false);
        $produits = [];

        if (empty($results)) {
            return $produits;
        }

        foreach ($results as $produit) {
            $produits[] = $this->transformerEnObjetProduit($produit);
        }
        return $produits;
    }

   
    public function getProduitById(int $id): ?Produit
    {
        $sql = "SELECT * FROM produits WHERE id = :id";
        $produit = Database::executeQuery($this->pdo, $sql, [':id' => $id]);

        return $produit ? $this->transformerEnObjetProduit($produit) : null;
    }

   
    public function getProduitByCode(string $code): ?Produit
    {
        $sql = "SELECT * FROM produits WHERE code = :code";
        $produit = Database::executeQuery($this->pdo, $sql, [':code' => $code]);

        return $produit ? $this->transformerEnObjetProduit($produit) : null;
    }

   
    public function getProduitsByCategorie(string $categorie): array
    {
        $sql = "SELECT * FROM produits WHERE categorie = :categorie ORDER BY libelle ASC";
        $results = Database::executeQuery($this->pdo, $sql, [':categorie' => $categorie], false);
        $produits = [];

        if (empty($results)) {
            return $produits;
        }

        foreach ($results as $produit) {
            $produits[] = $this->transformerEnObjetProduit($produit);
        }
        return $produits;
    }

   
    public function getProduitsEnAlerteStock(): array
    {
        $sql = "SELECT * FROM produits WHERE qte_stock <= seuil_alerte ORDER BY qte_stock ASC";
        $results = Database::executeQuery($this->pdo, $sql, [], false);
        $produits = [];

        if (empty($results)) {
            return $produits ;
        }

        foreach ($results as $produit) {
            $produits[] = $this->transformerEnObjetProduit($produit);
        }
        return $produits;
    }

  
    public function saveProduit(Produit $produit): int
    {
        $sql = "INSERT INTO produits (code, libelle, categorie, prix_achat, prix_vente, qte_stock, seuil_alerte) 
                VALUES (:code, :libelle, :categorie, :prix_achat, :prix_vente, :qte_stock, :seuil_alerte)";

        $params = [
            ':code'         => $produit->getCode(),
            ':libelle'      => $produit->getLibelle(),
            ':categorie'    => $produit->getCategorie(),
            ':prix_achat'   => $produit->getPrixAchat(),
            ':prix_vente'   => $produit->getPrixVente(),
            ':qte_stock'    => $produit->getQteStock(),
            ':seuil_alerte' => $produit->getSeuilAlerte()
        ];

        Database::executeUpdate($this->pdo, $sql, $params);
        $produitId = (int) $this->pdo->lastInsertId();
        $produit->setId($produitId);
        return $produitId;
    }

    
    public function updateProduit(Produit $produit): bool
    {
        $sql = "UPDATE produits 
                SET code = :code, 
                    libelle = :libelle, 
                    categorie = :categorie, 
                    prix_achat = :prix_achat, 
                    prix_vente = :prix_vente, 
                    qte_stock = :qte_stock, 
                    seuil_alerte = :seuil_alerte 
                WHERE id = :id";

        $params = [
            ':id'           => $produit->getId(),
            ':code'         => $produit->getCode(),
            ':libelle'      => $produit->getLibelle(),
            ':categorie'    => $produit->getCategorie(),
            ':prix_achat'   => $produit->getPrixAchat(),
            ':prix_vente'   => $produit->getPrixVente(),
            ':qte_stock'    => $produit->getQteStock(),
            ':seuil_alerte' => $produit->getSeuilAlerte()
        ];

        $nbrLigneAffecte = Database::executeUpdate($this->pdo, $sql, $params);
        return $nbrLigneAffecte > 0;
    }

    
    public function updateStockProduit(int $id, int $nouvelleQte): bool
    {
        $sql = "UPDATE produits SET qte_stock = :qte_stock WHERE id = :id";
        $nbrLignesModifiee = Database::executeUpdate($this->pdo, $sql, [
            ':id'        => $id,
            ':qte_stock' => max(0, $nouvelleQte)
        ]);
        return $nbrLignesModifiee > 0;
    }

   
    public function deleteProduitById(int $id): bool
    {
        $sql = "DELETE FROM produits WHERE id = :id";
        $nbrLignesSupprimee = Database::executeUpdate($this->pdo, $sql, [':id' => $id]);
        return $nbrLignesSupprimee > 0;
    }

   
    private function transformerEnObjetProduit(array $produit): Produit
    {
        return new Produit(
            (int)($produit['id'] ?? 0),
            (string)($produit['code'] ?? ''),
            (string)($produit['libelle'] ?? ''),
            (string)($produit['categorie'] ?? ''),
            (float)($produit['prix_achat'] ?? 0.0),
            (float)($produit['prix_vente'] ?? 0.0),
            (int)($produit['qte_stock'] ?? 0),
            (int)($produit['seuil_alerte'] ?? 10)
        );
    }
}
