<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp_path = $_FILES['uploaded_file']['tmp_name'];
    $file_name = $_FILES['uploaded_file']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validate file extension
    $allowed_extensions = ['csv', 'xls', 'xlsx'];
    if (!in_array($file_ext, $allowed_extensions)) {
      die('Invalid file format');
    }

    // Process the file
    $rows = [];
    try {
      if ($file_ext === 'csv') {
        $rows = processCsv($file_tmp_path);
      } else {
        $rows = processExcel($file_tmp_path);
      }

      foreach ($rows as $row) {
        // Copy item
        $item = new Item(
          '',
          $_POST['id_rfq'],
          $_POST["id_usuario"],
          '',
          $row['proposal_brand'] ?? '',
          $row['brand'] ?? '',
          $row['proposal_part_number'] ?? '',
          $row['part_number'] ?? '',
          $row['proposal_description'] ?? '',
          $row['description'] ?? '',
          $row['quantity'],
          0,
          0,
          $row['comments'] ?? '',
          $row['website'] ?? '',
          0,
          0,
          null
        );
        Conexion::abrir_conexion();
        RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
        Conexion::cerrar_conexion();
      }

      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
    } catch (Exception $e) {
      die('Error processing file: ' . $e->getMessage());
    }
  }
}

function processCsv($filePath) {
  $rows = [];
  if (($handle = fopen($filePath, "r")) !== FALSE) {
    // Skip header if needed
    $header = fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $rows[] = [
        'brand' => $data[0],
        'part_number' => $data[1],
        'description' => $data[2],
        'proposal_brand' => $data[3],
        'proposal_part_number' => $data[4],
        'proposal_description' => $data[5],
        'quantity' => $data[6],
        'comments' => $data[7],
        'website' => $data[8]
      ];
    }
    fclose($handle);
  }
  return $rows;
}

function processExcel($filePath) {
  $spreadsheet = IOFactory::load($filePath);
  $sheet = $spreadsheet->getActiveSheet();
  $rows = $sheet->toArray();

  $processed = [];
  // Remove header
  array_shift($rows);

  foreach ($rows as $row) {
    $processed[] = [
      'brand' => $row[0],
      'part_number' => $row[1],
      'description' => $row[2],
      'proposal_brand' => $row[3],
      'proposal_part_number' => $row[4],
      'proposal_description' => $row[5],
      'quantity' => $row[6],
      'comments' => $row[7],
      'website' => $row[8]
    ];
  }
  return $processed;
}
