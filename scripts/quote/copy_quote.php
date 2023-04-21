<?php
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$cotizacion_copia = new Rfq(
  '',
  $_SESSION['user']->obtener_id(),
  $cotizacion->obtener_usuario_designado(),
  $cotizacion->obtener_canal(),
  $cotizacion->obtener_email_code() . '(copia)',
  $cotizacion->obtener_type_of_bid(),
  $cotizacion->obtener_issue_date(),
  $cotizacion->obtener_end_date(),
  0,
  0,
  $cotizacion->obtener_total_cost(),
  $cotizacion->obtener_total_price(),
  $cotizacion->obtener_comments(),
  0,
  null,
  null,
  null,
  $cotizacion->obtener_payment_terms(),
  $cotizacion->obtener_address(),
  $cotizacion->obtener_ship_to(),
  null,
  $cotizacion->obtener_ship_via(),
  $cotizacion->obtener_taxes(),
  $cotizacion->obtener_profit(),
  $cotizacion->obtener_additional(),
  $cotizacion->obtener_shipping(),
  $cotizacion->obtener_shipping_cost(),
  0,
  null,
  $cotizacion->obtener_contract_number(),
  null,
  null,
  0,
  0,
  0,
  null,
  $id_rfq,
  0,
  null,
  0,
  0,
  null,
  null,
  null,
  null,
  null,
  'Net 30',
  $cotizacion->obtener_city(),
  $cotizacion->obtener_zip_code(),
  $cotizacion->obtener_state(),
  $cotizacion->obtener_client(),
  0,
  $cotizacion->getSetSide(),
  $cotizacion->getPoc(),
  $cotizacion->getCo(),
  $cotizacion->getEstimatedDeliveryDate(),
  $cotizacion->getShippingAddress(),
  $cotizacion->getSpecialRequirements(),
  $cotizacion->getFileDocument(),
  $cotizacion->getAccounting(),
  $cotizacion->getGsa()
);
list($cotizacion_insertada, $id_rfq_copia) = RepositorioRfq::insertar_cotizacion(Conexion::obtener_conexion(), $cotizacion_copia);
AuditTrailRepository::quote_status_audit_trail(Conexion::obtener_conexion(), 'Copied', $id_rfq_copia);
$rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
$rfq_copia_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq_copia;
Input::copy_files($rfq_directory, $rfq_copia_directory);

$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);

if (count($items)) {
  $items_copias = [];
  foreach ($items as $item) {
    $items_copias[] = new Item('', $id_rfq_copia, $item->obtener_id_usuario(), $item->obtener_provider_menor(), $item->obtener_brand(), $item->obtener_brand_project(), $item->obtener_part_number(), $item->obtener_part_number_project(), $item->obtener_description(), $item->obtener_description_project(), $item->obtener_quantity(), $item->obtener_unit_price(), $item->obtener_total_price(), $item->obtener_comments(), $item->obtener_website(), $item->obtener_additional(), $item->obtener_fulfillment_profit());
  }
  foreach ($items_copias as $item_copia) {
    RepositorioItem::insertar_item(Conexion::obtener_conexion(), $item_copia);
  }

  $items_copias = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq_copia);
  foreach ($items as $i => $item) {
    $item_copia = $items_copias[$i];
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
    if (count($subitems)) {
      $subitems_copias = [];
      foreach ($subitems as $subitem) {
        $subitems_copias[] = new Subitem('', $item_copia->obtener_id(), $subitem->obtener_provider_menor(), $subitem->obtener_brand(), $subitem->obtener_brand_project(), $subitem->obtener_part_number(), $subitem->obtener_part_number_project(), $subitem->obtener_description(), $subitem->obtener_description_project(), $subitem->obtener_quantity(), $subitem->obtener_unit_price(), $subitem->obtener_total_price(), $subitem->obtener_comments(), $subitem->obtener_website(), $subitem->obtener_additional(), $subitem->obtener_fulfillment_profit());
      }
      foreach ($subitems_copias as $subitem_copia) {
        RepositorioSubitem::insertar_subitem(Conexion::obtener_conexion(), $subitem_copia);
      }
      $subitems_copias = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item_copia->obtener_id());
      foreach ($subitems as $j => $subitem) {
        $subitem_copia = $subitems_copias[$j];
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
        if (count($providers_subitem)) {
          $providers_subitem_copias = [];
          foreach ($providers_subitem as $provider_subitem) {
            $providers_subitem_copias[] = new ProviderSubitem('', $subitem_copia->obtener_id(), $provider_subitem->obtener_provider(), $provider_subitem->obtener_price());
          }
          foreach ($providers_subitem_copias as $provider_subitem_copia) {
            RepositorioProviderSubitem::insertar_provider_subitem(Conexion::obtener_conexion(), $provider_subitem_copia);
          }
        }
      }
    }
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
    if (count($providers)) {
      $providers_copias = [];
      foreach ($providers as $provider) {
        $providers_copias[] = new Provider('', $item_copia->obtener_id(), $provider->obtener_provider(), $provider->obtener_price());
      }
      foreach ($providers_copias as $provider_copia) {
        RepositorioProvider::insertar_provider(Conexion::obtener_conexion(), $provider_copia);
      }
    }
  }
}
Conexion::cerrar_conexion();
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq_copia);
