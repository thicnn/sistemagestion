<h2>Gestión de Pedidos</h2>
<a href="/sistemagestion/orders/create" class="button">Crear Nuevo Pedido</a>

<table class="table">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Costo Total</th>
            <th>Fecha de Creación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)): ?>
            <tr>
                <td colspan="6">No hay pedidos registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['nombre_cliente'] ?? 'Sin cliente'); ?></td>
                    <td><?php echo htmlspecialchars($order['estado']); ?></td>
                    <td>$<?php echo number_format($order['costo_total'], 2); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['fecha_creacion'])); ?></td>
                    <td>
    <a href="/sistemagestion/orders/show/<?php echo $order['id']; ?>">Ver/Editar</a>
</td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>