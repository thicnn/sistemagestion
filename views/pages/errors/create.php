<h2>Registrar Error de Impresión</h2>

<form action="/sistemagestion/errors/store" method="POST" class="form-container">
    <div class="form-group">
        <label for="tipo_error">Tipo de Error</label>
        <select name="tipo_error" id="tipo_error" required>
            <option value="">Seleccione un tipo de error</option>
            <option value="Blanco y negro de Bh227">Blanco y negro de Bh227 ($0.9 por copia)</option>
            <option value="Blanco y negro de C454">Blanco y negro de C454 ($3 por copia)</option>
            <option value="Color c454">Color c454 ($10 por copia)</option>
        </select>
    </div>

    <div class="form-group">
        <label for="cantidad">Cantidad de Copias de Error</label>
        <input type="number" name="cantidad" id="cantidad" min="1" required>
    </div>

    <button type="submit" class="button">Registrar Error</button>
</form>

<style>
    .form-container {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group select,
    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .button {
        background-color: #dc3545;
        color: white;
    }
</style>
