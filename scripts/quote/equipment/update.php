<?php
header('Content-Type: application/json');
Conexion::abrir_conexion();
RepositorioItem::actualizar_item(
  Conexion::obtener_conexion(),
  $_POST['id_item'],
  $_POST['brand'],
  $_POST['brand_project'],
  $_POST['part_number'],
  $_POST['part_number_project'],
  htmlspecialchars($_POST['description']),
  htmlspecialchars($_POST['description_project']),
  $_POST['quantity'],
  $_POST['comments'],
  $_POST['website']
);
AuditTrailRepository::edit_item_events(
  Conexion::obtener_conexion(),
  $_POST['brand'],
  $_POST['brand_original'],
  $_POST['brand_project'],
  $_POST['brand_project_original'],
  $_POST['part_number'],
  $_POST['part_number_original'],
  $_POST['part_number_project'],
  $_POST['part_number_project_original'],
  $_POST['description'],
  $_POST['description_original'],
  $_POST['description_project'],
  $_POST['description_project_original'],
  $_POST['quantity'],
  $_POST['quantity_original'],
  $_POST['comments'],
  $_POST['comments_original'],
  $_POST['website'],
  $_POST['website_original'],
  $_POST['id_item'],
  $_POST['id_rfq']
);
RepositorioItem::updateMinorProvider(Conexion::obtener_conexion(), $_POST['id_item']);
RepositorioItem::updateItemPrice(Conexion::obtener_conexion(), $_POST['id_item']);
Conexion::cerrar_conexion();
echo json_encode(array(
  'id' => $_POST['id_rfq']
));
