<?php
require_once '../models/Order.php';
require_once '../models/Client.php';
require_once '../models/Product.php';

class OrderController
{
    private $orderModel;
    private $clientModel;
    private $productModel;

    public function __construct($db_connection)
    {
        $this->orderModel = new Order($db_connection);
        $this->clientModel = new Client($db_connection);
        $this->productModel = new Product($db_connection);
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        $orders = $this->orderModel->findAll();
        require_once '../views/layouts/header.php';
        require_once '../views/pages/orders/index.php';
        require_once '../views/layouts/footer.php';
    }

    public function showCreateForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        $clients = $this->clientModel->findAll();
        $products = $this->productModel->findAllAvailable();
        require_once '../views/layouts/header.php';
        require_once '../views/pages/orders/create.php';
        require_once '../views/layouts/footer.php';
    }

    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->orderModel->create(
                $_POST['cliente_id'],
                $_SESSION['user_id'],
                $_POST['estado'],
                $_POST['notas'],
                $_POST['items'] ?? []
            );
            if ($success) {
                header('Location: /sistemagestion/orders');
            } else {
                echo "Hubo un error al guardar el pedido.";
            }
            exit();
        }
    }

    public function show($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        $order = $this->orderModel->findByIdWithDetails($id);
        if (!$order) {
            echo "Pedido no encontrado.";
            exit();
        }
        require_once '../views/layouts/header.php';
        require_once '../views/pages/orders/show.php';
        require_once '../views/layouts/footer.php';
    }

    public function addPayment($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = $this->orderModel->findByIdWithDetails($id);
            if ($order) {
                $totalPagado = array_sum(array_column($order['pagos'], 'monto'));
                $saldoPendiente = $order['costo_total'] - $totalPagado;

                $monto = (float)$_POST['monto'];
                $metodo_pago = $_POST['metodo_pago'];

                // Validación de pago en el servidor
                if (!empty($monto) && is_numeric($monto) && $monto > 0 && $monto <= $saldoPendiente) {
                    $this->orderModel->addPayment($id, $monto, $metodo_pago);
                }
            }
            header('Location: /sistemagestion/orders/show/' . $id);
            exit();
        }
    }
    /**
     * ¡NUEVO! Muestra el formulario para editar un pedido.
     */
    public function showEditForm($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        $order = $this->orderModel->findByIdWithDetails($id);
        if (!$order) {
            echo "Pedido no encontrado.";
            exit();
        }
        require_once '../views/layouts/header.php';
        require_once '../views/pages/orders/edit.php';
        require_once '../views/layouts/footer.php';
    }

    /**
     * ¡NUEVO! Procesa la actualización de un pedido.
     */
    public function update($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado'];
            $notas = $_POST['notas'];
            $motivo_cancelacion = null;

            if (isset($_POST['cancelar_pedido']) && !empty($_POST['motivo_cancelacion'])) {
                $motivo_cancelacion = $_POST['motivo_cancelacion'];
            }

            $this->orderModel->update($id, $estado, $notas, $motivo_cancelacion);
            header('Location: /sistemagestion/orders/show/' . $id);
            exit();
        }
    }
}
