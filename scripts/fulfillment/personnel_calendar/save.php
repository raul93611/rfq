<?php
header('Content-Type: application/json');

try {
  // Validate and sanitize inputs
  if (!isset($_POST['id_personnel'], $_POST['name'], $_POST['start'], $_POST['end'], $_POST['color'])) {
    throw new Exception('Missing required parameters.');
  }

  // Trim input values to remove extra spaces
  $id_personnel = filter_var(trim($_POST['id_personnel']), FILTER_SANITIZE_NUMBER_INT);
  $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_SPECIAL_CHARS);
  $start = trim($_POST['start']);
  $end = trim($_POST['end']);
  $color = trim($_POST['color']);

  // Validate the color as a hex code
  if (!empty($color) && !preg_match('/^#[a-f0-9]{6}$/i', $color)) {
    throw new Exception('Invalid color format.');
  }

  // Validate date formats (assuming YYYY-MM-DD format for start and end dates)
  $startDate = DateTime::createFromFormat('m/d/Y', $start);
  $endDate = DateTime::createFromFormat('m/d/Y', $end);

  if (!$startDate || !$endDate || $startDate > $endDate) {
    throw new Exception('Invalid date format or date range.');
  }

  // Open database connection
  Conexion::abrir_conexion();

  // Create new CalendarEvent object
  $event = new CalendarEvent('', $id_personnel, $name, $start, $end, $color);

  // Save the event
  CalendarEventRepository::save(Conexion::obtener_conexion(), $event);

  // Close database connection
  Conexion::cerrar_conexion();

  // Respond with success message
  echo json_encode(array('data' => 'success'));
} catch (Exception $e) {
  // Ensure the database connection is closed in case of an error
  Conexion::cerrar_conexion();

  // Log the error message (optional)
  error_log($e->getMessage());

  // Respond with an error message
  echo json_encode(array('error' => $e->getMessage()));
}
