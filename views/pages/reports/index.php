<h2>Panel de Reportes</h2>

<div class="report-section">
    <h3>Producción del Mes y Contadores Manuales</h3>
    <div class="card-grid">
        <div class="report-card"><h4>BH-227 (Prod. del Mes)</h4><p class="data-number"><?php echo $bh227_total_prod; ?> / 2000</p></div>
        <div class="report-card"><h4>C454e (Prod. B&N Mes)</h4><p class="data-number"><?php echo $c454e_bn_prod; ?> / 950</p></div>
        <div class="report-card"><h4>C454e (Prod. Color Mes)</h4><p class="data-number"><?php echo $c454e_color_prod; ?> / 500</p></div>
        <div class="report-card wide-card">
            <h4>Registro de Contadores Manuales</h4>
            <p>Último registro: <?php echo htmlspecialchars($latestCounters[0]['fecha_fin'] ?? 'N/A'); ?> - BH-227 B&N: <?php echo htmlspecialchars($latestCounters[0]['contador_bn'] ?? 'N/A'); ?></p>
            <form action="/sistemagestion/reports/store_counter" method="POST" class="filter-form">
                <select name="maquina" id="maquina-selector" required><option value="Bh-227">Bh-227</option><option value="C454e">C454e</option></select>
                <input type="date" name="fecha_inicio" required>
                <input type="date" name="fecha_fin" required>
                <input type="number" name="contador_bn" placeholder="Contador B&N" required>
                <input type="number" name="contador_color" id="contador-color" placeholder="Contador Color">
                <button type="submit">Registrar</button>
            </form>
        </div>
    </div>
</div>

<div class="report-section">
    <h3>Gestión de Proveedor (Gramar)</h3>
    <div class="card-grid">
        <div class="report-card wide-card">
            <h4>Historial de Pagos</h4>
            <div class="table-container">
                <table class="table">
                    <thead><tr><th>Fecha</th><th>Descripción</th><th>Monto</th><th>Acciones</th></tr></thead>
                    <tbody>
                    <?php foreach($providerPayments as $payment): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($payment['fecha_pago'])); ?></td>
                            <td><?php echo htmlspecialchars($payment['descripcion']); ?></td>
                            <td>$<?php echo number_format($payment['monto'], 2); ?></td>
                            <td>
                                <form action="/sistemagestion/reports/delete_payment/<?php echo $payment['id']; ?>" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                    <button type="submit" class="action-btn delete">X</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<style>
    h2 {
        font-size: 28px;
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 30px;
    }

    .report-section {
        margin-bottom: 40px;
    }

    .report-section h3 {
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
    }

    /* --- Cuadrículas y Tarjetas --- */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .report-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .report-card h4 {
        margin-top: 0;
        margin-bottom: 10px;
        font-size: 16px;
        color: #6c757d;
    }

    .report-card p.data-number {
        font-size: 2.2em;
        font-weight: 600;
        color: #007bff;
        margin: 0;
    }

    .report-card small {
        color: #6c757d;
    }

    /* --- Formularios de Filtros y Registro --- */
    .filter-form,
    .form-card form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .filter-form {
        flex-direction: row;
        align-items: end;
        margin-bottom: 20px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    /* --- Secciones Específicas --- */
    .status-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    .provider-section .alert {
        background-color: #fff3cd;
        color: #856404;
        padding: 15px;
        border: 1px solid #ffeeba;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }

    .report-card.wide-card {
        grid-column: span 2;
    }

    /* Hace que la tabla de pagos ocupe más espacio */
    .table-container {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<script>
    document.getElementById('maquina-selector').addEventListener('change', function(){
        document.getElementById('contador-color').style.display = (this.value === 'C454e') ? 'block' : 'none';
    }).dispatchEvent(new Event('change'));
</script>