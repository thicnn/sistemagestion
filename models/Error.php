<?php
class ErrorModel {
    private $connection;
    private $table_name = "pedidos_errores";

    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    public function create($tipo_error, $cantidad, $costo_total, $registrado_por_id) {
        $query = "INSERT INTO " . $this->table_name . " (tipo_error, cantidad, costo_total, registrado_por_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sidi", $tipo_error, $cantidad, $costo_total, $registrado_por_id);
        return $stmt->execute();
    }

    public function findAll() {
        $query = "SELECT pe.*, u.nombre as usuario_nombre
                  FROM " . $this->table_name . " pe
                  LEFT JOIN usuarios u ON pe.registrado_por_id = u.id
                  ORDER BY pe.fecha_registro DESC";
        $result = $this->connection->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
