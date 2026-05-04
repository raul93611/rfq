<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


// Set headers for Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="providers_export_' . date('Y-m-d') . '.xlsx"');
header('Cache-Control: max-age=0');

try {
  // Open database connection
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  // Fetch all providers
  $providers = ProviderListRepository::get_all($conexion);

  // Close database connection
  Conexion::cerrar_conexion();

  // Create new Spreadsheet
  $spreadsheet = new Spreadsheet();
  $activeWorksheet = $spreadsheet->getActiveSheet();

  // Set document properties
  $spreadsheet->getProperties()
    ->setCreator('Export System')
    ->setTitle('Providers List')
    ->setSubject('Providers Export')
    ->setDescription('List of all providers exported on ' . date('Y-m-d H:i:s'));

  // Set default column widths
  $activeWorksheet->getColumnDimension('A')->setWidth(15);
  $activeWorksheet->getColumnDimension('B')->setWidth(40);

  // Set title
  $activeWorksheet->setCellValue('A1', 'Providers List');
  $activeWorksheet->mergeCells('A1:B1');
  $activeWorksheet->getStyle('A1')->applyFromArray([
    'font' => [
      'bold' => true,
      'size' => 16,
      'color' => ['rgb' => '1F497D']
    ],
    'alignment' => [
      'horizontal' => Alignment::HORIZONTAL_CENTER,
    ]
  ]);

  // Set export date
  $activeWorksheet->setCellValue('A2', 'Exported on:');
  $activeWorksheet->setCellValue('B2', date('Y-m-d H:i:s'));
  $activeWorksheet->getStyle('A2:B2')->applyFromArray([
    'font' => [
      'italic' => true,
      'size' => 10,
    ]
  ]);

  // Add empty row
  $activeWorksheet->setCellValue('A3', '');

  // Set headers
  $activeWorksheet->setCellValue('A4', 'ID');
  $activeWorksheet->setCellValue('B4', 'Company Name');

  // Style headers
  $headerStyle = [
    'font' => [
      'bold' => true,
      'color' => ['rgb' => 'FFFFFF']
    ],
    'fill' => [
      'fillType' => Fill::FILL_SOLID,
      'startColor' => ['rgb' => '4F81BD']
    ],
    'alignment' => [
      'horizontal' => Alignment::HORIZONTAL_CENTER,
      'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
      'allBorders' => [
        'borderStyle' => Border::BORDER_THIN,
        'color' => ['rgb' => '000000']
      ]
    ]
  ];

  $activeWorksheet->getStyle('A4:B4')->applyFromArray($headerStyle);
  $activeWorksheet->getRowDimension(4)->setRowHeight(25);

  // Start row for data
  $row = 5;

  // Check if providers exist
  if (!empty($providers)) {
    // Add data rows
    foreach ($providers as $provider) {
      $activeWorksheet->setCellValue('A' . $row, $provider->get_id());
      $activeWorksheet->setCellValue('B' . $row, $provider->get_company_name());
      $row++;
    }

    // Style data rows
    $dataStartRow = 5;
    $dataEndRow = $row - 1;
    $dataRange = 'A' . $dataStartRow . ':B' . $dataEndRow;

    $dataStyle = [
      'borders' => [
        'allBorders' => [
          'borderStyle' => Border::BORDER_THIN,
          'color' => ['rgb' => 'D9D9D9']
        ]
      ],
      'alignment' => [
        'vertical' => Alignment::VERTICAL_CENTER
      ]
    ];

    $activeWorksheet->getStyle($dataRange)->applyFromArray($dataStyle);

    // Alternate row coloring
    for ($i = $dataStartRow; $i <= $dataEndRow; $i++) {
      if ($i % 2 == 0) {
        $activeWorksheet->getStyle('A' . $i . ':B' . $i)
          ->getFill()
          ->setFillType(Fill::FILL_SOLID)
          ->getStartColor()
          ->setARGB('F2F2F2');
      }
    }

    // Auto-size columns based on content
    foreach (range('A', 'B') as $column) {
      $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Add totals row
    $activeWorksheet->setCellValue('A' . $row, 'Total:');
    $activeWorksheet->setCellValue('B' . $row, count($providers));
    $activeWorksheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
      'font' => [
        'bold' => true
      ],
      'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E6E6E6']
      ],
      'borders' => [
        'top' => [
          'borderStyle' => Border::BORDER_DOUBLE,
          'color' => ['rgb' => '000000']
        ]
      ]
    ]);
  } else {
    // No providers found
    $activeWorksheet->setCellValue('A5', 'No providers found.');
    $activeWorksheet->mergeCells('A5:B5');
    $activeWorksheet->getStyle('A5')->applyFromArray([
      'font' => [
        'italic' => true,
        'color' => ['rgb' => 'FF0000']
      ],
      'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER
      ]
    ]);
  }

  // Freeze panes (headers stay visible when scrolling)
  $activeWorksheet->freezePane('A5');

  // Set active sheet index to the first sheet
  $spreadsheet->setActiveSheetIndex(0);

  // Create Excel file and output to browser
  $writer = new Xlsx($spreadsheet);
  $writer->save('php://output');

  exit;
} catch (Exception $e) {
  // Handle errors
  echo "Error exporting providers: " . $e->getMessage();
  error_log("Export Error: " . $e->getMessage());
}
