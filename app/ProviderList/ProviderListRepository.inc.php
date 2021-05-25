<?php
class ProviderListRepository{
  public static function get_all($database){
    $providers_list = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM providers_list ORDER BY company_name ASC';
        $query = $database-> prepare($sql);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $providers_list[] = new ProviderList($row['id'], $row['company_name']);
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
