<h2>Crear Nuevo Pedido</h2>

<script>
    // Pasamos los datos de los productos de PHP a JavaScript para que el formulario los use
    const productsData = <?php echo json_encode($products); ?>;
</script>

<form action="/sistemagestion/orders/create" method="POST" id="order-form">
    
    <div class="form-section">
        <div class="form-grid-main">
            <div class="form-group">
                <label for="cliente_search">Buscar y Asociar Cliente (por Teléfono o Email):</label>
                <input type="text" id="cliente_search" placeholder="Escribe para buscar...">
                <input type="hidden" name="cliente_id" id="cliente_id" class="form-validator">
                <div id="search-results"></div> </div>
            <div class="form-group">
                <label for="estado">Estado Inicial del Pedido</label>
                <select name="estado" id="estado" class="form-validator" required>
                    <option value="Solicitud" selected>Solicitud</option>
                    <option value="Cotización">Cotización</option>
                    <option value="Confirmado">Confirmado</option>
                    <option value="En Curso">En Curso</option>
                    <option value="Listo para Retirar">Listo para Retirar</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
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
    
    <div class="total-section">
        <strong>Costo Total del Pedido: $</strong><span id="total-pedido">0.00</span>
    </div>

    <hr>
    
    <button type="submit" id="submit-button">Guardar Pedido</button>
</form>

<template id="item-template">
    <div class="item-form">
        <div class="form-grid-item">
            <div class="form-group"><label>Tipo de Servicio</label><select class="item-selector tipo_servicio form-validator" name="items[tipo_servicio][]"><option value="">Seleccionar...</option></select></div>
            <div class="form-group"><label>Categoría</label><select class="item-selector categoria form-validator" name="items[categoria][]" disabled><option value="">Seleccionar...</option></select></div>
            <div class="form-group"><label>Descripción</label><select class="item-selector descripcion form-validator" name="items[descripcion][]" disabled><option value="">Seleccionar...</option></select></div>
            <div class="form-group"><label>Cantidad (Carillas)</label><input type="number" class="item-selector cantidad form-validator" name="items[cantidad][]" min="1" value="1" disabled></div>
            <div class="form-group-checkbox"><input type="checkbox" class="item-selector doble_faz" name="items[doble_faz][]" value="1" disabled><label>Es Doble Faz</label></div>
        </div>
        <div class="subtotal-section"><strong>Subtotal: $</strong><span class="subtotal-item">0.00</span></div>
        <button type="button" class="remove-item-btn">Eliminar Ítem</button>
    </div>
</template>


<style>
    /* --- Contenedores y Cuadrículas --- */
    .form-section { 
        background-color: #fdfdfd; 
        border: 1px solid #e9ecef;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .form-grid-main { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
    }
    .form-grid-item { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); 
        gap: 15px; 
        align-items: end; 
    }

    /* --- Ítems Dinámicos --- */
    .item-form { 
        border: 1px solid #e0e0e0; 
        padding: 20px; 
        border-radius: 8px; 
        margin-bottom: 15px; 
        background-color: #f8f9fa;
    }

    /* --- Campos de Formulario --- */
    .form-group, .form-group-checkbox { margin-bottom: 0; } /* Reseteo para grids */
    label { 
        display: block; 
        margin-bottom: 8px; 
        font-weight: 500; 
        color: #495057;
        font-size: 14px;
    }
    input, textarea, select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 16px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .form-group-checkbox { display: flex; align-items: center; padding-bottom: 10px; }
    .form-group-checkbox input { width: auto; margin-right: 10px; }

    /* --- Botones --- */
    .remove-item-btn {
        background-color: #dc3545;
        color: white;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 15px;
        font-size: 14px;
        border: none;
    }
    .remove-item-btn:hover { background-color: #c82333; }
    #submit-button:disabled { background-color: #6c757d; cursor: not-allowed; }

    /* --- Secciones de Totales --- */
    .total-section, .subtotal-section { 
        text-align: right; 
        font-size: 1.2em; 
        margin-top: 15px; 
        font-weight: bold;
        color: #343a40;
    }

    /* --- Buscador de Clientes --- */
    #search-results { 
        position: relative;
        z-index: 10;
    }
    #search-results .result-item {
        background-color: #fff;
        border: 1px solid #ddd;
        border-top: none;
        padding: 10px;
        cursor: pointer;
    }
    #search-results .result-item:hover {
        background-color: #e9ecef;
    }
</style>


<script src="/sistemagestion/public/js/order_form.js"></script>