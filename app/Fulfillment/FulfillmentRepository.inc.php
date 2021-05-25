<?php
class FulfillmentRepository{
  public static function items_list($id_quote){
    Database::open_connection();
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
    $items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);
    Database::close_connection();
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
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($items as $i => $item) {
              self::item_list($item, $i, $id_quote);
            }
            ?>
            <tr>
              <td colspan="5"></td>
              <td><?php echo $quote-> get_total_price(); ?></td>
              <td colspan="5"></td>
              <td><?php echo $quote-> get_total_fulfillment(); ?></td>
              <td><?php echo $quote-> get_fulfillment_profit(); ?></td>
            </tr>
            <tr>
              <td colspan="12"></td>
              <td>% <?php echo number_format(100*(1-($quote-> get_total_fulfillment()/$quote-> get_total_price())), 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
    }
  }

  public static function item_list($item, $i, $id_quote){
    if(!isset($item)){
      return;
    }
    Database::open_connection();
    $fulfillment_items = FulfillmentItemRepository::get_all_by_id_item(Database::get_connection(), $item-> get_id());
    Database::close_connection();
    if(!count($fulfillment_items)){
      $fulfillment_items_quantity = 1;
    }else{
      $fulfillment_items_quantity = count($fulfillment_items);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <button type="button" class="add_fulfillment_item_button btn btn-warning" name="<?php echo $item-> get_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $item-> get_brand() . '<br>';
        echo '<b>Part #:</b> ' . $item-> get_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($item-> get_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> get_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> get_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> get_total_price(); ?></td>
      <?php
      if(count($fulfillment_items)){
            ?>
            <td class="align-middle text-center">
              <a href="#" data="<?php echo $fulfillment_items[0]-> get_id(); ?>" id_item="<?php echo $item-> get_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
              <a href="#" data="<?php echo $fulfillment_items[0]-> get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
            </td>
            <td><?php echo $fulfillment_items[0]-> get_provider(); ?></td>
            <td><?php echo $fulfillment_items[0]-> get_quantity(); ?></td>
            <td><?php echo $fulfillment_items[0]-> get_unit_cost(); ?></td>
            <td><?php echo $fulfillment_items[0]-> get_other_cost(); ?></td>
            <td><?php echo $fulfillment_items[0]-> get_real_cost(); ?></td>
            <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item-> get_fulfillment_profit(); ?></td>
            <?php
          ?>
        </tr>
        <?php
        for ($j = 1; $j < count($fulfillment_items); $j++) {
          $fulfillment_item = $fulfillment_items[$j];
          ?>
          <tr>
            <td class="align-middle text-center">
              <a href="#" data="<?php echo $fulfillment_item-> get_id(); ?>" id_item="<?php echo $item-> get_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
              <a href="#" data="<?php echo $fulfillment_item-> get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
            </td>
            <td><?php echo $fulfillment_item-> get_provider(); ?></td>
            <td><?php echo $fulfillment_item-> get_quantity(); ?></td>
            <td><?php echo $fulfillment_item-> get_unit_cost(); ?></td>
            <td><?php echo $fulfillment_item-> get_other_cost(); ?></td>
            <td><?php echo $fulfillment_item-> get_real_cost(); ?></td>
          </tr>
          <?php
        }
      }
    Database::open_connection();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
    Database::close_connection();
    foreach ($subitems as $key => $subitem) {
      self::subitem_list($subitem, $id_quote);
    }
  }

  public static function subitem_list($subitem, $id_quote){
    if(!isset($subitem)){
      return;
    }
    Database::open_connection();
    $fulfillment_subitems = FulfillmentSubitemRepository::get_all_by_id_subitem(Database::get_connection(), $subitem-> get_id());
    Database::close_connection();
    if(!count($fulfillment_subitems)){
      $fulfillment_subitems_quantity = 1;
    }else{
      $fulfillment_subitems_quantity = count($fulfillment_subitems);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
        <button type="button" class="add_fulfillment_subitem_button btn btn-warning" name="<?php echo $subitem-> get_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $subitem-> get_brand() . '<br>';
        echo '<b>Part #:</b> ' . $subitem-> get_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($subitem-> get_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> get_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> get_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> get_total_price(); ?></td>
      <?php
      if(count($fulfillment_subitems)){
            ?>
            <td class="align-middle text-center">
              <a href="#" data="<?php echo $fulfillment_subitems[0]-> get_id(); ?>" id_quote="<?php echo $id_quote; ?>" id_subitem="<?php echo $subitem-> get_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
              <a href="#" data="<?php echo $fulfillment_subitems[0]-> get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
            </td>
            <td><?php echo $fulfillment_subitems[0]-> get_provider(); ?></td>
            <td><?php echo $fulfillment_subitems[0]-> get_quantity(); ?></td>
            <td><?php echo $fulfillment_subitems[0]-> get_unit_cost(); ?></td>
            <td><?php echo $fulfillment_subitems[0]-> get_other_cost(); ?></td>
            <td><?php echo $fulfillment_subitems[0]-> get_real_cost(); ?></td>
            <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem-> get_fulfillment_profit(); ?></td>
            <?php
          ?>
        </tr>
        <?php
        for ($j = 1; $j < count($fulfillment_subitems); $j++) {
          $fulfillment_subitem = $fulfillment_subitems[$j];
          ?>
          <tr>
            <td class="align-middle text-center">
              <a href="#" data="<?php echo $fulfillment_subitem-> get_id(); ?>" id_quote="<?php echo $id_quote; ?>" id_subitem="<?php echo $subitem-> get_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
              <a href="#" data="<?php echo $fulfillment_subitem-> get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
            </td>
            <td><?php echo $fulfillment_subitem-> get_provider(); ?></td>
            <td><?php echo $fulfillment_subitem-> get_quantity(); ?></td>
            <td><?php echo $fulfillment_subitem-> get_unit_cost(); ?></td>
            <td><?php echo $fulfillment_subitem-> get_other_cost(); ?></td>
            <td><?php echo $fulfillment_subitem-> get_real_cost(); ?></td>
          </tr>
          <?php
        }
      }
  }

  public static function services_list($id_quote){
    Database::open_connection();
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
    $services = ServiceRepository::get_services(Database::get_connection(), $id_quote);
    $total = ServiceRepository::get_total(Database::get_connection(), $id_quote);
    Database::close_connection();
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
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($services as $i => $service) {
              self::service_list($service, $i, $id_quote);
            }
            ?>
            <tr>
              <td colspan="5"></td>
              <td><?php echo $total; ?></td>
              <td colspan="5"></td>
              <td><?php echo $quote-> get_total_services_fulfillment(); ?></td>
              <td><?php echo $quote-> get_services_fulfillment_profit(); ?></td>
            </tr>
            <tr>
              <td colspan="12"></td>
              <td>% <?php echo number_format(100*(1-($quote-> get_total_services_fulfillment()/$total)), 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
    }
  }

  public static function service_list($service, $i, $id_quote){
    if(!isset($service)){
      return;
    }
    Database::open_connection();
    $fulfillment_services = FulfillmentServiceRepository::get_all_by_id_service(Database::get_connection(), $service-> get_id());
    Database::close_connection();
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
        </tr>
        <?php
      }
    }
  }
}
?>
