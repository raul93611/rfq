<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
  if (isset($_FILES['uploaded_file']) && $_FILES['uploaded_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp_path = $_FILES['uploaded_file']['tmp_name'];
    $file_name = $_FILES['uploaded_file']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Validate file extension
    $allowed_extensions = ['csv', 'xls', 'xlsx'];
    if (!in_array($file_ext, $allowed_extensions)) {
      die('Invalid file format');
    }

    $import_mode = ($_POST['import_mode'] ?? 'append') === 'replace' ? 'replace' : 'append';

    // Process the file — the whole file is parsed (and would throw here on a malformed
    // file) before anything below touches existing items, so a bad upload never leaves a
    // Replace with existing items wiped and nothing imported in their place.
    $rows = [];
    try {
      if ($file_ext === 'csv') {
        $rows = processCsv($file_tmp_path);
      } else {
        $rows = processExcel($file_tmp_path);
      }

      $roomMap = []; // Tracks normalized room names => IDs

      Conexion::abrir_conexion();

      if ($import_mode === 'replace') {
        $existingItems = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $_POST['id_rfq']);
        foreach ($existingItems as $existingItem) {
          // delete_item() only cascades to `provider` — subitems need clearing separately
          // or they're left orphaned pointing at a deleted item.
          $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $existingItem->obtener_id());
          foreach ($subitems as $subitem) {
            RepositorioSubitem::delete_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
          }
          RepositorioItem::delete_item(Conexion::obtener_conexion(), $existingItem->obtener_id());
        }
      }

      // Phase 1: Identify unique rooms and create them
      $roomsToCreate = [];

      foreach ($rows as $row) {
        if (empty($row['room'])) continue; // Skip items without rooms

        $normalizedRoom = strtolower(trim($row['room']));
        if (!isset($roomMap[$normalizedRoom])) {
          $roomsToCreate[$normalizedRoom] = $row['room']; // Store original name
        }
      }

      // Batch-create rooms (efficient for large imports)
      foreach ($roomsToCreate as $normalized => $originalName) {
        $newRoom = new Room('', $_POST['id_rfq'], $originalName, null);
        $roomId = RoomRepository::save(Conexion::obtener_conexion(), $newRoom);
        $roomMap[$normalized] = $roomId;
      }

      foreach ($rows as $row) {
        $roomId = null;

        if (!empty($row['room'])) {
          $normalizedRoom = strtolower(trim($row['room']));
          $roomId = $roomMap[$normalizedRoom]; // Get pre-created room ID
        }

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
          $roomId
        );
        Conexion::abrir_conexion();
        $newItemId = RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item);
        Conexion::cerrar_conexion();

        foreach ($row['providers'] as $providerRow) {
          $provider = new Provider('', $newItemId, $providerRow['name'], $providerRow['price']);
          Conexion::abrir_conexion();
          RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider);
          Conexion::cerrar_conexion();
        }
      }

      Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
    } catch (Exception $e) {
      die('Error processing file: ' . $e->getMessage());
    }
  }
}

// Reads up to 5 Provider Name/Price pairs starting at $startIndex. A pair is skipped
// entirely when Name is blank; when Name is present but Price is blank/non-numeric,
// Price defaults to 0.
function parseProviderPairs($cells, $startIndex) {
  $providers = [];
  for ($p = 0; $p < 5; $p++) {
    $nameIndex = $startIndex + ($p * 2);
    $priceIndex = $nameIndex + 1;
    $name = isset($cells[$nameIndex]) ? trim((string) $cells[$nameIndex]) : '';
    if ($name === '') continue;
    $price = isset($cells[$priceIndex]) ? trim((string) $cells[$priceIndex]) : '';
    $providers[] = ['name' => $name, 'price' => is_numeric($price) ? $price : 0];
  }
  return $providers;
}

function processCsv($filePath) {
  $rows = [];
  if (($handle = fopen($filePath, "r")) !== FALSE) {
    // Skip header if needed
    $header = fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      // Skip if row is entirely empty (null or whitespace)
      if (empty(array_filter($data, fn($cell) => $cell !== null && trim($cell) !== ''))) {
        continue;
      }

      $room = isset($data[9]) ? trim($data[9]) : null;
      $rows[] = [
        'brand' => $data[0],
        'part_number' => $data[1],
        'description' => $data[2],
        'proposal_brand' => $data[3],
        'proposal_part_number' => $data[4],
        'proposal_description' => $data[5],
        'quantity' => $data[6],
        'comments' => $data[7],
        'website' => $data[8],
        'room' => !empty($room) ? $room : null,
        'providers' => parseProviderPairs($data, 10)
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
    // Skip if row is entirely empty (null or whitespace)
    if (empty(array_filter($row, fn($cell) => $cell !== null && trim($cell) !== ''))) {
      continue;
    }

    $room = isset($row[9]) ? trim($row[9]) : null;
    $processed[] = [
      'brand' => $row[0],
      'part_number' => $row[1],
      'description' => $row[2],
      'proposal_brand' => $row[3],
      'proposal_part_number' => $row[4],
      'proposal_description' => $row[5],
      'quantity' => $row[6],
      'comments' => $row[7],
      'website' => $row[8],
      'room' => !empty($room) ? $room : null,
      'providers' => parseProviderPairs($row, 10)
    ];
  }
  return $processed;
}
