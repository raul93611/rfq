<?php
class ProviderListRepository{
  public static function get_all($connection){
    $providers_list = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM providers_list ORDER BY company_name ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $providers_list[] = new ProviderList($fila['id'], $fila['company_name']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $providers_list;
  }
}
?>
