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
<table class="table table-bordered">
  <thead>
    <tr>
      <th></th>
      <th>MONTHLY GOAL</th>
      <th>MONTHLY GOAL RESULT</th>
      <th>TOTAL MONTHLY INVOICE</th>
      <th>TOTAL REAL PROFIT</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="width: 200px;">TOTALS</td>
      <td><?= htmlspecialchars($totals[0]['total_projected_amount']) ?></td>
      <td><?= htmlspecialchars($totals[0]['total_projected_result']) ?></td>
      <td><?= htmlspecialchars($totals[0]['total_total_price']) ?></td>
      <td><?= htmlspecialchars($totals[0]['total_profit']) ?></td>
    </tr>
  </tbody>
</table>