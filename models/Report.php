<?php
class Report {
    private $connection;

    public function __construct($db_connection) {
        $this->connection = $db_connection;
    }

    public function countOrdersByStatus() { /* ... se mantiene igual ... */ }
    public function getProductionCountForPeriod($maquina_id, $tipo, $categoria, $inicio, $fin) { /* ... se mantiene igual ... */ }

    // --- MÉTODOS NUEVOS Y MEJORADOS ---
    public function getLatestCounters() {
        $query = "SELECT * FROM impresora_contadores ORDER BY fecha_fin DESC LIMIT 2";
        $result = $this->connection->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function saveCounter($maquina, $fecha_inicio, $fecha_fin, $bn, $color, $notas) {
        $query = "INSERT INTO impresora_contadores (maquina_nombre, fecha_inicio, fecha_fin, contador_bn, contador_color, notas) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $color = empty($color) ? 0 : $color;
        $stmt->bind_param("sssiis", $maquina, $fecha_inicio, $fecha_fin, $bn, $color, $notas);
        return $stmt->execute();
    }

    public function saveProviderPayment($fecha, $descripcion, $monto) { /* ... se mantiene igual ... */ }
    public function getProviderPayments() { /* ... se mantiene igual ... */ }

    public function deleteProviderPayment($id) {
        $stmt = $this->connection->prepare("DELETE FROM proveedor_pagos WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}