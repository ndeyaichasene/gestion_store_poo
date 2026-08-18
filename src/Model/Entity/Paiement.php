<?php

require_once __DIR__ . "/Dette.php";
require_once __DIR__ . "/Utilisateur.php";
require_once __DIR__ . "/ModePaiement.php";

class Paiement
{
    // Attributs
    private static int $id;
    private static float $montant;
    private static string $statut;
    private static DateTime $date_paiement;
    private static ?Dette $dette;
    private static ?Utilisateur $utilisateur;
    private static ?ModePaiement $mode_paiement;

    // Constructeur
    private function __construct(
        int $id = 0,
        float $montant = 0.0,
        string $statut = "VALIDE",
        ?DateTime $date_paiement = null,
        ?Dette $dette = null,
        ?Utilisateur $utilisateur = null,
        ?ModePaiement $mode_paiement = null
    ) {
        self::$id = $id;
        self::$montant = $montant;
        self::$statut = !empty($statut) ? $statut : "VALIDE";
        self::$date_paiement = $date_paiement ?? new DateTime();
        self::$dette = $dette;
        self::$utilisateur = $utilisateur;
        self::$mode_paiement = $mode_paiement;
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getMontant(): float
    {
        return self::$montant;
    }

    public static function getStatut(): string
    {
        return self::$statut;
    }

    public static function getDatePaiement(): DateTime
    {
        return self::$date_paiement;
    }

    public static function getDette(): ?Dette
    {
        return self::$dette;
    }

    public static function getUtilisateur(): ?Utilisateur
    {
        return self::$utilisateur;
    }

    public static function getModePaiement(): ?ModePaiement
    {
        return self::$mode_paiement;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setMontant(float $montant): void
    {
        self::$montant = max(0.0, $montant);
    }

    public static function setStatut(string $statut): void
    {
        self::$statut = $statut;
    }

    public static function setDatePaiement(DateTime $date_paiement): void
    {
        self::$date_paiement = $date_paiement;
    }

    public static function setDette(?Dette $dette): void
    {
        self::$dette = $dette;
    }

    public static function setUtilisateur(?Utilisateur $utilisateur): void
    {
        self::$utilisateur = $utilisateur;
    }

    public static function setModePaiement(?ModePaiement $mode_paiement): void
    {
        self::$mode_paiement = $mode_paiement;
    }

    // Méthodes métiers
    public static function isValide(): bool
    {
        return self::$statut === 'VALIDE';
    }

    public static function isAnnule(): bool
    {
        return self::$statut === 'ANNULE';
    }

    public static function valider(): void
    {
        self::$statut = 'VALIDE';
    }

    public static function annuler(): void
    {
        self::$statut = 'ANNULE';
    }
}