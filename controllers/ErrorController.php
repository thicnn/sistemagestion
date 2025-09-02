<?php
require_once '../models/Error.php';

class ErrorController {
    private $errorModel;

    public function __construct($db_connection) {
        $this->errorModel = new ErrorModel($db_connection);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        $errors = $this->errorModel->findAll();
        require_once '../views/layouts/header.php';
        require_once '../views/pages/errors/index.php';
        require_once '../views/layouts/footer.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        require_once '../views/layouts/header.php';
        require_once '../views/pages/errors/create.php';
        require_once '../views/layouts/footer.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipo_error = $_POST['tipo_error'];
            $cantidad = (int)$_POST['cantidad'];
            $costo_unitario = 0;

            switch ($tipo_error) {
                case 'Blanco y negro de Bh227':
                    $costo_unitario = 0.9;
                    break;
                case 'Blanco y negro de C454':
                    $costo_unitario = 3;
                    break;
                case 'Color c454':
                    $costo_unitario = 10;
                    break;
            }

            $costo_total = $costo_unitario * $cantidad;
            $registrado_por_id = $_SESSION['user_id'];

            $this->errorModel->create($tipo_error, $cantidad, $costo_total, $registrado_por_id);

            header('Location: /sistemagestion/errors');
            exit();
        }
    }
}
?>
