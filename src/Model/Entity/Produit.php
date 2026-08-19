<?php

require_once __DIR__ . "/Fournisseur.php";

class Produit
{
    // Attributs
    private  int $id;
    private  string $code;
    private  string $libelle;
    private  string $categorie;
    private  float $prix_achat;
    private  float $prix_vente;
    private  int $qte_stock;
    private  int $seuil_alerte;
    private  ?Fournisseur $fournisseur;

    // Constructeur
    public function __construct(
        int $id = 0,
        string $code = "",
        string $libelle = "",
        string $categorie = "",
        float $prix_achat = 0.0,
        float $prix_vente = 0.0,
        int $qte_stock = 0,
        int $seuil_alerte = 10,
        ?Fournisseur $fournisseur = null
    ) {
       $this->id = $id;
       $this->code = $code;
       $this->libelle = $libelle;
       $this->categorie = $categorie;
       $this->prix_achat = $prix_achat;
       $this->prix_vente = $prix_vente;
       $this->qte_stock = $qte_stock;
       $this->seuil_alerte = $seuil_alerte;
       $this->fournisseur = $fournisseur;
    }

    // Getters
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getCode(): string
    {
        return $this->code;
    }

    public  function getLibelle(): string
    {
        return $this->libelle;
    }

    public  function getCategorie(): string
    {
        return $this->categorie;
    }

    public  function getPrixAchat(): float
    {
        return $this->prix_achat;
    }

    public  function getPrixVente(): float
    {
        return $this->prix_vente;
    }

    public  function getQteStock(): int
    {
        return $this->qte_stock;
    }

    public  function getSeuilAlerte(): int
    {
        return $this->seuil_alerte;
    }

    public  function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public  function setCode(string $code): void
    {
        $this->code = $code;
    }

    public  function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public  function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }

    public  function setPrixAchat(float $prix_achat): void
    {
        $this->prix_achat = max(0.0, $prix_achat);
    }

    public  function setPrixVente(float $prix_vente): void
    {
        $this->prix_vente = max(0.0, $prix_vente);
    }

    public  function setQteStock(int $qte_stock): void
    {
        $this->qte_stock = max(0, $qte_stock);
    }

    public  function setSeuilAlerte(int $seuil_alerte): void
    {
        $this->seuil_alerte = max(0, $seuil_alerte);
    }

    public  function setFournisseur(?Fournisseur $fournisseur): void
    {
        $this->fournisseur = $fournisseur;
    }

    // Méthodes métiers
    public  function isStockCritique(): bool
    {
        return $this->qte_stock <= $this->seuil_alerte;
    }

    public  function isEnRupture(): bool
    {
        return $this->qte_stock <= 0;
    }

    public  function isDisponible(int $quantite): bool
    {
        return $quantite > 0 && $this->qte_stock >= $quantite;
    }

    public  function diminuerStock(int $quantite): bool
    {
        if (Produit::isDisponible($quantite)) {
            $this->qte_stock -= $quantite;
            return true;
        }
        return false;
    }

    public  function augmenterStock(int $quantite): void
    {
        if ($quantite > 0) {
            self::$qte_stock += $quantite;
        }
    }

    public  function getMargeUnitaire(): float
    {
        return self::$prix_vente - self::$prix_achat;
    }

    public  function getValeurStock(): float
    {
        return self::$qte_stock * self::$prix_achat;
    }
}