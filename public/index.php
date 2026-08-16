<?php

declare(strict_types=1);

// Définition de la racine du projet si elle n'est pas déjà définie
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Inclusion des composants du noyau (Core)
require_once ROOT_PATH . '/src/Core/Database.php';
require_once ROOT_PATH . '/src/Core/SessionManager.php';
require_once ROOT_PATH . '/src/Core/Router.php';

// Démarrage de la session
SessionManager::start();

// Définition du profil utilisateur par défaut en session (si non connecté)
if (!SessionManager::has('user_id')) {
    SessionManager::set('user_id', 2);
    SessionManager::set('user_nom', 'Charge de vente');
    SessionManager::set('user_role', 'Session active');
}

// Lancement du routage
Router::dispatch();
