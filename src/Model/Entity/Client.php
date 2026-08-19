<?php

class Client
{
    // Attributs
    private  int $id;
    private  string $nom;
    private  string $prenom;
    private  string $telephone;
    private  string $adresse;
    private  float $solde_dette;
    private  float $limite_credit;

    // Constructeur
    public function __construct(
        int $id = 0,
        string $nom = "",
        string $prenom = "",
        string $telephone = "",
        string $adresse = "",
        float $solde_dette = 0.0,
        float $limite_credit = 0.0
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->solde_dette = $solde_dette;
        $this->limite_credit = $limite_credit;
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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNomComplet(): string
    {
        return trim($this->prenom . " " . $this->nom);
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function getSoldeDette(): float
    {
        return $this->solde_dette;
    }

    public function getLimiteCredit(): float
    {
        return $this->limite_credit;
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

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function setTelephone(string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setSoldeDette(float $solde_dette): void
    {
        $this->solde_dette = max(0.0, $solde_dette);
    }

    public function setLimiteCredit(float $limite_credit): void
    {
        $this->limite_credit = max(0.0, $limite_credit);
    }

    // Méthodes métiers
    public  function getCreditDisponible(): float
    {
        return max(0.0, $this->limite_credit - $this->solde_dette);
    }

    public  function peutPrendreCredit(float $montant): bool
    {
        if ($montant <= 0) {
            return true;
        }
        return ($this->solde_dette + $montant) <= $this->limite_credit;
    }

    public  function ajouterDette(float $montant): void
    {
        if ($montant > 0) {
            $this->solde_dette += $montant;
        }
    }

    public  function diminuerDette(float $montant): void
    {
        if ($montant > 0) {
            $this->solde_dette = max(0.0, $this->solde_dette - $montant);
        }
    }

    public  function hasDette(): bool
    {
        return $this->solde_dette > 0;
    }

    public function isLimiteAtteinte(): bool
    {
        return $this->limite_credit > 0 && $this->solde_dette >= $this->limite_credit;
    }
}