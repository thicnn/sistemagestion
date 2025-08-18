<?php

class Product {
    private $connection;
    private $table_name = "productos";

    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    /**
     * Recupera todos los productos disponibles.
     * @return array
     */
    public function findAllAvailable() {
        $query = "SELECT id, tipo_servicio, categoria, descripcion, precio FROM " . $this->table_name . " WHERE disponible = 1";

        $result = $this->connection->query($query);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}