<?php

class Client
{
    // Attributs
    private static int $id;
    private static string $nom;
    private static string $prenom;
    private static string $telephone;
    private static string $adresse;
    private static float $solde_dette;
    private static float $limite_credit;

    // Constructeur
    private function __construct(
        int $id = 0,
        string $nom = "",
        string $prenom = "",
        string $telephone = "",
        string $adresse = "",
        float $solde_dette = 0.0,
        float $limite_credit = 0.0
    ) {
        self::$id = $id;
        self::$nom = $nom;
        self::$prenom = $prenom;
        self::$telephone = $telephone;
        self::$adresse = $adresse;
        self::$solde_dette = $solde_dette;
        self::$limite_credit = $limite_credit;
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getNom(): string
    {
        return self::$nom;
    }

    public static function getPrenom(): string
    {
        return self::$prenom;
    }

    public static function getNomComplet(): string
    {
        return trim(self::$prenom . " " . self::$nom);
    }

    public static function getTelephone(): string
    {
        return self::$telephone;
    }

    public static function getAdresse(): string
    {
        return self::$adresse;
    }

    public static function getSoldeDette(): float
    {
        return self::$solde_dette;
    }

    public function getLimiteCredit(): float
    {
        return self::$limite_credit;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setNom(string $nom): void
    {
        self::$nom = $nom;
    }

    public static function setPrenom(string $prenom): void
    {
        self::$prenom = $prenom;
    }

    public static function setTelephone(string $telephone): void
    {
        self::$telephone = $telephone;
    }

    public static function setAdresse(string $adresse): void
    {
        self::$adresse = $adresse;
    }

    public static function setSoldeDette(float $solde_dette): void
    {
        self::$solde_dette = max(0.0, $solde_dette);
    }

    public static function setLimiteCredit(float $limite_credit): void
    {
        self::$limite_credit = max(0.0, $limite_credit);
    }

    // Méthodes métiers
    public static function getCreditDisponible(): float
    {
        return max(0.0, self::$limite_credit - self::$solde_dette);
    }

    public static function peutPrendreCredit(float $montant): bool
    {
        if ($montant <= 0) {
            return true;
        }
        return (self::$solde_dette + $montant) <= self::$limite_credit;
    }

    public static function ajouterDette(float $montant): void
    {
        if ($montant > 0) {
            self::$solde_dette += $montant;
        }
    }

    public static function diminuerDette(float $montant): void
    {
        if ($montant > 0) {
            self::$solde_dette = max(0.0, self::$solde_dette - $montant);
        }
    }

    public static function hasDette(): bool
    {
        return self::$solde_dette > 0;
    }

    public static function isLimiteAtteinte(): bool
    {
        return self::$limite_credit > 0 && self::$solde_dette >= self::$limite_credit;
    }
}