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

<table class="table table-bordered">
  <thead>
    <tr>
      <th></th>
      <th>TOTAL INVOICE AMOUNT</th>
      <th>TOTAL PROFIT($)</th>
      <th>TOTAL PROFIT(%)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="width: 200px;">TOTALS</td>
      <td><?= htmlspecialchars($totals[0]['sum_total_price']) ?></td>
      <td><?= htmlspecialchars($totals[0]['sum_total_profit']) ?></td>
      <td><?= htmlspecialchars($totals[0]['sum_total_profit_percentage']) ?></td>
    </tr>
  </tbody>
</table>