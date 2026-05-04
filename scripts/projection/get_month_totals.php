<?php
// Open database connection
Conexion::abrir_conexion();

try {
  // Validate and sanitize input
  if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get month totals from the repository
    $totals = YearlyProjectionRepository::getMonthTotals(Conexion::obtener_conexion(), $id);
  } else {
    throw new Exception("Invalid input");
  }
} catch (Exception $e) {
  // Handle any errors
  echo "Error: " . $e->getMessage();
  $totals = array(array(
    'sum_total_price' => 'N/A',
    'sum_total_profit' => 'N/A',
    'sum_total_profit_percentage' => 'N/A'
  ));
} finally {
  // Close database connection
  Conexion::cerrar_conexion();
}
?>
<div class="projection-totals-bar">
  <div class="projection-total-item">
    <span class="projection-total-label">Total Invoice Amount</span>
    <span class="projection-total-value" style="color:var(--color-primary);">$<?= htmlspecialchars($totals[0]['sum_total_price']) ?></span>
  </div>
  <div class="projection-totals-divider"></div>
  <div class="projection-total-item">
    <span class="projection-total-label">Total Profit ($)</span>
    <span class="projection-total-value <?= ($totals[0]['sum_total_profit'] >= 0) ? 'text-success' : 'text-danger' ?>">
      $<?= htmlspecialchars($totals[0]['sum_total_profit']) ?>
    </span>
  </div>
  <div class="projection-totals-divider"></div>
  <div class="projection-total-item">
    <span class="projection-total-label">Total Profit (%)</span>
    <span class="projection-total-value <?= ($totals[0]['sum_total_profit_percentage'] >= 0) ? 'text-success' : 'text-danger' ?>">
      <?= htmlspecialchars($totals[0]['sum_total_profit_percentage']) ?>%
    </span>
  </div>
</div>