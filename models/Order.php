<?php

class Order
{
    private $connection;
    private $table_name = "pedidos";
    private $items_table_name = "items_pedido";

    public function __construct($db_connection)
    {
        $this->connection = $db_connection;
    }

    public function findAll()
    {
        $query = "SELECT p.id, p.estado, p.costo_total, p.fecha_creacion, c.nombre as nombre_cliente 
                  FROM " . $this->table_name . " p
                  LEFT JOIN clientes c ON p.cliente_id = c.id
                  ORDER BY p.fecha_creacion DESC";
        $result = $this->connection->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function findByStatuses($statuses)
    {
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $query = "SELECT p.id, p.estado, p.costo_total, c.nombre as nombre_cliente 
                  FROM " . $this->table_name . " p
                  LEFT JOIN clientes c ON p.cliente_id = c.id
                  WHERE p.estado IN (" . $placeholders . ")
                  ORDER BY p.ultima_actualizacion ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param(str_repeat('s', count($statuses)), ...$statuses);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function findByIdWithDetails($id)
    {
        $query_pedido = "SELECT p.*, c.nombre as nombre_cliente FROM " . $this->table_name . " p LEFT JOIN clientes c ON p.cliente_id = c.id WHERE p.id = ?";
        $stmt_pedido = $this->connection->prepare($query_pedido);
        $stmt_pedido->bind_param("i", $id);
        $stmt_pedido->execute();
        $pedido = $stmt_pedido->get_result()->fetch_assoc();

        if (!$pedido) return null;

        $query_items = "SELECT * FROM items_pedido WHERE pedido_id = ?";
        $stmt_items = $this->connection->prepare($query_items);
        $stmt_items->bind_param("i", $id);
        $stmt_items->execute();
        $pedido['items'] = $stmt_items->get_result()->fetch_all(MYSQLI_ASSOC);

        $query_pagos = "SELECT * FROM pagos WHERE pedido_id = ? ORDER BY fecha_pago ASC";
        $stmt_pagos = $this->connection->prepare($query_pagos);
        $stmt_pagos->bind_param("i", $id);
        $stmt_pagos->execute();
        $pedido['pagos'] = $stmt_pagos->get_result()->fetch_all(MYSQLI_ASSOC);

        return $pedido;
    }

    public function addPayment($pedido_id, $monto, $metodo_pago)
    {
        $query = "INSERT INTO pagos (pedido_id, monto, metodo_pago) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ids", $pedido_id, $monto, $metodo_pago);
        return $stmt->execute();
    }

    public function update($id, $estado, $notas, $motivo_cancelacion = null)
    {
        // Si hay un motivo de cancelación, el estado siempre será "Cancelado"
        if ($motivo_cancelacion !== null) {
            $estado = 'Cancelado';
        }
        $query = "UPDATE " . $this->table_name . " SET estado = ?, notas_internas = ?, motivo_cancelacion = ? WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("sssi", $estado, $notas, $motivo_cancelacion, $id);
        return $stmt->execute();
    }

    public function create($cliente_id, $usuario_id, $estado, $notas, $items)
    {
        $this->connection->begin_transaction();
        try {
            // 1. Recalcular el precio total en el servidor por seguridad
            $costo_total_seguro = 0;
            $query_producto = "SELECT precio FROM productos WHERE descripcion = ? LIMIT 1";
            $stmt_producto = $this->connection->prepare($query_producto);

            foreach ($items['descripcion'] as $index => $descripcion) {
                $cantidad = (int)$items['cantidad'][$index];
                if (!empty($descripcion) && $cantidad > 0) {
                    $stmt_producto->bind_param("s", $descripcion);
                    $stmt_producto->execute();
                    $resultado = $stmt_producto->get_result()->fetch_assoc();
                    if ($resultado) {
                        $costo_total_seguro += $resultado['precio'] * $cantidad;
                    }
                }
            }

            // 2. Insertar el pedido principal
            $query_pedido = "INSERT INTO " . $this->table_name . " (cliente_id, usuario_id, estado, notas_internas, costo_total) VALUES (?, ?, ?, ?, ?)";
            $stmt_pedido = $this->connection->prepare($query_pedido);
            $stmt_pedido->bind_param("iisid", $cliente_id, $usuario_id, $estado, $notas, $costo_total_seguro);
            $stmt_pedido->execute();

            $pedido_id = $this->connection->insert_id;

            // 3. Insertar cada ítem del pedido con los nombres de columna correctos
            $query_item = "INSERT INTO " . $this->items_table_name . " (pedido_id, tipo, categoria, descripcion, cantidad, subtotal, doble_faz) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_item = $this->connection->prepare($query_item);

            foreach ($items['descripcion'] as $index => $descripcion) {
                $cantidad = (int)$items['cantidad'][$index];
                if (!empty($descripcion) && $cantidad > 0) {
                    $stmt_producto->bind_param("s", $descripcion);
                    $stmt_producto->execute();
                    $resultado = $stmt_producto->get_result()->fetch_assoc();
                    $subtotal_item = $resultado['precio'] * $cantidad;

                    $tipo_item = $items['tipo'][$index]; // ¡Nombre de variable corregido!
                    $categoria = $items['categoria'][$index];
                    $doble_faz = isset($items['doble_faz'][$index]) ? 1 : 0;

                    $stmt_item->bind_param("isssidi", $pedido_id, $tipo_item, $categoria, $descripcion, $cantidad, $subtotal_item, $doble_faz);
                    $stmt_item->execute();
                }
            }

            $this->connection->commit();
            return true;
        } catch (Exception $e) {
            $this->connection->rollback();
            error_log("Error al crear pedido: " . $e->getMessage());
            return false;
        }
    }
    public function getSalesReport($fechaInicio, $fechaFin)
    {
        $fechaFinCompleta = $fechaFin . ' 23:59:59';
        $query = "SELECT SUM(costo_total) as total_ventas, COUNT(id) as cantidad_pedidos 
                  FROM " . $this->table_name . " 
                  WHERE fecha_creacion BETWEEN ? AND ? AND estado NOT IN ('Cancelado', 'Cotización')";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("ss", $fechaInicio, $fechaFinCompleta);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_assoc() : ['total_ventas' => 0, 'cantidad_pedidos' => 0];
    }
}
