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
<table class="table table-bordered table-hover">
  <thead class="thead-dark">
    <tr>
      <th style="width: 200px; min-width: 200px;"></th>
      <th>MONTHLY GOAL</th>
      <th>MONTHLY GOAL RESULT</th>
      <th>TOTAL MONTHLY INVOICE</th>
      <th style="border-right: none;">TOTAL REAL PROFIT</th>
      <th style="width: 150px; min-width: 150px; border-left: none;"></th>
    </tr>
  </thead>
  <tbody>
    <tr class="font-weight-bold" style="background-color: #f8f9fa; font-size: 1.1em;">
      <td style="background-color: #e9ecef; font-weight: 700;">TOTALS</td>
      <td class="text-right font-weight-bold">$<?= $totals[0]['total_projected_amount'] ?></td>
      <td class="text-right font-weight-bold">$<?= $totals[0]['total_projected_result'] ?></td>
      <td class="text-right font-weight-bold text-primary">$<?= $totals[0]['total_total_price'] ?></td>
      <td style="border-right: none;" class="text-right font-weight-bold <?= ($totals[0]['total_profit'] >= 0) ? 'text-success' : 'text-danger' ?>">
        $<?= $totals[0]['total_profit'] ?>
      </td>
      <td style="border-left: none;"></td>
    </tr>
  </tbody>
</table>