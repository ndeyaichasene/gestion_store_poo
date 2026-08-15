<?php

class ModePaiement
{
    // Attributs
    private int $id;
    private string $nom;

    // Constructeur
    public function __construct(int $id = 0, string $nom = "")
    {
        $this->id = $id;
        $this->nom = $nom;
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