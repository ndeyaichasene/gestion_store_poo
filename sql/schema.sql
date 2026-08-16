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
    CHECK (seuil_alerte >= 0),
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



-- Nettoyage préalable des tables
TRUNCATE TABLE  lignes_vente, ventes RESTART IDENTITY CASCADE;

-- -----------------------------------------------------------------------------
-- 1. REFERENTIEL DES ROLES
-- -----------------------------------------------------------------------------
INSERT INTO roles (nom) VALUES 
('ADMIN'),
('VENTE'),
('STOCK'),
('INVENTAIRE');

SELECT * FROM roles;
-- -----------------------------------------------------------------------------
-- 2. MODES DE PAIEMENT
-- -----------------------------------------------------------------------------
INSERT INTO modes_paiement (nom) VALUES
('ESPECES'),
('WAVE'),
('ORANGE_MONEY'),
('VIREMENT');

SELECT * FROM modes_paiement;
-- -----------------------------------------------------------------------------
-- 3. UTILISATEURS (Mot de passe : demo1234)
-- -----------------------------------------------------------------------------
INSERT INTO utilisateurs (nom, prenom, email, password, adresse, telephone, role_id) VALUES
('Sene', 'Aissatou', 'admin@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Dakar Plateau', '770000001', (SELECT id FROM roles WHERE nom = 'ADMIN')),
('Diop', 'Amadou', 'vente@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Médina, Dakar', '770000002', (SELECT id FROM roles WHERE nom = 'VENTE')),
('Fall', 'Moussa', 'stock@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Grand Yoff', '770000003', (SELECT id FROM roles WHERE nom = 'STOCK')),
('Ndiaye', 'Fatou', 'inventaire@storemanager.sn', '$2y$10$wt8yceYaQ2834MY/IdJ2Eu2oZfAi03k3LwOGxlx4kyPQ1WkHrHp9S', 'Parcelles Assainies', '770000004', (SELECT id FROM roles WHERE nom = 'INVENTAIRE'));

SELECT * FROM utilisateurs;
-- -----------------------------------------------------------------------------
-- 4. FOURNISSEURS
-- -----------------------------------------------------------------------------
INSERT INTO fournisseurs (nom, telephone, email, adresse) VALUES
('Comptoir Céréalier Sénégalais', '338245678', 'contact@ccs.sn', 'Port de Dakar, Hangar 4'),
('Grossiste Diop & Frères', '773456789', 'contact@diopfreres.sn', 'Marché Grand Yoff, Lot B'),
('Sénégal Import-Export', '338211010', 'contact@senimport.sn', 'Zone Industrielle de Hann');

SELECT * FROM fournisseurs;
-- -----------------------------------------------------------------------------
-- 5. CLIENTS (Soldes initiaux à 0, ils seront incrémentés lors des ventes à crédit)
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

SELECT * FROM clients;
-- -----------------------------------------------------------------------------
-- 6. PRODUITS (Stock initial de base)
-- -----------------------------------------------------------------------------
INSERT INTO produits (code, libelle, categorie, prix_achat, prix_vente, qte_stock, seuil_alerte, fournisseur_id) VALUES
('PRD-001', 'Sac de riz 50kg', 'Alimentation', 21000.00, 25000.00, 100, 10, (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais')),
('PRD-002', 'Bidon d''huile 5L', 'Alimentation', 5600.00, 8000.00, 10, 10, (SELECT id FROM fournisseurs WHERE nom = 'Grossiste Diop & Frères')),
('PRD-003', 'Carton de savon', 'Hygiène & Entretien', 9000.00, 12000.00, 5, 5, (SELECT id FROM fournisseurs WHERE nom = 'Grossiste Diop & Frères')),
('PRD-004', 'Paquet de sucre 1kg', 'Alimentation', 1100.00, 1500.00, 210, 20, (SELECT id FROM fournisseurs WHERE nom = 'Sénégal Import-Export')),
('PRD-005', 'Carton de lait', 'Alimentation', 12000.00, 15000.00, 42, 10, (SELECT id FROM fournisseurs WHERE nom = 'Sénégal Import-Export')),
('PRD-006', 'Huile de palme 1L', 'Alimentation', 1500.00, 2000.00, 0, 5, (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais'));

SELECT * FROM produits;
-- =============================================================================
-- TRANSACTIONS D'APPROVISIONNEMENT (BONS DE LIVRAISON)
-- =============================================================================
BEGIN;
-- Approvisionnement 1 : BL-CCS-098 (Reçu)
WITH saveAppro AS (
    INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
    VALUES ('BL-CCS-098', 4200000.00, 'RECUE', '2026-08-01 09:00:00', '2026-08-01 14:00:00',
           (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais'),
           (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'))
    RETURNING id
)
INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(200, 200, 21000.00, 4200000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-001'));

-- Approvisionnement 2 : BL-DIP-099 (Reçu)
WITH saveAppro AS (
    INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
    VALUES ('BL-DIP-099', 320000.00, 'RECUE', '2026-08-03 10:00:00', '2026-08-03 16:30:00',
           (SELECT id FROM fournisseurs WHERE nom = 'Grossiste Diop & Frères'),
           (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'))
    RETURNING id
)
INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(20, 20, 7000.00, 140000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-002')),
(15, 15, 12000.00, 180000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-003'));

-- Approvisionnement 3 : BL-CCS-101 (En attente)
WITH saveAppro AS (
    INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
    VALUES ('BL-CCS-101', 525000.00, 'EN_ATTENTE', '2026-08-07 08:30:00', NULL,
           (SELECT id FROM fournisseurs WHERE nom = 'Comptoir Céréalier Sénégalais'),
           (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'))
    RETURNING id
)
INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(25, 0, 21000.00, 525000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-001'));

-- Approvisionnement 4 : BL-SEN-102 (En attente)
WITH saveAppro AS (
    INSERT INTO approvisionnements (reference_bl, montant_total, statut, date_appro, date_reception, fournisseur_id, utilisateur_id)
    VALUES ('BL-SEN-102', 190000.00, 'EN_ATTENTE', '2026-08-07 11:00:00', NULL,
           (SELECT id FROM fournisseurs WHERE nom = 'Sénégal Import-Export'),
           (SELECT id FROM utilisateurs WHERE email = 'stock@storemanager.sn'))
    RETURNING id
)
INSERT INTO lignes_approvisionnement (quantite_appro, quantite_recue, prix_achat, sous_total, approvisionnement_id, produit_id)
VALUES 
(50, 0, 1400.00, 70000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-004')),
(10, 0, 12000.00, 120000.00, (SELECT id FROM saveAppro), (SELECT id FROM produits WHERE code = 'PRD-005'));


-- =============================================================================
-- TRANSACTIONS DE VENTE COMPLÈTES (VENTE -> LIGNES -> DETTE -> STOCK -> CLIENT)
-- =============================================================================

-- Vente 1 : #CMD-1 (Comptant Wave - Client Abdou Ndiaye)
WITH saveVente AS (
    INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
    VALUES ('CMD-1', 58000.00, 58000.00, 'COMPTANT', '2026-08-01 10:30:00',
           (SELECT id FROM clients WHERE telephone = '776543210'),
           (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'))
    RETURNING id
)
INSERT INTO lignes_vente (qte_vente, prix_vente, vente_id, produit_id)
VALUES 
(2, 25000.00, (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-001')),
(1, 8000.00,  (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-002'));

UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-001';
UPDATE produits SET qte_stock = qte_stock - 1 WHERE code = 'PRD-002';


-- Vente 2 : #CMD-2 (Avance 10 000 F / Reste 34 000 F Crédit - Client Fama Diouf)
WITH saveVente AS (
    INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
    VALUES ('CMD-2', 44000.00, 10000.00, 'AVANCE', '2026-08-07 21:48:00',
           (SELECT id FROM clients WHERE telephone = '781234567'),
           (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'))
    RETURNING id, client_id
),
saveLignes AS (
    INSERT INTO lignes_vente (qte_vente, prix_vente, vente_id, produit_id)
    VALUES 
    (3, 8000.00,  (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-002')),
    (2, 10000.00, (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-005'))
),
saveDette AS (
    INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
    VALUES (44000.00, 10000.00, 34000.00, 'EN_COURS', '2026-08-07 21:48:00', (SELECT id FROM saveVente), (SELECT client_id FROM saveVente))
    RETURNING id
)
INSERT INTO paiements (montant, statut, date_paiement, dette_id, utilisateur_id, mode_paiement_id)
VALUES (10000.00, 'VALIDE', '2026-08-07 21:48:00', (SELECT id FROM saveDette),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'),
       (SELECT id FROM modes_paiement WHERE nom = 'WAVE'));

UPDATE produits SET qte_stock = qte_stock - 3 WHERE code = 'PRD-002';
UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-005';
UPDATE clients SET solde_dette = solde_dette + 34000.00 WHERE telephone = '781234567';


-- Vente 3 : #CMD-3 (Avance 24 000 F / Reste 50 000 F Crédit - Client Moussa Sarr)
WITH saveVente AS (
    INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
    VALUES ('CMD-3', 74000.00, 24000.00, 'AVANCE', '2026-08-07 22:48:00',
           (SELECT id FROM clients WHERE telephone = '769876543'),
           (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'))
    RETURNING id, client_id
),
saveLignes AS (
    INSERT INTO lignes_vente (qte_vente, prix_vente, vente_id, produit_id)
    VALUES 
    (2, 25000.00, (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-001')),
    (2, 12000.00, (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-003'))
),
saveDette AS (
    INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
    VALUES (74000.00, 24000.00, 50000.00, 'EN_COURS', '2026-08-07 22:48:00', (SELECT id FROM saveVente), (SELECT client_id FROM saveVente))
    RETURNING id
)
INSERT INTO paiements (montant, statut, date_paiement, dette_id, utilisateur_id, mode_paiement_id)
VALUES (24000.00, 'VALIDE', '2026-08-07 22:48:53', (SELECT id FROM saveDette),
       (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'),
       (SELECT id FROM modes_paiement WHERE nom = 'WAVE'));

UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-001';
UPDATE produits SET qte_stock = qte_stock - 2 WHERE code = 'PRD-003';
UPDATE clients SET solde_dette = solde_dette + 50000.00 WHERE telephone = '769876543';


-- Vente 4 : #CMD-4 (Crédit Total 15 000 F - Client Maimouna Diallo)
WITH saveVente AS (
    INSERT INTO ventes (reference, montant_total, montant_verse, statut, date_creation, client_id, utilisateur_id)
    VALUES ('CMD-4', 15000.00, 0.00, 'CREDIT_TOTAL', '2026-08-07 23:48:00',
           (SELECT id FROM clients WHERE telephone = '701122334'),
           (SELECT id FROM utilisateurs WHERE email = 'vente@storemanager.sn'))
    RETURNING id, client_id
),
saveLignes AS (
    INSERT INTO lignes_vente (qte_vente, prix_vente, vente_id, produit_id)
    VALUES 
    (10, 1500.00, (SELECT id FROM saveVente), (SELECT id FROM produits WHERE code = 'PRD-004'))
)
INSERT INTO dettes (montant_initial, montant_paye, montant_restant, statut, date_creation, vente_id, client_id)
VALUES (15000.00, 0.00, 15000.00, 'EN_COURS', '2026-08-07 23:48:00', (SELECT id FROM saveVente), (SELECT client_id FROM saveVente));

UPDATE produits SET qte_stock = qte_stock - 10 WHERE code = 'PRD-004';
UPDATE clients SET solde_dette = solde_dette + 15000.00 WHERE telephone = '701122334';

COMMIT;

SELECT * FROM ventes;
SELECT * FROM lignes_vente;
SELECT * FROM dettes;
SELECT * FROM paiements;
