<?php

require_once __DIR__ . "/Fournisseur.php";
require_once __DIR__ . "/Utilisateur.php";

class Approvisionnement
{
    // Attributs
    private static int $id;
    private static string $reference_bl;
    private static float $montant_total;
    private static string $statut;
    private static DateTime $date_appro;
    private static ?DateTime $date_reception;

    private static ?Fournisseur $fournisseur;
    private static ?Utilisateur $utilisateur;

    // Constructeur
    private function __construct(
        int $id = 0,
        string $reference_bl = "",
        float $montant_total = 0.0,
        string $statut = "EN_ATTENTE",
        ?DateTime $date_appro = null,
        ?DateTime $date_reception = null,
        ?Fournisseur $fournisseur = null,
        ?Utilisateur $utilisateur = null
    ) {
        self::$id = $id;
        self::$reference_bl = $reference_bl;
        self::$montant_total = $montant_total;
        self::$statut = !empty($statut) ? $statut : "EN_ATTENTE";
        self::$date_appro = $date_appro ?? new DateTime();
        self::$date_reception = $date_reception;
        self::$fournisseur = $fournisseur;
        self::$utilisateur = $utilisateur;
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getReferenceBl(): string
    {
        return self::$reference_bl;
    }

    public static function getMontantTotal(): float
    {
        return self::$montant_total;
    }

    public static function getStatut(): string
    {
        return self::$statut;
    }

    public static function getDateAppro(): DateTime
    {
        return self::$date_appro;
    }

    public static function getDateReception(): ?DateTime
    {
        return self::$date_reception;
    }

    public static function getFournisseur(): ?Fournisseur
    {
        return self::$fournisseur;
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

    public static function setReferenceBl(string $reference_bl): void
    {
        self::$reference_bl = $reference_bl;
    }

    public static function setMontantTotal(float $montant_total): void
    {
        self::$montant_total = max(0.0, $montant_total);
    }

    public static function setStatut(string $statut): void
    {
        self::$statut = $statut;
    }

    public static function setDateAppro(DateTime $date_appro): void
    {
        self::$date_appro = $date_appro;
    }

    public static function setDateReception(?DateTime $date_reception): void
    {
        self::$date_reception = $date_reception;
    }

    public static function setFournisseur(?Fournisseur $fournisseur): void
    {
        self::$fournisseur = $fournisseur;
    }

    public static function setUtilisateur(?Utilisateur $utilisateur): void
    {
        self::$utilisateur = $utilisateur;
    }

    // Méthodes métiers
    public static function isRecu(): bool
    {
        return self::$statut === 'RECUE';
    }

    public static function isEnAttente(): bool
    {
        return self::$statut === 'EN_ATTENTE';
    }

    public static function isAnnule(): bool
    {
        return self::$statut === 'ANNULE';
    }

    public static function validerReception(): void
    {
        self::$statut = 'RECUE';
        self::$date_reception = new DateTime();
    }

    public static function annuler(): void
    {
        self::$statut = 'ANNULE';
    }
}