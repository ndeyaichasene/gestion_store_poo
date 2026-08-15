#  Dossier de Conception UML - StoreManager Pro ERP

*** NDEYE AISSATOU SENE P8 ***

---

### Entrée 1.  Présentation des 4 Profils Utilisateurs (Diagrammes Use Case)

L'application découpe les responsabilités selon **4 rôles métier** et leurs vues autorisées :

-  **Admin Boutique** : [`usecase_admin.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_admin.puml) — Contrôle total : Tableau de bord (KPIs financiers, dernières ventes, dettes critiques), Caisse POS, Dettes, Approvisionnements et Catalogue.

-  **Chargé de Vente** : [`usecase_vendeur.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_vendeur.puml) — Accès restreint à la caisse tactile POS (recherche produit, constitution panier, sélection client, choix mode paiement, validation vente) et au suivi des dettes (enregistrement des versements).

-  **Chargé de Stock** : [`usecase_stock.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_stock.puml) — Gestion des approvisionnements (réception de Bons de Livraison BL) et consultation du catalogue produits (alertes stock critique) et fournisseurs.

-  **Inventaire** : [`usecase_inventaire.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_inventaire.puml) — Accès à l'écran Catalogue pour la consultation et le comptage des stocks, ainsi que la gestion des fiches clients et fournisseurs.

-  **Diagramme Global** : [`usecase.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase.puml) — Vue d'ensemble récapitulative des cas d'utilisation et des permissions par profil.

---

## a.  Diagramme de Classes POO ([`class_diagram.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/class_diagram.puml))

### Architecture & Modélisation des Entités
Le modèle métier POO s'articule autour des entités suivantes :

- `Role` : Énumération des rôles système (`ADMIN`, `VENTE`, `STOCK`, `INVENTAIRE`).

- `Utilisateur` : Gestion du compte utilisateur, authentification et contrôle des permissions .

- `Client` : Gestion des coordonnées, du solde de dette cumulé et du plafond de crédit.

- `Fournisseur` : Partenaire approvisionneur (coordonnées et suivi).

- `Produit` & `Categorie` : Catalogue d'articles avec seuil d'alerte  et ajustement de stock.

- `Commande` & `LigneCommande` : Transactions de vente en caisse POS (composition 1 vers N).

- `Dette` & `PaiementDette` : Suivi financier des impayés avec versements partiels .

- `Approvisionnement` & `LigneApprovisionnement` : Réceptions des Bons de Livraison fournisseurs avec incrémentation automatique du stock.

---

## b.  Matrice de Traçabilité Use Cases vs Entités



    Vendeur[ Chargé de Vente] --> Commande[Commande / LigneCommande]
    Commande --> Client[Client / Dette]
    Dette --> Paiement[PaiementDette]
    Stock[Chargé de Stock] --> Approvisionnement[Approvisionnement / LigneApprovisionnement]
    Approvisionnement --> Fournisseur[Fournisseur]
    Approvisionnement --> Produit[Produit]
    Inventaire[ Inventaire] --> Produit[Produit / Categorie]
    Inventaire --> Client
    Inventaire --> Fournisseur
    Admin[ Admin Boutique] --> Utilisateur[Utilisateur / Role]



---

### Entrée 2 : Modélisation Relationnelle & Implémentation SQL
- **Fichiers associés :**
  - Schéma PostgreSQL : SQL/schema.sql
  - Schéma SQLite : SQL/schema_sqlite.sql

- **Réalisations :**
  Création et validation des scripts DDL pour PostgreSQL et SQLite avec un découpage en 11 tables relationnelles :

  1. **Gestion des Accès & Utilisateurs :**
     - `roles` : Référentiel des rôles (`ADMIN`, `VENTE`, `STOCK`, `INVENTAIRE`).
     - `utilisateurs` : Comptes utilisateurs avec contrainte d'unicité sur email/téléphone et liaison clé étrangère vers `roles`.

  2. **Tiers & Partenaires :**
     - `clients` : Coordonnées, `solde_dette` et `limite_credit` avec contraintes `CHECK >= 0`.
     - `fournisseurs` : Coordonnées et contacts pour les approvisionnements.

  3. **Catalogue & Inventaire :**
     - `produits` : Identification (`code` UNIQUE, `libelle`, `categorie`), gestion tarifaire (`prix_achat`, `prix_vente` avec contrainte `CHECK (prix_vente >= prix_achat)`), niveau de stock `qte_stock` et `seuil_alerte`.

  4. **Cycle de Vente & Caisse POS :**
     - `modes_paiement` : Modalités de règlement (Espèces, Wave, Orange Money, Chèque, etc.).
     - `ventes` : En-tête des ventes avec référence unique, montants (`montant_total`, `montant_verse`), statut (`COMPTANT`, `AVANCE`, `CREDIT_TOTAL`), liaison client et vendeur.
     - `lignes_vente` : Détail des articles vendus, quantités (`CHECK > 0`), prix unitaires et intégrité référentielle en cascade.

  5. **Gestion Financière des Dettes & Règlements :**
     - `dettes` : Générée pour toute vente à crédit ou avance, avec vérification stricte `CHECK (montant_paye + montant_restant = montant_initial)` et statuts (`EN_COURS`, `SOLDEE`).
     - `paiements` : Historique des versements sur une dette avec mode de paiement, utilisateur responsable et validation de statut.

  6. **Approvisionnements & Réceptions :**
     - `approvisionnements` : Bons de livraison avec référence unique, statut (`EN_ATTENTE`, `RECUE`, `ANNULE`), dates de commande et de réception.
     - `lignes_approvisionnement` : Articles commandés vs reçus (`CHECK (quantite_recue <= quantite_appro)`), prix d'achat et sous-totaux.

- **Points Clés d'Intégrité & Robustesse :**
  - Application de contraintes `CHECK` strictes pour interdire les valeurs financières ou quantités négatives.
  - Gestion des règles de suppression (`ON DELETE CASCADE` pour les lignes dépendantes, `ON DELETE RESTRICT` pour préserver l'historique financier et référentiel).
  - Adaptation dialectale SQLite (`AUTOINCREMENT`, types `NUMERIC`/`TEXT`) et PostgreSQL (`SERIAL`, types `DECIMAL`, `TIMESTAMP`).


