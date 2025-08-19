<h2>Gestión de Clientes</h2>
<a href="/sistemagestion/clients/create" class="button">Crear Nuevo Cliente</a>

<table class="table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($clients)): ?>
            <tr>
                <td colspan="4">No hay clientes registrados.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo htmlspecialchars($client['nombre']); ?></td>
                    <td>
                        <span class="sensitive-data" data-content="<?php echo htmlspecialchars($client['telefono']); ?>">********</span>
                        <i class="toggle-visibility" title="Mostrar/Ocultar">👁️</i>
                    </td>
                    <td>
                        <span class="sensitive-data" data-content="<?php echo htmlspecialchars($client['email']); ?>">********</span>
                        <i class="toggle-visibility" title="Mostrar/Ocultar">👁️</i>
                    </td>
                    <td>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'administrador'): ?>
                            <a href="/sistemagestion/clients/edit/<?php echo $client['id']; ?>" class="action-btn edit">Editar</a>
                            <form action="/sistemagestion/clients/delete/<?php echo $client['id']; ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este cliente?');" style="display:inline;">
                                <button type="submit" class="action-btn delete">Eliminar</button>
                            </form>
                        <?php else: ?>
                            <span>N/A</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<style>
    .action-btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; color: white; border: none; cursor: pointer; font-size: 14px; }
    .action-btn.edit { background-color: #007bff; }
    .action-btn.delete { background-color: #dc3545; }
    .toggle-visibility { cursor: pointer; margin-left: 5px; user-select: none; }
</style>

<script>
document.querySelectorAll('.toggle-visibility').forEach(item => {
    item.addEventListener('click', event => {
        const span = event.target.previousElementSibling;
        const isHidden = span.textContent.includes('*');
        span.textContent = isHidden ? span.dataset.content : '********';
        event.target.textContent = isHidden ? '🙈' : '👁️';
    });
});
</script>