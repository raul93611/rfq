<?php
class ProviderRepository{
  public static function insert($database, $provider){
    $inserted_provider = false;
    if(isset($database)){
      try{
        $sql = 'INSERT INTO providers(id_item, provider, price) VALUES(:id_item, :provider, :price)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_item', $provider-> get_id_item(), PDO::PARAM_STR);
        $query-> bindParam(':provider', $provider-> get_provider(), PDO::PARAM_STR);
        $query-> bindParam(':price', $provider-> get_price(), PDO::PARAM_STR);
        $result = $query-> execute();
        if($result){
          $inserted_provider = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $inserted_provider;
  }

  public static function delete_provider($database, $id_provider){
    $deleted_provider = false;
    if(isset($database)){
      try{
        $sql = 'DELETE FROM providers WHERE id = :id_provider';
        $query = $database-> prepare($sql);
        $query->bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
        $result = $query-> execute();
        if($result){
          $deleted_provider = true;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $deleted_provider;
  }

  public static function get_all_by_id_item($database, $id_item){
    $providers = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM providers WHERE id_item = :id_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll();
        if(count($result)){
          foreach ($result as $row){
            $providers[] = new Provider($row['id'], $row['id_item'], $row['provider'], $row['price']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $providers;
  }

  public static function get_by_id($database, $id_provider){
    $provider = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM providers WHERE id = :id_provider';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch();
        if(!empty($result)){
          $provider = new Provider($result['id'], $result['id_item'], $result['provider'], $result['price']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $provider;
  }

  public static function update($database, $id_provider, $provider, $price){
    $updated_provider = false;
    if(isset($database)){
      try{
        $sql = 'UPDATE providers SET provider = :provider, price = :price WHERE id = :id_provider';
        $query = $database-> prepare($sql);
        $query-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $query-> bindParam(':price', $price, PDO::PARAM_STR);
        $query-> bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
        $query-> execute();
        if($query){
          $updated_provider = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_provider;
  }
}
?>
