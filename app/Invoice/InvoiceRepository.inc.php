<?php
class InvoiceRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Invoice($row['id'], $row['id_rfq'], $row['name'], $row['created_at']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    if(empty($row)) return null;
    $object = new Invoice($row['id'], $row['id_rfq'], $row['name'], $row['created_at']);

    return $object;
  }

  public static function save($connection, $invoice){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO invoices(id_rfq, name, created_at) VALUES(:id_rfq, :name, STR_TO_DATE(:created_at, "%m/%d/%Y"))';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_rfq', $invoice-> get_id_rfq(), PDO::PARAM_STR);
        $sentence-> bindValue(':name', $invoice-> get_name(), PDO::PARAM_STR);
        $sentence-> bindValue(':created_at', $invoice-> get_created_at(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_by_id_rfq($connection, $id_rfq){
    $invoices = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM invoices WHERE id_rfq = :id_rfq ORDER BY created_at ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> execute();
        $invoices = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $invoices;
  }

  public static function get_one($connection, $id_invoice){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM invoices WHERE id = :id_invoice';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function delete($connection, $id_invoice){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM invoices WHERE id = :id_invoice';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($connection, $name, $created_at, $id_invoice){
    if(isset($connection)){
      try{
        $sql = 'UPDATE invoices SET name = :name, created_at = STR_TO_DATE(:created_at, "%m/%d/%Y") WHERE id = :id_invoice';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':name', $name, PDO::PARAM_STR);
        $sentence-> bindValue(':created_at', $created_at, PDO::PARAM_STR);
        $sentence-> bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>