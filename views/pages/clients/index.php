<h1>Gestión de Clientes</h1>
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
                <td><?php echo htmlspecialchars($client['telefono']); ?></td>
                <td><?php echo htmlspecialchars($client['email']); ?></td>
                <td>
                    <a href="/sistemagestion/clients/edit/<?php echo $client['id']; ?>">Editar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table>

<style>
/* Estilos para la tabla y botones */
.table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
.table th { background-color: #f2f2f2; }
.button { display: inline-block; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
.button:hover { background-color: #218838; }
</style>