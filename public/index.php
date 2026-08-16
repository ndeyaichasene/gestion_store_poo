<?php

declare(strict_types=1);


if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}


spl_autoload_register(function (string $class) {
    $paths = [
        ROOT_PATH . '/src/Core/' . $class . '.php',
        ROOT_PATH . '/src/Controller/' . $class . '.php',
        ROOT_PATH . '/src/Repository/' . $class . '.php',
        ROOT_PATH . '/src/Service/' . $class . '.php',
        ROOT_PATH . '/src/Model/Entity/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});


require_once ROOT_PATH . '/src/Core/Database.php';
require_once ROOT_PATH . '/src/Core/SessionManager.php';
require_once ROOT_PATH . '/src/Core/Router.php';


SessionManager::start();


if (!SessionManager::has('user_id')) {
    SessionManager::set('user_id', 2);
    SessionManager::set('user_nom', 'Charge de vente');
    SessionManager::set('user_role', 'Session active');
}


$router = new Router();
$router->redirection();
