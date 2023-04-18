<?php
class FulfillmentSubitemRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new FulfillmentSubitem($row['id'], $row['id_subitem'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost'], $row['payment_term'], $row['comments'], $row['reviewed'], $row['created_at']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new FulfillmentSubitem($row['id'], $row['id_subitem'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost'], $row['payment_term'], $row['comments'], $row['reviewed'], $row['created_at']);

    return $object;
  }

  public static function get_all_by_id_subitem($connection, $id_subitem){
    $subitems = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM fulfillment_subitems WHERE id_subitem = :id_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $subitems = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $subitems;
  }

  public static function get_all_by_id_subitem_from_to($connection, $id_subitem, $from, $to){
    $items = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM fulfillment_subitems WHERE id_subitem = :id_subitem AND created_at BETWEEN :from AND :to';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> bindParam(':from', $from, PDO::PARAM_STR);
        $sentence-> bindParam(':to', $to, PDO::PARAM_STR);
        $sentence-> execute();
        $items = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function insert($connection, $fulfillment_subitem){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO fulfillment_subitems(id_subitem, provider, quantity, unit_cost, other_cost, real_cost, payment_term, comments, created_at) VALUES(:id_subitem, :provider, :quantity, :unit_cost, :other_cost, :real_cost, :payment_term, :comments, NOW())';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $fulfillment_subitem-> get_id_subitem(), PDO::PARAM_STR);
        $sentence-> bindParam(':provider', $fulfillment_subitem-> get_provider(), PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $fulfillment_subitem-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindParam(':unit_cost', $fulfillment_subitem-> get_unit_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':other_cost', $fulfillment_subitem-> get_other_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':real_cost', $fulfillment_subitem-> get_real_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':payment_term', $fulfillment_subitem-> get_payment_term(), PDO::PARAM_STR);
        $sentence-> bindParam(':comments', $fulfillment_subitem-> get_comments(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function update($connection, $id_fulfillment_subitem, $provider, $quantity, $unit_cost, $other_cost, $real_cost, $payment_term, $comment){
    if(isset($connection)){
      try{
        $sql = 'UPDATE fulfillment_subitems SET provider = :provider, quantity = :quantity, unit_cost = :unit_cost, other_cost = :other_cost, real_cost = :real_cost, payment_term = :payment_term, comments = :comments WHERE id = :id_fulfillment_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $sentence-> bindParam(':unit_cost', $unit_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':other_cost', $other_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':real_cost', $real_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence-> bindParam(':comments', $comment, PDO::PARAM_STR);
        $sentence-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function mark_as_reviewed($connection, $id_fulfillment_subitem){
    if(isset($connection)){
      try{
        $sql = 'UPDATE fulfillment_subitems SET reviewed = 1 - reviewed WHERE id = :id_fulfillment_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id_fulfillment_subitem){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM fulfillment_subitems WHERE id = :id_fulfillment_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total_cost($connection, $id_subitem){
    $total = 0;
    if(isset($connection)){
      try{
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_subitems WHERE id_subitem = :id_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
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

  public static function get_total_cost_from_to($connection, $id_subitem, $from, $to){
    $total = 0;
    if(isset($connection)){
      try{
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_subitems WHERE id_subitem = :id_subitem AND created_at BETWEEN :from AND :to';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> bindParam(':from', $from, PDO::PARAM_STR);
        $sentence-> bindParam(':to', $to, PDO::PARAM_STR);
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

  public static function get_one($connection, $id_fulfillment_subitem){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM fulfillment_subitems WHERE id = :id_fulfillment_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_fulfillment_subitem', $id_fulfillment_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
?>
