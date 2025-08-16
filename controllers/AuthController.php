<?php

// Incluimos el modelo que acabamos de crear
require_once '../models/User.php';

class AuthController
{
    private $userModel;

    // El constructor ahora recibe la conexión y crea el modelo
    public function __construct($db_connection)
    {
        $this->userModel = new User($db_connection);
    }

    public function showLoginForm()
    {
        require_once '../views/layouts/header.php';
        require_once '../views/pages/auth/login.php';
        require_once '../views/layouts/footer.php';
    }

    // ... dentro de la clase AuthController

    // NUEVO MÉTODO: Muestra la página del dashboard y la protege
    public function showDashboard()
    {
        // Si no existe la sesión del usuario, lo mandamos al login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        // Si la sesión existe, mostramos la página del dashboard
        require_once '../views/layouts/header.php';
        require_once '../views/pages/dashboard/index.php';
        require_once '../views/layouts/footer.php';
    }

    // NUEVO MÉTODO: Cierra la sesión del usuario
    public function logout()
    {
        // Limpiamos las variables de sesión
        session_unset();
        // Destruimos la sesión
        session_destroy();
        // Redirigimos al login
        header('Location: /sistemagestion/login');
        exit();
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // 1. Le pedimos al modelo que busque al usuario por email
            $user = $this->userModel->findByEmail($email);

            // 2. Verificamos si el usuario existe Y si la contraseña es correcta
            // password_verify() compara la contraseña enviada con el hash guardado en la BD
            if ($user && password_verify($password, $user['password_hash'])) {
                // ¡Éxito! Inicio de sesión correcto.
                // En lugar de mostrar un mensaje, guardamos los datos en la sesión.
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol'];

                // Redirigimos al usuario al dashboard
                header('Location: /sistemagestion/dashboard');
                exit(); // Es importante llamar a exit() después de una redirección

            } else {
                // Si el usuario no existe o la contraseña es incorrecta
                echo "<h1>Error</h1><p>Credenciales incorrectas. Inténtalo de nuevo.</p>";
            }

        } else {
            header('Location: /sistemagestion/login');
        }
    }
}