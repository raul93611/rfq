<?php
// Open database connection
Conexion::abrir_conexion();

try {
  // Validate and sanitize input
  if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);

    // Get year totals from the repository
    $totals = YearlyProjectionRepository::getYearTotals(Conexion::obtener_conexion(), $id);

    if (!$totals) {
      throw new Exception("No data found");
    }
  } else {
    throw new Exception("Invalid input");
  }
} catch (Exception $e) {
  // Handle any errors
  echo "Error: " . $e->getMessage();
  $totals = array(array(
    'total_projected_amount' => 'N/A',
    'total_projected_result' => 'N/A',
    'total_total_price' => 'N/A',
    'total_profit' => 'N/A'
  ));
} finally {
  // Close database connection
  Conexion::cerrar_conexion();
}
?>
<div class="projection-totals-bar">
  <div class="projection-total-item">
    <span class="projection-total-label">Monthly Goal</span>
    <span class="projection-total-value"><?= '$' . $totals[0]['total_projected_amount'] ?></span>
  </div>
  <div class="projection-totals-divider"></div>
  <div class="projection-total-item">
    <span class="projection-total-label">Monthly Goal Result</span>
    <span class="projection-total-value <?= ($totals[0]['total_projected_result'] >= 0) ? 'text-success' : 'text-danger' ?>">
      <?= '$' . $totals[0]['total_projected_result'] ?>
    </span>
  </div>
  <div class="projection-totals-divider"></div>
  <div class="projection-total-item">
    <span class="projection-total-label">Total Monthly Invoice</span>
    <span class="projection-total-value" style="color:var(--color-primary);"><?= '$' . $totals[0]['total_total_price'] ?></span>
  </div>
  <div class="projection-totals-divider"></div>
  <div class="projection-total-item">
    <span class="projection-total-label">Total Real Profit</span>
    <span class="projection-total-value <?= ($totals[0]['total_profit'] >= 0) ? 'text-success' : 'text-danger' ?>">
      <?= '$' . $totals[0]['total_profit'] ?>
    </span>
  </div>
</div>