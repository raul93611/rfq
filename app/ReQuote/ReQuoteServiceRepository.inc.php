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
    $services = self::get_services($connection, $re_quote->get_id());
    $total_service = self::get_total($connection, $re_quote->get_id());
    if (count($services)) {
?>
      <div class="container-fluid my-2">
        <div class="row">
          <div class="col-md-6">
            <label>Payment terms:</label>
            <div class="custom-control custom-radio">
              <input type="radio" id="net_30Services" name="services_payment_term" class="custom-control-input" value="Net 30" <?php echo $re_quote->get_services_payment_term() == 'Net 30' ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="net_30Services">Net 30</label>
            </div>
            <div class="custom-control custom-radio">
              <input type="radio" id="net_30ccServices" name="services_payment_term" class="custom-control-input" value="Net 30/CC" <?php echo $re_quote->get_services_payment_term() == 'Net 30/CC' ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="net_30ccServices">Net 30/CC</label>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="services_table">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th style="width: 50px;">Options</th>
              <th style="width: 30px;">#</th>
              <th>DESCRIPTION</th>
              <th style="width: 30px;">QTY</th>
              <th style="width: 100px;">UNIT PRICE</th>
              <th style="width: 100px;">TOTAL PRICE</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($services as $key => $service) {
              self::display_service($service, $key);
            }
            ?>
          <tfoot>
            <tr>
              <td colspan="5" class="display-4"><b>
                  <h4>TOTAL:</h4>
                </b></td>
              <td id="total_service">$ <?php echo $total_service; ?></td>
            </tr>
          </tfoot>
          </tbody>
        </table>
      </div>
    <?php
    } else {
    ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
    <?php
    }
  }

  public static function display_service($service, $key) {
    ?>
    <tr class="service_item" id="service<?php echo $service->get_id(); ?>">
      <td>
        <div class="btn-group-vertical">
          <button type="button" class="btn btn-item edit_service" data-service-id="<?php echo $service->get_id(); ?>"><i class="fas fa-pen"></i></button>
        </div>
      </td>
      <td><?php echo $key + 1; ?></td>
      <td><?php echo nl2br($service->get_description()); ?></td>
      <td><?php echo $service->get_quantity(); ?></td>
      <td><?php echo $service->get_unit_price(); ?></td>
      <td><?php echo $service->get_total_price(); ?></td>
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