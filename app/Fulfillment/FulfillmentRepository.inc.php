<?php
class FulfillmentRepository{
  public static function items_list($id_rfq){
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if(count($items)){
      ?>
      <div class="table-responsive">
        <table id="fulfillment_items_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="thin">OPTIONS</th>
              <th class="thin">#</th>
              <th class="description">PROJECT SPECIFICATIONS</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT PRICE</th>
              <th class="thin">TOTAL PRICE</th>
              <th class="thin">OPTIONS</th>
              <th class="thin">PROVIDER</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT COST</th>
              <th class="thin">OTHER COST</th>
              <th>REAL COST</th>
              <th>PAYMENT TERM</th>
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($items as $i => $item) {
              self::item_list($item, $i, $id_rfq);
            }
            ?>
            <tr>
              <td colspan="5"></td>
              <td><?php echo $quote-> obtener_total_price(); ?></td>
              <td colspan="5"></td>
              <td><?php echo $quote-> obtener_total_fulfillment(); ?></td>
              <td></td>
              <td><?php echo $quote-> obtener_fulfillment_profit(); ?></td>
            </tr>
            <tr>
              <td colspan="13"></td>
              <td>% <?php echo number_format(100*(1-($quote-> obtener_total_fulfillment()/$quote-> obtener_total_price())), 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
    }
  }

  public static function item_list($item, $i, $id_rfq){
    if(!isset($item)){
      return;
    }
    Conexion::abrir_conexion();
    $fulfillment_items = FulfillmentItemRepository::get_all_by_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    if(!count($fulfillment_items)){
      $fulfillment_items_quantity = 1;
    }else{
      $fulfillment_items_quantity = count($fulfillment_items);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <button type="button" class="add_fulfillment_item_button btn btn-warning" name="<?php echo $item-> obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $item-> obtener_brand() . '<br>';
        echo '<b>Part #:</b> ' . $item-> obtener_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($item-> obtener_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> obtener_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> obtener_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> obtener_total_price(); ?></td>
      <?php
  if(count($fulfillment_items)){
        ?>
        <td class="align-middle text-center">
          <a href="#" data="<?php echo $fulfillment_items[0]-> get_id(); ?>" id_item="<?php echo $item-> obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $fulfillment_items[0]-> get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td><?php echo $fulfillment_items[0]-> get_provider(); ?></td>
        <td><?php echo $fulfillment_items[0]-> get_quantity(); ?></td>
        <td><?php echo $fulfillment_items[0]-> get_unit_cost(); ?></td>
        <td><?php echo $fulfillment_items[0]-> get_other_cost(); ?></td>
        <td><?php echo $fulfillment_items[0]-> get_real_cost(); ?></td>
        <td><?php echo $fulfillment_items[0]-> get_payment_term(); ?></td>
        <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> obtener_fulfillment_profit(); ?></td>
        <?php
      ?>
    </tr>
    <?php
    for ($j = 1; $j < count($fulfillment_items); $j++) {
      $fulfillment_item = $fulfillment_items[$j];
      ?>
      <tr>
        <td class="align-middle text-center">
          <a href="#" data="<?php echo $fulfillment_item-> get_id(); ?>" id_item="<?php echo $item-> obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $fulfillment_item-> get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td><?php echo $fulfillment_item-> get_provider(); ?></td>
        <td><?php echo $fulfillment_item-> get_quantity(); ?></td>
        <td><?php echo $fulfillment_item-> get_unit_cost(); ?></td>
        <td><?php echo $fulfillment_item-> get_other_cost(); ?></td>
        <td><?php echo $fulfillment_item-> get_real_cost(); ?></td>
        <td><?php echo $fulfillment_item-> get_payment_term(); ?></td>
      </tr>
      <?php
    }
  }
    Conexion::abrir_conexion();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    foreach ($subitems as $key => $subitem) {
      self::subitem_list($subitem, $id_rfq);
    }
  }

  public static function subitem_list($subitem, $id_rfq){
    if(!isset($subitem)){
      return;
    }
    Conexion::abrir_conexion();
    $fulfillment_subitems = FulfillmentSubitemRepository::get_all_by_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
    Conexion::cerrar_conexion();
    if(!count($fulfillment_subitems)){
      $fulfillment_subitems_quantity = 1;
    }else{
      $fulfillment_subitems_quantity = count($fulfillment_subitems);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
        <button type="button" class="add_fulfillment_subitem_button btn btn-warning" name="<?php echo $subitem-> obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $subitem-> obtener_brand() . '<br>';
        echo '<b>Part #:</b> ' . $subitem-> obtener_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($subitem-> obtener_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> obtener_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> obtener_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> obtener_total_price(); ?></td>
      <?php
    if(count($fulfillment_subitems)){
          ?>
          <td class="align-middle text-center">
            <a href="#" data="<?php echo $fulfillment_subitems[0]-> get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem-> obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $fulfillment_subitems[0]-> get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $fulfillment_subitems[0]-> get_provider(); ?></td>
          <td><?php echo $fulfillment_subitems[0]-> get_quantity(); ?></td>
          <td><?php echo $fulfillment_subitems[0]-> get_unit_cost(); ?></td>
          <td><?php echo $fulfillment_subitems[0]-> get_other_cost(); ?></td>
          <td><?php echo $fulfillment_subitems[0]-> get_real_cost(); ?></td>
          <td><?php echo $fulfillment_subitems[0]-> get_payment_term(); ?></td>
          <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> obtener_fulfillment_profit(); ?></td>
          <?php
        ?>
      </tr>
      <?php
      for ($j = 1; $j < count($fulfillment_subitems); $j++) {
        $fulfillment_subitem = $fulfillment_subitems[$j];
        ?>
        <tr>
          <td class="align-middle text-center">
            <a href="#" data="<?php echo $fulfillment_subitem-> get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem-> obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $fulfillment_subitem-> get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $fulfillment_subitem-> get_provider(); ?></td>
          <td><?php echo $fulfillment_subitem-> get_quantity(); ?></td>
          <td><?php echo $fulfillment_subitem-> get_unit_cost(); ?></td>
          <td><?php echo $fulfillment_subitem-> get_other_cost(); ?></td>
          <td><?php echo $fulfillment_subitem-> get_real_cost(); ?></td>
          <td><?php echo $fulfillment_subitem-> get_payment_term(); ?></td>
        </tr>
        <?php
      }
    }
  }

  public static function services_list($id_rfq){
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
    $total = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if(count($services)){
      ?>
      <div class="table-responsive">
        <table id="fulfillment_services_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="thin">OPTIONS</th>
              <th class="thin">#</th>
              <th class="description">PROJECT SPECIFICATIONS</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT PRICE</th>
              <th class="thin">TOTAL PRICE</th>
              <th class="thin">OPTIONS</th>
              <th class="thin">PROVIDER</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT COST</th>
              <th class="thin">OTHER COST</th>
              <th>REAL COST</th>
              <th>PAYMENT TERM</th>
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($services as $i => $service) {
              self::service_list($service, $i, $id_rfq);
            }
            ?>
            <tr>
              <td colspan="5"></td>
              <td><?php echo $total; ?></td>
              <td colspan="5"></td>
              <td><?php echo $quote-> obtener_total_services_fulfillment(); ?></td>
              <td></td>
              <td><?php echo $quote-> obtener_services_fulfillment_profit(); ?></td>
            </tr>
            <tr>
              <td colspan="13"></td>
              <td>% <?php echo number_format(100*(1-($quote-> obtener_total_services_fulfillment()/$total)), 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
    }
  }

  public static function service_list($service, $i, $id_rfq){
    if(!isset($service)){
      return;
    }
    Conexion::abrir_conexion();
    $fulfillment_services = FulfillmentServiceRepository::get_all_by_id_service(Conexion::obtener_conexion(), $service-> get_id());
    Conexion::cerrar_conexion();
    if(!count($fulfillment_services)){
      $fulfillment_services_quantity = 1;
    }else{
      $fulfillment_services_quantity = count($fulfillment_services);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_services_quantity; ?>">
        <button type="button" class="add_fulfillment_service_button btn btn-warning" name="<?php echo $service-> get_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service-> get_description(); ?></td>
      <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service-> get_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service-> get_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service-> get_total_price(); ?></td>
      <?php
    if(count($fulfillment_services)){
          ?>
          <td class="align-middle text-center">
            <a href="#" data="<?php echo $fulfillment_services[0]-> get_id(); ?>" id_service="<?php echo $service-> get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $fulfillment_services[0]-> get_id(); ?>" class="edit_fulfillment_service_button btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $fulfillment_services[0]-> get_provider(); ?></td>
          <td><?php echo $fulfillment_services[0]-> get_quantity(); ?></td>
          <td><?php echo $fulfillment_services[0]-> get_unit_cost(); ?></td>
          <td><?php echo $fulfillment_services[0]-> get_other_cost(); ?></td>
          <td><?php echo $fulfillment_services[0]-> get_real_cost(); ?></td>
          <td><?php echo $fulfillment_services[0]-> get_payment_term(); ?></td>
          <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service-> get_fulfillment_profit(); ?></td>
          <?php
        ?>
      </tr>
      <?php
      for ($j = 1; $j < count($fulfillment_services); $j++) {
        $fulfillment_service = $fulfillment_services[$j];
        ?>
        <tr>
          <td class="align-middle text-center">
            <a href="#" data="<?php echo $fulfillment_service-> get_id(); ?>" id_service="<?php echo $service-> get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $fulfillment_service-> get_id(); ?>" class="edit_fulfillment_service_button btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $fulfillment_service-> get_provider(); ?></td>
          <td><?php echo $fulfillment_service-> get_quantity(); ?></td>
          <td><?php echo $fulfillment_service-> get_unit_cost(); ?></td>
          <td><?php echo $fulfillment_service-> get_other_cost(); ?></td>
          <td><?php echo $fulfillment_service-> get_real_cost(); ?></td>
          <td><?php echo $fulfillment_service-> get_payment_term(); ?></td>
        </tr>
        <?php
      }
    }
  }
}
?>
