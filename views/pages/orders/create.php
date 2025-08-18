<h2>Crear Nuevo Pedido</h2>
<script>
    const productsData = <?php echo json_encode($products); ?>;
</script>

<form action="/sistemagestion/orders/create" method="POST" id="order-form">
    <div class="form-section">
        <div class="form-grid-main">
            <div class="form-group">
                <label for="cliente_search">Buscar y Asociar Cliente:</label>
                <input type="text" id="cliente_search" placeholder="Buscar por Teléfono o Email...">
                <input type="hidden" name="cliente_id" id="cliente_id" class="form-validator">
                <div id="search-results"></div>
            </div>
            <div class="form-group">
                <label for="estado">Estado Inicial del Pedido</label>
                <select name="estado" id="estado" class="form-validator" required>
                    <option value="Solicitud" selected>Solicitud</option>
                    <option value="Cotización">Cotización</option>
                    <option value="Confirmado">Confirmado</option>
                    <option value="En Curso">En Curso</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="notas">Notas del Pedido (Opcional)</label>
            <textarea name="notas" id="notas" rows="2"></textarea>
        </div>
    </div>
    <hr>
    <h3>Ítems del Pedido</h3>
    <div id="items-container"></div>
    <button type="button" id="add-item-btn" class="button">Añadir Ítem</button>
    <div class="total-section"><strong>Costo Total: $</strong><span id="total-pedido">0.00</span></div>
    <hr>
    <button type="submit" id="submit-button">Guardar Pedido</button>
</form>

<template id="item-template">
    <div class="item-form">
        <div class="form-grid-item">
            <div class="form-group"><label>Tipo</label><select class="item-selector tipo form-validator" name="items[tipo][]">
                    <option value="">Seleccionar...</option>
                </select></div>
            <div class="form-group"><label>Categoría</label><select class="item-selector categoria form-validator" name="items[categoria][]" disabled>
                    <option value="">Seleccionar...</option>
                </select></div>
            <div class="form-group"><label>Descripción</label><select class="item-selector descripcion form-validator" name="items[descripcion][]" disabled>
                    <option value="">Seleccionar...</option>
                </select></div>
            <div class="form-group"><label>Cantidad (Carillas)</label><input type="number" class="item-selector cantidad form-validator" name="items[cantidad][]" min="1" value="1" disabled></div>
            <div class="form-group-checkbox"><input type="checkbox" class="item-selector doble_faz" name="items[doble_faz][]" value="1" disabled><label>Es Doble Faz</label></div>
        </div>
        <div class="subtotal-section"><strong>Subtotal: $</strong><span class="subtotal-item">0.00</span></div>
        <button type="button" class="remove-item-btn">Eliminar Ítem</button>
    </div>
</template>

<style>
    /* ... (Tus estilos se mantienen igual) ... */
</style>
<script src="/sistemagestion/public/js/order_form.js"></script>