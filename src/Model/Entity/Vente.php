<?php

require_once __DIR__ . "/Client.php";
require_once __DIR__ . "/Utilisateur.php";

class Vente
{
    // Attributs
    private  int $id;
    private  string $reference;
    private  float $montant_total;
    private  float $montant_verse;
    private  string $statut;
    private  DateTime $date_creation;

    private  ?Client $client;
    // private  ?Utilisateur $utilisateur;

    // Constructeur
    public function __construct(
        int $id = 0,
        string $reference = "",
        float $montant_total = 0.0,
        float $montant_verse = 0.0,
        string $statut = "",
        ?DateTime $date_creation = null,
        ?Client $client = null,
        ?Utilisateur $utilisateur = null
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->montant_total = $montant_total;
        $this->montant_verse = $montant_verse;
        $this->date_creation = $date_creation ?? new DateTime();
        $this->client = $client;
        $this->statut = !empty($statut) ? $statut : $this->determinerStatut();
    }

    // Getters
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getReference(): string
    {
        return $this->reference;
    }

    public  function getMontantTotal(): float
    {
        return $this->montant_total;
    }

    public  function getMontantVerse(): float
    {
        return $this->montant_verse;
    }

    public  function getStatut(): string
    {
        return $this->statut;
    }

    public  function getDateCreation(): DateTime
    {
        return $this->date_creation;
    }

    public  function getClient(): ?Client
    {
        return $this->client;
    }

   

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public  function setReference(string $reference): void
    {
        $this->reference = $reference;
    }

    public  function setMontantTotal(float $montant_total): void
    {
        $this->montant_total = max(0.0, $montant_total);
    }

    public  function setMontantVerse(float $montant_verse): void
    {
        $this->montant_verse = max(0.0, $montant_verse);
    }

    public  function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public  function setDateCreation(DateTime $date_creation): void
    {
        $this->date_creation = $date_creation;
    }

    public  function setClient(?Client $client): void
    {
        $this->client = $client;
    }

  

    // Méthodes métiers
    public  function getMontantRestant(): float
    {
        return max(0.0, $this->montant_total - $this->montant_verse);
    }

    public  function isSoldee(): bool
    {
        return $this->montant_verse >= $this->montant_total;
    }

    public  function isCredit(): bool
    {
        return $this->montant_verse < $this->montant_total;
    }

    public  function determinerStatut(): string
    {
        if ($this->montant_verse >= $this->montant_total && $this->montant_total > 0) {
            return 'COMPTANT';
        } elseif ($this->montant_verse > 0) {
            return 'AVANCE';
        }
        return 'CREDIT_TOTAL';
    }

    public  function ajouterVersement(float $montant): void
    {
        if ($montant > 0) {
            $this->montant_verse += $montant;
            $this->statut = Vente::determinerStatut();
        }
    }
}