<?php
require_once '../models/Order.php';

class ReportController {
    private $orderModel;

    public function __construct($db_connection) {
        $this->orderModel = new Order($db_connection);
    }

    /**
     * Muestra la página de reportes y procesa el formulario de fechas.
     */
    public function index() {
        // ¡Seguridad por Roles! Si no es admin, lo sacamos.
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }

        // Fechas por defecto: el mes actual
        $fechaInicio = $_POST['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_POST['fecha_fin'] ?? date('Y-m-d');

        $reportData = $this->orderModel->getSalesReport($fechaInicio, $fechaFin);

        require_once '../views/layouts/header.php';
        // Necesitaremos crear esta nueva vista
        require_once '../views/pages/reports/index.php';
        require_once '../views/layouts/footer.php';
    }
}