<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carga de archivos esenciales
require_once '../config/database.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/ClientController.php';
require_once '../controllers/OrderController.php';
require_once '../controllers/ReportController.php';
require_once '../controllers/AdminController.php';

// Creación de instancias de los controladores
$authController = new AuthController($connection);
$clientController = new ClientController($connection);
$orderController = new OrderController($connection);
$reportController = new ReportController($connection);
$adminController = new AdminController($connection);

$url = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos patrones de URL dinámicos (con IDs) primero
if (preg_match('#^clients/edit/(\d+)$#', $url, $matches)) {
    ($method === 'POST') ? $clientController->update((int)$matches[1]) : $clientController->showEditForm((int)$matches[1]);
} elseif (preg_match('#^clients/delete/(\d+)$#', $url, $matches)) {
    if ($method === 'POST') $clientController->delete((int)$matches[1]);
} elseif (preg_match('#^orders/show/(\d+)$#', $url, $matches)) {
    $orderController->show((int)$matches[1]);
} elseif (preg_match('#^orders/add_payment/(\d+)$#', $url, $matches)) {
    if ($method === 'POST') $orderController->addPayment((int)$matches[1]);
} elseif (preg_match('#^orders/edit/(\d+)$#', $url, $matches)) {
    ($method === 'POST') ? $orderController->update((int)$matches[1]) : $orderController->showEditForm((int)$matches[1]);
} elseif (preg_match('#^admin/products/edit/(\d+)$#', $url, $matches)) {
    ($method === 'POST') ? $adminController->updateProduct((int)$matches[1]) : $adminController->showProductEditForm((int)$matches[1]);
} elseif (preg_match('#^admin/products/delete/(\d+)$#', $url, $matches)) {
    if ($method === 'POST') {
        $adminController->deleteProduct((int)$matches[1]);
    }
} else {
    // Si no, usamos el switch para las rutas estáticas
    switch ($url) {
        case 'login':
            ($method === 'POST') ? $authController->handleLogin() : $authController->showLoginForm();
            break;
        case 'logout':
            $authController->logout();
            break;
        case 'dashboard':
        case '':
            $authController->showDashboard();
            break;

        case 'clients':
            $clientController->index();
            break;
        case 'clients/create':
            ($method === 'POST') ? $clientController->store() : $clientController->showCreateForm();
            break;
        case 'clients/search':
            $clientController->search();
            break;

        case 'orders':
            $orderController->index();
            break;
        case 'orders/create':
            ($method === 'POST') ? $orderController->store() : $orderController->showCreateForm();
            break;

        case 'reports':
            $reportController->index();
            break;
        case 'reports/store_counter':
            if ($method === 'POST') $reportController->storeCounter();
            break;
        case 'reports/store_payment':
            if ($method === 'POST') $reportController->storeProviderPayment();
            break;

        case 'admin/settings':
            $adminController->settings();
            break;
        case 'admin/settings/store':
            if ($method === 'POST') $adminController->storeSetting();
            break;
        case 'admin/products':
            $adminController->listProducts();
            break;
        case 'admin/products/create':
            ($method === 'POST') ? $adminController->storeProduct() : $adminController->showProductCreateForm();
            break;

        default:
            header("HTTP/1.0 404 Not Found");
            echo "<h1>Error 404: Página no encontrada</h1>";
            exit();
    }
}
