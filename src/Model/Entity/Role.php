<?php

class Role
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
    public function getId(): int
    {
        return self::$id;
    }

    public function getNom(): string
    {
        return self::$nom;
    }

    // Setters
    public function setId(int $id): void
    {
        self::$id = $id;
    }

    public function setNom(string $nom): void
    {
        self::$nom = $nom;
    }
}