<?php
class ServiceRepository {
  public static function store_service($connection, $service) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO services(id_rfq, description, quantity, unit_price, total_price, id_room) VALUES(:id_rfq, :description, :quantity, :unit_price, :total_price, :id_room)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $service->get_id_rfq(), PDO::PARAM_STR);
        $sentence->bindValue(':description', $service->get_description(), PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $service->get_quantity(), PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $service->get_unit_price(), PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $service->get_total_price(), PDO::PARAM_STR);
        $sentence->bindValue(':id_room', $service->getIdRoom(), PDO::PARAM_STR);
        $sentence->execute();
        $id = $connection->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_services($connection, $id_rfq) {
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
            $services[] = new Service($row['id'], $row['id_rfq'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['fulfillment_profit'], $row['id_room']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function getServicesByRoomId($connection, $id_rfq, $id_room) {
    $services = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM services WHERE id_rfq = :id_rfq AND id_room = :id_room';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':id_room', $id_room, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $key => $row) {
            $services[] = new Service($row['id'], $row['id_rfq'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['fulfillment_profit'], $row['id_room']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function set_fulfillment_profit($conexion, $fulfillment_profit, $id_service) {
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

  public static function display_services($connection, $quote) {
    $services = self::get_services($connection, $quote->obtener_id());
    $total_service = self::get_total($connection, $quote->obtener_id());
    if (count($services)) {
?>
      <div class="items-controls-bar user-form">
        <div class="row">
          <div class="col-md-4">
            <label>Payment Terms</label>
            <?php $services_payment_term = $quote->obtener_services_payment_term(); ?>
            <select name="services_payment_term" id="services_payment_term" class="form-control form-control-sm js-payment-terms">
              <option value="Net 30" <?= $services_payment_term == 'Net 30' ? 'selected' : ''; ?>>Net 30</option>
              <option value="Net 30/CC" <?= $services_payment_term == 'Net 30/CC' ? 'selected' : ''; ?>>Net 30/CC</option>
              <option value="50% Upfront / 50% on Completion" <?= $services_payment_term == '50% Upfront / 50% on Completion' ? 'selected' : ''; ?>>50% Upfront / 50% on Completion</option>
            </select>
          </div>
        </div>
      </div>
      <div class="table-responsive" id="services_table">
        <table class="table table-hover">
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
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" style="font-size:13px;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">TOTAL</td>
              <td id="total_service">$ <?= $total_service; ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php
    } else {
    ?>
      <div class="section-empty-state">
        <i class="fas fa-concierge-bell"></i>
        <p>No services added yet</p>
      </div>
    <?php
    }
  }

  public static function display_service($service, $key) {
    Conexion::abrir_conexion();
    $room = $service->getIdRoom() ? RoomRepository::getById(Conexion::obtener_conexion(), $service->getIdRoom()) : null;
    Conexion::cerrar_conexion();
    ?>
    <tr class="service_item" id="service<?= $service->get_id(); ?>">
      <td>
        <div class="item-actions">
          <button type="button" class="btn btn-item btn-xs item-action-btn edit_service" data="<?= $service->get_id(); ?>"><i class="fas fa-pen mr-1"></i> Edit</button>
          <button type="button" class="btn btn-item-del btn-xs item-action-btn svc-delete-btn" data-url="<?= DELETE_SERVICE . $service->get_id(); ?>"><i class="fas fa-trash mr-1"></i> Delete</button>
          <button type="button" class="btn btn-item-sec btn-xs item-action-btn svc-duplicate-btn" data-url="<?= DUPLICATE_SERVICE . $service->get_id(); ?>"><i class="fas fa-copy mr-1"></i> Duplicate</button>
        </div>
      </td>
      <td><?= $service->getIdRoom() ? '<span class="mb-2 badge badge-primary" style="background-color: ' . $room->getColor() . ';">' . $room->getName() . '</span>' : '' ?><?= $key + 1; ?></td>
      <td><?= nl2br(mb_substr($service->get_description(), 0, 100)) . ' ...'; ?></td>
      <td><?= $service->get_quantity(); ?></td>
      <td data-base-price="<?= htmlspecialchars($service->get_unit_price(), ENT_QUOTES, 'UTF-8'); ?>"><?= $service->get_unit_price(); ?></td>
      <td><?= $service->get_total_price(); ?></td>
    </tr>
<?php
  }

  public static function get_service($connection, $id_service) {
    $service = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM services WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $service = new Service($result['id'], $result['id_rfq'], $result['description'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['fulfillment_profit'], $result['id_room']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $service;
  }

  public static function edit_service($connection, $id, $description, $quantity, $unit_price, $total_price, $id_room) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE services SET description = :description, quantity = :quantity, unit_price = :unit_price, total_price = :total_price, id_room = :id_room WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':description', $description, PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $total_price, PDO::PARAM_STR);
        $sentence->bindValue(':id_service', $id, PDO::PARAM_STR);
        $sentence->bindValue(':id_room', $id_room, PDO::PARAM_STR);
        $sentence->execute();
        return true;
      } catch (PDOException $ex) {
        error_log('ERROR: ' . $ex->getMessage());
        return false;
      }
    }
    return false;
  }


  public static function delete_service($connection, $id_service) {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM services WHERE id = :id_service';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_service', $id_service, PDO::PARAM_STR);
        $result = $sentence->execute();

        // Check if any rows were affected
        if ($sentence->rowCount() > 0) {
          return true;
        } else {
          return false;
        }
      } catch (PDOException $ex) {
        // Log the error message
        error_log('ERROR: ' . $ex->getMessage());
        return false;
      }
    } else {
      // Return false if connection is not set
      return false;
    }
  }


  public static function get_total($connection, $id_rfq) {
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

  public static function calc_items_with_CC($connection, $payment_term, $id_rfq) {
    RepositorioRfq::set_services_payment_term($connection, $payment_term, $id_rfq);
    $payment_term = $payment_term == 'Net 30/CC' ? 1.03 : 1;
    if (isset($connection)) {
      try {
        // Cast the bound param to DECIMAL — as a plain placeholder MySQL evaluates the
        // multiplication in DOUBLE, so exact .xx5 ties (e.g. 10687.50 * 1.03 = 11008.125)
        // round down to match float representation instead of up like PHP/JS number_format.
        $sql = 'UPDATE services SET total_price = quantity * ROUND(unit_price * CAST(:payment_term AS DECIMAL(10,4)), 2) WHERE id_rfq = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function copyServices($connnection, $id_rfq, $id_rfq_copia) {
    $services = ServiceRepository::get_services($connnection, $id_rfq);
    if (count($services)) {
      foreach ($services as $key => $service) {
        $service_duplicate = new Service(
          '',
          $id_rfq_copia,
          $service->get_description(),
          $service->get_quantity(),
          $service->get_unit_price(),
          $service->get_total_price(),
          $service->get_fulfillment_profit(),
          $service->getIdRoom()
        );

        ServiceRepository::store_service($connnection, $service_duplicate);
      }
    }
  }
}
?>