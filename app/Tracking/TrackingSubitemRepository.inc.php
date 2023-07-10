<?php
class TrackingSubitemRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($result = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new TrackingSubitem($result['id'], $result['id_subitem'], $result['quantity'], $result['carrier'], $result['tracking_number'], $result['delivery_date'], $result['due_date'], $result['signed_by'], $result['comments']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $result = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new TrackingSubitem($result['id'], $result['id_subitem'], $result['quantity'], $result['carrier'], $result['tracking_number'], $result['delivery_date'], $result['due_date'], $result['signed_by'], $result['comments']);

    return $object;
  }

  public static function insert_tracking($connection, $tracking){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO trackings_subitems(id_subitem, quantity, carrier, tracking_number, delivery_date, due_date, signed_by, comments) VALUES(:id_subitem, :quantity, :carrier, :tracking_number, :delivery_date, :due_date, :signed_by, :comments)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_subitem', $tracking-> get_id_subitem(), PDO::PARAM_STR);
        $sentence-> bindValue(':quantity', $tracking-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindValue(':carrier', $tracking-> get_carrier(), PDO::PARAM_STR);
        $sentence-> bindValue(':tracking_number', $tracking-> get_tracking_number(), PDO::PARAM_STR);
        $sentence-> bindValue(':delivery_date', $tracking-> get_delivery_date(), PDO::PARAM_STR);
        $sentence-> bindValue(':due_date', $tracking-> get_due_date(), PDO::PARAM_STR);
        $sentence-> bindValue(':signed_by', $tracking-> get_signed_by(), PDO::PARAM_STR);
        $sentence-> bindValue(':comments', $tracking-> get_comments(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_trackings_by_id_subitem($connection, $id_subitem){
    $trackings_subitems = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM trackings_subitems WHERE id_subitem = :id_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $trackings_subitems = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $trackings_subitems;
  }

  public static function get_tracking_subitem_by_id($connection, $id_tracking_subitem){
    $tracking_subitem = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM trackings_subitems WHERE id = :id_tracking_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_tracking_subitem', $id_tracking_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $tracking_subitem = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tracking_subitem;
  }

  public static function delete_tracking_subitem($connection, $id_tracking_subitem){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM trackings_subitems WHERE id = :id_tracking_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_tracking_subitem', $id_tracking_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_sum_by_subitem($connection, $id_subitem){
    $sum_tracking_subitem = 0;
    if(isset($connection)){
      try{
        $sql = 'SELECT SUM(quantity) sum_tracking FROM trackings_subitems WHERE id_subitem = :id_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $sum_tracking_subitem = $result['sum_tracking'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sum_tracking_subitem;
  }

  public static function update_tracking_subitem($connection, $quantity, $carrier, $tracking_number, $delivery_date, $due_date, $signed_by, $comments, $id_tracking_subitem){
    if(isset($connection)){
      try{
        $sql = 'UPDATE trackings_subitems SET quantity = :quantity, carrier = :carrier, tracking_number = :tracking_number, delivery_date = :delivery_date, due_date = :due_date, signed_by = :signed_by, comments = :comments WHERE id = :id_tracking_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentence-> bindValue(':carrier', $carrier, PDO::PARAM_STR);
        $sentence-> bindValue(':tracking_number', $tracking_number, PDO::PARAM_STR);
        $sentence-> bindValue(':delivery_date', $delivery_date, PDO::PARAM_STR);
        $sentence-> bindValue(':due_date', $due_date, PDO::PARAM_STR);
        $sentence-> bindValue(':signed_by', $signed_by, PDO::PARAM_STR);
        $sentence-> bindValue(':comments', $comments, PDO::PARAM_STR);
        $sentence-> bindValue(':id_tracking_subitem', $id_tracking_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
