<?php
session_start();
if(isset($_POST['guardar_fullfillment_form'])){
  Conexion::abrir_conexion();
  RepositorioRfq::check_fullfillment(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote-> get_id());
  $cotizacion_copia = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $re_quote-> get_total_cost(), $re_quote-> get_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $re_quote-> get_taxes(), $re_quote-> get_profit(), $re_quote-> get_additional(), $re_quote-> get_shipping(), $re_quote-> get_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment(), $cotizacion-> obtener_contract_number());
  ConnectionFullFillment::open_connection();
  RepositorioRfqFullFillment::insertar_cotizacion_fullfillment(ConnectionFullFillment::get_connection(), $cotizacion_copia);
  if(count($re_quote_items)){
    foreach ($re_quote_items as $re_quote_item) {
      $item_fullfillment = new Item('', $_POST['id_rfq'], 0, 0, $re_quote_item-> get_brand(), $re_quote_item-> get_brand_project(), $re_quote_item-> get_part_number(), $re_quote_item-> get_part_number_project(), $re_quote_item-> get_description(), $re_quote_item-> get_description_project(), $re_quote_item-> get_quantity(), $re_quote_item-> get_unit_price(), $re_quote_item-> get_total_price(), $re_quote_item-> get_comments(), $re_quote_item-> get_website(), $re_quote_item-> get_additional());
      $id_item_fullfillment = RepositorioItemFullFillment::insertar_item(ConnectionFullFillment::get_connection(), $item_fullfillment);
      $extra_item = new ExtraItem('', $id_item_fullfillment, '');
      ExtraItemRepository::insert_extra_item(ConnectionFullFillment::get_connection(), $extra_item);
      $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
      if(count($re_quote_providers)){
        foreach ($re_quote_providers as $re_quote_provider) {
          $provider_fullfillment = new Provider('', $id_item_fullfillment, $re_quote_provider-> get_provider(), $re_quote_provider-> get_price());
          RepositorioProviderFullFillment::insertar_provider(ConnectionFullFillment::get_connection(), $provider_fullfillment);
        }
      }
      $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
      if(count($re_quote_subitems)){
        foreach ($re_quote_subitems as $re_quote_subitem) {
          $subitem_fullfillment = new Subitem('', $id_item_fullfillment, 0, $re_quote_subitem-> get_brand(), $re_quote_subitem-> get_brand_project(), $re_quote_subitem-> get_part_number(), $re_quote_subitem-> get_part_number_project(), $re_quote_subitem-> get_description(), $re_quote_subitem-> get_description_project(), $re_quote_subitem-> get_quantity(), $re_quote_subitem-> get_unit_price(), $re_quote_subitem-> get_total_price(), $re_quote_subitem-> get_comments(), $re_quote_subitem-> get_website(), $re_quote_subitem-> get_additional());
          $id_subitem_fullfillment = RepositorioSubitemFullFillment::insertar_subitem(ConnectionFullFillment::get_connection(), $subitem_fullfillment);
          $extra_subitem = new ExtraSubitem('', $id_subitem_fullfillment, '');
          ExtraSubitemRepository::insert_extra_subitem(ConnectionFullFillment::get_connection(), $extra_subitem);
          $re_quote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem-> get_id());
          if(count($re_quote_subitem_providers)){
            foreach ($re_quote_subitem_providers as $re_quote_subitem_provider) {
              $provider_subitem_fullfillment = new ProviderSubitem('', $id_subitem_fullfillment, $re_quote_subitem_provider-> get_provider(), $re_quote_subitem_provider-> get_price());
              RepositorioProviderSubitemFullfillment::insertar_provider_subitem(ConnectionFullFillment::get_connection(), $provider_subitem_fullfillment);
            }
          }
        }
      }
    }
  }
  $comment = new CommentRfqFullFillment('', $_POST['id_rfq'], $_SESSION['nombre_usuario'], $_POST['fullfillment_comment'], '');
  RepositorioRfqFullFillmentComment::insertar_comment(ConnectionFullFillment::get_connection(), $comment);
  $rfq_fullfillment_part = new RfqFullFillmentPart('', $_POST['id_rfq'], '', '', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '', 0, '', '', '', '');
  RfqFullFillmentPartRepository::insert_rfq_fullfillment_part(ConnectionFullFillment::get_connection(), $rfq_fullfillment_part);
  $fullfillment_users = UserFullFillmentRepository::get_all_fullfillment_users(ConnectionFullFillment::get_connection());
  ConnectionFullFillment::close_connection();
  Conexion::cerrar_conexion();

  foreach ($fullfillment_users as $fullfillment_user) {
    $to = $fullfillment_user-> get_email();
    $subject = 'New quote: proposal ' . $cotizacion-> obtener_id();
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From:" .  $_SESSION['nombre_usuario']  . " <elogic@e-logic.us>\r\n";
    $message = '
    <html>
    <body>
    <h3>Details:</h3>
    <p>' . $_POST['fullfillment_comment'] . '</p>
    <h5>Quote:</h5>
    <p><a href="http://www.elogicportal.com/fullfillment/profile/edit_quote/' . $cotizacion-> obtener_id() . '">' . $cotizacion-> obtener_id() . '</a></p>
    <h5>Comment:</h5>
    <p>New quote in fullfillment.</p>
    </body>
    </html>
    ';
    mail($to, $subject, $message, $headers);
  }

  $fullfillment_directory = $_SERVER['DOCUMENT_ROOT'] . '/fullfillment/documents/rfq_team/' . $_POST['id_rfq'];
  $rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $_POST['id_rfq'];
  mkdir($fullfillment_directory, 0777);
  if(is_dir($rfq_directory)){
    $manager = opendir($rfq_directory);
    $folder = @scandir($rfq_directory);
    while(($file = readdir($manager)) !== false){
      if($file != '.' && $file != '..'){
        copy($rfq_directory . '/' . $file, $fullfillment_directory . '/' . $file);
      }
    }
    closedir($manager);
  }
  Redireccion::redirigir1(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
