<?php
class TypeOfBidRepository{
  public static function get_all($connection){
    $type_of_bids = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM type_of_bids ORDER BY type_of_bid ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $type_of_bids[] = new TypeOfBid($fila['id'], $fila['type_of_bid']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $type_of_bids;
  }
}
?>
