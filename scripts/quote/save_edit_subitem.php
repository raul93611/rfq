<?php
session_start();
if (isset($_POST['guardar_cambios_subitem'])) {
  Database::open_connection();
  RepositorioSubitem::actualizar_subitem(Database::get_connection(), $_POST['id_subitem'], $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], htmlspecialchars($_POST['description']), htmlspecialchars($_POST['description_project']), $_POST['quantity'], $_POST['comments'], $_POST['website']);
  AuditTrailRepository::edit_subitem_events(Database::get_connection(), $_POST['brand'], $_POST['brand_original'], $_POST['brand_project'], $_POST['brand_project_original'], $_POST['part_number'], $_POST['part_number_original'], $_POST['part_number_project'], $_POST['part_number_project_original'], $_POST['description'], $_POST['description_original'], $_POST['description_project'], $_POST['description_project_original'], $_POST['quantity'], $_POST['quantity_original'], $_POST['comments'], $_POST['comments_original'], $_POST['website'], $_POST['website_original'], $_POST['id_subitem'], $_POST['id_quote']);
  Database::close_connection();
  Redirection::redirect(EDIT_QUOTE . '/' . $_POST['id_quote'] . '#subitem' . $_POST['id_subitem']);
}
?>
