<h2>Editando Pedido #<?php echo $order['id']; ?></h2>

<form action="/sistemagestion/orders/edit/<?php echo $order['id']; ?>" method="POST">
    <div class="form-group">
        <label>Cliente:</label>
        <input type="text" value="<?php echo htmlspecialchars($order['nombre_cliente']); ?>" disabled>
    </div>
    <div class="form-group">
        <label for="estado">Cambiar Estado:</label>
        <select name="estado" id="estado" required <?php echo $order['estado'] === 'Cancelado' ? 'disabled' : ''; ?>>
            <?php
            $estados = ["Solicitud", "Cotización", "Confirmado", "En Curso", "Listo para Retirar", "Entregado"];
            foreach ($estados as $estado) {
                $selected = ($order['estado'] == $estado) ? 'selected' : '';
                echo "<option value='{$estado}' {$selected}>{$estado}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="notas">Notas del Pedido:</label>
        <textarea name="notas" id="notas" rows="3" <?php echo $order['estado'] === 'Cancelado' ? 'disabled' : ''; ?>><?php echo htmlspecialchars($order['notas_internas']); ?></textarea>
    </div>

    <hr>

    <?php if ($order['estado'] !== 'Cancelado'): ?>
        <div class="cancel-section">
            <label><input type="checkbox" id="cancelar-checkbox" name="cancelar_pedido"> <strong>Cancelar Pedido</strong></label>
            <div class="form-group" id="motivo-container" style="display:none; margin-top:10px;">
                <label for="motivo_cancelacion">Motivo de la Cancelación (requerido):</label>
                <textarea name="motivo_cancelacion" id="motivo_cancelacion" rows="3"></textarea>
            </div>
        </div>
        <hr>
    <?php endif; ?>

    <button type="submit" <?php echo $order['estado'] === 'Cancelado' ? 'disabled' : ''; ?>>Actualizar Pedido</button>
</form>

<script>
    // Script para mostrar/ocultar el motivo de cancelación
    const cancelarCheckbox = document.getElementById('cancelar-checkbox');
    const motivoContainer = document.getElementById('motivo-container');
    const motivoTextarea = document.getElementById('motivo_cancelacion');
    const estadoSelect = document.getElementById('estado');

    cancelarCheckbox.addEventListener('change', function() {
        if (this.checked) {
            motivoContainer.style.display = 'block';
            motivoTextarea.required = true;
            estadoSelect.disabled = true;
        } else {
            motivoContainer.style.display = 'none';
            motivoTextarea.required = false;
            estadoSelect.disabled = false;
        }
    });
</script>