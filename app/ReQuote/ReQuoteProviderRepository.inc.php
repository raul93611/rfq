<?php
class ReQuoteProviderRepository{
  public static function insert_re_quote_provider($connection, $re_quote_provider){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quote_providers(id_re_quote_item, provider, price) VALUES(:id_re_quote_item, :provider, :price)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_item', $re_quote_provider-> get_id_re_quote_item(), PDO::PARAM_STR);
        $sentence-> bindParam(':provider', $re_quote_provider-> get_provider(), PDO::PARAM_STR);
        $sentence-> bindParam(':price', $re_quote_provider-> get_price(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_re_quote_providers_by_id_re_quote_item($connection, $id_re_quote_item){
    $re_quote_providers = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_providers WHERE id_re_quote_item = :id_re_quote_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $re_quote_providers[] = new ReQuoteProvider($row['id'], $row['id_re_quote_item'], $row['provider'], $row['price']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_providers;
  }

  public static function get_re_quote_provider_by_id($connection, $id_re_quote_provider){
    $re_quote_provider = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_providers WHERE id = :id_re_quote_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_provider', $id_re_quote_provider, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote_provider = new ReQuoteProvider($result['id'], $result['id_re_quote_item'], $result['provider'], $result['price']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_provider;
  }

  public static function update_re_quote_provider($connection, $provider, $price, $id_re_quote_provider){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_providers SET provider = :provider, price = :price WHERE id = :id_re_quote_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':provider', $provider, PDO::PARAM_STR);
        $sentence-> bindParam(':price', $price, PDO::PARAM_STR);
        $sentence-> bindParam(':id_re_quote_provider', $id_re_quote_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_provider($connection, $id_re_quote_provider){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM re_quote_providers WHERE id = :id_re_quote_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_provider', $id_re_quote_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
