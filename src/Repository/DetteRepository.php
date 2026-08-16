<?php

require_once dirname(__DIR__) . "/Core/Database.php";
require_once dirname(__DIR__) . "/Model/Entity/Dette.php";
require_once dirname(__DIR__) . "/Model/Entity/Paiement.php";
require_once dirname(__DIR__) . "/Model/Entity/Client.php";
require_once dirname(__DIR__) . "/Model/Entity/Vente.php";
require_once dirname(__DIR__) . "/Model/Entity/ModePaiement.php";
require_once dirname(__DIR__) . "/Model/Entity/Utilisateur.php";

class DetteRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::connexionDB();
    }

    /**
     * Récupère toutes les dettes avec leurs clients et ventes associées
     */
    public function getAllDettes(?string $statut = null): array
    {
        $sql = "SELECT d.*, 
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, 
                       c.adresse AS client_adresse, c.solde_dette AS client_solde_dette, c.limite_credit AS client_limite_credit,
                       v.id AS vente_id, v.reference AS vente_reference, v.montant_total AS vente_montant_total,
                       v.montant_verse AS vente_montant_verse, v.statut AS vente_statut, v.date_creation AS vente_date_creation
                FROM dettes d
                INNER JOIN clients c ON d.client_id = c.id
                INNER JOIN ventes v ON d.vente_id = v.id";

        $params = [];
        if ($statut !== null && $statut !== '') {
            $sql .= " WHERE d.statut = :statut";
            $params[':statut'] = $statut;
        }

        $sql .= " ORDER BY d.id DESC";

        $results = Database::executeQuery($this->pdo, $sql, $params, false);
        $dettes = [];

        if (empty($results)) {
            return $dettes;
        }

        foreach ($results as $data) {
            $dettes[] = $this->transformerEnObjetDette($data);
        }

        return $dettes;
    }

    /**
     * Récupère une dette par son identifiant unique
     */
    public function getDetteById(int $id): ?Dette
    {
        $sql = "SELECT d.*, 
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, 
                       c.adresse AS client_adresse, c.solde_dette AS client_solde_dette, c.limite_credit AS client_limite_credit,
                       v.id AS vente_id, v.reference AS vente_reference, v.montant_total AS vente_montant_total,
                       v.montant_verse AS vente_montant_verse, v.statut AS vente_statut, v.date_creation AS vente_date_creation
                FROM dettes d
                INNER JOIN clients c ON d.client_id = c.id
                INNER JOIN ventes v ON d.vente_id = v.id
                WHERE d.id = :id";

        $data = Database::executeQuery($this->pdo, $sql, [':id' => $id]);

        return $data ? $this->transformerEnObjetDette($data) : null;
    }

    /**
     * Récupère les dettes d'un client spécifique
     */
    public function getDettesByClientId(int $clientId): array
    {
        $sql = "SELECT d.*, 
                       c.id AS client_id, c.nom AS client_nom, c.prenom AS client_prenom, c.telephone AS client_telephone, 
                       c.adresse AS client_adresse, c.solde_dette AS client_solde_dette, c.limite_credit AS client_limite_credit,
                       v.id AS vente_id, v.reference AS vente_reference, v.montant_total AS vente_montant_total,
                       v.montant_verse AS vente_montant_verse, v.statut AS vente_statut, v.date_creation AS vente_date_creation
                FROM dettes d
                INNER JOIN clients c ON d.client_id = c.id
                INNER JOIN ventes v ON d.vente_id = v.id
                WHERE d.client_id = :client_id
                ORDER BY d.id DESC";

        $results = Database::executeQuery($this->pdo, $sql, [':client_id' => $clientId], false);
        $dettes = [];

        if (empty($results)) {
            return $dettes;
        }

        foreach ($results as $data) {
            $dettes[] = $this->transformerEnObjetDette($data);
        }

        return $dettes;
    }

    /**
     * Récupère l'historique des paiements d'une dette
     */
    public function getPaiementsByDetteId(int $detteId): array
    {
        $sql = "SELECT p.*,
                       mp.id AS mode_paiement_id, mp.nom AS mode_paiement_nom,
                       u.id AS user_id, u.nom AS user_nom, u.prenom AS user_prenom, u.email AS user_email
                FROM paiements p
                INNER JOIN modes_paiement mp ON p.mode_paiement_id = mp.id
                INNER JOIN utilisateurs u ON p.utilisateur_id = u.id
                WHERE p.dette_id = :dette_id
                ORDER BY p.id DESC";

        $results = Database::executeQuery($this->pdo, $sql, [':dette_id' => $detteId], false);
        $paiements = [];

        if (empty($results)) {
            return $paiements;
        }

        foreach ($results as $data) {
            $paiements[] = $this->transformerEnObjetPaiement($data);
        }

        return $paiements;
    }

    /**
     * Enregistre une nouvelle dette
     */
    public function saveDette(Dette $dette): int
    {
        $sql = "INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, vente_id, client_id)
                VALUES (:montant_initial, :montant_paye, :montant_restant, :statut, :vente_id, :client_id)";

        return Database::executeUpdate($this->pdo, $sql, [
            ':montant_initial' => $dette->getMontantInitial(),
            ':montant_paye'    => $dette->getMontantPaye(),
            ':montant_restant' => $dette->getMontantRestant(),
            ':statut'          => $dette->getStatut(),
            ':vente_id'        => $dette->getVente() ? $dette->getVente()->getId() : null,
            ':client_id'       => $dette->getClient() ? $dette->getClient()->getId() : null
        ]);
    }

    /**
     * Met à jour l'état financier et le statut d'une dette
     */
    public function updateDette(Dette $dette): int
    {
        $sql = "UPDATE dettes 
                SET montant_paye = :montant_paye,
                    montant_restant = :montant_restant,
                    statut = :statut
                WHERE id = :id";

        return Database::executeUpdate($this->pdo, $sql, [
            ':montant_paye'    => $dette->getMontantPaye(),
            ':montant_restant' => $dette->getMontantRestant(),
            ':statut'          => $dette->getStatut(),
            ':id'              => $dette->getId()
        ]);
    }

    /**
     * Enregistre un nouveau paiement sur une dette
     */
    public function savePaiement(Paiement $paiement): int
    {
        $sql = "INSERT INTO paiements (montant, statut, dette_id, utilisateur_id, mode_paiement_id)
                VALUES (:montant, :statut, :dette_id, :utilisateur_id, :mode_paiement_id)";

        return Database::executeUpdate($this->pdo, $sql, [
            ':montant'          => $paiement->getMontant(),
            ':statut'           => $paiement->getStatut(),
            ':dette_id'         => $paiement->getDette() ? $paiement->getDette()->getId() : null,
            ':utilisateur_id'   => $paiement->getUtilisateur() ? $paiement->getUtilisateur()->getId() : 2,
            ':mode_paiement_id' => $paiement->getModePaiement() ? $paiement->getModePaiement()->getId() : 1
        ]);
    }

    /**
     * Statistiques financières globales sur les dettes
     */
    public function getStatsDettes(): array
    {
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN statut = 'EN_COURS' THEN montant_restant ELSE 0 END), 0) AS creances_actives,
                    COUNT(DISTINCT CASE WHEN statut = 'EN_COURS' THEN client_id END) AS clients_debiteurs,
                    COALESCE(SUM(montant_paye), 0) AS total_recouvrements,
                    COUNT(*) AS total_dettes
                FROM dettes";

        $stats = Database::executeQuery($this->pdo, $sql, []);

        return [
            'creances_actives'    => (float) ($stats['creances_actives'] ?? 0),
            'clients_debiteurs'   => (int) ($stats['clients_debiteurs'] ?? 0),
            'total_recouvrements' => (float) ($stats['total_recouvrements'] ?? 0),
            'total_dettes'        => (int) ($stats['total_dettes'] ?? 0),
        ];
    }

    /**
     * Hydratation d'un enregistrement SQL vers l'entité Dette
     */
    public function transformerEnObjetDette(array $data): Dette
    {
        $client = new Client();
        $client->setId((int) ($data['client_id'] ?? 0));
        $client->setNom((string) ($data['client_nom'] ?? ''));
        $client->setPrenom((string) ($data['client_prenom'] ?? ''));
        $client->setTelephone((string) ($data['client_telephone'] ?? ''));
        $client->setAdresse((string) ($data['client_adresse'] ?? ''));
        $client->setSoldeDette((float) ($data['client_solde_dette'] ?? 0.0));
        $client->setLimiteCredit((float) ($data['client_limite_credit'] ?? 0.0));

        $vente = new Vente();
        $vente->setId((int) ($data['vente_id'] ?? 0));
        $vente->setReference((string) ($data['vente_reference'] ?? ''));
        $vente->setMontantTotal((float) ($data['vente_montant_total'] ?? 0.0));
        $vente->setMontantVerse((float) ($data['vente_montant_verse'] ?? 0.0));
        $vente->setStatut((string) ($data['vente_statut'] ?? ''));
        if (!empty($data['vente_date_creation'])) {
            try {
                $vente->setDateCreation(new DateTime($data['vente_date_creation']));
            } catch (Exception $e) {
                $vente->setDateCreation(new DateTime());
            }
        }
        $vente->setClient($client);

        $dateCreation = !empty($data['date_creation']) ? new DateTime($data['date_creation']) : new DateTime();

        return new Dette(
            (int) ($data['id'] ?? 0),
            (float) ($data['montant_initial'] ?? 0.0),
            (float) ($data['montant_paye'] ?? 0.0),
            (float) ($data['montant_restant'] ?? 0.0),
            (string) ($data['statut'] ?? 'EN_COURS'),
            $dateCreation,
            $vente,
            $client
        );
    }

    /**
     * Hydratation d'un enregistrement SQL vers l'entité Paiement
     */
    public function transformerEnObjetPaiement(array $data): Paiement
    {
        $user = new Utilisateur();
        $user->setId((int) ($data['user_id'] ?? 0));
        $user->setNom((string) ($data['user_nom'] ?? ''));
        $user->setPrenom((string) ($data['user_prenom'] ?? ''));
        $user->setEmail((string) ($data['user_email'] ?? ''));

        $modePaiement = new ModePaiement(
            (int) ($data['mode_paiement_id'] ?? 0),
            (string) ($data['mode_paiement_nom'] ?? 'Espèces')
        );

        $datePaiement = !empty($data['date_paiement']) ? new DateTime($data['date_paiement']) : new DateTime();

        return new Paiement(
            (int) ($data['id'] ?? 0),
            (float) ($data['montant'] ?? 0.0),
            (string) ($data['statut'] ?? 'VALIDE'),
            $datePaiement,
            null,
            $user,
            $modePaiement
        );
    }
}
