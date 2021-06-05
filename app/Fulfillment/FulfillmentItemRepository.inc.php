<?php
class FulfillmentItemRepository{
  public static function get_all_by_id_item($connection, $id_item){
    $items = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM fulfillment_items WHERE id_item = :id_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $items[] = new FulfillmentItem($row['id'], $row['id_item'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost'], $row['payment_term']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function insert($connection, $fulfillment_item){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO fulfillment_items(id_item, provider, quantity, unit_cost, other_cost, real_cost, payment_term) VALUES(:id_item, :provider, :quantity, :unit_cost, :other_cost, :real_cost, :payment_term)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_item', $fulfillment_item-> get_id_item(), PDO::PARAM_STR);
        $sentence-> bindParam(':provider', $fulfillment_item-> get_provider(), PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $fulfillment_item-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindParam(':unit_cost', $fulfillment_item-> get_unit_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':other_cost', $fulfillment_item-> get_other_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':real_cost', $fulfillment_item-> get_real_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':payment_term', $fulfillment_item-> get_payment_term(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($connection, $id_fulfillment_item, $provider, $quantity, $unit_cost, $other_cost, $real_cost, $payment_term){
    if(isset($connection)){
      try{
        $sql = 'UPDATE fulfillment_items SET provider = :provider, quantity = :quantity, unit_cost = :unit_cost, other_cost = :other_cost, real_cost = :real_cost, payment_term = :payment_term WHERE id = :id_fulfillment_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $sentence-> bindParam(':unit_cost', $unit_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':other_cost', $other_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':real_cost', $real_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence-> bindParam(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id_fulfillment_item){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM fulfillment_items WHERE id = :id_fulfillment_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total_cost($connection, $id_item){
    $total = 0;
    if(isset($connection)){
      try{
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_items WHERE id_item = :id_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $total = $result['total_cost'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total;
  }

  public static function get_one($connection, $id_fulfillment_item){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM fulfillment_items WHERE id = :id_fulfillment_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $item = new FulfillmentItem($result['id'], $result['id_item'], $result['provider'], $result['quantity'], $result['unit_cost'], $result['other_cost'], $result['real_cost'], $result['payment_term']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
?>