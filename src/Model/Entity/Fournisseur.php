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
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getNom(): string
    {
        return $this->nom;
    }

    public  function getTelephone(): string
    {
        return $this->telephone;
    }

    public  function getEmail(): string
    {
        return $this->email;
    }

    public  function getAdresse(): string
    {
        return $this->adresse;
    }

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }
}