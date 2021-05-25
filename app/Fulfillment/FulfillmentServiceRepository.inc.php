<?php
class FulfillmentServiceRepository{
  public static function get_all_by_id_service($database, $id_service){
    $services = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM fulfillment_services WHERE id_service = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $services[] = new FulfillmentItem($row['id'], $row['id_service'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $services;
  }

  public static function insert($database, $fulfillment_service){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO fulfillment_services(id_service, provider, quantity, unit_cost, other_cost, real_cost) VALUES(:id_service, :provider, :quantity, :unit_cost, :other_cost, :real_cost)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_service', $fulfillment_service-> get_id_service(), PDO::PARAM_STR);
        $query-> bindParam(':provider', $fulfillment_service-> get_provider(), PDO::PARAM_STR);
        $query-> bindParam(':quantity', $fulfillment_service-> get_quantity(), PDO::PARAM_STR);
        $query-> bindParam(':unit_cost', $fulfillment_service-> get_unit_cost(), PDO::PARAM_STR);
        $query-> bindParam(':other_cost', $fulfillment_service-> get_other_cost(), PDO::PARAM_STR);
        $query-> bindParam(':real_cost', $fulfillment_service-> get_real_cost(), PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($database, $id_fulfillment_service, $provider, $quantity, $unit_cost, $other_cost, $real_cost){
    if(isset($database)){
      try{
        $sql = 'UPDATE fulfillment_services SET provider = :provider, quantity = :quantity, unit_cost = :unit_cost, other_cost = :other_cost, real_cost = :real_cost WHERE id = :id_fulfillment_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $query-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $query-> bindParam(':unit_cost', $unit_cost, PDO::PARAM_STR);
        $query-> bindParam(':other_cost', $other_cost, PDO::PARAM_STR);
        $query-> bindParam(':real_cost', $real_cost, PDO::PARAM_STR);
        $query-> bindParam(':id_fulfillment_service', $id_fulfillment_service, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($database, $id_fulfillment_service){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM fulfillment_services WHERE id = :id_fulfillment_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_fulfillment_service', $id_fulfillment_service, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total_cost($database, $id_service){
    $total = 0;
    if(isset($database)){
      try{
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_services WHERE id_service = :id_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_service', $id_service, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $total = $result['total_cost'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total;
  }

  public static function get_one($database, $id_fulfillment_service){
    $item = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM fulfillment_services WHERE id = :id_fulfillment_service';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_fulfillment_service', $id_fulfillment_service, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $item = new FulfillmentService($result['id'], $result['id_service'], $result['provider'], $result['quantity'], $result['unit_cost'], $result['other_cost'], $result['real_cost']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
?>
