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
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
}