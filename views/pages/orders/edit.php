<h2>Editando Pedido #<?php echo $order['id']; ?></h2>

<form action="/sistemagestion/orders/edit/<?php echo $order['id']; ?>" method="POST">
    <div class="form-grid-main">
        <div class="form-group">
            <label>Cliente:</label>
            <input type="text" value="<?php echo htmlspecialchars($order['nombre_cliente']); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="estado">Cambiar Estado:</label>
            <select name="estado" id="estado" required>
                <?php 
                $estados = ["Solicitud", "Cotización", "Confirmado", "En Curso", "Listo para Retirar", "Entregado", "Cancelado"];
                foreach ($estados as $estado) {
                    $selected = ($order['estado'] == $estado) ? 'selected' : '';
                    echo "<option value='{$estado}' {$selected}>{$estado}</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="notas">Notas del Pedido (Opcional)</label>
        <textarea name="notas" id="notas" rows="3"><?php echo htmlspecialchars($order['notas_internas']); ?></textarea>
    </div>

    <hr>
    <button type="submit">Actualizar Pedido</button>
</form>