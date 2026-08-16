<?php

class Router
{
   
    public static function dispatch(): void
    {
        $view = $_GET['view'] ?? 'pos';

        switch ($view) {
            case 'pos':
                require_once ROOT_PATH . '/src/Controller/POSController.php';
                $controller = new POSController();
                $controller->index();
                break;

            case 'dashboard':
            case 'admin':
                require_once ROOT_PATH . '/src/Repository/VenteRepository.php';
                $venteRepo = new VenteRepository();
                $stats = $venteRepo->getStats();
                $activePage = 'dashboard';
                require_once ROOT_PATH . '/views/admin/index.php';
                break;

            case 'dettes':
                $activePage = 'dettes';
                require_once ROOT_PATH . '/views/dettes/index.php';
                break;

            case 'stock':
            case 'supplies':
                $activePage = 'stock';
                require_once ROOT_PATH . '/views/stock/index.php';
                break;

            case 'inventaire':
            case 'catalog':
                $activePage = 'inventaire';
                require_once ROOT_PATH . '/views/inventaire/index.php';
                break;

            default:
                
                require_once ROOT_PATH . '/src/Controller/POSController.php';
                $controller = new POSController();
                $controller->index();
                break;
        }
    }
}
