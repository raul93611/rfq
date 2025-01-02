<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path'])) {
  // Get the file path
  $filePath = $_SERVER['DOCUMENT_ROOT'] . "/rfq/employee_docs/" . $_POST['path'];

  // Check if the file exists before attempting to delete
  if (file_exists($filePath)) {
    if (unlink($filePath)) {
      // Successfully deleted the file
      echo json_encode(['message' => 'File deleted successfully.']);
    } else {
      // Failed to delete the file
      http_response_code(500);
      echo json_encode(['error' => 'Failed to delete the file.']);
    }
  } else {
    // File not found
    http_response_code(404);
    echo json_encode(['error' => 'File not found.']);
  }
} else {
  // Invalid request
  http_response_code(400);
  echo json_encode(['error' => 'Invalid request.']);
}