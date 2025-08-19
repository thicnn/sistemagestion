<h2>Crear Nuevo Producto</h2>
<form action="/sistemagestion/admin/products/create" method="POST">
    <div class="form-group">
        <label for="tipo">Tipo de Producto</label>
        <select name="tipo" id="tipo-producto" required>
            <option value="">Seleccionar...</option>
            <option value="Impresion">Impresión</option>
            <option value="Fotocopia">Fotocopia</option>
            <option value="Servicio">Servicio</option>
        </select>
    </div>

    <div id="campos-extra">
        <div class="form-group">
            <label for="categoria">Categoría</label>
            <input type="text" name="categoria" placeholder="Ej: Blanco y Negro, Color">
        </div>
    </div>

    <div class="form-group">
        <label for="descripcion">Descripción / Nombre</label>
        <input type="text" name="descripcion" required>
    </div>
    <div class="form-group">
        <label for="precio">Precio</label>
        <input type="number" name="precio" step="0.01" required>
    </div>
    <button type="submit">Guardar Producto</button>
</form>

<script>
document.getElementById('tipo-producto').addEventListener('change', function() {
    // Si se selecciona 'Servicio', se ocultan los campos extra
    if (this.value === 'Servicio') {
        document.getElementById('campos-extra').style.display = 'none';
    } else {
        document.getElementById('campos-extra').style.display = 'block';
    }
});
</script>