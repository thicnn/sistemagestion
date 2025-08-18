<?php
require_once '../models/Config.php';
require_once '../models/Product.php';

class AdminController
{
    private $configModel;
    private $productModel;

    public function __construct($db_connection)
    {
        $this->configModel = new Config($db_connection);
        $this->productModel = new Product($db_connection);
    }

    public function settings()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        $papeles = $this->configModel->findByType('papel');
        $acabados = $this->configModel->findByType('acabado');
        require_once '../views/layouts/header.php';
        require_once '../views/pages/admin/settings.php';
        require_once '../views/layouts/footer.php';
    }

    public function storeSetting()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->configModel->create($_POST['tipo'], $_POST['nombre'], $_POST['valor']);
        }
        header('Location: /sistemagestion/admin/settings');
        exit();
    }

    public function listProducts()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        $filters = ['search' => $_GET['search'] ?? null, 'tipo' => $_GET['tipo'] ?? null];
        $products = $this->productModel->searchAndFilter($filters);
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode($products);
            exit();
        }
        require_once '../views/layouts/header.php';
        require_once '../views/pages/admin/products.php';
        require_once '../views/layouts/footer.php';
    }

    public function showProductCreateForm()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        require_once '../views/layouts/header.php';
        require_once '../views/pages/admin/create_product.php';
        require_once '../views/layouts/footer.php';
    }

    public function storeProduct()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->productModel->create(
                $_POST['tipo'],
                $_POST['categoria'] ?? '',
                $_POST['descripcion'],
                $_POST['precio']
            );
        }
        header('Location: /sistemagestion/admin/products');
        exit();
    }

    public function showProductEditForm($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        $product = $this->productModel->findById($id);
        require_once '../views/layouts/header.php';
        require_once '../views/pages/admin/edit_product.php';
        require_once '../views/layouts/footer.php';
    }

    public function updateProduct($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $disponible = isset($_POST['disponible']) ? 1 : 0;
            $this->productModel->update($id, $_POST['descripcion'], $_POST['precio'], $disponible);
        }
        header('Location: /sistemagestion/admin/products');
        exit();
    }
    /**
     * ¡NUEVO! Procesa la eliminación de un producto.
     */
    public function deleteProduct($id)
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'administrador') {
            header('Location: /sistemagestion/dashboard');
            exit();
        }
        $this->productModel->delete($id);
        // Redirigimos de vuelta a la lista de productos
        header('Location: /sistemagestion/admin/products');
        exit();
    }
}
