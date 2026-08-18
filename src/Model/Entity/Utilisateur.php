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
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getNom(): string
    {
        return $this->nom;
    }

    public  function getPrenom(): string
    {
        return $this->prenom;
    }

    public  function getNomComplet(): string
    {
        return trim($this->prenom . " " . $this->nom);
    }

    public  function getEmail(): string
    {
        return $this->email;
    }

    public  function getPassword(): string
    {
        return $this->password;
    }

    public  function getAdresse(): string
    {
        return $this->adresse;
    }

    public  function getTelephone(): string
    {
        return $this->telephone;
    }

    public  function getRole(): ?Role
    {
        return $this->role;
    }

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public  function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public  function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public  function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public  function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public  function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public  function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public  function setRole(?Role $role): void
    {
        $this->role = $role;
    }

    // Méthodes métiers
    public  function isPassword(string $passwordValide): bool
    {
        return password_verify($passwordValide, $this->password);
    }

    public  function hasRole(string $nomRole): bool
    {
        return $this->role !== null && strtoupper($this->role->getNom()) === strtoupper($nomRole);
    }

    public  function isAdmin(): bool
    {
        return Utilisateur::hasRole('ADMIN');
    }

    public  function isVendeur(): bool
    {
        return Utilisateur::hasRole('VENTE') || Utilisateur::hasRole('VENDEUR');
    }

    public  function isStock(): bool
    {
        return Utilisateur::hasRole('STOCK');
    }

    public  function isInventaire(): bool
    {
        return Utilisateur::hasRole('INVENTAIRE');
    }
}