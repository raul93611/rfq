<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$completed_quotes_by_user_and_last_current_month = RepositorioUsuario::getCompletedQuotesByUserAndLastCurrentMonth(Conexion::obtener_conexion());
$annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth(Conexion::obtener_conexion(), date("Y"));
$past_annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth(Conexion::obtener_conexion(), date("Y") - 1);
list($monthly_price_awards, $past_monthly_price_awards, $monthly_awards, $past_monthly_awards) = RepositorioRfq::obtener_monto_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
echo json_encode(array(
  'usernames' => $nombres_usuario,
  'completed_quotes' => $cotizaciones_completadas,
  'past_completed_quotes' => $cotizaciones_completadas_pasadas,
  'monthly_price_awards' => $monthly_price_awards,
  'past_monthly_price_awards' => $past_monthly_price_awards,
  'award_quotes' => $cotizaciones_ganadas,
  'past_award_quotes' => $cotizaciones_ganadas_pasadas,
  'past_monthly_awards' => $past_monthly_awards,
  'total_annual_awards_amounts' => number_format(array_sum($monthly_price_awards), 2),
  'past_total_annual_awards_amounts' => number_format(array_sum($past_monthly_price_awards), 2),
  'total_annual_awards' => array_sum($monthly_awards),
  'past_total_annual_awards' => array_sum($past_monthly_awards),

  'annual_awards_amount_by_month' => $annual_awards_amount_by_month,
  'past_annual_awards_amount_by_month' => $past_annual_awards_amount_by_month,
  'completed_quotes_by_user_and_last_current_month' => $completed_quotes_by_user_and_last_current_month
));
?>
