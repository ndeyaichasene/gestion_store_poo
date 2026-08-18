<?php

require_once __DIR__ . "/Produit.php";
require_once __DIR__ . "/Vente.php";

class LigneVente
{
    // Attributs
    private static int $id;
    private static int $quantite;
    private static float $prix_unitaire;
    private static ?Vente $vente;
    private static ?Produit $produit;

    // Constructeur
    private function __construct(
        int $id = 0,
        int $quantite = 0,
        float $prix_unitaire = 0.0,
        ?Vente $vente = null,
        ?Produit $produit = null
    ) {
        self::$id = $id;
        self::$quantite = $quantite;
        self::$prix_unitaire = $prix_unitaire;
        self::$vente = $vente;
        self::$produit = $produit;
    }

    // Getters
    public  function getId(): int
    {
        return $this->id;
    }

    public  function getQuantite(): int
    {
        return $this->quantite;
    }

    public  function getPrixUnitaire(): float
    {
        return $this->prix_unitaire;
    }

    public  function getVente(): ?Vente
    {
        return $this->vente;
    }

    public  function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public  function getSousTotal(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }

    // Setters
    public  function setId(int $id): void
    {
        $this->id = $id;
    }

    public  function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public  function setPrixUnitaire(float $prix_unitaire): void
    {
        $this->prix_unitaire = $prix_unitaire;
    }

    public  function setVente(?Vente $vente): void
    {
        $this->vente = $vente;
    }

    public  function setProduit(?Produit $produit): void
    {
        $this->produit = $produit;
    }
}
