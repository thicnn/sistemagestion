<?php
// Calculamos los totales de pago y el saldo pendiente
$totalPagado = 0;
foreach ($order['pagos'] as $pago) {
    $totalPagado += $pago['monto'];
}
$saldoPendiente = $order['costo_total'] - $totalPagado;
?>

<h2>Detalles del Pedido #<?php echo $order['id']; ?></h2>
<a href="/sistemagestion/orders/edit/<?php echo $order['id']; ?>" class="button">Editar Pedido</a>
<div class="order-details-grid">
    <div class="details-section">
        <h4>Información General</h4>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($order['nombre_cliente'] ?? 'N/A'); ?></p>
        <p><strong>Estado:</strong> <span class="status-badge"><?php echo htmlspecialchars($order['estado']); ?></span></p>
        <p><strong>Fecha de Creación:</strong> <?php echo date('d/m/Y H:i', strtotime($order['fecha_creacion'])); ?></p>
        <p><strong>Notas:</strong> <?php echo nl2br(htmlspecialchars($order['notas_internas'] ?? 'Sin notas.')); ?></p>

        <?php if ($order['estado'] === 'Cancelado' && !empty($order['motivo_cancelacion'])): ?>
            <p class="cancel-reason"><strong>Motivo de Cancelación:</strong> <?php echo htmlspecialchars($order['motivo_cancelacion']); ?></p>
        <?php endif; ?>

        <div class="details-section financial-summary">
            <h4>Resumen Financiero</h4>
            <p><strong>Costo Total:</strong> $<?php echo number_format($order['costo_total'], 2); ?></p>
            <p class="paid"><strong>Total Pagado:</strong> $<?php echo number_format($totalPagado, 2); ?></p>
            <p class="pending"><strong>Saldo Pendiente:</strong> $<?php echo number_format($saldoPendiente, 2); ?></p>
        </div>

        <div class="details-section full-width">
            <h4>Ítems del Pedido</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['descripcion']); ?></td>
                            <td><?php echo $item['cantidad']; ?></td>
                            <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="details-section full-width">
            <h4>Historial de Pagos</h4>
            <?php if (empty($order['pagos'])): ?>
                <p>No se han registrado pagos para este pedido.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Método</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['pagos'] as $pago): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></td>
                                <td>$<?php echo number_format($pago['monto'], 2); ?></td>
                                <td><?php echo htmlspecialchars($pago['metodo_pago']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php if ($saldoPendiente > 0): ?>
                <form action="/sistemagestion/orders/add_payment/<?php echo $order['id']; ?>" method="POST" class="payment-form">
                    <h5>Registrar Nuevo Pago</h5>
                    <div class="form-group">
                        <label for="monto">Monto:</label>
                        <input type="number" name="monto" step="0.01" max="<?php echo $saldoPendiente; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago:</label>
                        <input type="text" name="metodo_pago" value="Efectivo">
                    </div>
                    <button type="submit">Registrar Pago</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <style>
        /* ... (Estilos para la nueva página) ... */
    </style>