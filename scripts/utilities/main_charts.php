<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
$completed_quotes_by_user_current_month = RepositorioUsuario::getQuotesByUserAndMonth(Conexion::obtener_conexion(), 'completed', date("Y-m"));
$completed_quotes_by_user_past_month = RepositorioUsuario::getQuotesByUserAndMonth(Conexion::obtener_conexion(), 'completed', date("Y-m", strtotime("last month")));
$award_quotes_by_user_current_month = RepositorioUsuario::getQuotesByUserAndMonth(Conexion::obtener_conexion(), 'award', date("Y-m"));
$award_quotes_by_user_past_month = RepositorioUsuario::getQuotesByUserAndMonth(Conexion::obtener_conexion(), 'award', date("Y-m", strtotime("last month")));
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
  'award_quotes_by_user_current_month' => $award_quotes_by_user_current_month,
  'award_quotes_by_user_past_month' => $award_quotes_by_user_past_month,
  'annual_awards_amount_by_month' => $annual_awards_amount_by_month,
  'past_annual_awards_amount_by_month' => $past_annual_awards_amount_by_month,
  'completed_quotes_by_user_current_month' => $completed_quotes_by_user_current_month,
  'completed_quotes_by_user_past_month' => $completed_quotes_by_user_past_month
));
