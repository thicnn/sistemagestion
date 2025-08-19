<?php
require_once '../models/User.php';
require_once '../models/Order.php';

class AuthController
{
    // 1. Primero, declaramos las propiedades que la clase usará.
    private $userModel;
    private $orderModel;

    // 2. Luego, DENTRO del constructor, les asignamos sus valores.
    public function __construct($db_connection)
    {
        $this->userModel = new User($db_connection);
        $this->orderModel = new Order($db_connection);
    }

    public function showLoginForm()
    {
        require_once '../views/layouts/header.php';
        require_once '../views/pages/auth/login.php';
        require_once '../views/layouts/footer.php';
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        $pedidosEnCurso = $this->orderModel->findByStatuses(['En Curso', 'Confirmado']);
        $pedidosListos = $this->orderModel->findByStatuses(['Listo para Retirar']);

        require_once '../views/layouts/header.php';
        require_once '../views/pages/dashboard/index.php';
        require_once '../views/layouts/footer.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /sistemagestion/login');
        exit();
    }

    public function handleLogin()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_role'] = $user['rol'];
            header('Location: /sistemagestion/dashboard');
            exit();
        } else {
            echo "<h1>Error</h1><p>Credenciales incorrectas. Inténtalo de nuevo.</p>";
            echo '<a href="/sistemagestion/login">Volver</a>';
        }
    }
}