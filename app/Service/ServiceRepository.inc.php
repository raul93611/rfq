<?php
class ServiceRepository
{
  public static function store_service($connection, $service)
  {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO services(id_rfq, description, quantity, unit_price, total_price) VALUES(:id_rfq, :description, :quantity, :unit_price, :total_price)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $service->get_id_rfq(), PDO::PARAM_STR);
        $sentence->bindValue(':description', $service->get_description(), PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $service->get_quantity(), PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $service->get_unit_price(), PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $service->get_total_price(), PDO::PARAM_STR);
        $sentence->execute();
        $id = $connection->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_services($connection, $id_rfq)
  {
    $services = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM services WHERE id_rfq = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $key => $row) {
            $services[] = new Service($row['id'], $row['id_rfq'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['fulfillment_profit']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function set_fulfillment_profit($conexion, $fulfillment_profit, $id_service)
  {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE services SET fulfillment_profit = :fulfillment_profit WHERE id = :id_service';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $sentencia->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function display_services($connection, $quote)
  {
    $services = self::get_services($connection, $quote-> obtener_id());
    $total_service = self::get_total($connection, $quote-> obtener_id());
    if (count($services)) {
    ?>
      <div class="container-fluid my-2">
        <div class="row">
          <div class="col-md-6">
            <label>Payment terms:</label>
            <div class="custom-control custom-radio">
              <input type="radio" id="net_30Services" name="services_payment_term" class="custom-control-input" value="Net 30" <?php echo $quote-> obtener_services_payment_term() == 'Net 30' ? 'checked' : ''; ?>>
              <label class="custom-control-label" for="net_30Services">Net 30</label>
            </div>
            <div class="custom-control custom-radio">
              <input type="radio" id="net_30ccServices" name="services_payment_term" class="custom-control-input" value="Net 30/CC" <?php echo $quote-> obtener_services_payment_term() == 'Net 30/CC' ? 'checked' : ''; ?>>
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
            <tr>
              <td colspan="5" class="display-4"><b>
                  <h4>TOTAL:</h4>
                </b></td>
              <td id="total_service">$ <?php echo $total_service; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php
    } else {
    ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services!</h3>
    <?php
    }
  }

  public static function display_service($service, $key)
  {
    ?>
    <tr class="service_item" id="service<?php echo $service->get_id(); ?>">
      <td>
        <div class="btn-group-vertical">
          <button type="button" class="btn btn-warning edit_service" data="<?php echo $service->get_id(); ?>"><i class="fas fa-pen"></i></button>
          <a href="<?php echo DELETE_SERVICE . $service->get_id(); ?>" class="btn btn-warning"><i class="fas fa-trash"></i></a>
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

  public static function get_service($connection, $id_service)
  {
    $service = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM services WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $service = new Service($result['id'], $result['id_rfq'], $result['description'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['fulfillment_profit']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $service;
  }

  public static function edit_service($connection, $id, $description, $quantity, $unit_price, $total_price)
  {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE services SET description = :description, quantity = :quantity, unit_price = :unit_price, total_price = :total_price WHERE id = :id_service';
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

  public static function delete_service($connection, $id_service)
  {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM services WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total($connection, $id_rfq)
  {
    $total_service = 0;
    if (isset($connection)) {
      try {
        $sql = 'SELECT SUM(total_price) AS total_service FROM services WHERE id_rfq = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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

  public static function calc_items_with_CC($connection, $payment_term, $id_rfq){
    RepositorioRfq::set_services_payment_term($connection, $payment_term, $id_rfq);
    $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
    if (isset($connection)) {
      try {
        $sql = 'UPDATE services SET total_price = quantity * (unit_price * :payment_term) WHERE id_rfq = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>