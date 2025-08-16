<h1>Crear Nuevo Cliente</h1>

<form action="/sistemagestion/clients/create" method="POST">
    <div class="form-group">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono">
    </div>
    <div class="form-group">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email">
    </div>
    <div class="form-group">
        <label for="notas">Notas:</label>
        <textarea id="notas" name="notas" rows="4"></textarea>
    </div>
    <button type="submit">Guardar Cliente</button>
</form>