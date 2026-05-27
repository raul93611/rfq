<?php
if (!ControlSesion::sesion_iniciada()) {
  http_response_code(401);
  echo json_encode([]);
  exit;
}

header('Content-Type: application/json');

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$rows = RepositorioUsuario::getAllActiveUsers($conexion);

Conexion::cerrar_conexion();

$users = array_map(fn($r) => [
  'id'       => (int) $r['id'],
  'username' => $r['nombre_usuario'],
  'name'     => trim($r['nombres'] . ' ' . $r['apellidos']),
], $rows);

echo json_encode($users);
