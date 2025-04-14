<?php
class FulfillmentRepository {
  public static function items_list($id_rfq) {
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if (count($items)) :
?>
      <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" data-id="<?= $id_rfq; ?>" class="custom-control-input" id="net30_cc" <?= $quote->obtener_net30_fulfillment() ? 'checked' : ''; ?>>
        <label class="custom-control-label" for="net30_cc">Net30/CC</label>
      </div>
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
              <th class="thin">INVOICE</th>
              <th class="thin">PROVIDER</th>
              <th class="thin">TRANSACTION DATE</th>
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
                <button type="button" id="edit_fulfillment_shipping" data-id="<?= $quote->obtener_id(); ?>" class="btn btn-item" name=""><i class="fas fa-pen"></i></button>
              </td>
              <td colspan="4">Shipping (from Proposal): <?= $quote->obtener_shipping(); ?></td>
              <td>$ <?= $quote->obtener_shipping_cost(); ?></td>
              <td></td>
              <td></td>
              <td colspan="5">
                <?= str_replace('|', '<br>', $quote->obtener_fulfillment_shipping() ?? ''); ?>
              </td>
              <td>
                <?= str_replace('|', '<br>', $quote->obtener_fulfillment_shipping_cost() ?? ''); ?>
              </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td colspan="4">Total RFQ (from Proposal):</td>
              <td><?= $quote->obtener_total_price(); ?></td>
              <td colspan="7"></td>
              <td><?= $quote->obtener_total_fulfillment(); ?></td>
              <td></td>
              <td></td>
              <td><?= $quote->getRfqFulfillmentProfit(); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php
    endif;
  }

  public static function item_list($item, $i, $id_rfq) {
    if (!isset($item)) return;
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
      <td class="align-middle text-center" rowspan="<?= $fulfillment_items_quantity; ?>">
        <button type="button" class="add_fulfillment_item_button btn btn-item" name="<?= $item->obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?= $fulfillment_items_quantity; ?>"><?= $i + 1; ?></td>
      <td rowspan="<?= $fulfillment_items_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $item->obtener_brand() . '<br>';
        echo '<b>Part #:</b> ' . $item->obtener_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($item->obtener_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?= $fulfillment_items_quantity; ?>"><?= $item->obtener_quantity(); ?></td>
      <td rowspan="<?= $fulfillment_items_quantity; ?>"><?= $item->obtener_unit_price(); ?></td>
      <td rowspan="<?= $fulfillment_items_quantity; ?>"><?= $item->obtener_total_price(); ?></td>
      <?php
      if (count($fulfillment_items)) {
      ?>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?> align-middle text-center">
          <a href="#" data-id="<?= $fulfillment_items[0]->get_id(); ?>" id_item="<?= $item->obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-item"><i class="fas fa-trash"></i></a><br>
          <a href="#" data-id="<?= $fulfillment_items[0]->get_id(); ?>" class="edit_fulfillment_item_button btn btn-item"><i class="fas fa-pen"></i></a>
        </td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->getInvoiceName(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_provider(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_items[0]->getTransactionDate())) : ''; ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_quantity(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_unit_cost(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_other_cost(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_real_cost(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id() . ($fulfillment_items[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_items[0]->get_payment_term(); ?></td>
        <td class="item<?= $fulfillment_items[0]->get_id(); ?> text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_items[0]->get_comments()) ? nl2br($fulfillment_items[0]->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
          <br>
          <button data-id="<?= $fulfillment_items[0]->get_id(); ?>" data-id_item="<?= $item->obtener_id(); ?>" type="button" class="reviewed_button text-success btn btn-link">
            <i class="fas fa-check-circle fa-2x"></i>
          </button>
        </td>
        <td rowspan="<?= $fulfillment_items_quantity; ?>"><?= $item->obtener_fulfillment_profit(); ?></td>
        <?php
        ?>
    </tr>
    <?php
        for ($j = 1; $j < count($fulfillment_items); $j++) {
          $fulfillment_item = $fulfillment_items[$j];
    ?>
      <tr class="item<?= $fulfillment_item->get_id(); ?>">
        <td class="align-middle text-center <?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>">
          <a href="#" data-id="<?= $fulfillment_item->get_id(); ?>" id_item="<?= $item->obtener_id(); ?>" class="delete_fulfillment_item_button mb-2 btn btn-item"><i class="fas fa-trash"></i></a><br>
          <a href="#" data-id="<?= $fulfillment_item->get_id(); ?>" class="edit_fulfillment_item_button btn btn-item"><i class="fas fa-pen"></i></a>
        </td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->getInvoiceName(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_provider(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_item->getTransactionDate())) : ''; ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_quantity(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_unit_cost(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_other_cost(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_real_cost(); ?></td>
        <td class="<?= $fulfillment_item->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_item->get_payment_term(); ?></td>
        <td class="text-center">
          <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_item->get_comments()) ? nl2br($fulfillment_item->get_comments()) : 'No comments'; ?>">
            <i class="fas fa-comment fa-2x"></i>
          </button>
          <br>
          <button data-id="<?= $fulfillment_item->get_id(); ?>" data-id_item="<?= $item->obtener_id(); ?>" type="button" class="reviewed_button text-success btn btn-link">
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

    public static function subitem_list($subitem, $id_rfq) {
      if (!isset($subitem)) return;
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
    <td class="align-middle text-center" rowspan="<?= $fulfillment_subitems_quantity; ?>">
      <button type="button" class="add_fulfillment_subitem_button btn btn-subitem" name="<?= $subitem->obtener_id(); ?>"><i class="fas fa-plus"></i></button>
    </td>
    <td rowspan="<?= $fulfillment_subitems_quantity; ?>"></td>
    <td rowspan="<?= $fulfillment_subitems_quantity; ?>">
      <?php
      echo '<b>Brand:</b> ' . $subitem->obtener_brand() . '<br>';
      echo '<b>Part #:</b> ' . $subitem->obtener_part_number() . '<br>';
      echo '<b>Description:</b> ' . nl2br(mb_substr($subitem->obtener_description(), 0, 100));
      ?>
    </td>
    <td rowspan="<?= $fulfillment_subitems_quantity; ?>"><?= $subitem->obtener_quantity(); ?></td>
    <td rowspan="<?= $fulfillment_subitems_quantity; ?>"><?= $subitem->obtener_unit_price(); ?></td>
    <td rowspan="<?= $fulfillment_subitems_quantity; ?>"><?= $subitem->obtener_total_price(); ?></td>
    <?php
      if (count($fulfillment_subitems)) {
    ?>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?> align-middle text-center">
        <a href="#" data-id="<?= $fulfillment_subitems[0]->get_id(); ?>" id_rfq="<?= $id_rfq; ?>" id_subitem="<?= $subitem->obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-subitem"><i class="fas fa-trash"></i></a><br>
        <a href="#" data-id="<?= $fulfillment_subitems[0]->get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-subitem"><i class="fas fa-pen"></i></a>
      </td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->getInvoiceName(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_provider(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_subitems[0]->getTransactionDate())) : ''; ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_quantity(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_unit_cost(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_other_cost(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_real_cost(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id() . ($fulfillment_subitems[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_subitems[0]->get_payment_term(); ?></td>
      <td class="subitem<?= $fulfillment_subitems[0]->get_id(); ?> text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_subitems[0]->get_comments()) ? nl2br($fulfillment_subitems[0]->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
        <br>
        <button data-id="<?= $fulfillment_subitems[0]->get_id(); ?>" data-id_rfq="<?= $id_rfq; ?>" data-id_subitem="<?= $subitem->obtener_id(); ?>" type="button" class="subitem_reviewed_button text-success btn btn-link">
          <i class="fas fa-check-circle fa-2x"></i>
        </button>
      </td>
      <td rowspan="<?= $fulfillment_subitems_quantity; ?>"><?= $subitem->obtener_fulfillment_profit(); ?></td>
      <?php
      ?>
  </tr>
  <?php
        for ($j = 1; $j < count($fulfillment_subitems); $j++) {
          $fulfillment_subitem = $fulfillment_subitems[$j];
  ?>
    <tr class="subitem<?= $fulfillment_subitem->get_id(); ?>">
      <td class="align-middle text-center <?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>">
        <a href="#" data-id="<?= $fulfillment_subitem->get_id(); ?>" id_rfq="<?= $id_rfq; ?>" id_subitem="<?= $subitem->obtener_id(); ?>" class="delete_fulfillment_subitem_button mb-2 btn btn-subitem"><i class="fas fa-trash"></i></a><br>
        <a href="#" data-id="<?= $fulfillment_subitem->get_id(); ?>" class="edit_fulfillment_subitem_button btn btn-subitem"><i class="fas fa-pen"></i></a>
      </td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->getInvoiceName(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_provider(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_subitem->getTransactionDate())) : ''; ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_quantity(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_unit_cost(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_other_cost(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_real_cost(); ?></td>
      <td class="<?= $fulfillment_subitem->get_reviewed() ? ' success-opacity' : ''; ?>"><?= $fulfillment_subitem->get_payment_term(); ?></td>
      <td class="text-center">
        <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_subitem->get_comments()) ? nl2br($fulfillment_subitem->get_comments()) : 'No comments'; ?>">
          <i class="fas fa-comment fa-2x"></i>
        </button>
        <br>
        <button data-id="<?= $fulfillment_subitem->get_id(); ?>" data-id_rfq="<?= $id_rfq; ?>" data-id_subitem="<?= $subitem->obtener_id(); ?>" type="button" class="subitem_reviewed_button text-success btn btn-link">
          <i class="fas fa-check-circle fa-2x"></i>
        </button>
      </td>
    </tr>
  <?php
        }
      }
    }

    public static function services_list($id_rfq) {
      Conexion::abrir_conexion();
      $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
      $services = ServiceRepository::get_services(Conexion::obtener_conexion(), $id_rfq);
      Conexion::cerrar_conexion();
      if (count($services)) :
  ?>
  <div class="custom-control custom-checkbox mb-3">
    <input type="checkbox" data-id="<?= $id_rfq; ?>" class="custom-control-input" id="net30_cc_services" <?= $quote->getNet30FulfillmentServices() ? 'checked' : ''; ?>>
    <label class="custom-control-label" for="net30_cc_services">Net30/CC</label>
  </div>
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
          <th class="thin">INVOICE</th>
          <th class="thin">PROVIDER</th>
          <th class="thin">TRANSACTION DATE</th>
          <th class="thin">QTY</th>
          <th class="thin">UNIT COST</th>
          <th class="thin">OTHER COST</th>
          <th>REAL COST</th>
          <th>PAYMENT TERM</th>
          <th>COMMENTS</th>
          <th>PROFIT</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($services as $i => $service) {
          self::service_list($service, $i, $quote->obtener_services_payment_term());
        }
        ?>
        <tr>
          <td colspan="5">Total RFP (from Proposal):</td>
          <td><?= $quote->getTotalQuoteServices(); ?></td>
          <td colspan="7"></td>
          <td><?= $quote->obtener_total_services_fulfillment(); ?></td>
          <td></td>
          <td></td>
          <td><?= $quote->getRfpFulfillmentProfit(); ?></td>
        </tr>
      </tbody>
    </table>
  </div>
<?php endif;
    }

    public static function service_list($service, $i, $payment_term) {
      $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
      if (!isset($service)) return;
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
  <td class="align-middle text-center" rowspan="<?= $fulfillment_services_quantity; ?>">
    <button type="button" class="add_fulfillment_service_button btn btn-item" name="<?= $service->get_id(); ?>"><i class="fas fa-plus"></i></button>
  </td>
  <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= $i + 1; ?></td>
  <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= mb_substr($service->get_description(), 0, 100) . ' ...'; ?></td>
  <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= $service->get_quantity(); ?></td>
  <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= number_format($service->get_unit_price() * $payment_term, 2); ?></td>
  <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= $service->get_total_price(); ?></td>
  <?php
      if (count($fulfillment_services)) {
  ?>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?> align-middle text-center">
      <a href="#" data-id="<?= $fulfillment_services[0]->get_id(); ?>" id_service="<?= $service->get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-item"><i class="fas fa-trash"></i></a><br>
      <a href="#" data-id="<?= $fulfillment_services[0]->get_id(); ?>" class="edit_fulfillment_service_button btn btn-item"><i class="fas fa-pen"></i></a>
    </td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->getInvoiceName(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_provider(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_services[0]->getTransactionDate())) : ''; ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_quantity(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_unit_cost(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_other_cost(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_real_cost(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?>"><?= $fulfillment_services[0]->get_payment_term(); ?></td>
    <td class="service<?= $fulfillment_services[0]->get_id() . ($fulfillment_services[0]->get_reviewed() ? ' success-opacity' : ''); ?> text-center">
      <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_services[0]->getComments()) ? nl2br($fulfillment_services[0]->getComments()) : 'No comments'; ?>">
        <i class="fas fa-comment fa-2x"></i>
      </button>
      <br>
      <button data-id="<?= $fulfillment_services[0]->get_id(); ?>" data-id_service="<?= $service->get_id(); ?>" type="button" class="reviewed_service_button text-success btn btn-link">
        <i class="fas fa-check-circle fa-2x"></i>
      </button>
    </td>
    <td rowspan="<?= $fulfillment_services_quantity; ?>"><?= $service->get_fulfillment_profit(); ?></td>
    <?php
    ?>
</tr>
<?php
        for ($j = 1; $j < count($fulfillment_services); $j++) {
          $fulfillment_service = $fulfillment_services[$j];
?>
  <tr class="service<?= $fulfillment_service->get_id(); ?>">
    <td class="align-middle text-center <?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>">
      <a href="#" data-id="<?= $fulfillment_service->get_id(); ?>" id_service="<?= $service->get_id(); ?>" class="delete_fulfillment_service_button mb-2 btn btn-item"><i class="fas fa-trash"></i></a><br>
      <a href="#" data-id="<?= $fulfillment_service->get_id(); ?>" class="edit_fulfillment_service_button btn btn-item"><i class="fas fa-pen"></i></a>
    </td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->getInvoiceName(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_provider(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->getTransactionDate() ? date("m/d/Y", strtotime($fulfillment_service->getTransactionDate())) : ''; ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_quantity(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_unit_cost(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_other_cost(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_real_cost(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?>"><?= $fulfillment_service->get_payment_term(); ?></td>
    <td class="<?= $fulfillment_service->get_reviewed() ? 'success-opacity' : ''; ?> text-center">
      <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="<?= !empty($fulfillment_service->getComments()) ? nl2br($fulfillment_service->getComments()) : 'No comments'; ?>">
        <i class="fas fa-comment fa-2x"></i>
      </button>
      <br>
      <button data-id="<?= $fulfillment_service->get_id(); ?>" data-id_service="<?= $service->get_id(); ?>" type="button" class="reviewed_service_button text-success btn btn-link">
        <i class="fas fa-check-circle fa-2x"></i>
      </button>
    </td>
  </tr>
<?php
        }
      }
    }
  }
?>