<h2>Panel de Reportes</h2>

<div class="report-section">
    <h3>Ventas por Período</h3>
    <form action="/sistemagestion/reports" method="GET" class="filter-form">
        <div class="form-group">
            <label>Fecha de Inicio:</label>
            <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fechaInicio); ?>">
        </div>
        <div class="form-group">
            <label>Fecha de Fin:</label>
            <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fechaFin); ?>">
        </div>
        <button type="submit">Generar</button>
    </form>
    <div class="card-grid">
        <div class="report-card">
            <h4>Ventas Totales</h4>
            <p class="data-number">$<?php echo number_format($salesData['total_ventas'] ?? 0, 2); ?></p>
        </div>
        <div class="report-card">
            <h4>Pedidos en Período</h4>
            <p class="data-number"><?php echo $salesData['cantidad_pedidos'] ?? 0; ?></p>
        </div>
    </div>
</div>

<div class="report-section">
    <h3>Resumen de Pedidos por Estado</h3>
    <div class="card-grid status-grid">
        <?php
        $status_totals = [];
        foreach ($statusCounts as $status) {
            $status_totals[$status['estado']] = $status['total'];
        }
        $all_statuses = ["Solicitud", "Cotización", "Confirmado", "En Curso", "Listo para Retirar", "Entregado", "Cancelado"];
        foreach ($all_statuses as $s) {
            echo "<div class='report-card'><h4>{$s}</h4><p class='data-number'>" . ($status_totals[$s] ?? 0) . "</p></div>";
        }
        ?>
    </div>
</div>

<div class="report-section">
    <h3>Producción y Contadores de Máquinas</h3>
    <div class="card-grid">
        <div class="report-card">
            <h4>Bh-227 (Total B&N)</h4>
            <p class="data-number"><?php echo $bh227_total; ?> / 2000</p>
            <small>Mínimo mensual</small>
        </div>
        <div class="report-card">
            <h4>C454e (Total B&N)</h4>
            <p class="data-number"><?php echo $c454e_bn; ?> / 950</p>
            <small>Mínimo mensual</small>
        </div>
        <div class="report-card">
            <h4>C454e (Total Color)</h4>
            <p class="data-number"><?php echo $c454e_color; ?> / 500</p>
            <small>Mínimo mensual</small>
        </div>
        <div class="report-card form-card">
            <h4>Registrar Contador</h4>
            <form action="/sistemagestion/reports/store_counter" method="POST">
                <select name="maquina" required>
                    <option value="Bh-227">Bh-227</option>
                    <option value="C454e">C454e</option>
                </select>
                <input type="number" name="contador_bn" placeholder="Contador B&N">
                <input type="number" name="contador_color" placeholder="Contador Color (si aplica)">
                <input type="hidden" name="periodo" value="mensual">
                <input type="hidden" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit">Registrar</button>
            </form>
        </div>
    </div>
</div>

<div class="report-section">
    <h3>Gestión de Proveedor (Gramar)</h3>
    <div class="provider-section">
        <div class="alert">
            <strong>Próximo Vencimiento:</strong> 1 de <?php echo date('F', strtotime('+1 month')); ?>
        </div>
        <div class="card-grid">
            <div class="report-card form-card">
                <h4>Registrar Pago a Proveedor</h4>
                <form action="/sistemagestion/reports/store_payment" method="POST">
                    <input type="date" name="fecha_pago" value="<?php echo date('Y-m-d'); ?>" required>
                    <input type="text" name="descripcion" placeholder="Descripción (ej: Alquiler Agosto)" required>
                    <input type="number" name="monto" step="0.01" placeholder="Monto" required>
                    <button type="submit">Registrar Pago</button>
                </form>
            </div>
            <div class="report-card wide-card">
                <h4>Historial de Pagos</h4>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($providerPayments)): ?>
                                <tr>
                                    <td colspan="3">No hay pagos registrados.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($providerPayments as $payment): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($payment['fecha_pago'])); ?></td>
                                        <td><?php echo htmlspecialchars($payment['descripcion']); ?></td>
                                        <td>$<?php echo number_format($payment['monto'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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