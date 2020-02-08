<?php
Conexion::abrir_conexion();
$re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $id_rfq);
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
if(!$re_quote_exists){
  $re_quote = new ReQuote('', $id_rfq, $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_shipping());
  $id_re_quote = ReQuoteRepository::insert_re_quote(Conexion::obtener_conexion(), $re_quote);
  $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
  if(count($items)){
    foreach ($items as $key => $item) {
      $re_quote_item = new ReQuoteItem('', $id_re_quote, $item-> obtener_brand(), $item-> obtener_brand_project(), $item-> obtener_part_number(), $item-> obtener_part_number_project(), $item-> obtener_description(), $item-> obtener_description_project(), $item-> obtener_quantity(), $item-> obtener_unit_price(), $item-> obtener_total_price(), $item-> obtener_comments(), $item-> obtener_website(), $item-> obtener_additional());
      $id_re_quote_item = ReQuoteItemRepository::insert_re_quote_item(Conexion::obtener_conexion(), $re_quote_item);
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
      if(count($subitems)){
        foreach ($subitems as $key => $subitem) {
          $re_quote_subitem = new ReQuoteSubitem('', $id_re_quote_item, $subitem-> obtener_brand(), $subitem-> obtener_brand_project(), $subitem-> obtener_part_number(), $subitem-> obtener_part_number_project(), $subitem-> obtener_description(), $subitem-> obtener_description_project(), $subitem-> obtener_quantity(), $subitem-> obtener_unit_price(), $subitem-> obtener_total_price(), $subitem-> obtener_comments(), $subitem-> obtener_website(), $subitem-> obtener_additional());
          $id_re_quote_subitem = ReQuoteSubitemRepository::insert_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem);
          $subitem_providers = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
          if(count($subitem_providers)){
            foreach ($subitem_providers as $key => $subitem_provider) {
              $re_quote_subitem_provider = new ReQuoteSubitemProvider('', $id_re_quote_subitem, $subitem_provider-> obtener_provider(), $subitem_provider-> obtener_price());
              ReQuoteSubitemProviderRepository::insert_re_quote_subitem_provider(Conexion::obtener_conexion(), $re_quote_subitem_provider);
            }
          }
        }
      }
      $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
      if(count($providers)){
        foreach ($providers as $key => $provider) {
          $re_quote_provider = new ReQuoteProvider('', $id_re_quote_item, $provider-> obtener_provider(), $provider-> obtener_price());
          ReQuoteProviderRepository::insert_re_quote_provider(Conexion::obtener_conexion(), $re_quote_provider);
        }
      }
    }
  }
}
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Re quote</h1>
        </div>
        <div class="col-sm-10">
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <form class="" id="re_quote_form" action="<?php echo SAVE_RE_QUOTE; ?>" method="post">
              <input type="hidden" name="id_re_quote" value="<?php echo $re_quote-> get_id(); ?>">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
              </div>
              <div class="card-body">
                <?php
                  ReQuoteItemRepository::print_re_quote_items($re_quote-> get_id());
                ?>
              </div>
              <div class="card-footer footer_item">
                <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $re_quote-> get_id_rfq(); ?>"><i class="fa fa-reply"></i></a>
                <button type="submit" class="btn btn-success" name="save_re_quote"><i class="fas fa-check"></i> Save</button>
                <a href="<?php echo ADD_RE_QUOTE_ITEM . $re_quote-> get_id(); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add item</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
