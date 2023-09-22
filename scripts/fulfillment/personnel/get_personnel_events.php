<?php
header('Content-Type: application/json');

Conexion::abrir_conexion();
$personnel = PersonnelRepository::getAll(Conexion::obtener_conexion());
$events = CalendarEventRepository::getAll(Conexion::obtener_conexion());
Conexion::cerrar_conexion();

$response = array(
  "personnel" => $personnel,
  "events" => $events
);

echo json_encode($response);
