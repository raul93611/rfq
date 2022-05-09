<?php
class TypeOfContractRepository{
  public static function get_all($connection){
    $type_of_contracts = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM type_of_contracts ORDER BY type_of_contract ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $type_of_contracts[] = new TypeOfContract($fila['id'], $fila['type_of_contract']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $type_of_contracts;
  }
}
?>
