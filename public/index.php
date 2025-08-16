<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Carga de archivos esenciales
require_once '../config/database.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/ClientController.php';

// Creación de instancias de los controladores
$authController = new AuthController($connection);
$clientController = new ClientController($connection);

// --- INICIO DEL ENRUTADOR CON SWITCH ---

$url = $_GET['url'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Primero, verificamos si la URL coincide con un patrón dinámico (como el de edición)
if (preg_match('#^clients/edit/(\d+)$#', $url, $matches)) {
    $id = (int)$matches[1]; // Capturamos el ID
    if ($method === 'POST') {
        $clientController->update($id);
    } else {
        $clientController->showEditForm($id);
    }
} else {
    // Si no es una ruta dinámica, usamos el switch para las rutas estáticas
    switch ($url) {
        case 'login':
            if ($method === 'POST') {
                $authController->handleLogin();
            } else {
                $authController->showLoginForm();
            }
            break;

        case 'logout':
            $authController->logout();
            break;

        case 'dashboard':
        case '': // Ruta principal o vacía
            if (isset($_SESSION['user_id'])) {
                $authController->showDashboard();
            } else {
                $authController->showLoginForm();
            }
            break;

        case 'clients':
            $clientController->index();
            break;

        case 'clients/create':
            if ($method === 'POST') {
                $clientController->store();
            } else {
                $clientController->showCreateForm();
            }
            break;

        default:
            // Si ninguna ruta coincide, mostramos un error 404
            header("HTTP/1.0 404 Not Found");
            echo "<h1>Error 404: Página no encontrada</h1>";
            exit();
    }
}