<?php
class ServiceRepository{
  public static function store_service($database, $service){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO services(id_quote, description, quantity, unit_price, total_price) VALUES(:id_quote, :description, :quantity, :unit_price, :total_price)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $service-> get_id_quote(), PDO::PARAM_STR);
        $query-> bindParam(':description', $service-> get_description(), PDO::PARAM_STR);
        $query-> bindParam(':quantity', $service-> get_quantity(), PDO::PARAM_STR);
        $query-> bindParam(':unit_price', $service-> get_unit_price(), PDO::PARAM_STR);
        $query-> bindParam(':total_price', $service-> get_total_price(), PDO::PARAM_STR);
        $query-> execute();
        $id = $database-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_services($database, $id_quote){
    $services = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM services WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $services[] = new Service($row['id'], $row['id_quote'], $row['description'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['fulfillment_profit']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function set_fulfillment_profit($database, $fulfillment_profit, $id_service){
    if(isset($database)){
      try{
        $sql = 'UPDATE services SET fulfillment_profit = :fulfillment_profit WHERE id = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $query-> bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function display_services($database, $id_quote){
    $services = self::get_services($database, $id_quote);
    $total_service = self::get_total($database, $id_quote);
    if(count($services)){
      ?>
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
              <td colspan="5" class="display-4"><b><h4>TOTAL:</h4></b></td>
              <td id="total_service">$ <?php echo $total_service; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
    }else{
      ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
      <?php
    }
  }

  public static function display_service($service, $key){
    ?>
    <tr id="service<?php echo $service-> get_id(); ?>">
      <td>
        <div class="btn-group-vertical">
          <button type="button" class="btn btn-warning edit_service" data="<?php echo $service-> get_id(); ?>"><i class="fas fa-pen"></i></button>
          <a href="<?php echo DELETE_SERVICE . $service-> get_id(); ?>" class="btn btn-warning"><i class="fas fa-trash"></i></a>
        </div>
      </td>
      <td><?php echo $key+1; ?></td>
      <td><?php echo nl2br($service-> get_description()); ?></td>
      <td><?php echo $service-> get_quantity(); ?></td>
      <td><?php echo $service-> get_unit_price(); ?></td>
      <td><?php echo $service-> get_total_price(); ?></td>
    </tr>
    <?php
  }

  public static function get_service($database, $id_service){
    $service = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM services WHERE id = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $service = new Service($result['id'], $result['id_quote'], $result['description'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['fulfillment_profit']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $service;
  }

  public static function edit_service($database, $id, $description, $quantity, $unit_price, $total_price){
    if(isset($database)){
      try{
        $sql = 'UPDATE services SET description = :description, quantity = :quantity, unit_price = :unit_price, total_price = :total_price WHERE id = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':description', $description, PDO::PARAM_STR);
        $query-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $query-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
        $query-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $query-> bindParam(':id_service', $id, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_service($database, $id_service){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM services WHERE id = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total($database, $id_quote){
    $total_service = 0;
    if(isset($database)){
      try{
        $sql = 'SELECT SUM(total_price) AS total_service FROM services WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $total_service = $result['total_service'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total_service;
  }
}
?>
