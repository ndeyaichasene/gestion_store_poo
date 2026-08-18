<?php

require_once __DIR__ . "/Role.php";

class Utilisateur
{
    // Attributs
    private static int $id;
    private static string $nom;
    private static string $prenom;
    private static string $email;
    private static string $password;
    private static string $adresse;
    private static string $telephone;
    private static ?Role $role;

    // Constructeur
    private function __construct(
        string $nom = "",
        string $prenom = "",
        string $email = "",
        string $password = "",
        ?Role $role = null,
        string $adresse = "",
        string $telephone = "",
        int $id = 0
    ) {
        self::$id = $id;
        self::$nom = $nom;
        self::$prenom = $prenom;
        self::$email = $email;
        self::$password = $password;
        self::$role = $role;
        self::$adresse = $adresse;
        self::$telephone = $telephone;
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

    public static function getEmail(): string
    {
        return self::$email;
    }

    public static function getPassword(): string
    {
        return self::$password;
    }

    public static function getAdresse(): string
    {
        return self::$adresse;
    }

    public static function getTelephone(): string
    {
        return self::$telephone;
    }

    public static function getRole(): ?Role
    {
        return self::$role;
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

    public static function setEmail(string $email): void
    {
        self::$email = $email;
    }

    public static function setPassword(string $password): void
    {
        self::$password = $password;
    }

    public static function setAdresse(string $adresse): void
    {
        self::$adresse = $adresse;
    }

    public static function setTelephone(string $telephone): void
    {
        self::$telephone = $telephone;
    }

    public static function setRole(?Role $role): void
    {
        self::$role = $role;
    }

    // Méthodes métiers
    public static function isPassword(string $passwordValide): bool
    {
        return password_verify($passwordValide, self::$password);
    }

    public static function hasRole(string $nomRole): bool
    {
        return self::$role !== null && strtoupper(self::$role->getNom()) === strtoupper($nomRole);
    }

    public static function isAdmin(): bool
    {
        return Utilisateur::hasRole('ADMIN');
    }

    public static function isVendeur(): bool
    {
        return Utilisateur::hasRole('VENTE') || Utilisateur::hasRole('VENDEUR');
    }

    public static function isStock(): bool
    {
        return Utilisateur::hasRole('STOCK');
    }

    public static function isInventaire(): bool
    {
        return Utilisateur::hasRole('INVENTAIRE');
    }
}