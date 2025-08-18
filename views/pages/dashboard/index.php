<h2>Cola de Producción</h2>
<p>Aquí puedes ver el estado actual de los trabajos en el taller.</p>

<div class="production-queues">

    <div class="queue-column">
        <h3><span class="status-circle in-progress"></span>En Curso</h3>
        <div class="order-list">
            <?php if (empty($pedidosEnCurso)): ?>
                <p>No hay pedidos en curso.</p>
            <?php else: ?>
                <?php foreach ($pedidosEnCurso as $pedido): ?>
                    <div class="order-card">
                        <strong>Pedido #<?php echo $pedido['id']; ?></strong>
                        <p><?php echo htmlspecialchars($pedido['nombre_cliente'] ?? 'Sin cliente'); ?></p>
                        <span>$<?php echo number_format($pedido['costo_total'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="queue-column">
        <h3><span class="status-circle ready"></span>Listos para Retirar</h3>
         <div class="order-list">
            <?php if (empty($pedidosListos)): ?>
                <p>No hay pedidos listos.</p>
            <?php else: ?>
                <?php foreach ($pedidosListos as $pedido): ?>
                    <div class="order-card">
                        <strong>Pedido #<?php echo $pedido['id']; ?></strong>
                        <p><?php echo htmlspecialchars($pedido['nombre_cliente'] ?? 'Sin cliente'); ?></p>
                        <span>$<?php echo number_format($pedido['costo_total'], 2); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<style>
    .production-queues { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .queue-column h3 { display: flex; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status-circle { width: 15px; height: 15px; border-radius: 50%; margin-right: 10px; }
    .status-circle.in-progress { background-color: #ffc107; } /* Amarillo */
    .status-circle.ready { background-color: #28a745; } /* Verde */
    .order-list { margin-top: 15px; }
    .order-card { background-color: #f8f9fa; border: 1px solid #dee2e6; border-left: 5px solid #ffc107; padding: 15px; border-radius: 5px; margin-bottom: 10px; }
    .queue-column:last-child .order-card { border-left-color: #28a745; }
    .order-card strong { font-size: 1.1em; }
    .order-card p { margin: 5px 0; }
    .order-card span { font-weight: bold; color: #333; }
</style>