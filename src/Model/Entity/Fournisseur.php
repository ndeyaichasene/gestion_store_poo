<?php

class Fournisseur
{
    // Attributs
    private static int $id;
    private static string $nom;
    private static string $telephone;
    private static string $email;
    private static string $adresse;

    // Constructeur
    private function __construct( int $id = 0, string $nom = "", string $telephone = "", string $email = "", string $adresse = "")
     {
        Fournisseur::$id = $id;
        Fournisseur::$nom = $nom;
        Fournisseur::$telephone = $telephone;
        Fournisseur::$email = $email;
        Fournisseur::$adresse = $adresse;
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

    public static function getTelephone(): string
    {
        return self::$telephone;
    }

    public static function getEmail(): string
    {
        return self::$email;
    }

    public static function getAdresse(): string
    {
        return self::$adresse;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public function setNom(string $nom): void
    {
        self::$nom = $nom;
    }

    public function setTelephone(string $telephone): void
    {
        self::$telephone = $telephone;
    }

    public function setEmail(string $email): void
    {
        self::$email = $email;
    }

    public function setAdresse(string $adresse): void
    {
        self::$adresse = $adresse;
    }
}