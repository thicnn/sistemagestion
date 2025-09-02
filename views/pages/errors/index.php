<h2>Historial de Errores de Impresión</h2>

<a href="/sistemagestion/errors/create" class="button">Registrar Nuevo Error</a>

<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo de Error</th>
            <th>Cantidad</th>
            <th>Costo Total</th>
            <th>Registrado por</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($errors)): ?>
            <tr>
                <td colspan="5">No hay errores registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($errors as $error): ?>
                <tr>
                    <td><?php echo date('d/m/Y H:i', strtotime($error['fecha_registro'])); ?></td>
                    <td><?php echo htmlspecialchars($error['tipo_error']); ?></td>
                    <td><?php echo $error['cantidad']; ?></td>
                    <td>$<?php echo number_format($error['costo_total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($error['usuario_nombre']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
