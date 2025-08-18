document.addEventListener('DOMContentLoaded', function() {
    const orderForm = document.getElementById('order-form');
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsContainer = document.getElementById('items-container');
    const template = document.getElementById('item-template');
    const totalPedidoSpan = document.getElementById('total-pedido');
    const submitButton = document.getElementById('submit-button');

    // --- ¡INICIO DE LA NUEVA LÓGICA DE BÚSQUEDA DE CLIENTE! ---
    const clientSearchInput = document.getElementById('cliente_search');
    const clientHiddenInput = document.getElementById('cliente_id'); // Este es el input que usará la validación
    const searchResultsDiv = document.getElementById('search-results');

    clientSearchInput.addEventListener('keyup', async function() {
        const searchTerm = clientSearchInput.value;
        searchResultsDiv.innerHTML = ''; 

        if (searchTerm.length < 2) {
            clientHiddenInput.value = ''; // Limpiamos el ID si se borra la búsqueda
            validateForm();
            return;
        }

        const response = await fetch(`/sistemagestion/clients/search?term=${searchTerm}`);
        const clients = await response.json();

        clients.forEach(client => {
            const resultItem = document.createElement('div');
            resultItem.classList.add('result-item');
            resultItem.textContent = `${client.nombre} - ${client.telefono || client.email}`;
            resultItem.dataset.clientId = client.id;
            resultItem.dataset.clientName = client.nombre;
            searchResultsDiv.appendChild(resultItem);
        });
    });

    searchResultsDiv.addEventListener('click', function(e) {
        if (e.target.classList.contains('result-item')) {
            const selectedId = e.target.dataset.clientId;
            const selectedName = e.target.dataset.clientName;

            clientHiddenInput.value = selectedId;
            clientSearchInput.value = selectedName;
            
            searchResultsDiv.innerHTML = '';
            validateForm(); 
        }
    });
    // --- FIN DE LA LÓGICA DE BÚSQUEDA ---


    const validateForm = () => {
        let isFormValid = true;
        // ¡CAMBIO IMPORTANTE! Ahora validamos el input oculto en lugar del select
        const clienteSeleccionado = document.getElementById('cliente_id').value;

        if (!clienteSeleccionado) isFormValid = false;

        const items = itemsContainer.querySelectorAll('.item-form');
        if (items.length === 0) isFormValid = false;

        items.forEach(item => {
            const desc = item.querySelector('.descripcion').value;
            const qty = parseInt(item.querySelector('.cantidad').value);
            if (!desc || qty < 1) isFormValid = false;
        });

        submitButton.disabled = !isFormValid;
    };

    // --- El resto de tu código se mantiene exactamente igual ---
    const calculateTotals = () => {
        let totalGeneral = 0;
        itemsContainer.querySelectorAll('.item-form').forEach(itemForm => {
            const selectedDescripcion = itemForm.querySelector('.descripcion').value;
            const cantidadCarillas = parseInt(itemForm.querySelector('.cantidad').value) || 0;
            const product = productsData.find(p => p.descripcion === selectedDescripcion);
            let subtotal = 0;
            if (product && cantidadCarillas > 0) {
                subtotal = product.precio * cantidadCarillas;
            }
            itemForm.querySelector('.subtotal-item').textContent = subtotal.toFixed(2);
            totalGeneral += subtotal;
        });
        totalPedidoSpan.textContent = totalGeneral.toFixed(2);
    };

    const updateItemState = (itemForm) => {
        const selects = {
            tipo: itemForm.querySelector('.tipo_servicio'),
            cat: itemForm.querySelector('.categoria'),
            desc: itemForm.querySelector('.descripcion'),
            qty: itemForm.querySelector('.cantidad'),
            faz: itemForm.querySelector('.doble_faz')
        };
        const values = {
            tipo: selects.tipo.value,
            cat: selects.cat.value,
            desc: selects.desc.value
        };
        if (values.tipo) {
            const cats = [...new Set(productsData.filter(p => p.tipo_servicio === values.tipo).map(p => p.categoria))];
            populateSelect(selects.cat, cats, values.cat);
            selects.cat.disabled = false;
        }
        if (values.tipo && values.cat) {
            const descs = productsData.filter(p => p.tipo_servicio === values.tipo && p.categoria === values.cat).map(p => p.descripcion);
            populateSelect(selects.desc, descs, values.desc);
            selects.desc.disabled = false;
        }
        const allSelected = values.tipo && values.cat && values.desc;
        selects.qty.disabled = !allSelected;
        selects.faz.disabled = !allSelected;
        calculateTotals();
        validateForm();
    };

    const populateSelect = (select, options, selectedValue) => {
        select.innerHTML = '<option value="">Seleccionar...</option>';
        options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt;
            option.textContent = opt;
            select.appendChild(option);
        });
        select.value = selectedValue;
    };

    addItemBtn.addEventListener('click', () => {
        const newItem = template.content.cloneNode(true);
        const tipoSelect = newItem.querySelector('.tipo_servicio');
        populateSelect(tipoSelect, [...new Set(productsData.map(p => p.tipo_servicio))]);
        itemsContainer.appendChild(newItem);
        validateForm();
    });

    itemsContainer.addEventListener('input', e => {
        if (e.target.classList.contains('item-selector')) {
            const itemForm = e.target.closest('.item-form');
            if (e.target.classList.contains('tipo_servicio')) {
                itemForm.querySelector('.categoria').value = '';
                itemForm.querySelector('.descripcion').value = '';
            }
            if (e.target.classList.contains('categoria')) {
                itemForm.querySelector('.descripcion').value = '';
            }
            updateItemState(itemForm);
        }
    });

    itemsContainer.addEventListener('click', e => {
        if (e.target.classList.contains('remove-item-btn')) {
            e.target.closest('.item-form').remove();
            calculateTotals();
            validateForm();
        }
    });

    orderForm.addEventListener('change', validateForm);
    orderForm.addEventListener('submit', e => {
        if (submitButton.disabled) {
            e.preventDefault();
            alert('Por favor, complete todos los campos requeridos para guardar el pedido.');
        }
    });

    validateForm();
});