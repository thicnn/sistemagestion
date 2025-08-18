<?php
require_once '../models/Order.php';
require_once '../models/Report.php'; // Incluimos el nuevo modelo

class ReportController {
    private $orderModel;
    private $reportModel; // Añadimos el nuevo modelo

    public function __construct($db_connection) {
        $this->orderModel = new Order($db_connection);
        $this->reportModel = new Report($db_connection); // Lo instanciamos
    }

    public function index() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') { header('Location: /sistemagestion/dashboard'); exit(); }

        // Recoger datos para el dashboard de reportes
        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

        $salesData = $this->orderModel->getSalesReport($fechaInicio, $fechaFin);
        $statusCounts = $this->reportModel->countOrdersByStatus();
        $monthlyComparison = $this->orderModel->getMonthlySalesComparison();
        $providerPayments = $this->reportModel->getProviderPayments();

        // Producción de la C454e (maquina_id = 2)
        $c454e_bn = $this->reportModel->getProductionCount(2, 'Impresion', 'Blanco y Negro') + $this->reportModel->getProductionCount(2, 'Fotocopia', 'Blanco y Negro');
        $c454e_color = $this->reportModel->getProductionCount(2, 'Impresion', 'Color') + $this->reportModel->getProductionCount(2, 'Fotocopia', 'Color');

        // Producción de la Bh-227 (maquina_id = 1)
        $bh227_total = $this->reportModel->getProductionCount(1, 'Impresion', 'Blanco y Negro') + $this->reportModel->getProductionCount(1, 'Fotocopia', 'Blanco y Negro');

        require_once '../views/layouts/header.php';
        require_once '../views/pages/reports/index.php';
        require_once '../views/layouts/footer.php';
    }

    public function storeCounter() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') { exit('Acceso denegado'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->reportModel->saveCounter($_POST['maquina'], $_POST['periodo'], $_POST['fecha'], $_POST['contador_bn'], $_POST['contador_color'], $_POST['notas']);
        }
        header('Location: /sistemagestion/reports');
        exit();
    }

    public function storeProviderPayment() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') { exit('Acceso denegado'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->reportModel->saveProviderPayment($_POST['fecha_pago'], $_POST['descripcion'], $_POST['monto']);
        }
        header('Location: /sistemagestion/reports');
        exit();
    }
}