<?php
class ProviderSubitemRepository{
  public static function insert($database, $provider_subitem){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO provider_subitems(id_subitem, provider, price) VALUES(:id_subitem, :provider, :price)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_subitem', $provider_subitem-> get_id_subitem(), PDO::PARAM_STR);
        $query-> bindParam(':provider', $provider_subitem-> get_provider(), PDO::PARAM_STR);
        $query-> bindParam(':price', $provider_subitem-> get_price(), PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_by_id_subitem($database, $id_subitem){
    $providers_subitem = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM provider_subitems WHERE id_subitem = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll();
        if(count($result)){
          foreach ($result as $row){
            $providers_subitem[] = new ProviderSubitem($row['id'], $row['id_subitem'], $row['provider'], $row['price']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $providers_subitem;
  }

  public static function get_by_id($database, $id_provider_subitem){
    $provider_subitem = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM provider_subitems WHERE id = :id_provider_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch();
        if(!empty($result)){
          $provider_subitem = new ProviderSubitem($result['id'], $result['id_subitem'], $result['provider'], $result['price']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $provider_subitem;
  }

  public static function update($database, $id_provider_subitem, $provider, $price){
    if(isset($database)){
      try{
        $sql = 'UPDATE provider_subitems SET provider = :provider, price = :price WHERE id = :id_provider_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $query-> bindParam(':price', $price, PDO::PARAM_STR);
        $query-> bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($database, $id_provider_subitem){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM provider_subitems WHERE id = :id_provider_subitem';
        $query = $database-> prepare($sql);
        $query->bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
