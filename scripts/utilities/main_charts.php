<?php
header('Content-Type: application/json');

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $current_year = (int)date("Y");
  $past_year    = $current_year - 1;

  $curr_start = date("Y-m-01");
  $curr_end   = date("Y-m-01", strtotime("next month"));
  $past_start = date("Y-m-01", strtotime("last month"));
  $past_end   = $curr_start;

  // Query 1: per-user counts for completed and awarded quotes, current and past month
  $user_quotes = RepositorioUsuario::getQuotesByUserForMonths($conexion, $curr_start, $curr_end, $past_start, $past_end);

  // Query 2: monthly awarded-quote counts and amounts for both years
  $awards_data = RepositorioRfq::getAnnualAwardsDataByMonthBothYears($conexion, $current_year, $past_year);

  Conexion::cerrar_conexion();

  // Split the unified user-quotes row into the four arrays the JS expects
  $completed_quotes_by_user_current_month = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['completed_current']], $user_quotes);
  $completed_quotes_by_user_past_month    = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['completed_past']],    $user_quotes);
  $award_quotes_by_user_current_month     = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['award_current']],     $user_quotes);
  $award_quotes_by_user_past_month        = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['award_past']],        $user_quotes);

  // Monthly arrays (each element has total_quotes and total_price — JS reads only the key it needs)
  $annual_awards_by_month             = $awards_data['current_by_month'];
  $past_annual_awards_by_month        = $awards_data['past_by_month'];
  $annual_awards_amount_by_month      = $awards_data['current_by_month'];
  $past_annual_awards_amount_by_month = $awards_data['past_by_month'];

  // Annual totals derived from monthly data — no extra queries needed
  $annual_awards             = array_sum(array_column($awards_data['current_by_month'], 'total_quotes'));
  $past_annual_awards        = array_sum(array_column($awards_data['past_by_month'],   'total_quotes'));
  $annual_awards_amount      = array_sum(array_column($awards_data['current_by_month'], 'total_price'));
  $past_annual_awards_amount = array_sum(array_column($awards_data['past_by_month'],   'total_price'));

  echo json_encode([
    'annual_awards'                          => $annual_awards,
    'past_annual_awards'                     => $past_annual_awards,
    'annual_awards_amount'                   => number_format($annual_awards_amount, 2),
    'past_annual_awards_amount'              => number_format($past_annual_awards_amount, 2),
    'annual_awards_by_month'                 => $annual_awards_by_month,
    'past_annual_awards_by_month'            => $past_annual_awards_by_month,
    'award_quotes_by_user_current_month'     => $award_quotes_by_user_current_month,
    'award_quotes_by_user_past_month'        => $award_quotes_by_user_past_month,
    'annual_awards_amount_by_month'          => $annual_awards_amount_by_month,
    'past_annual_awards_amount_by_month'     => $past_annual_awards_amount_by_month,
    'completed_quotes_by_user_current_month' => $completed_quotes_by_user_current_month,
    'completed_quotes_by_user_past_month'    => $completed_quotes_by_user_past_month,
  ]);
} catch (Exception $e) {
  echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
} finally {
  Conexion::cerrar_conexion();
}
