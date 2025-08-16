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

// --- Lógica del Enrutador ---

// Obtener la URL solicitada. Si está vacía, decidir si mostrar el dashboard o el login.
$url = $_GET['url'] ?? '';
if ($url === '') {
    $url = isset($_SESSION['user_id']) ? 'dashboard' : 'login';
}

// Obtener el método de la petición (GET, POST, etc.)
$method = $_SERVER['REQUEST_METHOD'];

// Estructura de control para dirigir a la acción correcta
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
        $authController->showDashboard();
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
        // Si no se encuentra la ruta, mostrar un error 404
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Error 404: Página no encontrada</h1>";
        exit();
}