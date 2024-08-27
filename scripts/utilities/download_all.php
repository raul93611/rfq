<?php
header('Content-Type: application/json'); // Set the content type to JSON

$response = []; // Array to store the response data

try {
  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    throw new Exception("Invalid request method.");
  }

  if (empty($_POST['idRfq'])) {
    throw new Exception("Missing or invalid folder ID.");
  }

  $idRfq = $_POST['idRfq'];
  $folderPath = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/'  . $idRfq;

  // Ensure the folder exists and is a directory
  if (!is_dir($folderPath)) {
    throw new Exception("Folder not found.");
  }

  // Define the ZIP file name and path
  $zipFileName = 'files_' . $idRfq . '.zip';
  $zipFilePath = $_SERVER['DOCUMENT_ROOT'] . '/rfq/tmp/zips/' . $zipFileName; // Path where the ZIP file will be saved

  $zip = new ZipArchive();

  if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
    throw new Exception("Unable to create ZIP file.");
  }

  // Recursively add files to the ZIP archive
  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folderPath),
    RecursiveIteratorIterator::LEAVES_ONLY
  );

  foreach ($files as $file) {
    if (!$file->isDir()) {
      $filePath = $file->getRealPath();
      $relativePath = substr($filePath, strlen($folderPath) + 1);
      $zip->addFile($filePath, $relativePath);
    }
  }

  $zip->close();

  // Set the download URL in the response
  $response['downloadUrl'] = '/rfq/tmp/zips/' . $zipFileName;
} catch (Exception $e) {
  // Handle any exceptions by returning an error message
  http_response_code(400); // Bad request
  $response['error'] = $e->getMessage();
}

// Return the JSON response
echo json_encode($response);
exit;
