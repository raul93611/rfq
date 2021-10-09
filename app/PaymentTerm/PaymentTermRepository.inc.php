<?php
class PaymentTermRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new PaymentTerm($row['id'], $row['payment_term']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new PaymentTerm($row['id'], $row['payment_term']);

    return $object;
  }

  public static function insert($connection, $payment_term){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO payment_terms(payment_term) VALUES(:payment_term)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':payment_term', $payment_term-> get_payment_term(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($connection, $payment_term, $id_payment_term){
    if(isset($connection)){
      try{
        $sql = 'UPDATE payment_terms SET payment_term = :payment_term WHERE id = :id_payment_term';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':payment_term', $payment_term-> get_payment_term(), PDO::PARAM_STR);
        $sentence-> bindParam(':id_payment_term', $id_payment_term, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id_payment_term){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM payment_terms WHERE id = :id_payment_term';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_payment_term', $id_payment_term, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all($connection){
    $payment_terms = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM payment_terms ORDER BY payment_term ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $payment_terms[] = new PaymentTerm($fila['id'], $fila['payment_term']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $payment_terms;
  }

  public static function get_one($connection, $id_payment_term){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM payment_terms WHERE id = :id_payment_term';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_payment_term', $id_payment_term, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function validate_payment_term($payment_term){
    $name = Input::test_input($payment_term['name']);
    Conexion::abrir_conexion();
    $result = self::name_uniqueness(Conexion::obtener_conexion(), $name);
    Conexion::cerrar_conexion();
    $payment_term = $result ? new PaymentTerm('', $name) : false;

    return $payment_term;
  }

  public static function name_uniqueness($connection, $payment_term){
    $unique = false;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM payment_terms WHERE payment_term = :payment_term';
        $sentence = $connection->prepare($sql);
        $sentence-> bindParam(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        $unique = count($result) ? false : true;
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $unique;
  }
}
?>
