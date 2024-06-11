<?php
header('Content-Type: application/json');

// Retrieve and validate input data
$start = $_POST['start'] ?? 0;
$length = $_POST['length'] ?? 10;
$search = $_POST['search']['value'] ?? '';
$sort_column_index = $_POST['order'][0]['column'] ?? 0;
$sort_direction = $_POST['order'][0]['dir'] ?? 'asc';
$report_type = $_POST['report'] ?? '';
$type = $_POST['type'] ?? '';
$quarter = $_POST['quarter'] ?? null;
$month = $_POST['month'] ?? null;
$year = $_POST['year'] ?? null;

$response = [
  "draw" => $_POST['draw'],
  "recordsTotal" => 0,
  "recordsFiltered" => 0,
  "data" => [],
  "additional" => 'true'
];

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Define report mappings
  $reportMappings = [
    'award' => [
      'getReport' => 'getAwardReport',
      'getTotalCount' => 'getAwardReportCount',
      'getFilteredCount' => 'getFilteredAwardReportCount'
    ],
    'submitted' => [
      'getReport' => 'getSubmittedReport',
      'getTotalCount' => 'getSubmittedReportCount',
      'getFilteredCount' => 'getFilteredSubmittedReportCount'
    ],
    'fulfillment' => [
      'getReport' => 'getFulfillmentReport',
      'getTotalCount' => 'getFulfillmentReportCount',
      'getFilteredCount' => 'getFilteredFulfillmentReportCount'
    ],
    'accounts-payable-fulfillment' => [
      'getReport' => 'getAccountsPayableFulfillmentReport',
      'getTotalCount' => 'getAccountsPayableFulfillmentReportCount',
      'getFilteredCount' => 'getFilteredAccountsPayableFulfillmentReportCount'
    ],
    'sales-commission' => [
      'getReport' => 'getSalesCommissionReport',
      'getTotalCount' => 'getSalesCommissionReportCount',
      'getFilteredCount' => 'getFilteredSalesCommissionReportCount'
    ]
  ];

  // Check if the report type is valid
  if (isset($reportMappings[$report_type])) {
    $report = $reportMappings[$report_type];

    // Fetch report data
    $quotes = Report::{$report['getReport']}(
      $conexion,
      $start,
      $length,
      $search,
      $sort_column_index,
      $sort_direction,
      $type,
      $quarter,
      $month,
      $year
    );

    // Fetch total and filtered record counts
    $total_records = Report::{$report['getTotalCount']}($conexion, $type, $quarter, $month, $year);
    $total_filtered_records = Report::{$report['getFilteredCount']}($conexion, $search, $type, $quarter, $month, $year);

    // Prepare response data
    $response['recordsTotal'] = $total_records;
    $response['recordsFiltered'] = $total_filtered_records;
    $response['data'] = $quotes;
  }

  // Close database connection
  Conexion::cerrar_conexion();
} catch (Exception $e) {
  // Handle any exceptions that occur
  if (isset($conexion)) {
    Conexion::cerrar_conexion();
  }
  $response['error'] = 'An error occurred: ' . $e->getMessage();
}

// Send JSON response
echo json_encode($response);
