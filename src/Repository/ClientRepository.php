<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Client.php";

class ClientRepository
{
    private static PDO $pdo;

    private  function __construct(?PDO $pdo = null)
    {
        self::$pdo = $pdo ?? Database::connexionDB();
    }

  
    private static function getAllClient(): array
    {
        $sql = "SELECT * FROM clients ORDER BY nom ASC, prenom ASC";
        $results = Database::executeQuery(self::$pdo, $sql, [], false);
        $clients = [];

        if (empty($results)) {
            return $clients;
        }

        foreach ($results as $client) {
            $clients[] = self::transformerEnObjetClient($client);
        }
        return $clients;
    }

  
    public static function getClientById(int $id): ?Client
    {
        $sql = "SELECT * FROM clients WHERE id = :id";
        $client = Database::executeQuery(self::$pdo, $sql, [':id' => $id]);

        return $client ? self::transformerEnObjetClient($client) : null;
    }

  
    public static function getClientByTelephone(string $telephone): ?Client
    {
        $sql = "SELECT * FROM clients WHERE telephone = :telephone";
        $client = Database::executeQuery(self::$pdo, $sql, [':telephone' => $telephone]);

        return $client ? self::transformerEnObjetClient($client) : null;
    }

  
    public static function getClientsAvecDettes(): array
    {
        $sql = "SELECT * FROM clients WHERE solde_dette > 0 ORDER BY solde_dette DESC";
        $results = Database::executeQuery(self::$pdo, $sql, [], false);
        $clients = [];

        if (empty($results)) {
            return $clients;
        }

        foreach ($results as $client) {
            $clients[] = self::transformerEnObjetClient($client);
        }
        return $clients;
    }

   
    public static function searchClients(string $terme): array
    {
        $sql = "SELECT * FROM clients 
                WHERE LOWER(nom) LIKE :terme 
                   OR LOWER(prenom) LIKE :terme 
                   OR telephone LIKE :terme 
                ORDER BY nom ASC";

        $results = Database::executeQuery(self::$pdo, $sql, [':terme' => '%' . strtolower($terme) . '%'], false);
        $clients = [];

        if (empty($results)) {
            return $clients;
        }

        foreach ($results as $client) {
            $clients[] = self::transformerEnObjetClient($client);
        }
        return $clients;
    }

    
    public static function saveClient(Client $client): int
    {
        $sql = "INSERT INTO clients (nom, prenom, adresse, telephone, solde_dette, limite_credit) 
                VALUES (:nom, :prenom, :adresse, :telephone, :solde_dette, :limite_credit)";

        $params = [
            ':nom'           => $client->getNom(),
            ':prenom'        => $client->getPrenom(),
            ':adresse'       => $client->getAdresse(),
            ':telephone'     => $client->getTelephone(),
            ':solde_dette'   => $client->getSoldeDette(),
            ':limite_credit' => $client->getLimiteCredit()
        ];

        Database::executeUpdate(self::$pdo, $sql, $params);
        $clientId = (int) self::$pdo->lastInsertId();
        $client->setId($clientId);
        return $clientId;
    }

    
    public static function updateClient(Client $client): bool
    {
        $sql = "UPDATE clients 
                SET nom = :nom, 
                    prenom = :prenom, 
                    adresse = :adresse, 
                    telephone = :telephone, 
                    solde_dette = :solde_dette, 
                    limite_credit = :limite_credit 
                WHERE id = :id";

        $params = [
            ':id'            => $client->getId(),
            ':nom'           => $client->getNom(),
            ':prenom'        => $client->getPrenom(),
            ':adresse'       => $client->getAdresse(),
            ':telephone'     => $client->getTelephone(),
            ':solde_dette'   => $client->getSoldeDette(),
            ':limite_credit' => $client->getLimiteCredit()
        ];

        $nbrLigneAffecte = Database::executeUpdate(self::$pdo, $sql, $params);
        return $nbrLigneAffecte > 0;
    }

   
    public static function updateSoldeDetteClient(int $id, float $nouveauSolde): bool
    {
        $sql = "UPDATE clients SET solde_dette = :solde_dette WHERE id = :id";
        $nbrLignesModifiee = Database::executeUpdate(self::$pdo, $sql, [
            ':id'          => $id,
            ':solde_dette' => max(0.0, $nouveauSolde)
        ]);
        return $nbrLignesModifiee > 0;
    }

    
    public static function deleteClient(int $id): bool
    {
        $sql = "DELETE FROM clients WHERE id = :id";
        $nbrLignesSupprimee = Database::executeUpdate(self::$pdo, $sql, [':id' => $id]);
        return $nbrLignesSupprimee > 0;
    }

   
    public static  function transformerEnObjetClient(array $client): Client
    {
        return new Client(
            (int)($client['id'] ?? 0),
            (string)($client['nom'] ?? ''),
            (string)($client['prenom'] ?? ''),
            (string)($client['telephone'] ?? ''),
            (string)($client['adresse'] ?? ''),
            (float)($client['solde_dette'] ?? 0.0),
            (float)($client['limite_credit'] ?? 0.0)
        );
    }
}
