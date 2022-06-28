<?php
class SalesCommissionRepository{
  public static function get_all($connection){
    $sales_commissions = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM sales_commissions ORDER BY sales_commission ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $sales_commissions[] = new SalesCommission($fila['id'], $fila['sales_commission']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sales_commissions;
  }
}
?>
