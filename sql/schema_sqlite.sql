

CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL 
);


CREATE TABLE utilisateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    adresse TEXT,
    telephone TEXT,
    role_id INTEGER NOT NULL ,

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


PRAGMA foreign_keys = OFF;



-- Nettoyage complet
DELETE FROM lignes_approvisionnement;
DELETE FROM approvisionnements;
DELETE FROM paiements;
DELETE FROM dettes;
DELETE FROM lignes_vente;
DELETE FROM ventes;
DELETE FROM modes_paiement;
DELETE FROM produits;
DELETE FROM fournisseurs;
DELETE FROM clients;
DELETE FROM utilisateurs;
DELETE FROM roles;
DELETE FROM sqlite_sequence;

-- -----------------------------------------------------------------------------
-- 1. REFERENTIEL DES ROLES
-- -----------------------------------------------------------------------------
INSERT INTO roles (nom) VALUES 
('ADMIN'),
('VENTE'),
('STOCK'),
('INVENTAIRE');

-- -----------------------------------------------------------------------------
-- 2. MODES DE PAIEMENT
-- -----------------------------------------------------------------------------
INSERT INTO modes_paiement (nom) VALUES
('ESPECES'),
('WAVE'),
('ORANGE_MONEY'),
('VIREMENT');

-- -----------------------------------------------------------------------------
-- 3. UTILISATEURS (Mot de passe : demo1234)
-- -----------------------------------------------------------------------------
INSERT INTO utilisateurs (nom, prenom, email, password, adresse, telephone, role_id) VALUES
('Sene', 'Aissatou', 'admin@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Dakar Plateau', '770000001', (SELECT id FROM roles WHERE nom = 'ADMIN')),
('Diop', 'Amadou', 'vente@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Médina, Dakar', '770000002', (SELECT id FROM roles WHERE nom = 'VENTE')),
('Fall', 'Moussa', 'stock@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Grand Yoff', '770000003', (SELECT id FROM roles WHERE nom = 'STOCK')),
('Ndiaye', 'Fatou', 'inventaire@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Parcelles Assainies', '770000004', (SELECT id FROM roles WHERE nom = 'INVENTAIRE'));

-- -----------------------------------------------------------------------------
-- 4. FOURNISSEURS
-- -----------------------------------------------------------------------------
INSERT INTO fournisseurs (nom, telephone, email, adresse) VALUES
('Comptoir Céréalier Sénégalais', '338245678', 'contact@ccs.sn', 'Port de Dakar, Hangar 4'),
('Grossiste Diop & Frères', '773456789', 'contact@diopfreres.sn', 'Marché Grand Yoff, Lot B'),
('Sénégal Import-Export', '338211010', 'contact@senimport.sn', 'Zone Industrielle de Hann');

-- -----------------------------------------------------------------------------
-- 5. CLIENTS (Soldes initiaux à 0)
-- -----------------------------------------------------------------------------
INSERT INTO clients (nom, prenom, adresse, telephone, solde_dette, limite_credit) VALUES
('Cisse', 'Awa', 'Dakar', '783332211', 0.00, 300000.00),
('Diouf', 'Fama', 'Dakar', '781234567', 0.00, 200000.00),
('Sarr', 'Moussa', 'Pikine', '769876543', 0.00, 250000.00),
('Diallo', 'Maimouna', 'Guédiawaye', '701122334', 0.00, 120000.00),
('Fall', 'Fatou', 'Dakar', '789998877', 0.00, 250000.00),
('Faye', 'Babacar', 'Thiès', '762221100', 0.00, 150000.00),
('Gueye', 'Ibrahima', 'Mbour', '778887766', 0.00, 100000.00),
('Mbacke', 'Khady', 'Touba', '704443322', 0.00, 400000.00),
('Ndiaye', 'Abdou', 'Saint-Louis', '776543210', 0.00, 150000.00),
('Sow', 'Ousmane', 'Rufisque', '775554433', 0.00, 180000.00);

-- -----------------------------------------------------------------------------
-- 6. PRODUITS (Stock initial de base)
-- -----------------------------------------------------------------------------
INSERT INTO produits (code, libelle, categorie, prix_achat, prix_vente, qte_stock, seuil_alerte) VALUES
('PRD-001', 'Sac de riz 50kg', 'Alimentation', 21000.00, 25000.00, 100, 10),
('PRD-002', 'Bidon d''huile 5L', 'Alimentation', 5600.00, 8000.00, 10, 10),
('PRD-003', 'Carton de savon', 'Hygiène & Entretien', 9000.00, 12000.00, 5, 5),
('PRD-004', 'Paquet de sucre 1kg', 'Alimentation', 1100.00, 1500.00, 210, 20),
('PRD-005', 'Carton de lait', 'Alimentation', 12000.00, 15000.00, 42, 10),
('PRD-006', 'Huile de palme 1L', 'Alimentation', 1500.00, 2000.00, 0, 5);

-- =============================================================================
-- TRANSACTIONS D'APPROVISIONNEMENT (BONS DE LIVRAISON)
-- =============================================================================
BEGIN TRANSACTION;
-- Approvisionnement 1 : BL-CCS-098
INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
VALUES ('BL-CCS-098', 4200000.00, 'RECUE', '2026-08-01 09:00:00', '2026-08-01 14:00:00',
       (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais'),
       (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'));

INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES (200, 200, 21000.00, 4200000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-CCS-098'), (SELECT id FROM produits WHERE code = 'PRD-001'));

-- Approvisionnement 2 : BL-DIP-099
INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
VALUES ('BL-DIP-099', 320000.00, 'RECUE', '2026-08-03 10:00:00', '2026-08-03 16:30:00',
       (SELECT id FROM fournisseurs WHERE nom = 'Grossiste Diop & Frères'),
       (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'));

INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(20, 20, 7000.00, 140000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-DIP-099'), (SELECT id FROM produits WHERE code = 'PRD-002')),
(15, 15, 12000.00, 180000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-DIP-099'), (SELECT id FROM produits WHERE code = 'PRD-003'));

-- Approvisionnement 3 : BL-CCS-101
INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
VALUES ('BL-CCS-101', 525000.00, 'EN_ATTENTE', '2026-08-07 08:30:00', NULL,
       (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais'),
       (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'));

INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES (25, 0, 21000.00, 525000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-CCS-101'), (SELECT id FROM produits WHERE code = 'PRD-001'));

-- Approvisionnement 4 : BL-SEN-102
INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
VALUES ('BL-SEN-102', 190000.00, 'EN_ATTENTE', '2026-08-07 11:00:00', NULL,
       (SELECT id FROM fournisseurs WHERE nom = 'Sénégal Import-Export'),
       (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'));

INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(50, 0, 1400.00, 70000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-SEN-102'), (SELECT id FROM produits WHERE code = 'PRD-004')),
(10, 0, 12000.00, 120000.00, (SELECT id FROM approvisionnements WHERE reference_bl = 'BL-SEN-102'), (SELECT id FROM produits WHERE code = 'PRD-005'));


-- =============================================================================
-- TRANSACTIONS DE VENTE COMPLÈTES (VENTE -> LIGNES -> DETTE -> STOCK -> CLIENT)
-- =============================================================================

-- Vente 1 : #CMD-1 (Comptant)
INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
VALUES ('CMD-1', 58000.00, 58000.00, 'COMPTANT', '2026-08-01 10:30:00',
       (SELECT id FROM clients WHERE telephone = '776543210'),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'));

INSERT INTO lignes_vente (quantite, prix_unitaire, vente_id, produit_id)
VALUES 
(2, 25000.00, (SELECT id FROM ventes WHERE reference = 'CMD-1'), (SELECT id FROM produits WHERE code = 'PRD-001')),
(1, 8000.00,  (SELECT id FROM ventes WHERE reference = 'CMD-1'), (SELECT id FROM produits WHERE code = 'PRD-002'));

UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-001';
UPDATE produits SET qte_stock = qte_stock - 1 WHERE code = 'PRD-002';


-- Vente 2 : #CMD-2 (Avance 10 000 F / Dette 34 000 F)
INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
VALUES ('CMD-2', 44000.00, 10000.00, 'AVANCE', '2026-08-07 21:48:00',
       (SELECT id FROM clients WHERE telephone = '781234567'),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'));

INSERT INTO lignes_vente (quantite, prix_unitaire, vente_id, produit_id)
VALUES 
(3, 8000.00,  (SELECT id FROM ventes WHERE reference = 'CMD-2'), (SELECT id FROM produits WHERE code = 'PRD-002')),
(2, 10000.00, (SELECT id FROM ventes WHERE reference = 'CMD-2'), (SELECT id FROM produits WHERE code = 'PRD-005'));

INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
VALUES (44000.00, 10000.00, 34000.00, 'EN_COURS', '2026-08-07 21:48:00',
       (SELECT id FROM ventes WHERE reference = 'CMD-2'),
       (SELECT id FROM clients WHERE telephone = '781234567'));

INSERT INTO paiements (montant, statut, date_paiement, dette_id, utilisateur_id, mode_paiement_id)
VALUES (10000.00, 'VALIDE', '2026-08-07 21:48:00',
       (SELECT id FROM dettes WHERE vente_id = (SELECT id FROM ventes WHERE reference = 'CMD-2')),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'),
       (SELECT id FROM modes_paiement WHERE nom = 'WAVE'));

UPDATE produits SET qte_stock = qte_stock - 3 WHERE code = 'PRD-002';
UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-005';
UPDATE clients SET solde_dette = solde_dette + 34000.00 WHERE telephone = '781234567';


-- Vente 3 : #CMD-3 (Avance 24 000 F / Dette 50 000 F)
INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
VALUES ('CMD-3', 74000.00, 24000.00, 'AVANCE', '2026-08-07 22:48:00',
       (SELECT id FROM clients WHERE telephone = '769876543'),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'));

INSERT INTO lignes_vente (quantite, prix_unitaire, vente_id, produit_id)
VALUES 
(2, 25000.00, (SELECT id FROM ventes WHERE reference = 'CMD-3'), (SELECT id FROM produits WHERE code = 'PRD-001')),
(2, 12000.00, (SELECT id FROM ventes WHERE reference = 'CMD-3'), (SELECT id FROM produits WHERE code = 'PRD-003'));

INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
VALUES (74000.00, 24000.00, 50000.00, 'EN_COURS', '2026-08-07 22:48:00',
       (SELECT id FROM ventes WHERE reference = 'CMD-3'),
       (SELECT id FROM clients WHERE telephone = '769876543'));

INSERT INTO paiements (montant, statut, date_paiement, dette_id, utilisateur_id, mode_paiement_id)
VALUES (24000.00, 'VALIDE', '2026-08-07 22:48:53',
       (SELECT id FROM dettes WHERE vente_id = (SELECT id FROM ventes WHERE reference = 'CMD-3')),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'),
       (SELECT id FROM modes_paiement WHERE nom = 'WAVE'));

UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-001';
UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-003';
UPDATE clients SET solde_dette = solde_dette + 50000.00 WHERE telephone = '769876543';


-- Vente 4 : #CMD-4 (Crédit Total 15 000 F)
INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
VALUES ('CMD-4', 15000.00, 0.00, 'CREDIT_TOTAL', '2026-08-07 23:48:00',
       (SELECT id FROM clients WHERE telephone = '701122334'),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'));

INSERT INTO lignes_vente (quantite, prix_unitaire, vente_id, produit_id)
VALUES 
(10, 1500.00, (SELECT id FROM ventes WHERE reference = 'CMD-4'), (SELECT id FROM produits WHERE code = 'PRD-004'));

INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
VALUES (15000.00, 0.00, 15000.00, 'EN_COURS', '2026-08-07 23:48:00',
       (SELECT id FROM ventes WHERE reference = 'CMD-4'),
       (SELECT id FROM clients WHERE telephone = '701122334'));

UPDATE produits SET qte_stock = qte_stock - 10 WHERE code = 'PRD-004';
UPDATE clients SET solde_dette = solde_dette + 15000.00 WHERE telephone = '701122334';

COMMIT;

PRAGMA foreign_keys = ON;
