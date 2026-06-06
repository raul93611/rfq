<?php
/**
 * Excel (.xlsx) export of the Bid Pipeline Metrics report.
 * Params (GET): period (mode/year/quarter/month) + optional chart= to export a
 * single report (status|winloss|awards|submitted|pricing). Omitted/all = full workbook.
 */
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  exit('Unauthorized');
}

$mode = $_GET['mode'] ?? 'year';
$mode = in_array($mode, ['year', 'quarter', 'month'], true) ? $mode : 'year';
$period = ['mode' => $mode, 'year' => (int)($_GET['year'] ?? date('Y'))];
if ($mode === 'quarter') $period['quarter'] = max(1, min(4, (int)($_GET['quarter'] ?? 1)));
if ($mode === 'month')   $period['month']   = max(1, min(12, (int)($_GET['month'] ?? date('n'))));

$chart = $_GET['chart'] ?? 'all';
$valid = ['status', 'winloss', 'awards', 'submitted', 'pricing'];
$which = in_array($chart, $valid, true) ? [$chart] : $valid;

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$m = PipelineMetricsRepository::getMetrics($conexion, $period);
Conexion::cerrar_conexion();

$label = PipelineMetricsRepository::periodLabel($period);

$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0);
$first = true;

/** Add a titled sheet with a header row and data rows. */
$addSheet = function ($title, array $headers, array $rows) use ($spreadsheet, &$first, $label) {
  $sheet = $spreadsheet->createSheet();
  $sheet->setTitle(substr($title, 0, 31));
  $sheet->setCellValue('A1', 'Bid Pipeline Metrics · ' . $title . ' · ' . $label);
  $lastCol = chr(ord('A') + count($headers) - 1);
  $sheet->mergeCells("A1:{$lastCol}1");
  $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);

  $hdrRow = 3;
  foreach ($headers as $i => $h) {
    $col = chr(ord('A') + $i);
    $sheet->setCellValue($col . $hdrRow, $h);
    $sheet->getStyle($col . $hdrRow)->getFont()->setBold(true);
    $sheet->getStyle($col . $hdrRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1D2A3D');
    $sheet->getStyle($col . $hdrRow)->getFont()->getColor()->setRGB('FFFFFF');
    $sheet->getColumnDimension($col)->setWidth($i === 0 ? 34 : 18);
  }
  $r = $hdrRow + 1;
  foreach ($rows as $row) {
    foreach ($row as $i => $val) {
      $sheet->setCellValue(chr(ord('A') + $i) . $r, $val);
    }
    $r++;
  }
  $first = false;
};

$money = fn($n) => round((float)$n, 2);

// --- KPI summary (always first sheet on a full export) ---
if (count($which) > 1) {
  $wl = $m['winLoss'];
  $addSheet('Summary', ['Metric', 'Value'], [
    ['Period', $label],
    ['Total pipeline (bids)', $m['count']],
    ['Total est. value', $money($m['totalValue'])],
    ['Submitted (bids)', $m['submittedCount']],
    ['Submitted value', $money($m['submittedValue'])],
    ['Awarded (bids)', $m['awardedCount']],
    ['Awarded value', $money($m['awardedValue'])],
    ['Lost (bids)', $m['lostCount']],
    ['Pending (bids)', $m['pendingCount']],
    ['Win rate', $m['winRate'] === null ? 'N/A' : round($m['winRate'] * 100) . '%'],
  ]);
}

foreach ($which as $key) {
  if ($key === 'status') {
    $rows = array_map(fn($s) => [$s['label'], $s['count'], $money($s['value'])], $m['status']);
    $addSheet('Status Distribution', ['Status', 'Count', 'Value'], $rows);
  } elseif ($key === 'winloss') {
    $wl = $m['winLoss'];
    $rows = array_map(fn($s) => [$s['label'], $s['count']], $wl['series']);
    $rows[] = ['Denominator (excl. sources sought)', $wl['denominator']];
    $rows[] = ['Win rate', $m['winRate'] === null ? 'N/A' : round($m['winRate'] * 100) . '%'];
    $addSheet('Win-Loss', ['Outcome', 'Count'], $rows);
  } elseif ($key === 'awards') {
    $rows = array_map(fn($c) => [$c['category'], $c['count']], $m['awardsByCategory']);
    $addSheet('Awards by Category', ['Category', 'Awards'], $rows);
  } elseif ($key === 'submitted') {
    $rows = array_map(fn($c) => [$c['category'], $c['count']], $m['submittedByCategory']);
    $addSheet('Submitted by Category', ['Category', 'Submitted'], $rows);
  } elseif ($key === 'pricing') {
    $rows = array_map(fn($b) => [$b['label'], $b['count']], $m['pricing']['buckets']);
    $rows[] = ['Total priced bids', $m['pricing']['total']];
    $addSheet('Pricing Effort', ['Outcome', 'Count'], $rows);
  }
}

if ($spreadsheet->getSheetCount() === 0) { $spreadsheet->createSheet()->setTitle('Empty'); }
$spreadsheet->setActiveSheetIndex(0);

$slug = count($which) === 1 ? $which[0] : 'report';
$file = 'bid_pipeline_' . $slug . '_' . preg_replace('/[^\w-]+/', '_', $label) . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $file . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
