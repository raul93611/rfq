<?php
if (isset($_POST['guardar_cambios_provider_subitem'])) {
  Conexion::abrir_conexion();
  RepositorioProviderSubitem::actualizar_provider_subitem(Conexion::obtener_conexion(), $_POST['id_provider_subitem'], $_POST['provider'], $_POST['price']);
  $provider_subitem = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $_POST['id_provider_subitem']);
  $subitem = RepositorioSubitem::obtener_subitem_por_id(Conexion::obtener_conexion(), $provider_subitem-> obtener_id_subitem());
  AuditTrailRepository::edit_provider_subitem_events(Conexion::obtener_conexion(), $_POST['provider'], $_POST['provider_original'], $_POST['price'], $_POST['price_original'], $subitem-> obtener_id(), $_POST['id_rfq']);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq'] . '#subitem' . $subitem-> obtener_id());
}
?>
