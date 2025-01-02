<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the selected directory name from the form
  $directoryName = $_POST['doc_type'];

  // Define the upload directory
  $baseDirectory = $_SERVER['DOCUMENT_ROOT'] . "/rfq/employee_docs";
  $uploadDirectory = $baseDirectory . "/$directoryName/";

  // Ensure the directory exists, create it if it doesn't
  if (!is_dir($uploadDirectory)) {
    if (!mkdir($uploadDirectory, 0755, true)) {
      http_response_code(500);
      echo json_encode(['error' => 'Failed to create upload directory.']);
      exit;
    }
  }

  // Handle the uploaded file
  if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file_upload']['tmp_name'];
    $fileName = basename($_FILES['file_upload']['name']); // Sanitize file name
    $fileDestination = $uploadDirectory . $fileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($fileTmpPath, $fileDestination)) {
      http_response_code(200);
      echo json_encode(['message' => 'File uploaded successfully.']);
    } else {
      http_response_code(500);
      echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
  } else {
    http_response_code(400);
    echo json_encode(['error' => 'No file uploaded or an error occurred.']);
  }
} else {
  http_response_code(405);
  echo json_encode(['error' => 'Invalid request method.']);
}