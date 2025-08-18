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

// Creación de instancias de los controladores
$authController = new AuthController($connection);
$clientController = new ClientController($connection);
$orderController = new OrderController($connection);
$reportController = new ReportController($connection);

$url = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos patrones de URL dinámicos primero
if (preg_match('#^clients/edit/(\d+)$#', $url, $matches)) {
    $clientController->update((int)$matches[1]);
} elseif (preg_match('#^orders/show/(\d+)$#', $url, $matches)) {
    $orderController->show((int)$matches[1]);
} elseif (preg_match('#^orders/add_payment/(\d+)$#', $url, $matches)) {
    $orderController->addPayment((int)$matches[1]);
} elseif (preg_match('#^orders/edit/(\d+)$#', $url, $matches)) {
    $id = (int)$matches[1];
    if ($method === 'POST') {
        $orderController->update($id);
    } else {
        $orderController->showEditForm($id);
    }
    // --- FIN DE NUEVA REGLA ---

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
        case 'orders':
            $orderController->index();
            break;
        case 'orders/create':
            ($method === 'POST') ? $orderController->store() : $orderController->showCreateForm();
            break;
        case 'clients/search':
            $clientController->search();
            break;
        case 'reports':
            $reportController->index();
            break;
        default:
            header("HTTP/1.0 404 Not Found");
            echo "<h1>Error 404: Página no encontrada</h1>";
            exit();
    }
}
