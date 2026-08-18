<?php

require_once __DIR__ . "/Client.php";
require_once __DIR__ . "/Vente.php";

class Dette
{
    // Attributs
    private static int $id;
    private static float $montant_initial;
    private static float $montant_paye;
    private static float $montant_restant;
    private static string $statut;
    private static DateTime $date_creation;
    private static ?Vente $vente;
    private static ?Client $client;

    // Constructeur
    public function __construct(
        int $id = 0,
        float $montant_initial = 0.0,
        float $montant_paye = 0.0,
        float $montant_restant = 0.0,
        string $statut = "",
        ?DateTime $date_creation = null,
        ?Vente $vente = null,
        ?Client $client = null
    ) {
        self::$id = $id;
        self::$montant_initial = $montant_initial;
        self::$montant_paye = $montant_paye;
        self::$montant_restant = ($montant_restant > 0.0 || $montant_initial === 0.0)
            ? $montant_restant
            : max(0.0, $montant_initial - $montant_paye);
        self::$statut = !empty($statut) ? $statut : (self::$montant_restant <= 0 ? 'SOLDEE' : 'EN_COURS');
        self::$date_creation = $date_creation ?? new DateTime();
        self::$vente = $vente;
        self::$client = $client;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getMontantInitial(): float
    {
        return $this->montant_initial;
    }

    public function getMontantPaye(): float
    {
        return $this->montant_paye;
    }

    public function getMontantRestant(): float
    {
        return $this->montant_restant;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateCreation(): DateTime
    {
        return $this->date_creation;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setMontantInitial(float $montant_initial): void
    {
        $this->montant_initial = max(0.0, $montant_initial);
    }

    public function setMontantPaye(float $montant_paye): void
    {
        $this->montant_paye = max(0.0, $montant_paye);
    }

    public function setMontantRestant(float $montant_restant): void
    {
        $this->montant_restant = max(0.0, $montant_restant);
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDateCreation(DateTime $date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    public function setVente(?Vente $vente): void
    {
        $this->vente = $vente;
    }

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    // Méthodes métiers
    public function isSoldee(): bool
    {
        return $this->statut === 'SOLDEE' || $this->montant_restant <= 0;
    }

    public function isEnCours(): bool
    {
        return $this->statut === 'EN_COURS' && $this->montant_restant > 0;
    }

    public function enregistrerPaiement(float $montant): void
    {
        if ($montant > 0) {
            $this->montant_paye += $montant;
            $this->montant_restant = max(0.0, $this->montant_initial - $this->montant_paye);
            if ($this->montant_restant <= 0) {
                $this->statut = 'SOLDEE';
            }
        }
    }
}