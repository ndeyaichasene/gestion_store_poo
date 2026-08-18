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
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getQuantite(): int
    {
        return self::$quantite;
    }

    public static function getPrixUnitaire(): float
    {
        return self::$prix_unitaire;
    }

    public static function getVente(): ?Vente
    {
        return self::$vente;
    }

    public static function getProduit(): ?Produit
    {
        return self::$produit;
    }

    public static function getSousTotal(): float
    {
        return self::$quantite * self::$prix_unitaire;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setQuantite(int $quantite): void
    {
        self::$quantite = $quantite;
    }

    public static function setPrixUnitaire(float $prix_unitaire): void
    {
        self::$prix_unitaire = $prix_unitaire;
    }

    public static function setVente(?Vente $vente): void
    {
        self::$vente = $vente;
    }

    public static function setProduit(?Produit $produit): void
    {
        self::$produit = $produit;
    }
}
