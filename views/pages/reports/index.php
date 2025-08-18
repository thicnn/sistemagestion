<h2>Reporte de Ventas</h2>
<p>Selecciona un rango de fechas para generar el reporte.</p>

<form action="/sistemagestion/reports" method="POST" class="report-form">
    <div class="form-group">
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fechaInicio); ?>">
    </div>
    <div class="form-group">
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fechaFin); ?>">
    </div>
    <button type="submit">Generar Reporte</button>
</form>

<div class="report-results">
    <h3>Resultados para el período del <?php echo date('d/m/Y', strtotime($fechaInicio)); ?> al <?php echo date('d/m/Y', strtotime($fechaFin)); ?></h3>
    <div class="result-card">
        <h4>Ventas Totales</h4>
        <p>$<?php echo number_format($reportData['total_ventas'] ?? 0, 2); ?></p>
    </div>
    <div class="result-card">
        <h4>Pedidos Completados</h4>
        <p><?php echo $reportData['cantidad_pedidos'] ?? 0; ?></p>
    </div>
</div>

<style>
    .report-form { display: flex; gap: 20px; align-items: end; margin-bottom: 30px; }
    .report-results { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; text-align: center; }
    .result-card { background-color: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #dee2e6; }
    .result-card h4 { margin-top: 0; }
    .result-card p { font-size: 2em; font-weight: bold; color: #007bff; margin-bottom: 0; }
</style>