<?php

require_once dirname(__DIR__) . "/core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Fournisseur.php";

class FournisseurRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
    }

   
    public function getAllFournisseur(): array
    {
        $sql = "SELECT * FROM fournisseurs ORDER BY nom ASC";
        $results = Database::executeQuery($this->pdo, $sql, [], false);
        $fournisseurs = [];

        if (empty($results)) {
            return $fournisseurs;
        }

        foreach ($results as $fournisseur) {
            $fournisseurs[] = $this->transformerEnObjetFournisseur($fournisseur);
        }
        return $fournisseurs;
    }

   
    public function getFournisseurById(int $id): ?Fournisseur
    {
        $sql = "SELECT * FROM fournisseurs WHERE id = :id";
        $fournisseur = Database::executeQuery($this->pdo, $sql, [':id' => $id]);

        return $fournisseur ? $this->transformerEnObjetFournisseur($fournisseur) : null;
    }

   
    public function getFournisseurByTelephone(string $telephone): ?Fournisseur
    {
        $sql = "SELECT * FROM fournisseurs WHERE telephone = :telephone";
        $fournisseur = Database::executeQuery($this->pdo, $sql, [':telephone' => $telephone]);

        return $fournisseur ? $this->transformerEnObjetFournisseur($fournisseur) : null;
    }

   
    public function getFournisseurByEmail(string $email): ?Fournisseur
    {
        $sql = "SELECT * FROM fournisseurs WHERE email = :email";
        $fournisseur = Database::executeQuery($this->pdo, $sql, [':email' => $email], true);

        return $fournisseur ? $this->transformerEnObjetFournisseur($fournisseur) : null;
    }

   
    public function searchFournisseurs(string $terme): array
    {
        $sql = "SELECT * FROM fournisseurs 
                WHERE LOWER(nom) LIKE :terme 
                   OR telephone LIKE :terme 
                   OR LOWER(email) LIKE :terme 
                ORDER BY nom ASC";

        $results = Database::executeQuery($this->pdo, $sql, [':terme' => '%' . strtolower($terme) . '%'], false);
        $fournisseurs = [];

        if (empty($results)) {
            return $fournisseurs;
        }

        foreach ($results as $fournisseur) {
            $fournisseurs[] = $this->transformerEnObjetFournisseur($fournisseur);
        }
        return $fournisseurs;
    }

    
    public function saveFournisseur(Fournisseur $fournisseur): int
    {
        $sql = "INSERT INTO fournisseurs (nom, telephone, email, adresse) 
                VALUES (:nom, :telephone, :email, :adresse)";

        $params = [
            ':nom'       => $fournisseur->getNom(),
            ':telephone' => $fournisseur->getTelephone(),
            ':email'     => $fournisseur->getEmail(),
            ':adresse'   => $fournisseur->getAdresse()
        ];

        Database::executeUpdate($this->pdo, $sql, $params);
        $fournisseurId = (int) $this->pdo->lastInsertId();
        $fournisseur->setId($fournisseurId);
        return $fournisseurId;
    }

   
    public function updateFournisseur(Fournisseur $fournisseur): bool
    {
        $sql = "UPDATE fournisseurs 
                SET nom = :nom, 
                    telephone = :telephone, 
                    email = :email, 
                    adresse = :adresse 
                WHERE id = :id";

        $params = [
            ':id'        => $fournisseur->getId(),
            ':nom'       => $fournisseur->getNom(),
            ':telephone' => $fournisseur->getTelephone(),
            ':email'     => $fournisseur->getEmail(),
            ':adresse'   => $fournisseur->getAdresse()
        ];

        $nbrLignesModifiee = Database::executeUpdate($this->pdo, $sql, $params);
        return $nbrLignesModifiee >= 0;
    }

    
    public function deleteFournisseur(int $id): bool
    {
        $sql = "DELETE FROM fournisseurs WHERE id = :id";
        $nbrLignesSupprimee = Database::executeUpdate($this->pdo, $sql, [':id' => $id]);
        return $nbrLignesSupprimee > 0;
    }

   
    public function transformerEnObjetFournisseur(array $fournisseur): Fournisseur
    {
        return new Fournisseur(
            (int)($fournisseur['id'] ?? 0),
            (string)($fournisseur['nom'] ?? ''),
            (string)($fournisseur['telephone'] ?? ''),
            (string)($fournisseur['email'] ?? ''),
            (string)($fournisseur['adresse'] ?? '')
        );
    }
}
