<?php
class FulfillmentRepository
{
  public static function items_list($id_rfq)
  {
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if (count($items)) {
?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" data="<?php echo $id_rfq; ?>" class="custom-control-input" id="net30_cc" <?php echo $quote->obtener_net30_fulfillment() ? 'checked' : ''; ?>>
        <label class="custom-control-label" for="net30_cc">Net30/CC</label>
      </div>
      <br>
      <div class="table-responsive">
        <table id="fulfillment_items_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="thin">OPTIONS</th>
              <th class="thin">#</th>
              <th class="description">E-LOGIC PROPOSAL</th>
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
              <th>COMMENT</th>
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
              <td class="align-middle text-center">
                <button type="button" id="edit_fulfillment_shipping" data="<?php echo $quote->obtener_id(); ?>" class="btn btn-warning" name=""><i class="fas fa-pen"></i></button>
              </td>
              <td colspan="4">Shipping (from Proposal): <?php echo $quote->obtener_shipping(); ?></td>
              <td>$ <?php echo $quote->obtener_shipping_cost(); ?></td>
              <td></td>
              <td colspan="4">
                <?php echo str_replace('|', '<br>', $quote->obtener_fulfillment_shipping()); ?>
              </td>
              <td>
                <?php echo str_replace('|', '<br>', $quote->obtener_fulfillment_shipping_cost()); ?>
              </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="4">Total RFQ (from Proposal):</td>
              <td><?php echo $quote->obtener_total_price(); ?></td>
              <td colspan="5"></td>
              <td><?php echo $quote->obtener_total_fulfillment(); ?></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php
    }
  }

  public static function item_list($item, $i, $id_rfq)
  {
    if (!isset($item)) {
      return;
    }
    Conexion::abrir_conexion();
    $fulfillment_items = FulfillmentItemRepository::get_all_by_id_item(Conexion::obtener_conexion(), $item->obtener_id());
    Conexion::cerrar_conexion();
    if (!count($fulfillment_items)) {
      $fulfillment_items_quantity = 1;
    } else {
      $fulfillment_items_quantity = count($fulfillment_items);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <button type="button" class="add_fulfillment_item_button btn btn-warning" name="<?php echo $item->obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $item->obtener_brand() . '<br>';
        echo '<b>Part #:</b> ' . $item->obtener_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($item->obtener_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_total_price(); ?></td>
      <?php
      if (count($fulfillment_items)) {
      ?>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?> align-middle text-center">
          <a href="#" data="<?php echo $fulfillment_items[0]->get_id(); ?>" id_item="<?php echo $item->obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $fulfillment_items[0]->get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_provider(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_quantity(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_unit_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_other_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_real_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_payment_term(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id(); ?> text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_items[0]->get_comments()) ? nl2br($fulfillment_items[0]->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
          <br>
          <button data="<?php echo $fulfillment_items[0]->get_id(); ?>" id_item="<?php echo $item->obtener_id(); ?>" type="button" class="reviewed_button text-success btn btn-link">
            <i class="fas fa-check-circle fa-2x"></i>
          </button>
        </td>
        <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_fulfillment_profit(); ?></td>
        <?php
        ?>
    </tr>
    <?php
        for ($j = 1; $j < count($fulfillment_items); $j++) {
          $fulfillment_item = $fulfillment_items[$j];
    ?>
      <tr class="item<?php echo $fulfillment_item->get_id(); ?>">
        <td class="align-middle text-center <?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>">
          <a href="#" data="<?php echo $fulfillment_item->get_id(); ?>" id_item="<?php echo $item->obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $fulfillment_item->get_id(); ?>" class="edit_fulfillment_item_button btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_provider(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_quantity(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_unit_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_other_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_real_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_payment_term(); ?></td>
        <td class="text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_item->get_comments()) ? nl2br($fulfillment_item->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
          <br>
          <button data="<?php echo $fulfillment_item->get_id(); ?>" id_item="<?php echo $item->obtener_id(); ?>" type="button" class="reviewed_button text-success btn btn-link">
            <i class="fas fa-check-circle fa-2x"></i>
          </button>
        </td>
      </tr>
  <?php
        }
      }
      Conexion::abrir_conexion();
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
      Conexion::cerrar_conexion();
      foreach ($subitems as $key => $subitem) {
        self::subitem_list($subitem, $id_rfq);
      }
    }

    public static function subitem_list($subitem, $id_rfq)
    {
      if (!isset($subitem)) {
        return;
      }
      Conexion::abrir_conexion();
      $fulfillment_subitems = FulfillmentSubitemRepository::get_all_by_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
      Conexion::cerrar_conexion();
      if (!count($fulfillment_subitems)) {
        $fulfillment_subitems_quantity = 1;
      } else {
        $fulfillment_subitems_quantity = count($fulfillment_subitems);
      }
  ?>
  <tr>
    <td class="align-middle text-center" rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
      <button type="button" class="add_fulfillment_subitem_button btn btn-warning" name="<?php echo $subitem->obtener_id(); ?>"><i class="fas fa-plus"></i></button>
    </td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
      <?php
      echo '<b>Brand:</b> ' . $subitem->obtener_brand() . '<br>';
      echo '<b>Part #:</b> ' . $subitem->obtener_part_number() . '<br>';
      echo '<b>Description:</b> ' . nl2br(mb_substr($subitem->obtener_description(), 0, 100));
      ?>
    </td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_quantity(); ?></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_unit_price(); ?></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_total_price(); ?></td>
    <?php
      if (count($fulfillment_subitems)) {
    ?>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?> align-middle text-center">
        <a href="#" data="<?php echo $fulfillment_subitems[0]->get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem->obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
        <a href="#" data="<?php echo $fulfillment_subitems[0]->get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
      </td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_provider(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_quantity(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_unit_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_other_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_real_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_payment_term(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); ?> text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_subitems[0]->get_comments()) ? nl2br($fulfillment_subitems[0]->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
        <br>
        <button data="<?php echo $fulfillment_subitems[0]->get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem->obtener_id(); ?>" type="button" class="subitem_reviewed_button text-success btn btn-link">
          <i class="fas fa-check-circle fa-2x"></i>
        </button>
      </td>
      <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_fulfillment_profit(); ?></td>
      <?php
      ?>
  </tr>
  <?php
        for ($j = 1; $j < count($fulfillment_subitems); $j++) {
          $fulfillment_subitem = $fulfillment_subitems[$j];
  ?>
    <tr class="subitem<?php echo $fulfillment_subitem->get_id();?>">
      <td class="align-middle text-center <?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>">
        <a href="#" data="<?php echo $fulfillment_subitem->get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem->obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
        <a href="#" data="<?php echo $fulfillment_subitem->get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-warning"><i class="fas fa-pen"></i></a>
      </td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_provider(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_quantity(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_unit_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_other_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_real_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_payment_term(); ?></td>
      <td class="text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_subitem->get_comments()) ? nl2br($fulfillment_subitem->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
        <br>
        <button data="<?php echo $fulfillment_subitem->get_id(); ?>" id_rfq="<?php echo $id_rfq; ?>" id_subitem="<?php echo $subitem->obtener_id(); ?>" type="button" class="subitem_reviewed_button text-success btn btn-link">
          <i class="fas fa-check-circle fa-2x"></i>
        </button>
      </td>
    </tr>
  <?php
        }
      }
    }

    public static function services_list($id_rfq)
    {
      Conexion::abrir_conexion();
      $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
      $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
      $total = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
      Conexion::cerrar_conexion();
      if (count($services)) {
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
          self::service_list($service, $i, $quote-> obtener_services_payment_term());
        }
        ?>
        <tr>
          <td colspan="5">Total RFP (from Proposal):</td>
          <td><?php echo $total; ?></td>
          <td colspan="5"></td>
          <td><?php echo $quote->obtener_total_services_fulfillment(); ?></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
<?php
      }
    }

    public static function service_list($service, $i, $payment_term)
    {
      $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
      if (!isset($service)) {
        return;
      }
      Conexion::abrir_conexion();
      $fulfillment_services = FulfillmentServiceRepository::get_all_by_id_service(Conexion::obtener_conexion(), $service->get_id());
      Conexion::cerrar_conexion();
      if (!count($fulfillment_services)) {
        $fulfillment_services_quantity = 1;
      } else {
        $fulfillment_services_quantity = count($fulfillment_services);
      }
?>
<tr>
  <td class="align-middle text-center" rowspan="<?php echo $fulfillment_services_quantity; ?>">
    <button type="button" class="add_fulfillment_service_button btn btn-warning" name="<?php echo $service->get_id(); ?>"><i class="fas fa-plus"></i></button>
  </td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $i + 1; ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_description(); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_quantity(); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo number_format($service->get_unit_price() * $payment_term, 2); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_total_price(); ?></td>
  <?php
      if (count($fulfillment_services)) {
  ?>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?> align-middle text-center">
      <a href="#" data="<?php echo $fulfillment_services[0]->get_id(); ?>" id_service="<?php echo $service->get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
      <a href="#" data="<?php echo $fulfillment_services[0]->get_id(); ?>" class="edit_fulfillment_service_button btn btn-warning"><i class="fas fa-pen"></i></a>
    </td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_provider(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_quantity(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_unit_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_other_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_real_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_payment_term(); ?></td>
    <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_fulfillment_profit(); ?></td>
    <?php
    ?>
</tr>
<?php
        for ($j = 1; $j < count($fulfillment_services); $j++) {
          $fulfillment_service = $fulfillment_services[$j];
?>
  <tr class="service<?php echo $fulfillment_service->get_id(); ?>">
    <td class="align-middle text-center">
      <a href="#" data="<?php echo $fulfillment_service->get_id(); ?>" id_service="<?php echo $service->get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
      <a href="#" data="<?php echo $fulfillment_service->get_id(); ?>" class="edit_fulfillment_service_button btn btn-warning"><i class="fas fa-pen"></i></a>
    </td>
    <td><?php echo $fulfillment_service->get_provider(); ?></td>
    <td><?php echo $fulfillment_service->get_quantity(); ?></td>
    <td><?php echo $fulfillment_service->get_unit_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_other_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_real_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_payment_term(); ?></td>
  </tr>
<?php
        }
      }
    }

    public static function items_list_invoice($id_rfq, $from, $to, $total_items)
  {
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if (count($items)) {
?>
      <br>
      <div class="table-responsive">
        <table id="fulfillment_items_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="thin">#</th>
              <th class="description">E-LOGIC PROPOSAL</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT PRICE</th>
              <th class="thin">TOTAL PRICE</th>
              <th class="thin">PROVIDER</th>
              <th class="thin">QTY</th>
              <th class="thin">UNIT COST</th>
              <th class="thin">OTHER COST</th>
              <th>REAL COST</th>
              <th>PAYMENT TERM</th>
              <th>COMMENT</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($items as $i => $item) {
              self::item_list_invoice($item, $i, $id_rfq, $from, $to);
            }
            ?>
            <tr>
              <td colspan="4">Shipping (from Proposal): <?php echo $quote->obtener_shipping(); ?></td>
              <td>$ <?php echo $quote->obtener_shipping_cost(); ?></td>
              <td></td>
              <td colspan="7">
              </td>
            </tr>
            <tr>
              <td colspan="4">Total RFQ (from Proposal):</td>
              <td><?php echo $quote->obtener_total_price(); ?></td>
              <td colspan="4"></td>
              <td><?php echo $total_items; ?></td>
              <td colspan="2"></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php
    }
  }

  public static function item_list_invoice($item, $i, $id_rfq, $from, $to)
  {
    if (!isset($item)) {
      return;
    }
    Conexion::abrir_conexion();
    $fulfillment_items = FulfillmentItemRepository::get_all_by_id_item_from_to(Conexion::obtener_conexion(), $item->obtener_id(), $from, $to);
    Conexion::cerrar_conexion();
    if (!count($fulfillment_items)) {
      $fulfillment_items_quantity = 1;
    } else {
      $fulfillment_items_quantity = count($fulfillment_items);
    }
    ?>
    <tr>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $item->obtener_brand() . '<br>';
        echo '<b>Part #:</b> ' . $item->obtener_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($item->obtener_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_quantity(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_unit_price(); ?></td>
      <td rowspan="<?php echo $fulfillment_items_quantity; ?>"><?php echo $item->obtener_total_price(); ?></td>
      <?php
      if (count($fulfillment_items)) {
      ?>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_provider(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_quantity(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_unit_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_other_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_real_cost(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id();
                        echo $fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_items[0]->get_payment_term(); ?></td>
        <td class="item<?php echo $fulfillment_items[0]->get_id(); ?> text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_items[0]->get_comments()) ? nl2br($fulfillment_items[0]->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
        </td>
        <?php
        ?>
    </tr>
    <?php
        for ($j = 1; $j < count($fulfillment_items); $j++) {
          $fulfillment_item = $fulfillment_items[$j];
    ?>
      <tr class="item<?php echo $fulfillment_item->get_id(); ?>">
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_provider(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_quantity(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_unit_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_other_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_real_cost(); ?></td>
        <td class="<?php echo $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?php echo $fulfillment_item->get_payment_term(); ?></td>
        <td class="text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_item->get_comments()) ? nl2br($fulfillment_item->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
        </td>
      </tr>
  <?php
        }
      }
      Conexion::abrir_conexion();
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
      Conexion::cerrar_conexion();
      foreach ($subitems as $key => $subitem) {
        self::subitem_list_invoice($subitem, $id_rfq, $from, $to);
      }
    }

    public static function subitem_list_invoice($subitem, $id_rfq, $from, $to)
    {
      if (!isset($subitem)) {
        return;
      }
      Conexion::abrir_conexion();
      $fulfillment_subitems = FulfillmentSubitemRepository::get_all_by_id_subitem_from_to(Conexion::obtener_conexion(), $subitem->obtener_id(), $from, $to);
      Conexion::cerrar_conexion();
      if (!count($fulfillment_subitems)) {
        $fulfillment_subitems_quantity = 1;
      } else {
        $fulfillment_subitems_quantity = count($fulfillment_subitems);
      }
  ?>
  <tr>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>">
      <?php
      echo '<b>Brand:</b> ' . $subitem->obtener_brand() . '<br>';
      echo '<b>Part #:</b> ' . $subitem->obtener_part_number() . '<br>';
      echo '<b>Description:</b> ' . nl2br(mb_substr($subitem->obtener_description(), 0, 100));
      ?>
    </td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_quantity(); ?></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_unit_price(); ?></td>
    <td rowspan="<?php echo $fulfillment_subitems_quantity; ?>"><?php echo $subitem->obtener_total_price(); ?></td>
    <?php
      if (count($fulfillment_subitems)) {
    ?>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_provider(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_quantity(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_unit_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_other_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_real_cost(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); echo $fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : '';?>"><?php echo $fulfillment_subitems[0]->get_payment_term(); ?></td>
      <td class="subitem<?php echo $fulfillment_subitems[0]->get_id(); ?> text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_subitems[0]->get_comments()) ? nl2br($fulfillment_subitems[0]->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
      </td>
      <?php
      ?>
  </tr>
  <?php
        for ($j = 1; $j < count($fulfillment_subitems); $j++) {
          $fulfillment_subitem = $fulfillment_subitems[$j];
  ?>
    <tr class="subitem<?php echo $fulfillment_subitem->get_id();?>">
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_provider(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_quantity(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_unit_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_other_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_real_cost(); ?></td>
      <td class="<?php echo $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?php echo $fulfillment_subitem->get_payment_term(); ?></td>
      <td class="text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?php echo !empty($fulfillment_subitem->get_comments()) ? nl2br($fulfillment_subitem->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
      </td>
    </tr>
  <?php
        }
      }
    }

    public static function services_list_invoice($id_rfq, $from, $to, $total_services)
    {
      Conexion::abrir_conexion();
      $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
      $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
      $total = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
      Conexion::cerrar_conexion();
      if (count($services)) {
  ?>
  <div class="table-responsive">
    <table id="fulfillment_services_table" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="thin">#</th>
          <th class="description">PROJECT SPECIFICATIONS</th>
          <th class="thin">QTY</th>
          <th class="thin">UNIT PRICE</th>
          <th class="thin">TOTAL PRICE</th>
          <th class="thin">PROVIDER</th>
          <th class="thin">QTY</th>
          <th class="thin">UNIT COST</th>
          <th class="thin">OTHER COST</th>
          <th>REAL COST</th>
          <th>PAYMENT TERM</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($services as $i => $service) {
          self::service_list_invoice($service, $i, $quote-> obtener_services_payment_term(), $from, $to);
        }
        ?>
        <tr>
          <td colspan="4">Total RFP (from Proposal):</td>
          <td><?php echo $total; ?></td>
          <td colspan="4"></td>
          <td><?php echo $total_services; ?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
<?php
      }
    }

    public static function service_list_invoice($service, $i, $payment_term, $from, $to) {
      $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
      if (!isset($service)) {
        return;
      }
      Conexion::abrir_conexion();
      $fulfillment_services = FulfillmentServiceRepository::get_all_by_id_service_from_to(Conexion::obtener_conexion(), $service->get_id(), $from, $to);
      Conexion::cerrar_conexion();
      if (!count($fulfillment_services)) {
        $fulfillment_services_quantity = 1;
      } else {
        $fulfillment_services_quantity = count($fulfillment_services);
      }
?>
<tr>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $i + 1; ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_description(); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_quantity(); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo number_format($service->get_unit_price() * $payment_term, 2); ?></td>
  <td rowspan="<?php echo $fulfillment_services_quantity; ?>"><?php echo $service->get_total_price(); ?></td>
  <?php
      if (count($fulfillment_services)) {
  ?>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_provider(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_quantity(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_unit_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_other_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_real_cost(); ?></td>
    <td class="service<?php echo $fulfillment_services[0]->get_id(); ?>"><?php echo $fulfillment_services[0]->get_payment_term(); ?></td>
    <?php
    ?>
</tr>
<?php
        for ($j = 1; $j < count($fulfillment_services); $j++) {
          $fulfillment_service = $fulfillment_services[$j];
?>
  <tr class="service<?php echo $fulfillment_service->get_id(); ?>">
    <td><?php echo $fulfillment_service->get_provider(); ?></td>
    <td><?php echo $fulfillment_service->get_quantity(); ?></td>
    <td><?php echo $fulfillment_service->get_unit_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_other_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_real_cost(); ?></td>
    <td><?php echo $fulfillment_service->get_payment_term(); ?></td>
  </tr>
<?php
        }
      }
    }
  }
?>