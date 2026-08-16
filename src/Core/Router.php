<?php

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            '/' => [
                'controller' => 'PosController',
                'action' => 'index'
            ],
            '/pos' => [
                'controller' => 'PosController',
                'action' => 'index'
            ],
            '/pos/addToCart' => [
                'controller' => 'PosController',
                'action' => 'addToCart'
            ],
            '/pos/removeToCart' => [
                'controller' => 'PosController',
                'action' => 'removeToCart'
            ],
            '/pos/addVente' => [
                'controller' => 'PosController',
                'action' => 'addVente'
            ],
            '/dette' => [
                'controller' => 'DetteController',
                'action' => 'index'
            ],
            '/dette/remboursement' => [
                'controller' => 'DetteController',
                'action' => 'remboursement'
            ],
            '/approvisionnement' => [
                'controller' => 'ApprovisionnementController',
                'action' => 'index'
            ],
            '/approvisionnement/reception' => [
                'controller' => 'ApprovisionnementController',
                'action' => 'reception'
            ],
        ];
    }

    public function redirection(): void
    {
        $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        // Support transparent pour les requêtes ?view=... (mode GET classique)
        if ($rawUri === '/' || $rawUri === '/index.php' || $rawUri === '') {
            if (isset($_GET['view'])) {
                $viewParam = trim((string)$_GET['view'], '/');
                if ($viewParam === 'dettes') {
                    $rawUri = '/dette';
                } elseif ($viewParam === 'stock' || $viewParam === 'supplies') {
                    $rawUri = '/approvisionnement';
                } else {
                    $rawUri = '/' . $viewParam;
                }
            } else {
                $rawUri = '/pos';
            }
        }

        // Nettoyage de l'URI (tolérance slash terminal)
        $uri = (strlen($rawUri) > 1) ? rtrim($rawUri, '/') : $rawUri;

        // Alias de compatibilité
        if ($uri === '/dettes') {
            $uri = '/dette';
        }

        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Page introuvable";
            exit;
        }

        $controllerClass = $this->routes[$uri]['controller'];
        $action = $this->routes[$uri]['action'];

        if (class_exists($controllerClass)) {
            $controllerInstance = new $controllerClass();

            if (method_exists($controllerInstance, $action)) {
                $controllerInstance->$action();
            } else {
                http_response_code(500);
                echo "Erreur : La méthode '$action' est introuvable.";
            }
        } else {
            http_response_code(404);
            echo "Erreur : Le contrôleur '$controllerClass' est introuvable.";
        }
    }
}
