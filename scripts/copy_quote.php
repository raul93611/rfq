<?php
session_start();
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$cotizacion_copia = new Rfq('', $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code() . '(copia)', $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), 0, 0, $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), 0, '', '', '', $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), '', $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), 0, 0);
list($cotizacion_insertada, $id_rfq_copia) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion_copia);
$cuestionario_copia = new Cuestionario('', $id_rfq_copia, '', '', '', '', '', '', '', '', '');
RepositorioCuestionario::insertar_cuestionario(Conexion::obtener_conexion(), $cuestionario_copia);

$rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
$rfq_copia_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq_copia;
mkdir($rfq_copia_directory, 0777);
if(is_dir($rfp_directory)){
  $manager = opendir($rfq_directory);
  $folder = @scandir($rfq_directory);
  while(($file = readdir($manager)) !== false){
    if($file != '.' && $file != '..'){
      copy($rfq_directory . '/' . $file, $rfq_copia_directory . '/' . $file);
    }
  }
  closedir($manager);
}

$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
$items_copias = [];
$providers_copias = [];
$subitems_copias = [];
$providers_subitem_copias = [];
if(count($items)){
  foreach ($items as $item) {
    $items_copias[] = new Item('', $id_rfq_copia, $item-> obtener_id_usuario(), $item-> obtener_provider_menor(), $item-> obtener_brand(), $item-> obtener_brand_project(), $item-> obtener_part_number(), $item-> obtener_part_number_project(), $item-> obtener_description(), $item-> obtener_description_project(), $item-> obtener_quantity(), $item-> obtener_unit_price(), $item-> obtener_total_price(), $item-> obtener_comments(), $item-> obtener_website(), $item-> obtener_additional());
  }
  foreach ($items_copias as $item_copia) {
    RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item_copia);
  }
  $items_copias = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq_copia);
  $i = 0;
  foreach ($items as $item) {
    $item_copia = $items_copias[$i];
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    if(count($subitems)){
      foreach ($subitems as $subitem) {
        $subitems_copias[] = new Subitem('', $item_copia-> obtener_id(), $subitem-> obtener_provider_menor(), $subitem-> obtener_brand(), $subitem-> obtener_brand_project(), $subitem-> obtener_part_number(), $subitem-> obtener_part_number_project(), $subitem-> obtener_description(), $subitem-> obtener_description_project(), $subitem-> obtener_quantity(), $subitem-> obtener_unit_price(), $subitem-> obtener_total_price(), $subitem-> obtener_comments(), $subitem-> obtener_website(), $subitem-> obtener_additional());
      }
      foreach ($subitems_copias as $subitem_copia) {
        RepositorioSubitem::insertar_subitem(Conexion::obtener_conexion(), $subitem_copia);
      }
      $subitems_copias = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item_copia-> obtener_id());
      $j = 0;
      foreach ($subitems as $subitem) {
        $subitem_copia = $subitems_copias[$j];
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
        if(count($providers_subitem)){
          foreach ($providers_subitem as $provider_subitem) {
            $providers_subitem_copias[] = new ProviderSubitem('', $subitem_copia-> obtener_id(), $provider_subitem-> obtener_provider(), $provider_subitem-> obtener_price());
          }
          foreach ($providers_subitem_copias as $provider_subitem_copia) {
            RepositorioProviderSubitem::insertar_provider_subitem(Conexion::obtener_conexion(), $provider_subitem_copia);
          }
        }
        $j++;
      }
    }
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    if(count($providers)){
      foreach ($providers as $provider) {
        $providers_copias[] = new Provider('', $item_copia-> obtener_id(), $provider-> obtener_provider(), $provider-> obtener_price());
      }
      foreach ($providers_copias as $provider_copia) {
        RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider_copia);
      }
    }
    $i++;
  }
}
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq_copia);
Conexion::cerrar_conexion();
?>
