<?php
class FulfillmentSubitemRepository{
  public static function get_all_by_id_subitem($database, $id_subitem){
    $subitems = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM fulfillment_subitems WHERE id_subitem = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $subitems[] = new FulfillmentItem($row['id'], $row['id_subitem'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $subitems;
  }

  public static function insert($database, $fulfillment_subitem){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO fulfillment_subitems(id_subitem, provider, quantity, unit_cost, other_cost, real_cost) VALUES(:id_subitem, :provider, :quantity, :unit_cost, :other_cost, :real_cost)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_subitem', $fulfillment_subitem-> get_id_subitem(), PDO::PARAM_STR);
        $query-> bindParam(':provider', $fulfillment_subitem-> get_provider(), PDO::PARAM_STR);
        $query-> bindParam(':quantity', $fulfillment_subitem-> get_quantity(), PDO::PARAM_STR);
        $query-> bindParam(':unit_cost', $fulfillment_subitem-> get_unit_cost(), PDO::PARAM_STR);
        $query-> bindParam(':other_cost', $fulfillment_subitem-> get_other_cost(), PDO::PARAM_STR);
        $query-> bindParam(':real_cost', $fulfillment_subitem-> get_real_cost(), PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($database, $id_fulfillment_subitem, $provider, $quantity, $unit_cost, $other_cost, $real_cost){
    if(isset($database)){
      try{
        $sql = 'UPDATE fulfillment_subitems SET provider = :provider, quantity = :quantity, unit_cost = :unit_cost, other_cost = :other_cost, real_cost = :real_cost WHERE id = :id_fulfillment_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $query-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $query-> bindParam(':unit_cost', $unit_cost, PDO::PARAM_STR);
        $query-> bindParam(':other_cost', $other_cost, PDO::PARAM_STR);
        $query-> bindParam(':real_cost', $real_cost, PDO::PARAM_STR);
        $query-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($database, $id_fulfillment_subitem){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM fulfillment_subitems WHERE id = :id_fulfillment_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total_cost($database, $id_subitem){
    $total = 0;
    if(isset($database)){
      try{
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_subitems WHERE id_subitem = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
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

  public static function get_one($database, $id_fulfillment_subitem){
    $item = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM fulfillment_subitems WHERE id = :id_fulfillment_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $item = new FulfillmentSubitem($result['id'], $result['id_subitem'], $result['provider'], $result['quantity'], $result['unit_cost'], $result['other_cost'], $result['real_cost']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
?>
