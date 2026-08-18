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
    private function __construct(
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
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getMontantInitial(): float
    {
        return self::$montant_initial;
    }

    public static function getMontantPaye(): float
    {
        return self::$montant_paye;
    }

    public static function getMontantRestant(): float
    {
        return self::$montant_restant;
    }

    public static function getStatut(): string
    {
        return self::$statut;
    }

    public static function getDateCreation(): DateTime
    {
        return self::$date_creation;
    }

    public static function getVente(): ?Vente
    {
        return self::$vente;
    }

    public static function getClient(): ?Client
    {
        return self::$client;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setMontantInitial(float $montant_initial): void
    {
        self::$montant_initial = max(0.0, $montant_initial);
    }

    public static function setMontantPaye(float $montant_paye): void
    {
        self::$montant_paye = max(0.0, $montant_paye);
    }

    public static function setMontantRestant(float $montant_restant): void
    {
        self::$montant_restant = max(0.0, $montant_restant);
    }

    public static function setStatut(string $statut): void
    {
        self::$statut = $statut;
    }

    public static function setDateCreation(DateTime $date_creation): void
    {
        self::$date_creation = $date_creation;
    }

    public static function setVente(?Vente $vente): void
    {
        self::$vente = $vente;
    }

    public static function setClient(?Client $client): void
    {
        self::$client = $client;
    }

    // Méthodes métiers
    public static function isSoldee(): bool
    {
        return self::$statut === 'SOLDEE' || self::$montant_restant <= 0;
    }

    public static function isEnCours(): bool
    {
        return self::$statut === 'EN_COURS' && self::$montant_restant > 0;
    }

    public static function enregistrerPaiement(float $montant): void
    {
        if ($montant > 0) {
            self::$montant_paye += $montant;
            self::$montant_restant = max(0.0, self::$montant_initial - self::$montant_paye);
            if (self::$montant_restant <= 0) {
                self::$statut = 'SOLDEE';
            }
        }
    }
}