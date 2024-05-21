<?php
if (isset($_POST['save_tracking_subitem'])) {
  Conexion::abrir_conexion();
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_subitem']);
  $item = RepositorioItem::obtener_item_por_id(Conexion::obtener_conexion(), $subitem->obtener_id_item());
  $delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
  $due_date = RepositorioComment::english_format_to_mysql_date($_POST['due_date']);
  $tracking = new TrackingSubitem('', $_POST['id_subitem'], $_POST['quantity_shipped'], $_POST['carrier'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $due_date, $_POST['signed_by'], htmlspecialchars($_POST['comments']));
  TrackingSubitemRepository::insert_tracking(Conexion::obtener_conexion(), $tracking);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(TRACKING . $item->obtener_id_rfq());
}
