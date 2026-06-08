<?php
header('Content-Type: application/json');

try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Rolling 3-year window for the Annual Awards cards: current year + 2 prior,
  // chronological (oldest first) so the bar order and color ramp read oldest -> newest.
  $current_year = (int)date("Y");
  $years = [$current_year - 2, $current_year - 1, $current_year];

  $curr_start = date("Y-m-01");
  $curr_end   = date("Y-m-01", strtotime("next month"));
  $past_start = date("Y-m-01", strtotime("last month"));
  $past_end   = $curr_start;

  // Query 1: per-user counts for completed and awarded quotes, current and past month
  $user_quotes = RepositorioUsuario::getQuotesByUserForMonths($conexion, $curr_start, $curr_end, $past_start, $past_end);

  // Query 2: monthly awarded-quote counts and amounts for the 3-year window
  $awards_data = RepositorioRfq::getAnnualAwardsDataByMonthForYears($conexion, $years);

  Conexion::cerrar_conexion();

  // Split the unified user-quotes row into the four arrays the JS expects (unchanged cards)
  $completed_quotes_by_user_current_month = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['completed_current']], $user_quotes);
  $completed_quotes_by_user_past_month    = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['completed_past']],    $user_quotes);
  $award_quotes_by_user_current_month     = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['award_current']],     $user_quotes);
  $award_quotes_by_user_past_month        = array_map(fn($r) => ['user_name' => $r['user_name'], 'total_quotes' => $r['award_past']],        $user_quotes);

  // One entry per year (chronological): annual count + amount totals and the 12 monthly
  // points. Both count and amount charts read from this single structure.
  $annual_awards_years = array_map(function ($y) use ($awards_data) {
    $months = $awards_data[$y];
    return [
      'year'         => $y,
      'total_quotes' => (int)array_sum(array_column($months, 'total_quotes')),
      'total_price'  => (float)array_sum(array_column($months, 'total_price')),
      'by_month'     => array_values($months),
    ];
  }, $years);

  echo json_encode([
    'annual_awards_years'                    => $annual_awards_years,
    'award_quotes_by_user_current_month'     => $award_quotes_by_user_current_month,
    'award_quotes_by_user_past_month'        => $award_quotes_by_user_past_month,
    'completed_quotes_by_user_current_month' => $completed_quotes_by_user_current_month,
    'completed_quotes_by_user_past_month'    => $completed_quotes_by_user_past_month,
  ]);
} catch (Exception $e) {
  echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
} finally {
  Conexion::cerrar_conexion();
}
