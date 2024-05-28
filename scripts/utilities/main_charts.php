<?php
header('Content-Type: application/json');

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Define the current and past month
  $current_month = date("Y-m");
  $past_month = date("Y-m", strtotime("last month"));
  $current_year = date("Y");
  $past_year = date("Y") - 1;

  // Fetch data
  $completed_quotes_by_user_current_month = RepositorioUsuario::getQuotesByUserAndMonth($conexion, 'completed', $current_month);
  $completed_quotes_by_user_past_month = RepositorioUsuario::getQuotesByUserAndMonth($conexion, 'completed', $past_month);
  $award_quotes_by_user_current_month = RepositorioUsuario::getQuotesByUserAndMonth($conexion, 'award', $current_month);
  $award_quotes_by_user_past_month = RepositorioUsuario::getQuotesByUserAndMonth($conexion, 'award', $past_month);
  $annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth($conexion, $current_year);
  $past_annual_awards_amount_by_month = RepositorioRfq::getAnnualAwardsAmountByMonth($conexion, $past_year);
  $annual_awards_by_month = RepositorioRfq::getAnnualAwardsByMonth($conexion, $current_year);
  $past_annual_awards_by_month = RepositorioRfq::getAnnualAwardsByMonth($conexion, $past_year);
  $annual_awards_amount = RepositorioRfq::getTotalAnnualAwardsAmount($conexion, $current_year);
  $past_annual_awards_amount = RepositorioRfq::getTotalAnnualAwardsAmount($conexion, $past_year);
  $annual_awards = RepositorioRfq::getTotalAnnualAwards($conexion, $current_year);
  $past_annual_awards = RepositorioRfq::getTotalAnnualAwards($conexion, $past_year);

  // Close database connection
  Conexion::cerrar_conexion();

  // Prepare response data
  $response = [
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
  ];

  // Output the response as JSON
  echo json_encode($response);
} catch (Exception $e) {
  // Handle exceptions and include error message in the response
  echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
} finally {
  // Ensure the database connection is closed
  Conexion::cerrar_conexion();
}
