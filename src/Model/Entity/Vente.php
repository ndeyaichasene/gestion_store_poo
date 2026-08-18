<?php

require_once __DIR__ . "/Client.php";
require_once __DIR__ . "/Utilisateur.php";

class Vente
{
    // Attributs
    private static int $id;
    private static string $reference;
    private static float $montant_total;
    private static float $montant_verse;
    private static string $statut;
    private static DateTime $date_creation;

    private static ?Client $client;
    private static ?Utilisateur $utilisateur;

    // Constructeur
    private function __construct(
        int $id = 0,
        string $reference = "",
        float $montant_total = 0.0,
        float $montant_verse = 0.0,
        string $statut = "",
        ?DateTime $date_creation = null,
        ?Client $client = null,
        ?Utilisateur $utilisateur = null
    ) {
        self::$id = $id;
        self::$reference = $reference;
        self::$montant_total = $montant_total;
        self::$montant_verse = $montant_verse;
        self::$date_creation = $date_creation ?? new DateTime();
        self::$client = $client;
        self::$utilisateur = $utilisateur;
        self::$statut = !empty($statut) ? $statut : self::determinerStatut();
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getReference(): string
    {
        return self::$reference;
    }

    public static function getMontantTotal(): float
    {
        return self::$montant_total;
    }

    public static function getMontantVerse(): float
    {
        return self::$montant_verse;
    }

    public static function getStatut(): string
    {
        return self::$statut;
    }

    public static function getDateCreation(): DateTime
    {
        return self::$date_creation;
    }

    public static function getClient(): ?Client
    {
        return self::$client;
    }

    public static function getUtilisateur(): ?Utilisateur
    {
        return self::$utilisateur;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setReference(string $reference): void
    {
        self::$reference = $reference;
    }

    public static function setMontantTotal(float $montant_total): void
    {
        self::$montant_total = max(0.0, $montant_total);
    }

    public static function setMontantVerse(float $montant_verse): void
    {
        self::$montant_verse = max(0.0, $montant_verse);
    }

    public static function setStatut(string $statut): void
    {
        self::$statut = $statut;
    }

    public static function setDateCreation(DateTime $date_creation): void
    {
        self::$date_creation = $date_creation;
    }

    public static function setClient(?Client $client): void
    {
        self::$client = $client;
    }

    public static function setUtilisateur(?Utilisateur $utilisateur): void
    {
        self::$utilisateur = $utilisateur;
    }

    // Méthodes métiers
    public static function getMontantRestant(): float
    {
        return max(0.0, self::$montant_total - self::$montant_verse);
    }

    public static function isSoldee(): bool
    {
        return self::$montant_verse >= self::$montant_total;
    }

    public static function isCredit(): bool
    {
        return self::$montant_verse < self::$montant_total;
    }

    public static function determinerStatut(): string
    {
        if (self::$montant_verse >= self::$montant_total && self::$montant_total > 0) {
            return 'COMPTANT';
        } elseif (self::$montant_verse > 0) {
            return 'AVANCE';
        }
        return 'CREDIT_TOTAL';
    }

    public static function ajouterVersement(float $montant): void
    {
        if ($montant > 0) {
            self::$montant_verse += $montant;
            self::$statut = Vente::determinerStatut();
        }
    }
}