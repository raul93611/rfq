<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$completed_quotes_by_user_and_last_current_month = RepositorioUsuario::getQuotesByUserAndLastCurrentMonth(Conexion::obtener_conexion(), 'completed');
$award_quotes_by_user_and_last_current_month = RepositorioUsuario::getQuotesByUserAndLastCurrentMonth(Conexion::obtener_conexion(), 'award');
$annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth(Conexion::obtener_conexion(), date("Y"));
$past_annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth(Conexion::obtener_conexion(), date("Y") - 1);
$annual_awards_by_month = RepositorioRfq::getAnnualAwardsByMonth(Conexion::obtener_conexion(), date("Y"));
$past_annual_awards_by_month = RepositorioRfq::getAnnualAwardsByMonth(Conexion::obtener_conexion(), date("Y") - 1);
$annual_awards_amount = RepositorioRfq::getTotalAnnualAwardsAmount(Conexion::obtener_conexion(), date("Y"));
$past_annual_awards_amount = RepositorioRfq::getTotalAnnualAwardsAmount(Conexion::obtener_conexion(), date("Y") - 1);
$annual_awards = RepositorioRfq::getTotalAnnualAwards(Conexion::obtener_conexion(), date("Y"));
$past_annual_awards = RepositorioRfq::getTotalAnnualAwards(Conexion::obtener_conexion(), date("Y") - 1);
Conexion::cerrar_conexion();
echo json_encode(array(
  'annual_awards' => $annual_awards,
  'past_annual_awards' => $past_annual_awards,
  'annual_awards_amount' => number_format($annual_awards_amount, 2),
  'past_annual_awards_amount' => number_format($past_annual_awards_amount, 2),
  'annual_awards_by_month' => $annual_awards_by_month,
  'past_annual_awards_by_month' => $past_annual_awards_by_month,
  'award_quotes_by_user_and_last_current_month' => $award_quotes_by_user_and_last_current_month,
  'annual_awards_amount_by_month' => $annual_awards_amount_by_month,
  'past_annual_awards_amount_by_month' => $past_annual_awards_amount_by_month,
  'completed_quotes_by_user_and_last_current_month' => $completed_quotes_by_user_and_last_current_month
));
?>
