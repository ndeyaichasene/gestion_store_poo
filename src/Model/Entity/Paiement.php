<?php

require_once __DIR__ . "/Dette.php";
require_once __DIR__ . "/Utilisateur.php";
require_once __DIR__ . "/ModePaiement.php";

class Paiement
{
    // Attributs
    private int $id;
    private float $montant;
    private string $statut;
    private DateTime $date_paiement;
    private ?Dette $dette;
    private ?Utilisateur $utilisateur;
    private ?ModePaiement $mode_paiement;

    // Constructeur
    public function __construct(
        int $id = 0,
        float $montant = 0.0,
        string $statut = "VALIDE",
        ?DateTime $date_paiement = null,
        ?Dette $dette = null,
        ?Utilisateur $utilisateur = null,
        ?ModePaiement $mode_paiement = null
    ) {
        $this->id = $id;
        $this->montant = $montant;
        $this->statut = !empty($statut) ? $statut : "VALIDE";
        $this->date_paiement = $date_paiement ?? new DateTime();
        $this->dette = $dette;
        $this->utilisateur = $utilisateur;
        $this->mode_paiement = $mode_paiement;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDatePaiement(): DateTime
    {
        return $this->date_paiement;
    }

    public function getDette(): ?Dette
    {
        return $this->dette;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function getModePaiement(): ?ModePaiement
    {
        return $this->mode_paiement;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setMontant(float $montant): void
    {
        $this->montant = max(0.0, $montant);
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setDatePaiement(DateTime $date_paiement): void
    {
        $this->date_paiement = $date_paiement;
    }

    public function setDette(?Dette $dette): void
    {
        $this->dette = $dette;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    public function setModePaiement(?ModePaiement $mode_paiement): void
    {
        $this->mode_paiement = $mode_paiement;
    }

    // Méthodes métiers
    public function isValide(): bool
    {
        return $this->statut === 'VALIDE';
    }

    public function isAnnule(): bool
    {
        return $this->statut === 'ANNULE';
    }

    public function valider(): void
    {
        $this->statut = 'VALIDE';
    }

    public function annuler(): void
    {
        $this->statut = 'ANNULE';
    }
}