<?php

require_once __DIR__ . "/Approvisionnement.php";
require_once __DIR__ . "/Produit.php";

class LigneApprovisionnement
{
    // Attributs
    private int $id;
    private int $quantite_appro;
    private int $quantite_recue;
    private float $prix_achat;
    private float $sous_total;
    private ?Approvisionnement $approvisionnement;
    private ?Produit $produit;

    // Constructeur
    public function __construct(
        int $id = 0,
        int $quantite_appro = 0,
        int $quantite_recue = 0,
        float $prix_achat = 0.0,
        float $sous_total = 0.0,
        ?Approvisionnement $approvisionnement = null,
        ?Produit $produit = null
    ) {
        $this->id = $id;
        $this->quantite_appro = $quantite_appro;
        $this->quantite_recue = $quantite_recue;
        $this->prix_achat = $prix_achat;
        $this->sous_total = ($sous_total > 0.0) ? $sous_total : ($quantite_appro * $prix_achat);
        $this->approvisionnement = $approvisionnement;
        $this->produit = $produit;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantiteAppro(): int
    {
        return $this->quantite_appro;
    }

    public function getQuantiteRecue(): int
    {
        return $this->quantite_recue;
    }

    public function getPrixAchat(): float
    {
        return $this->prix_achat;
    }

    public function getSousTotal(): float
    {
        return $this->sous_total;
    }

    public function getApprovisionnement(): ?Approvisionnement
    {
        return $this->approvisionnement;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setQuantiteAppro(int $quantite_appro): void
    {
        $this->quantite_appro = max(0, $quantite_appro);
        $this->sous_total = $this->quantite_appro * $this->prix_achat;
    }

    public function setQuantiteRecue(int $quantite_recue): void
    {
        $this->quantite_recue = max(0, $quantite_recue);
    }

    public function setPrixAchat(float $prix_achat): void
    {
        $this->prix_achat = max(0.0, $prix_achat);
        $this->sous_total = $this->quantite_appro * $this->prix_achat;
    }

    public function setSousTotal(float $sous_total): void
    {
        $this->sous_total = max(0.0, $sous_total);
    }

    public function setApprovisionnement(?Approvisionnement $approvisionnement): void
    {
        $this->approvisionnement = $approvisionnement;
    }

    public function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }

    // Méthodes métiers
    public function isTotalementRecue(): bool
    {
        return $this->quantite_recue >= $this->quantite_appro && $this->quantite_appro > 0;
    }

    public function getQuantiteRestante(): int
    {
        return max(0, $this->quantite_appro - $this->quantite_recue);
    }

    public function receptionner(int $quantite): void
    {
        if ($quantite > 0) {
            $this->quantite_recue = min($this->quantite_appro, $this->quantite_recue + $quantite);
        }
    }
}