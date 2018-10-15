<?php
session_start();
if(isset($_POST['guardar_fullfillment_form'])){
  Conexion::abrir_conexion();
  RepositorioRfq::check_fullfillment(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $_POST['id_rfq']);
  $cotizacion_copia = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment());
  ConnectionFullFillment::open_connection();
  RepositorioRfqFullFillment::insertar_cotizacion_fullfillment(ConnectionFullFillment::get_connection(), $cotizacion_copia);
  if(count($items)){
    foreach ($items as $item) {
      $item_fullfillment = new Item('', $_POST['id_rfq'], $item-> obtener_id_usuario(), $item-> obtener_provider_menor(), $item-> obtener_brand(), $item-> obtener_brand_project(), $item-> obtener_part_number(), $item-> obtener_part_number_project(), $item-> obtener_description(), $item-> obtener_description_project(), $item-> obtener_quantity(), $item-> obtener_unit_price(), $item-> obtener_total_price(), $item-> obtener_comments(), $item-> obtener_website(), $item-> obtener_additional());
      $id_item_fullfillment = RepositorioItemFullFillment::insertar_item(ConnectionFullFillment::get_connection(), $item_fullfillment);
      $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
      if(count($providers)){
        foreach ($providers as $provider) {
          $provider_fullfillment = new Provider('', $id_item_fullfillment, $provider-> obtener_provider(), $provider-> obtener_price());
          RepositorioProviderFullFillment::insertar_provider(ConnectionFullFillment::get_connection(), $provider_fullfillment);
        }
      }
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
      if(count($subitems)){
        foreach ($subitems as $subitem) {
          $subitem_fullfillment = new Subitem('', $id_item_fullfillment, $subitem-> obtener_provider_menor(), $subitem-> obtener_brand(), $subitem-> obtener_brand_project(), $subitem-> obtener_part_number(), $subitem-> obtener_part_number_project(), $subitem-> obtener_description(), $subitem-> obtener_description_project(), $subitem-> obtener_quantity(), $subitem-> obtener_unit_price(), $subitem-> obtener_total_price(), $subitem-> obtener_comments(), $subitem-> obtener_website(), $subitem-> obtener_additional());
          $id_subitem_fullfillment = RepositorioSubitemFullFillment::insertar_subitem(ConnectionFullFillment::get_connection(), $subitem_fullfillment);
          $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
          if(count($providers_subitem)){
            foreach ($providers_subitem as $provider_subitem) {
              $provider_subitem_fullfillment = new ProviderSubitem('', $id_subitem_fullfillment, $provider_subitem-> obtener_provider(), $provider_subitem-> obtener_price());
              RepositorioProviderSubitemFullfillment::insertar_provider_subitem(ConnectionFullFillment::get_connection(), $provider_subitem_fullfillment);
            }
          }
        }
      }
    }
  }
  $comment = new CommentRfqFullFillment('', $_POST['id_rfq'], $_SESSION['nombre_usuario'], $_POST['fullfillment_comment'], '');
  RepositorioRfqFullFillmentComment::insertar_comment(ConnectionFullFillment::get_connection(), $comment);
  ConnectionFullFillment::close_connection();
  Conexion::cerrar_conexion();

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
  Redireccion::redirigir(EDITAR_COTIZACION . '/' . $_POST['id_rfq']);
}
?>
