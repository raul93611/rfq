<?php
class PaymentTermRepository{
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
}
?>
