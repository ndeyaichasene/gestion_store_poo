<?php

class ModePaiement
{
    // Attributs
    private static int $id;
    private static string $nom;

    // Constructeur
    private function __construct(int $id = 0, string $nom = "")
    {
        self::$id = $id;
        self::$nom = $nom;
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

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setNom(string $nom): void
    {
        self::$nom = $nom;
    }
}