<?php

require_once __DIR__ . "/Fournisseur.php";

class Produit
{
    // Attributs
    private static int $id;
    private static string $code;
    private static string $libelle;
    private static string $categorie;
    private static float $prix_achat;
    private static float $prix_vente;
    private static int $qte_stock;
    private static int $seuil_alerte;
    private static ?Fournisseur $fournisseur;

    // Constructeur
    private function __construct(
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
        self::$id = $id;
        self::$code = $code;
        self::$libelle = $libelle;
        self::$categorie = $categorie;
        self::$prix_achat = $prix_achat;
        self::$prix_vente = $prix_vente;
        self::$qte_stock = $qte_stock;
        self::$seuil_alerte = $seuil_alerte;
        self::$fournisseur = $fournisseur;
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getCode(): string
    {
        return self::$code;
    }

    public static function getLibelle(): string
    {
        return self::$libelle;
    }

    public static function getCategorie(): string
    {
        return self::$categorie;
    }

    public static function getPrixAchat(): float
    {
        return self::$prix_achat;
    }

    public static function getPrixVente(): float
    {
        return self::$prix_vente;
    }

    public static function getQteStock(): int
    {
        return self::$qte_stock;
    }

    public static function getSeuilAlerte(): int
    {
        return self::$seuil_alerte;
    }

    public static function getFournisseur(): ?Fournisseur
    {
        return self::$fournisseur;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setCode(string $code): void
    {
        self::$code = $code;
    }

    public static function setLibelle(string $libelle): void
    {
        self::$libelle = $libelle;
    }

    public static function setCategorie(string $categorie): void
    {
        self::$categorie = $categorie;
    }

    public static function setPrixAchat(float $prix_achat): void
    {
        self::$prix_achat = max(0.0, $prix_achat);
    }

    public static function setPrixVente(float $prix_vente): void
    {
        self::$prix_vente = max(0.0, $prix_vente);
    }

    public static function setQteStock(int $qte_stock): void
    {
        self::$qte_stock = max(0, $qte_stock);
    }

    public static function setSeuilAlerte(int $seuil_alerte): void
    {
        self::$seuil_alerte = max(0, $seuil_alerte);
    }

    public static function setFournisseur(?Fournisseur $fournisseur): void
    {
        self::$fournisseur = $fournisseur;
    }

    // Méthodes métiers
    public static function isStockCritique(): bool
    {
        return self::$qte_stock <= self::$seuil_alerte;
    }

    public static function isEnRupture(): bool
    {
        return self::$qte_stock <= 0;
    }

    public static function isDisponible(int $quantite): bool
    {
        return $quantite > 0 && self::$qte_stock >= $quantite;
    }

    public static function diminuerStock(int $quantite): bool
    {
        if (Produit::isDisponible($quantite)) {
            self::$qte_stock -= $quantite;
            return true;
        }
        return false;
    }

    public static function augmenterStock(int $quantite): void
    {
        if ($quantite > 0) {
            self::$qte_stock += $quantite;
        }
    }

    public static function getMargeUnitaire(): float
    {
        return self::$prix_vente - self::$prix_achat;
    }

    public static function getValeurStock(): float
    {
        return self::$qte_stock * self::$prix_achat;
    }
}