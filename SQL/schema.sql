CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    adresse VARCHAR(25),
    telephone VARCHAR(25) UNIQUE,
    role_id INTEGER NOT NULL,

    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    adresse VARCHAR(50) ,
    telephone VARCHAR(30) UNIQUE,
    solde_dette DECIMAL(12,2) DEFAULT 0,
    limite_credit DECIMAL(12,2) DEFAULT 0,

    CHECK (solde_dette >= 0),
    CHECK (limite_credit >= 0)
);


CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    telephone VARCHAR(30) UNIQUE,
    email VARCHAR(50) UNIQUE,
    adresse VARCHAR(50)
);


CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    libelle VARCHAR(50) NOT NULL,
    categorie VARCHAR(50) NOT NULL,
    prix_achat DECIMAL(12,2) NOT NULL,
    prix_vente DECIMAL(12,2) NOT NULL,
    qte_stock INTEGER DEFAULT 0,
    seuil_alerte INTEGER DEFAULT 10,

    CHECK (prix_achat >= 0),
    CHECK (prix_vente >= 0),
    CHECK (qte_stock >= 0),
    CHECK (seuil_alerte >= 0)
    CHECK (prix_vente >= prix_achat)

);

CREATE TABLE modes_paiement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE ventes (
    id SERIAL PRIMARY KEY,
    reference VARCHAR(50) NOT NULL UNIQUE,
    montant_total DECIMAL(12,2) DEFAULT 0,
    montant_verse DECIMAL(12,2) DEFAULT 0,
    statut VARCHAR(30) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    client_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,

    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),

    CHECK (montant_total >= 0),
    CHECK (montant_verse >= 0),
    CHECK (montant_verse <= montant_total),
    CHECK (statut IN ('COMPTANT', 'AVANCE', 'CREDIT_TOTAL'))
);


CREATE TABLE lignes_vente (
    id SERIAL PRIMARY KEY,
    quantite INTEGER NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,

    vente_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,

    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id),

    CHECK (quantite > 0),
    CHECK (prix_unitaire >= 0)
);

CREATE TABLE dettes (
    id SERIAL PRIMARY KEY,
    montant_initial DECIMAL(12,2) NOT NULL,
    montant_paye DECIMAL(12,2) DEFAULT 0,
    montant_restant DECIMAL(12,2) NOT NULL,
    statut VARCHAR(30) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    vente_id INTEGER NOT NULL UNIQUE,
    client_id INTEGER NOT NULL,

    FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id),

    CHECK (montant_initial >= 0),
    CHECK (montant_paye >= 0),
    CHECK (montant_restant >= 0),
    CHECK (montant_paye + montant_restant = montant_initial),
    CHECK (statut IN ('EN_COURS', 'SOLDEE'))
);

CREATE TABLE paiements (
    id SERIAL PRIMARY KEY,
    montant DECIMAL(12,2) NOT NULL,
    statut VARCHAR(30) NOT NULL,
    date_paiement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    dette_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,

    FOREIGN KEY (dette_id) REFERENCES dettes(id) ON DELETE CASCADE,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (mode_paiement_id) REFERENCES modes_paiement(id),

    CHECK (montant > 0),
    CHECK (statut IN ('VALIDE', 'ANNULE'))
);

CREATE TABLE approvisionnements (
    id SERIAL PRIMARY KEY,
    reference_bl VARCHAR(50) NOT NULL UNIQUE,
    montant_total DECIMAL(12,2) DEFAULT 0,
    statut VARCHAR(30) NOT NULL,
    date_appro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_reception TIMESTAMP,

    fournisseur_id INTEGER NOT NULL,
    utilisateur_id INTEGER NOT NULL,

    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),

    CHECK (montant_total >= 0),
    CHECK (statut IN ('EN_ATTENTE', 'RECUE', 'ANNULE'))
);

CREATE TABLE lignes_approvisionnement (
    id SERIAL PRIMARY KEY,
    quantite_appro INTEGER NOT NULL,
    quantite_recue INTEGER DEFAULT 0,
    prix_achat DECIMAL(12,2) NOT NULL,
    sous_total DECIMAL(12,2) NOT NULL,

    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,

    FOREIGN KEY (approvisionnement_id)
        REFERENCES approvisionnements(id)
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id)
        REFERENCES produits(id),

    CHECK (quantite_appro > 0),
    CHECK (quantite_recue >= 0),
    CHECK (quantite_recue <= quantite_appro),
    CHECK (prix_achat >= 0),
    CHECK (sous_total >= 0)
);