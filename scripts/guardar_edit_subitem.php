<?php
session_start();
if (isset($_POST['guardar_cambios_subitem'])) {
  Conexion::abrir_conexion();
  RepositorioSubitem::actualizar_subitem(Conexion::obtener_conexion(), $_POST['id_subitem'], $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], $_POST['comments'], $_POST['website']);
  $cambios = [];
  if($_POST['brand'] != $_POST['brand_original']){
    $cambios[] = 'brand';
  }

  if($_POST['brand_project'] != $_POST['brand_project_original']){
    $cambios[] = 'brand_project';
  }

  if($_POST['part_number'] != $_POST['part_number_original']){
    $cambios[] = 'part_number';
  }

  if($_POST['part_number_project'] != $_POST['part_number_project_original']){
    $cambios[] = 'part_number_project';
  }

  if($_POST['description'] != $_POST['description_original']){
    $cambios[] = 'description';
  }

  if($_POST['description_project'] != $_POST['description_project_original']){
    $cambios[] = 'description_project';
  }

  if($_POST['quantity'] != $_POST['quantity_original']){
    $cambios[] = 'quantity';
  }

  if($_POST['comments'] != $_POST['comments_original']){
    $cambios[] = 'comments';
  }

  if($_POST['website'] != $_POST['website_original']){
    $cambios[] = 'website';
  }

  if(count($cambios)){
    $cambios = implode(',', $cambios);
    $description_comment = 'A subitem was modified. The fields: <b>' . $cambios . '</b><br>'
    . '<b>Brand:</b><br>'
    . $_POST['brand'] . '<br>' .
    '<b>Part number:</b><br>'
     . $_POST['part_number'];
    $comment = new Comment('', $_POST['id_rfq'], $_SESSION['id_usuario'], $description_comment, '');
    RepositorioComment::insertar_comment(Conexion::obtener_conexion(), $comment);
  }
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#caja_items');
}
?>
