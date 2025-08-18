<?php
class Report {
    private $connection;

    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    // Cuenta todos los pedidos por cada estado
    public function countOrdersByStatus() {
        $query = "SELECT estado, COUNT(id) as total FROM pedidos GROUP BY estado";
        $result = $this->connection->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Cuenta la producción por máquina y tipo
    public function getProductionCount($maquina_id, $tipo, $categoria) {
        $query = "SELECT SUM(i.cantidad) as total 
                  FROM items_pedido i
                  JOIN productos p ON i.descripcion = p.descripcion
                  WHERE p.maquina_id = ? AND i.tipo = ? AND i.categoria = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iss", $maquina_id, $tipo, $categoria);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }

    // Guarda un nuevo registro de contador
    public function saveCounter($maquina, $periodo, $fecha, $bn, $color, $notas) {
        $query = "INSERT INTO impresora_contadores (maquina_nombre, periodo, fecha_registro, contador_bn, contador_color, notas) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssiis", $maquina, $periodo, $fecha, $bn, $color, $notas);
        return $stmt->execute();
    }

    // Guarda un nuevo pago al proveedor
    public function saveProviderPayment($fecha, $descripcion, $monto) {
        $query = "INSERT INTO proveedor_pagos (fecha_pago, descripcion, monto) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ssd", $fecha, $descripcion, $monto);
        return $stmt->execute();
    }

    // Obtiene el historial de pagos al proveedor
    public function getProviderPayments() {
        $query = "SELECT * FROM proveedor_pagos ORDER BY fecha_pago DESC";
        $result = $this->connection->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}