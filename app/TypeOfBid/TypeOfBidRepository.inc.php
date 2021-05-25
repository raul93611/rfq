<?php
class TypeOfBidRepository{
  public static function get_all($database){
    $type_of_bids = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM type_of_bids ORDER BY type_of_bid ASC';
        $query = $database-> prepare($sql);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $type_of_bids[] = new TypeOfBid($row['id'], $row['type_of_bid']);
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
