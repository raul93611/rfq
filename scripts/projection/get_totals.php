<?php
Conexion::abrir_conexion();
$totals = YearlyProjectionRepository::getYearTotals(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
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
      <td><?= $totals[0]['total_projected_amount'] ?></td>
      <td><?= $totals[0]['total_projected_result'] ?></td>
      <td><?= $totals[0]['total_total_price'] ?></td>
      <td><?= $totals[0]['total_profit'] ?></td>
    </tr>
  </tbody>
</table>