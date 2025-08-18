<h2>Editando Producto</h2>
<form action="/sistemagestion/admin/products/edit/<?php echo $product['id']; ?>" method="POST">
    <div class="form-group">
        <label for="descripcion">Descripción (Nombre del Producto)</label>
        <input type="text" name="descripcion" value="<?php echo htmlspecialchars($product['descripcion']); ?>" required>
    </div>
    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" name="precio" step="0.01" value="<?php echo htmlspecialchars($product['precio']); ?>" required>
    </div>
    <div class="form-group">
        <label>
            <input type="checkbox" name="disponible" value="1" <?php echo $product['disponible'] ? 'checked' : ''; ?>>
            Disponible (aparecerá en la creación de pedidos)
        </label>
    </div>
    <button type="submit">Actualizar Producto</button>
</form>