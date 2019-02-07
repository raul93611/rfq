<?php
if(isset($_POST['save_re_quote_item'])){
  $re_quote_item = new ReQuoteItem('', $_POST['id_re_quote'], $_POST['brand'], $_POST['brand_project'], $_POST['part_number'], $_POST['part_number_project'], $_POST['description'], $_POST['description_project'], $_POST['quantity'], 0, 0, htmlspecialchars($_POST['comments']), $_POST['website'], 0);
  Conexion::abrir_conexion();
  $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $_POST['id_re_quote']);
  ReQuoteItemRepository::insert_re_quote_item(Conexion::obtener_conexion(), $re_quote_item);
  Conexion::cerrar_conexion();
  Redireccion::redirigir(RE_QUOTE . $re_quote-> get_id_rfq());
}
?>
