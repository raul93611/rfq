<?php
session_start();
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$quote_copia = new Quote('', $quote-> get_id_user(), $quote-> get_assigned_user(), $quote-> get_channel(), $quote-> get_email_code() . '(copia)', $quote-> get_type_of_bid(), $quote-> get_issue_date(), $quote-> get_end_date(), 0, 0, $quote-> get_total_cost(), $quote-> get_total_price(), $quote-> get_comments(), 0, '', '', '', $quote-> get_payment_terms(), $quote-> get_address(), $quote-> get_ship_to(), '', $quote-> get_ship_via(), $quote-> get_taxes(), $quote-> get_profit(), $quote-> get_additional(), $quote-> get_shipping(), $quote-> get_shipping_cost(), 0, 0, $quote-> get_contract_number(), $quote-> get_fulfillment_profit(), $quote-> get_services_fulfillment_profit(), $quote-> get_total_fulfillment(), $quote-> get_total_services_fulfillment());
list($inserted_quote, $id_quote_copia) = QuoteRepository::insert(Database::get_connection(), $quote_copia);

$rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $id_quote;
$rfq_copia_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $id_quote_copia;
Input::copy_files($rfq_directory, $rfq_copia_directory);

$items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);

if(count($items)){
  $items_copias = [];
  foreach ($items as $item) {
    $items_copias[] = new Item('', $id_quote_copia, $item-> get_id_user(), $item-> get_least_provider(), $item-> get_brand(), $item-> get_brand_project(), $item-> get_part_number(), $item-> get_part_number_project(), $item-> get_description(), $item-> get_description_project(), $item-> get_quantity(), $item-> get_unit_price(), $item-> get_total_price(), $item-> get_comments(), $item-> get_website(), $item-> get_additional(), $item-> get_fulfillment_profit());
  }
  foreach ($items_copias as $item_copia) {
    ItemRepository::insert(Database::get_connection(), $item_copia);
  }

  $items_copias = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote_copia);
  foreach ($items as $i=> $item) {
    $item_copia = $items_copias[$i];
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
    if(count($subitems)){
      $subitems_copias = [];
      foreach ($subitems as $subitem) {
        $subitems_copias[] = new Subitem('', $item_copia-> get_id(), $subitem-> get_least_provider(), $subitem-> get_brand(), $subitem-> get_brand_project(), $subitem-> get_part_number(), $subitem-> get_part_number_project(), $subitem-> get_description(), $subitem-> get_description_project(), $subitem-> get_quantity(), $subitem-> get_unit_price(), $subitem-> get_total_price(), $subitem-> get_comments(), $subitem-> get_website(), $subitem-> get_additional(), $subitem-> get_fulfillment_profit());
      }
      foreach ($subitems_copias as $subitem_copia) {
        RepositorioSubitem::insertar_subitem(Database::get_connection(), $subitem_copia);
      }
      $subitems_copias = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item_copia-> get_id());
      foreach ($subitems as $j=> $subitem) {
        $subitem_copia = $subitems_copias[$j];
        $providers_subitem = ProviderSubitemRepository::get_all_by_id_subitem(Database::get_connection(), $subitem-> get_id());
        if(count($providers_subitem)){
          $providers_subitem_copias = [];
          foreach ($providers_subitem as $provider_subitem) {
            $providers_subitem_copias[] = new ProviderSubitem('', $subitem_copia-> get_id(), $provider_subitem-> get_provider(), $provider_subitem-> get_price());
          }
          foreach ($providers_subitem_copias as $provider_subitem_copia) {
            ProviderSubitemRepository::insert(Database::get_connection(), $provider_subitem_copia);
          }
        }
      }
    }
    $providers = ProviderRepository::get_all_by_id_item(Database::get_connection(), $item-> get_id());
    if(count($providers)){
      $providers_copias = [];
      foreach ($providers as $provider) {
        $providers_copias[] = new Provider('', $item_copia-> get_id(), $provider-> get_provider(), $provider-> get_price());
      }
      foreach ($providers_copias as $provider_copia) {
        ProviderRepository::insert(Database::get_connection(), $provider_copia);
      }
    }
  }
}
Database::close_connection();
Redirection::redirect(EDIT_QUOTE . '/' . $id_quote_copia);
?>
