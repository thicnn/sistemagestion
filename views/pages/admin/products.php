<h2>Gestionar Productos</h2>
<a href="/sistemagestion/admin/products/create" class="button">Crear Nuevo Producto</a>

<form action="/sistemagestion/admin/products" method="GET" class="filter-form">
    <input type="text" id="search-box" name="search" placeholder="Buscar por descripción..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <select name="tipo" onchange="this.form.submit()">
        <option value="">Todos los Tipos</option>
        <option value="Impresion" <?php echo ($_GET['tipo'] ?? '') == 'Impresion' ? 'selected' : ''; ?>>Impresión</option>
        <option value="Fotocopia" <?php echo ($_GET['tipo'] ?? '') == 'Fotocopia' ? 'selected' : ''; ?>>Fotocopia</option>
        <option value="Servicio" <?php echo ($_GET['tipo'] ?? '') == 'Servicio' ? 'selected' : ''; ?>>Servicio</option>
    </select>
    <button type="submit">Filtrar</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Categoría</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Disponibilidad</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="products-table-body">
    </tbody>
</table>

<script>
    const productsTableBody = document.getElementById('products-table-body');
    const searchBox = document.getElementById('search-box');
    let initialProducts = <?php echo json_encode($products); ?>;

    function renderTable(products) {
        productsTableBody.innerHTML = '';
        if (products.length === 0) {
            productsTableBody.innerHTML = '<tr><td colspan="6">No se encontraron productos.</td></tr>';
            return;
        }
        products.forEach(product => {
            const row = `
            <tr>
                <td>${product.tipo}</td>
                <td>${product.categoria}</td>
                <td>${product.descripcion}</td>
                <td>$${product.precio}</td>
                <td>${product.disponible == 1 ? 'Activo' : 'Inactivo'}</td>
                <td>
                    <a href="/sistemagestion/admin/products/edit/${product.id}" class="action-btn edit">Editar</a>
                    <form action="/sistemagestion/admin/products/delete/${product.id}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');" style="display:inline;">
                        <button type="submit" class="action-btn delete">Eliminar</button>
                    </form>
                </td>
            </tr>`;
            productsTableBody.innerHTML += row;
        });
    }

    searchBox.addEventListener('keyup', async function() {
        const searchTerm = searchBox.value.toLowerCase();
        // Aseguramos que el filtro de tipo se mantenga al buscar
        const tipoFiltro = document.querySelector('select[name="tipo"]').value;
        const response = await fetch(`/sistemagestion/admin/products?ajax=1&search=${searchTerm}&tipo=${tipoFiltro}`);
        const filteredProducts = await response.json();
        renderTable(filteredProducts);
    });

    // Carga inicial de la tabla
    renderTable(initialProducts);
</script>