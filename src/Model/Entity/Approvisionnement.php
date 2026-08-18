<?php

require_once __DIR__ . "/Fournisseur.php";
require_once __DIR__ . "/Utilisateur.php";

class Approvisionnement
{
    // Attributs
    private static int $id;
    private static string $reference_bl;
    private static float $montant_total;
    private static string $statut;
    private static DateTime $date_appro;
    private static ?DateTime $date_reception;

    private static ?Fournisseur $fournisseur;
    private static ?Utilisateur $utilisateur;

    // Constructeur
    private function __construct(
        int $id = 0,
        string $reference_bl = "",
        float $montant_total = 0.0,
        string $statut = "EN_ATTENTE",
        ?DateTime $date_appro = null,
        ?DateTime $date_reception = null,
        ?Fournisseur $fournisseur = null,
        ?Utilisateur $utilisateur = null
    ) {
        self::$id = $id;
        self::$reference_bl = $reference_bl;
        self::$montant_total = $montant_total;
        self::$statut = !empty($statut) ? $statut : "EN_ATTENTE";
        self::$date_appro = $date_appro ?? new DateTime();
        self::$date_reception = $date_reception;
        self::$fournisseur = $fournisseur;
        self::$utilisateur = $utilisateur;
    }

    // Getters
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getReferenceBl(): string
    {
        return $this->reference_bl;
    }

    public  function getMontantTotal(): float
    {
        return $this->montant_total;
    }

    public  function getStatut(): string
    {
        return $this->statut;
    }

    public  function getDateAppro(): DateTime
    {
        return $this->date_appro;
    }

    public  function getDateReception(): ?DateTime
    {
        return $this->date_reception;
    }

    public  function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public  function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public  function setReferenceBl(string $reference_bl): void
    {
        $this->reference_bl = $reference_bl;
    }

    public  function setMontantTotal(float $montant_total): void
    {
        $this->montant_total = max(0.0, $montant_total);
    }

    public  function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public  function setDateAppro(DateTime $date_appro): void
    {
        $this->date_appro = $date_appro;
    }

    public  function setDateReception(?DateTime $date_reception): void
    {
        $this->date_reception = $date_reception;
    }

    public  function setFournisseur(?Fournisseur $fournisseur): void
    {
        $this->fournisseur = $fournisseur;
    }

    public  function setUtilisateur(?Utilisateur $utilisateur): void
    {
        $this->utilisateur = $utilisateur;
    }

    // Méthodes métiers
    public  function isRecu(): bool
    {
        return $this->statut === 'RECUE';
    }

    public  function isEnAttente(): bool
    {
        return $this->statut === 'EN_ATTENTE';
    }

    public  function isAnnule(): bool
    {
        return $this->statut === 'ANNULE';
    }

    public  function validerReception(): void
    {
        $this->statut = 'RECUE';
        $this->date_reception = new DateTime();
    }

    public  function annuler(): void
    {
        $this->statut = 'ANNULE';
    }
}