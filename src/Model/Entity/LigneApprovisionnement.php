<?php

require_once __DIR__ . "/Approvisionnement.php";
require_once __DIR__ . "/Produit.php";

class LigneApprovisionnement
{
    // Attributs
    private static int $id;
    private static int $quantite_appro;
    private static int $quantite_recue;
    private static float $prix_achat;
    private static float $sous_total;
    private static ?Approvisionnement $approvisionnement;
    private static ?Produit $produit;

    // Constructeur
    private function __construct(
        int $id = 0,
        int $quantite_appro = 0,
        int $quantite_recue = 0,
        float $prix_achat = 0.0,
        float $sous_total = 0.0,
        ?Approvisionnement $approvisionnement = null,
        ?Produit $produit = null
    ) {
        self::$id = $id;
        self::$quantite_appro = $quantite_appro;
        self::$quantite_recue = $quantite_recue;
        self::$prix_achat = $prix_achat;
        self::$sous_total = ($sous_total > 0.0) ? $sous_total : ($quantite_appro * $prix_achat);
        self::$approvisionnement = $approvisionnement;
        self::$produit = $produit;
    }

    // Getters
    public static function getId(): int
    {
        return self::$id;
    }

    public static function getQuantiteAppro(): int
    {
        return self::$quantite_appro;
    }

    public static function getQuantiteRecue(): int
    {
        return self::$quantite_recue;
    }

    public static function getPrixAchat(): float
    {
        return self::$prix_achat;
    }

    public static function getSousTotal(): float
    {
        return self::$sous_total;
    }

    public static function getApprovisionnement(): ?Approvisionnement
    {
        return self::$approvisionnement;
    }

    public static function getProduit(): ?Produit
    {
        return self::$produit;
    }

    // Setters
    public static function setId(int $id): void
    {
        self::$id = $id;
    }

    public static function setQuantiteAppro(int $quantite_appro): void
    {
        self::$quantite_appro = max(0, $quantite_appro);
        self::$sous_total = self::$quantite_appro * self::$prix_achat;
    }

    public static function setQuantiteRecue(int $quantite_recue): void
    {
        self::$quantite_recue = max(0, $quantite_recue);
    }

    public static function setPrixAchat(float $prix_achat): void
    {
        self::$prix_achat = max(0.0, $prix_achat);
        self::$sous_total = self::$quantite_appro * self::$prix_achat;
    }

    public static function setSousTotal(float $sous_total): void
    {
        self::$sous_total = max(0.0, $sous_total);
    }

    public static function setApprovisionnement(?Approvisionnement $approvisionnement): void
    {
        self::$approvisionnement = $approvisionnement;
    }

    public static function setProduit(?Produit $produit): void
    {
        self::$produit = $produit;
    }

    // Méthodes métiers
    public static function isTotalementRecue(): bool
    {
        return self::$quantite_recue >= self::$quantite_appro && self::$quantite_appro > 0;
    }

    public static function getQuantiteRestante(): int
    {
        return max(0, self::$quantite_appro - self::$quantite_recue);
    }

    public static function receptionner(int $quantite): void
    {
        if ($quantite > 0) {
            self::$quantite_recue = min(self::$quantite_appro, self::$quantite_recue + $quantite);
        }
    }
}