

CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);


CREATE TABLE utilisateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    adresse TEXT,
    telephone TEXT,
    role_id INTEGER NOT NULL UNIQUE,

    FOREIGN KEY (role_id)
        REFERENCES roles(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    telephone TEXT,
    adresse TEXT,
    solde_dette NUMERIC NOT NULL DEFAULT 0,
    limite_credit NUMERIC NOT NULL DEFAULT 0,

    CHECK (solde_dette >= 0),
    CHECK (limite_credit >= 0)
);


CREATE TABLE fournisseurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    telephone TEXT,
    email TEXT UNIQUE,
    adresse TEXT
);


CREATE TABLE produits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    libelle TEXT NOT NULL,
    categorie TEXT NOT NULL,
    prix_achat NUMERIC NOT NULL,
    prix_vente NUMERIC NOT NULL,
    qte_stock INTEGER NOT NULL DEFAULT 0,
    seuil_alerte INTEGER NOT NULL DEFAULT 0,

    CHECK (prix_achat >= 0),
    CHECK (prix_vente >= 0),
    CHECK (qte_stock >= 0),
    CHECK (seuil_alerte >= 0),
    CHECK (prix_vente >= prix_achat)
);


CREATE TABLE modes_paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE
);


CREATE TABLE ventes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference TEXT NOT NULL UNIQUE,
    montant_total NUMERIC NOT NULL DEFAULT 0,
    montant_verse NUMERIC NOT NULL DEFAULT 0,
    statut TEXT NOT NULL,
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    client_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,

    FOREIGN KEY (client_id)
        REFERENCES clients(id),

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateurs(id),

    CHECK (montant_total >= 0),
    CHECK (montant_verse >= 0),
    CHECK (montant_verse <= montant_total),
    CHECK (statut IN ('COMPTANT', 'AVANCE', 'CREDIT_TOTAL'))
);


CREATE TABLE lignes_vente (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    quantite INTEGER NOT NULL,
    prix_unitaire NUMERIC NOT NULL,

    vente_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,

    FOREIGN KEY (vente_id)
        REFERENCES ventes(id),

    FOREIGN KEY (produit_id)
        REFERENCES produits(id),

    CHECK (quantite > 0),
    CHECK (prix_unitaire >= 0),

    UNIQUE (vente_id, produit_id)
);


CREATE TABLE dettes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_initial NUMERIC NOT NULL,
    montant_paye NUMERIC NOT NULL DEFAULT 0,
    montant_restant NUMERIC NOT NULL,
    statut TEXT NOT NULL,
    date_creation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    vente_id INTEGER NOT NULL UNIQUE,
    client_id INTEGER NOT NULL,

    FOREIGN KEY (vente_id)
        REFERENCES ventes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON DELETE RESTRICT,

    CHECK (montant_initial >= 0),
    CHECK (montant_paye >= 0),
    CHECK (montant_restant >= 0),
    CHECK (montant_paye + montant_restant = montant_initial),
    CHECK (statut IN ('EN_COURS', 'SOLDEE'))
);

CREATE TABLE paiements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant NUMERIC NOT NULL,
    statut TEXT NOT NULL,
    date_paiement TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    dette_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,

    FOREIGN KEY (dette_id)
        REFERENCES dettes(id)
        ON DELETE CASCADE,

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateurs(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (mode_paiement_id)
        REFERENCES modes_paiement(id)
        ON DELETE RESTRICT,

    CHECK (montant > 0),
    CHECK (statut IN ('VALIDE', 'ANNULE'))
);


CREATE TABLE approvisionnements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference_bl TEXT NOT NULL UNIQUE,
    montant_total NUMERIC NOT NULL DEFAULT 0,
    statut TEXT NOT NULL,
    date_appro TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_reception TEXT,

    fournisseur_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,

    FOREIGN KEY (fournisseur_id)
        REFERENCES fournisseurs(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id)
        REFERENCES utilisateurs(id)
        ON DELETE RESTRICT,

    CHECK (montant_total >= 0),
    CHECK (statut IN ('EN_ATTENTE', 'RECUE', 'ANNULE'))
);


CREATE TABLE lignes_approvisionnement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    quantite_appro INTEGER NOT NULL,
    quantite_recue INTEGER NOT NULL DEFAULT 0,
    prix_achat NUMERIC NOT NULL,
    sous_total NUMERIC NOT NULL,

    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,

    FOREIGN KEY (approvisionnement_id)
        REFERENCES approvisionnements(id)
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id)
        REFERENCES produits(id)
        ON DELETE RESTRICT,

    CHECK (quantite_appro > 0),
    CHECK (
        quantite_recue >= 0
        AND quantite_recue <= quantite_appro
    ),
    CHECK (prix_achat >= 0),
    CHECK (sous_total >= 0),

    UNIQUE (approvisionnement_id, produit_id)
);