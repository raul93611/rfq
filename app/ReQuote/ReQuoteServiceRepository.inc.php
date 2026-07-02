<?php
class ReQuoteServiceRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new ReQuoteService($row['id'], $row['id_re_quote'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new ReQuoteService($row['id'], $row['id_re_quote'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price']);

    return $object;
  }

  public static function display_services($connection, $re_quote) {
    $services      = self::get_services($connection, $re_quote->get_id());
    $total_service = self::get_total($connection, $re_quote->get_id());

    if (!count($services)): ?>
      <div class="section-empty-state">
        <i class="fas fa-concierge-bell"></i>
        <p>No services to display</p>
      </div>
    <?php return; endif; ?>

    <!-- Payment terms -->
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;font-size:13px;">
      <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.4px;color:#8896a5;">Payment Terms</span>
      <?php $rq_services_payment_term = $re_quote->get_services_payment_term(); ?>
      <select name="services_payment_term" id="rq_services_payment_term" class="form-control form-control-sm js-payment-terms" style="width:auto;">
        <option value="Net 30" <?= $rq_services_payment_term == 'Net 30' ? 'selected' : ''; ?>>Net 30</option>
        <option value="Net 30/CC" <?= $rq_services_payment_term == 'Net 30/CC' ? 'selected' : ''; ?>>Net 30/CC</option>
        <option value="50% Upfront / 50% on Completion" <?= $rq_services_payment_term == '50% Upfront / 50% on Completion' ? 'selected' : ''; ?>>50% Upfront / 50% on Completion</option>
      </select>
    </div>

    <!-- Services table -->
    <div id="services_table">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width:130px;">Options</th>
            <th style="width:30px;">#</th>
            <th>Description</th>
            <th>QTY</th>
            <th>Unit Price</th>
            <th>Total Price</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $key => $service): ?>
            <?php self::display_service($service, $key); ?>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5">TOTAL</td>
            <td id="total_service">$ <?= $total_service; ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  <?php
  }

  public static function display_service($service, $key) { ?>
    <tr class="service_item" id="service<?= $service->get_id(); ?>">
      <td>
        <div class="item-actions">
          <button type="button" class="btn btn-xs item-action-btn btn-item edit_service" data-service-id="<?= $service->get_id(); ?>">
            <i class="fas fa-pen mr-1"></i> Edit
          </button>
        </div>
      </td>
      <td><?= $key + 1; ?></td>
      <td><?= nl2br($service->get_description()); ?></td>
      <td><?= $service->get_quantity(); ?></td>
      <td><?= $service->get_unit_price(); ?></td>
      <td><?= $service->get_total_price(); ?></td>
    </tr>
  <?php
  }

  public static function get_services($connection, $id_re_quote) {
    $services = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM re_quote_services WHERE id_re_quote = :id_re_quote';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence->execute();
        $services = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function get_total($connection, $id_re_quote) {
    $total_service = 0;
    if (isset($connection)) {
      try {
        $sql = 'SELECT SUM(total_price) AS total_service FROM re_quote_services WHERE id_re_quote = :id_re_quote';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $total_service = $result['total_service'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total_service;
  }

  public static function insert($connection, $re_quote_service) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO re_quote_services(id_re_quote, description, quantity, unit_price, total_price) VALUES(:id_re_quote, :description, :quantity, :unit_price, :total_price)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $re_quote_service->get_id_re_quote(), PDO::PARAM_STR);
        $sentence->bindValue(':description', $re_quote_service->get_description(), PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $re_quote_service->get_quantity(), PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $re_quote_service->get_unit_price(), PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $re_quote_service->get_total_price(), PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_by_id_re_quote($connection, $id_re_quote) {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM re_quote_services WHERE id_re_quote = :id_re_quote';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_service($connection, $id_service) {
    $service = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM re_quote_services WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $sentence->execute();
        $service = self::single_result_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $service;
  }

  public static function edit_service($connection, $id, $description, $quantity, $unit_price, $total_price) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE re_quote_services SET description = :description, quantity = :quantity, unit_price = :unit_price, total_price = :total_price WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':description', $description, PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $total_price, PDO::PARAM_STR);
        $sentence->bindValue(':id_service', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function calc_items_with_CC($connection, $payment_term, $id_re_quote) {
    $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
    if (isset($connection)) {
      try {
        $sql = 'UPDATE re_quote_services SET total_price = quantity * (unit_price * :payment_term) WHERE id_re_quote = :id_re_quote';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence->bindValue(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>