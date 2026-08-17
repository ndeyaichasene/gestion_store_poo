# 📓 Journal de Développement (DEVLOG) — StoreManager Pro ERP

**Projet** : StoreManager Pro (ERP de Gestion Commerciale & Caisse POS en PHP / POO)  
**Auteur** : NDEYE AISSATOU SENE (P8)  
**Architecture** : POO / MVC Modulaire (Core, Model/Entity, Repository, Service, Controller, Views)

---

## 📌 Entrée 1 : Dossier de Conception UML & Modélisation des Rôles

### 1.1. Profils Utilisateurs & Cas d'Utilisation (Diagrammes Use Case)
L'application découpe les responsabilités métier selon **4 profils utilisateurs distincts** modélisés en PlantUML :

- **Admin Boutique** ([`docs/usecase_admin.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_admin.puml)) :  
  Supervision intégrale du système : tableau de bord analytique (KPIs financiers, suivi des ventes, alertes créances critiques), accès caisse POS, suivi des dettes, validation des approvisionnements, gestion des produits et utilisateurs.

- **Chargé de Vente** ([`docs/usecase_vendeur.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_vendeur.puml)) :  
  Opérations de caisse POS (recherche et ajout d'articles au panier, sélection du client débiteur/comptant, vérification du plafond de crédit, choix du mode de règlement, validation de commande) et consultation/versement sur les dettes clients.

- **Chargé de Stock** ([`docs/usecase_stock.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_stock.puml)) :  
  Gestion logistique des entrées en stock : enregistrement et réception des Bons de Livraison (BL) fournisseurs, pointage des quantités et consultation des alertes de rupture/stock critique.

- **Responsable Inventaire** ([`docs/usecase_inventaire.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase_inventaire.puml)) :  
  Gestion du catalogue d'articles (produits, catégories, prix d'achat/vente, seuils d'alerte), comptage des stocks physiques et tenue des répertoires clients et fournisseurs.

- **Vue d'Ensemble des Cas d'Utilisation** ([`docs/usecase.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/usecase.puml)) :  
  Diagramme récapitulatif global des interactions et des frontières d'autorisation par acteur.

### 1.2. Diagramme de Classes Métier POO ([`docs/class_diagram.puml`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/class_diagram.puml))
Modélisation orientée objet des 12 entités fondamentales du domaine avec leurs relations (associations, compositions, multiplicités) :
- `Role`, `Utilisateur` : Authentification et habilitation selon les profils.
- `Client`, `Fournisseur` : Gestion des tiers, suivi des plafonds de crédit et de l'encours de dette.
- `Produit` : Gestion des caractéristiques d'articles, valorisation et seuils d'alerte.
- `Vente`, `LigneVente` : Transactions commerciales de caisse (relation composition 1 vers N).
- `Dette`, `Paiement` : Suivi des créances clients et règlements échelonnés.
- `Approvisionnement`, `LigneApprovisionnement` : Réception des marchandises fournisseurs et mise à jour des stocks.

### 1.3. Matrice de Traçabilité Use Cases vs Entités ([`docs/dossier_conception.md`](file:///home/aicha/Bureau/POO/gestion_boutique/docs/dossier_conception.md))
Cartographie reliant chaque acteur métier aux entités qu'il manipule directement dans l'ERP.

---

## 🗄️ Entrée 2 : Modélisation Relationnelle & Schémas SQL

- **Fichiers associés** :
  - Script PostgreSQL : [`sql/schema.sql`](file:///home/aicha/Bureau/POO/gestion_boutique/sql/schema.sql)
  - Script SQLite : [`sql/schema_sqlite.sql`](file:///home/aicha/Bureau/POO/gestion_boutique/sql/schema_sqlite.sql)
  - Base de données active : `erp.db`

### Réalisations & Structure de la Base de Données (12 Tables) :
1. **`roles`** : Référentiel des rôles système (`ADMIN`, `VENTE`, `STOCK`, `INVENTAIRE`).
2. **`utilisateurs`** : Comptes collaborateurs avec identifiants uniques, hash de mot de passe et clé étrangère vers `roles`.
3. **`clients`** : Registre clients avec contraintes d'intégrité (`CHECK solde_dette >= 0`, `CHECK limite_credit >= 0`).
4. **`fournisseurs`** : Coordonnées des partenaires d'approvisionnement.
5. **`produits`** : Catalogue articles (`code` UNIQUE, `libelle`, `categorie`, `prix_achat`, `prix_vente`, `qte_stock`, `seuil_alerte`) avec contrainte `CHECK (prix_vente >= prix_achat)`.
6. **`modes_paiement`** : Modalités d'encaissement (Espèces, Wave, Orange Money, Chèque, Virement).
7. **`ventes`** : Entête de vente (`reference` UNIQUE, `montant_total`, `montant_verse`, `statut` : `COMPTANT`, `AVANCE`, `CREDIT_TOTAL`).
8. **`lignes_vente`** : Articles vendus avec quantité (`CHECK quantite > 0`), prix unitaire et calcul du sous-total.
9. **`dettes`** : Créances générées sur ventes à crédit (`montant_initial`, `montant_paye`, `montant_restant`, `statut` : `EN_COURS`, `SOLDEE`) avec contrainte de cohérence `CHECK (montant_paye + montant_restant = montant_initial)`.
10. **`paiements`** : Historique des versements sur dettes avec référence de dette, utilisateur encaisseur et mode de paiement.
11. **`approvisionnements`** : Bons de réception fournisseurs (`reference`, `statut` : `EN_ATTENTE`, `RECUE`, `ANNULE`, dates de commande et de réception).
12. **`lignes_approvisionnement`** : Lignes de réception d'articles avec suivi des quantités commandées vs reçues (`CHECK quantite_recue <= quantite_appro`).

---

## ⚙️ Entrée 3 : Couche Fondamentale (Core)

- **Fichiers associés** :
  - Connexion & Abstraction DB : [`src/Core/Database.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Core/Database.php)
  - Gestionnaire de Session : [`src/Core/SessionManager.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Core/SessionManager.php)

### Réalisations & Architecture :
1. **Singleton PDO & Multi-Driver (`Database.php`)** :
   - Instance unique partagée `PDO` via `Database::connexionDB()` / `getInstanceDB()`.
   - Stratégie de basculement automatique : tentative PostgreSQL (`pgsql`) avec fallback transparent sur SQLite (`sqlite:erp.db`).
   - Configuration stricte des options PDO (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`).
   - Méthodes utilitaires CRUD sécurisées : `executeQuery()` (lecture sécurisée unitaire/multiple), `executeUpdate()` (écriture INSERT/UPDATE/DELETE avec retour de `lastInsertId` ou `rowCount`), `prepare()`, `query()`, et `getAllTable()`.

2. **Gestionnaire de Session POO (`SessionManager.php`)** :
   - Encapsulation des accès à `$_SESSION` : `start()`, `get()`, `set()`, `has()`, `unset()`, `clear()`, `destroy()`.
   - Support des messages flash de notification (`flash_success`, `flash_error`) et persistance des contextes utilisateur et panier.

---

## 📦 Entrée 4 : Entités Métiers POO (Modèle Domaine)

- **Répertoire** : [`src/Model/Entity/`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/)
- **12 Classes Entités** :
  1. [`Role.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Role.php) : Gestion des libellés de profils.
  2. [`Utilisateur.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Utilisateur.php) : Données du compte, vérification du mot de passe et helpers de rôle (`isAdmin()`, `isVendeur()`, `isStock()`, `isInventaire()`, `hasRole()`).
  3. [`Client.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Client.php) : Encapsulation du solde de dette, contrôle du plafond de crédit (`peutPrendreCredit()`, `getCreditDisponible()`, `ajouterDette()`, `diminuerDette()`).
  4. [`Fournisseur.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Fournisseur.php) : Fiche de contact fournisseur.
  5. [`Produit.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Produit.php) : Logique de stock (`isDisponible()`, `isStockCritique()`, `isEnRupture()`, `diminuerStock()`, `augmenterStock()`, `getMargeUnitaire()`, `getValeurStock()`).
  6. [`ModePaiement.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/ModePaiement.php) : Types de règlement.
  7. [`Vente.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Vente.php) : En-tête de vente avec calculs de solde (`getMontantRestant()`, `isSoldee()`, `isCredit()`, `determinerStatut()`, `ajouterVersement()`).
  8. [`LigneVente.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/LigneVente.php) : Détail d'un article vendu (`getSousTotal() = quantite * prix_unitaire`).
  9. [`Dette.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Dette.php) : Gestion des créances, contrôle d'apurement (`isSoldee()`, `isEnCours()`) et enregistrement de versement (`enregistrerPaiement()`).
  10. [`Paiement.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Paiement.php) : Enregistrement de versement avec statut (`valider()`, `annuler()`, `isValide()`).
  11. [`Approvisionnement.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/Approvisionnement.php) : Cycle de vie du BL (`isRecu()`, `isEnAttente()`, `validerReception()`, `annuler()`).
  12. [`LigneApprovisionnement.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Model/Entity/LigneApprovisionnement.php) : Suivi des réceptions partielles/totales (`isTotalementRecue()`, `getQuantiteRestante()`, `receptionner()`, `getSousTotal()`).

---

## 🗃️ Entrée 5 : Couche d'Accès aux Données (Repositories)

- **Répertoire** : [`src/Repository/`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/)
- **6 Classes Repositories Implémentées** :
  1. [`ProduitRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/ProduitRepository.php) :  
     - Lecture : `getAllProduit()`, `getProduitById()`, `getProduitByCode()`, `getProduitByCategorie()`.
     - Alertes et mise à jour de stock : `getAlertesStock()`, `updateStock()`.
     - Écriture : `saveProduit()`, `updateProduit()`, `deleteProduit()`.
     - Hydratation : `transformerEnObjetProduit()`.

  2. [`ClientRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/ClientRepository.php) :  
     - Lecture & Recherche : `getAllClient()`, `getClientById()`, `getClientByTelephone()`, `searchClient()`.
     - Créances : `getClientsAvecDettes()`, `updateSoldeDetteClient()`.
     - Écriture & Hydratation : `saveClient()`, `updateClient()`, `deleteClient()`, `transformerEnObjetClient()`.

  3. [`FournisseurRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/FournisseurRepository.php) :  
     - Lecture & Recherche : `getAllFournisseur()`, `getFournisseurById()`, `getFournisseurByTelephone()`, `getFournisseurByEmail()`, `searchFournisseur()`.
     - Écriture & Hydratation : `saveFournisseur()`, `updateFournisseur()`, `deleteFournisseur()`, `transformerEnObjetFournisseur()`.

  4. [`VenteRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/VenteRepository.php) :  
     - Lecture des ventes et statistiques : `getAllVente()`, `getVenteById()`, `getLignesVente()`, `getPosStats()`.
     - Écriture : `saveVente()`, `saveLigneVente()`, `transformerEnObjetVente()`, `transformerEnObjetLigneVente()`.

  5. [`DetteRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/DetteRepository.php) :  
     - Lecture et indicateurs : `getAllDettes()`, `getDetteById()`, `getDettesByClientId()`, `getPaiementsByDetteId()`, `getStatsDettes()`.
     - Écriture : `saveDette()`, `updateDette()`, `savePaiement()`.
     - Hydratation : `transformerEnObjetDette()`, `transformerEnObjetPaiement()`.

  6. [`ModePaiementRepository.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Repository/ModePaiementRepository.php) :  
     - Lecture : `getAllModePaiement()`, `getModePaiementById()`.

---

## 💼 Entrée 6 : Couche Service Métier (VenteService)

- **Fichier associé** : [`src/Service/VenteService.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Service/VenteService.php)

### Responsabilités & Fonctionnalités Métier :
1. **Gestion du Panier de Caisse en Mémoire** :
   - `ajouterArticle(int $produitId, int $quantite)` : Vérification dynamique de la disponibilité en stock et ajout/incrémentation.
   - `modifierQuantite(int $produitId, int $nouvelleQuantite)` : Ajustement avec re-validation de stock.
   - `supprimerArticle(int $produitId)`, `viderPanier()`, `getPanier()`.
   - `calculerTotalPanier()`, `getNombreArticles()`.

2. **Validation Transactionnelle Atomique (ACID via PDO)** :
   - Contrôles préalables : panier non-vide, existence du client, solvabilité et vérification stricte du plafond de crédit (`client->peutPrendreCredit($reliquat)`).
   - Encadrement sous transaction SQL (`beginTransaction()`, `commit()`, `rollBack()`).
   - Génération de la référence unique de commande (`CMD-XXXX`).
   - Insertion de la vente (`ventes`) avec calcul automatique du statut (`COMPTANT`, `AVANCE`, `CREDIT_TOTAL`).
   - Décrémentation atomique des stocks des articles vendus (`produits.qte_stock`).
   - Création automatique de la créance (`dettes`) et majoration du solde débiteur du client (`clients.solde_dette`) en cas de reliquat impayé.

---

## 🖥️ Entrée 7 : Interfaces Utilisateurs & Organisation des Vues

- **Layouts Partagés** :
  - En-tête globale & Barre de navigation : [`views/layout/header.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/layout/header.php)
  - Pied de page : [`views/layout/footer.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/layout/footer.php)

- **Vues Métiers Découpées** :
  1. **Caisse Tactile POS (Chargé de Vente)** : [`views/pos/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/pos/index.php)  
     - KPIs en temps réel (*CA Encaissé Net, Encours Client Total, Commandes Enregistrées*).
     - Panneau de saisie de vente (sélection client avec solde/plafond, sélecteur de produit avec stock, quantité, panier dynamique, choix du versement et validation).
     - Registre des ventes récentes avec accordéons déroulants des lignes de commande.

  2. **Gestion des Dettes & Créances** : [`views/dettes/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/dettes/index.php)  
     - Indicateurs financiers (*Créances Actives, Clients Débiteurs, Total Recouvrements*).
     - Registre des créances avec recherche et statuts (*NON SOLDÉE / SOLDÉE*).
     - Tiroirs interactifs par créance : historique des acomptes, articles de la vente et formulaire de remboursement avec raccourcis rapides.

  3. **Tableau de Bord Administrateur** : [`views/admin/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/admin/index.php)  
     - Vue analytique globale des indicateurs financiers et performances de la boutique.

  4. **Approvisionnements & Réceptions BL (Chargé de Stock)** : [`views/appro/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/appro/index.php)  
     - Suivi des bordereaux de livraison et réceptions fournisseurs.

  5. **Produits & Tiers (Responsable Inventaire)** : [`views/inventaire/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/inventaire/index.php)  
     - Catalogue des articles en stock et gestion des répertoires clients et fournisseurs.

- **Design System & Styles** :
  - Feuille de style CSS Vanilla dédiée : [`public/css/style.css`](file:///home/aicha/Bureau/POO/gestion_boutique/public/css/style.css) (Palette sombre/teal ergonomique, typographie moderne Plus Jakarta Sans, composants de cartes, tableaux, badges et tiroirs réactifs).

---

## 🎮 Entrée 8 : Contrôleur Caisse POS (PosController)

- **Fichier associé** : [`src/Controller/PosController.php`](file:///home/aicha/Bureau/POO/gestion_boutique/src/Controller/PosController.php)

### Rôle & Architecture :
Le `PosController` orchestre les interactions du terminal de caisse tactile entre l'interface utilisateur, les repositories de données (`ProduitRepository`, `ClientRepository`, `VenteRepository`), la gestion de session (`SessionManager`) et la couche métier transactionnelle (`VenteService`).

### Méthodes & Logique Implémentée :
1. **`__construct()`** :
   - Démarrage sécurisé de la session via `SessionManager::start()`.
   - Initialisation de la connexion PDO singleton via `Database::connexionDB()`.
   - Instanciation des dépendances : `ProduitRepository`, `ClientRepository`, `VenteRepository` et `VenteService`.
   - Initialisation du panier de caisse dans la session (`pos_cart`) s'il n'existe pas.

2. **`index(): void`** :
   - Récupération du catalogue des produits disponibles pour la vente via `$produitRepository->getAllProduit()`.
   - Récupération de la liste des clients et de leurs informations de solvabilité via `$clientRepository->getAllClient()`.
   - Chargement de l'historique des ventes avec hydratation des lignes associées (`$venteRepository->getAllVente()` et `$venteRepository->getLignesVente()`).
   - Calcul dynamique du total du panier en cours (`$totalPanier`).
   - Récupération des indicateurs analytiques du POS via `$venteRepository->getPosStats()` (*CA net encaissé, encours client total, nombre de commandes*).
   - Transmission et consommation des notifications flash (`flash_success`, `flash_error`).
   - Rendu de la vue de caisse [`views/pos/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/pos/index.php).

3. **`addToCart(): void`** :
   - Réception et assainissement des paramètres POST (`produit_id`, `quantite`).
   - Vérification de l'existence de l'article en base de données.
   - Contrôle préventif du stock disponible en tenant compte des quantités déjà présentes dans le panier.
   - Ajout ou mise à jour de la ligne d'article dans le panier en session (`pos_cart`) avec calcul du sous-total unitaire.
   - Enregistrement des messages flash de confirmation ou d'erreur et redirection HTTP propre vers `?view=pos`.

4. **`addVente(): void`** :
   - Réception des données de validation de commande (`client_id`, `montant_verse`).
   - Validation de l'intégrité de la commande (sélection obligatoire du client, panier non vide).
   - Transfert des articles du panier vers le service métier `VenteService`.
   - Déclenchement de la validation transactionnelle ACID via `VenteService::validerVente()`.
   - Vidage du panier de session après confirmation de l'enregistrement.
   - Notification flash de succès et redirection vers la vue POS.

---

## 💻 Entrée 9 : Interface & Composants de la Vue POS (pos/index.php)

- **Fichier associé** : [`views/pos/index.php`](file:///home/aicha/Bureau/POO/gestion_boutique/views/pos/index.php)

### Conception & Fonctionnalités de l'Interface Caisse :
1. **Barre d'Indicateurs Financiers (KPIs)** :
   - *CA Encaissé Net* : Affichage du chiffre d'affaires effectivement perçu.
   - *Encours Client Total* : Suivi du montant global des dettes et créances en circulation.
   - *Commandes Enregistrées* : Compteur des transactions réalisées.

2. **Console de Caisse & Panier Interactif (Sticky Panel)** :
   - **Sélecteur Client** : Menu déroulant listant les clients avec leur numéro de téléphone et leur plafond de crédit.
   - **Sélection d'Article avec Indicateur Visuel de Stock** : Pastilles colorées dynamiques (🟢 stock suffisant, 🟡 stock sous alerte, 🔴 rupture).
   - **Panier en Direct** : Tableau listant les articles ajoutés, les quantités, les sous-totaux et permettant la suppression unitaire ou le vidage complet du panier.
   - **Afficheur Digital Grand Format** : Montant total net à payer en FCFA mis en évidence.
   - **Formulaire de Règlement** : Sélection du mode de paiement (Wave, Orange Money, Espèces, Virement) et saisie du montant versé / acompte avec validation transactionnelle.

3. **Registre des Ventes & Tiroirs de Détails** :
   - Tableau chronologique des ventes avec référence unique (`#CMD-X`), nom et téléphone du client, montant total et badges d'état colorés (`COMPTANT`, `AVANCE`, `CREDIT_TOTAL`).
   - Tiroirs accordéons interactifs en JavaScript (`toggleDetails()`) affichant instantanément le détail des lignes de facture (articles, quantités, prix unitaires, sous-totaux et date/heure précise).
