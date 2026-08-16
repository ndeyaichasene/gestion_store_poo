<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/ModePaiement.php";

class ModePaiementRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
    }

    /**
     * Récupère la liste de tous les modes de paiement disponibles.
     *
     * @return ModePaiement[]
     */
    public function getAllModePaiement(): array
    {
        $sql = "SELECT * FROM modes_paiement ORDER BY id ASC";
        $results = Database::executeQuery($this->pdo, $sql, [], false);
        $modes = [];

        if (empty($results)) {
            return $modes;
        }

        foreach ($results as $item) {
            $modes[] = new ModePaiement(
                (int)($item['id'] ?? 0),
                (string)($item['nom'] ?? '')
            );
        }

        return $modes;
    }

    /**
     * Récupère un mode de paiement par son identifiant.
     */
    public function getModePaiementById(int $id): ?ModePaiement
    {
        $sql = "SELECT * FROM modes_paiement WHERE id = :id";
        $res = Database::executeQuery($this->pdo, $sql, [':id' => $id]);

        if (!$res) {
            return null;
        }

        return new ModePaiement(
            (int)($res['id'] ?? 0),
            (string)($res['nom'] ?? '')
        );
    }
}
