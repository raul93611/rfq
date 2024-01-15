<?php
Conexion::abrir_conexion();
$totals = YearlyProjectionRepository::getYearTotals(Conexion::obtener_conexion(), $_POST['id']);
Conexion::cerrar_conexion();
?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th></th>
      <th>TOTAL INVOICE AMOUNT</th>
      <th>TOTAL SALES COMMISSION($)</th>
      <th>TOTAL PROFIT($)</th>
      <th>TOTAL PROFIT(%)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="width: 200px;">TOTALS</td>
      <td><?= $totals[0]['sum_total_price'] ?></td>
      <td><?= $totals[0]['sum_sales_commission'] ?></td>
      <td><?= $totals[0]['sum_total_profit'] ?></td>
      <td><?= $totals[0]['sum_total_profit_percentage'] ?></td>
    </tr>
  </tbody>
</table>