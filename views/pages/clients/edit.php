<h2>Editar Cliente: <?php echo htmlspecialchars($client['nombre']); ?></h2>

<form action="/sistemagestion/clients/edit/<?php echo $client['id']; ?>" method="POST">
    <div class="form-group">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($client['nombre']); ?>" required>
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($client['telefono']); ?>">
    </div>
    <div class="form-group">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>">
    </div>
    <div class="form-group">
        <label for="notas">Notas (Requerimientos del cliente):</label>
        <textarea id="notas" name="notas" rows="4"><?php echo htmlspecialchars($client['notas']); ?></textarea>
    </div>
    <button type="submit">Actualizar Cliente</button>
</form>