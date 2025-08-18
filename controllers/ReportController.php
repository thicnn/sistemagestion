<?php
require_once '../models/Order.php';
require_once '../models/Report.php';

class ReportController {
    private $orderModel;
    private $reportModel;

    public function __construct($db_connection) {
        $this->orderModel = new Order($db_connection);
        $this->reportModel = new Report($db_connection);
    }

    public function index() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') { header('Location: /sistemagestion/dashboard'); exit(); }

        $fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
        $fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');
        
        // --- TODAS LAS LLAMADAS CORREGIDAS CON '->' ---
        $salesData = $this->orderModel->getSalesReport($fechaInicio, $fechaFin);
        $statusCounts = $this->reportModel->countOrdersByStatus();
        $monthlyComparison = $this->orderModel->getMonthlySalesComparison();
        $providerPayments = $this->reportModel->getProviderPayments();
        $latestCounters = $this->reportModel->getLatestCounters();

        $primerDiaMes = date('Y-m-01');
        $hoy = date('Y-m-d');

        $c454e_bn_prod = $this->reportModel->getProductionCountForPeriod(2, 'Impresion', 'blanco y negro', $primerDiaMes, $hoy) + $this->reportModel->getProductionCountForPeriod(2, 'Fotocopia', 'blanco y negro', $primerDiaMes, $hoy);
        $c454e_color_prod = $this->reportModel->getProductionCountForPeriod(2, 'Impresion', 'color', $primerDiaMes, $hoy) + $this->reportModel->getProductionCountForPeriod(2, 'Fotocopia', 'color', $primerDiaMes, $hoy);
        $bh227_total_prod = $this->reportModel->getProductionCountForPeriod(1, 'Impresion', 'blanco y negro', $primerDiaMes, $hoy) + $this->reportModel->getProductionCountForPeriod(1, 'Fotocopia', 'blanco y negro', $primerDiaMes, $hoy);
        
        require_once '../views/layouts/header.php';
        require_once '../views/pages/reports/index.php';
        require_once '../views/layouts/footer.php';
    }

    public function showStatusDetails($status) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') { header('Location: /sistemagestion/dashboard'); exit(); }
        $orders = $this->orderModel->findByStatus($status);
        require_once '../views/layouts/header.php';
        require_once '../views/pages/reports/status_details.php';
        require_once '../views/layouts/footer.php';
    }

    public function storeCounter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->reportModel->saveCounter($_POST['maquina'], $_POST['fecha_inicio'], $_POST['fecha_fin'], $_POST['contador_bn'], $_POST['contador_color'] ?? 0, $_POST['notas'] ?? '');
        }
        header('Location: /sistemagestion/reports'); exit();
    }

    public function storeProviderPayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->reportModel->saveProviderPayment($_POST['fecha_pago'], $_POST['descripcion'], $_POST['monto']);
        }
        header('Location: /sistemagestion/reports'); exit();
    }
    
    public function deleteProviderPayment($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->reportModel->deleteProviderPayment($id);
        }
        header('Location: /sistemagestion/reports');
        exit();
    }
}