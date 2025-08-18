<?php
require_once '../models/Client.php';

class ClientController
{
    private $clientModel;

    public function __construct($db_connection)
    {
        $this->clientModel = new Client($db_connection);
    }

    /**
     * Muestra la lista de todos los clientes.
     */
    public function index()
    {
        // Protegemos la página: si no hay sesión, al login
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        $clients = $this->clientModel->findAll();

        require_once '../views/layouts/header.php';
        require_once '../views/pages/clients/index.php';
        require_once '../views/layouts/footer.php';
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function showCreateForm()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        require_once '../views/layouts/header.php';
        require_once '../views/pages/clients/create.php';
        require_once '../views/layouts/footer.php';
    }

    /**
     * Guarda el nuevo cliente en la base de datos.
     */
    /**
     * Guarda el nuevo cliente en la base de datos.
     */
    public function store()
    {
        // La protección de la sesión se mantiene
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        // Ya no necesitamos 'if ($_SERVER['REQUEST_METHOD'] === 'POST')' aquí,
        // porque el enrutador ya se encargó de verificarlo.

        $this->clientModel->create(
            $_POST['nombre'],
            $_POST['telefono'],
            $_POST['email'],
            $_POST['notas']
        );

        // Redirigimos al usuario a la lista de clientes
        header('Location: /sistemagestion/clients');
        exit();
    }
    /**
     * Muestra el formulario de edición con los datos de un cliente.
     */
    public function showEditForm($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        $client = $this->clientModel->findById($id);

        require_once '../views/layouts/header.php';
        require_once '../views/pages/clients/edit.php';
        require_once '../views/layouts/footer.php';
    }

    /**
     * Procesa la actualización de un cliente.
     */
    /**
     * Procesa la actualización de un cliente.
     */
    public function update($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sistemagestion/login');
            exit();
        }

        // Ya no se necesita la comprobación del método POST aquí.
        $this->clientModel->update(
            $id,
            $_POST['nombre'],
            $_POST['telefono'],
            $_POST['email'],
            $_POST['notas']
        );

        header('Location: /sistemagestion/clients');
        exit();
    }
    /**
     * ¡NUEVO! Maneja las peticiones de búsqueda de clientes y devuelve los resultados en JSON.
     */
    public function search()
    {
        // Solo responde si es una petición GET y si se ha enviado un término de búsqueda
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['term'])) {
            $term = $_GET['term'];
            $clients = $this->clientModel->searchByTerm($term);

            // Le decimos al navegador que la respuesta es de tipo JSON
            header('Content-Type: application/json');
            // Imprimimos los resultados codificados en JSON
            echo json_encode($clients);
            exit();
        }
    }
}
