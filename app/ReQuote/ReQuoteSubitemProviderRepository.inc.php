<?php
class ReQuoteSubitemProviderRepository{
  public static function insert_re_quote_subitem_provider($connection, $re_quote_subitem_provider){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quote_subitem_providers(id_re_quote_subitem, provider, price) VALUES(:id_re_quote_subitem, :provider, :price)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_re_quote_subitem', $re_quote_subitem_provider-> get_id_re_quote_subitem(), PDO::PARAM_STR);
        $sentence-> bindValue(':provider', $re_quote_subitem_provider-> get_provider(), PDO::PARAM_STR);
        $sentence-> bindValue(':price', $re_quote_subitem_provider-> get_price(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_re_quote_subitem_providers_by_id_re_quote_subitem($connection, $id_re_quote_subitem){
    $re_quote_subitem_providers = [];
    if(isset($re_quote_subitem_providers)){
      try{
        $sql = 'SELECT * FROM re_quote_subitem_providers WHERE id_re_quote_subitem = :id_re_quote_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $re_quote_subitem_providers[] = new ReQuoteSubitemProvider($row['id'], $row['id_re_quote_subitem'], $row['provider'], $row['price']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_subitem_providers;
  }

  public static function get_re_quote_subitem_provider_by_id($connection, $id_re_quote_subitem_provider){
    $re_quote_subitem_provider = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_subitem_providers WHERE id = :id_re_quote_subitem_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_re_quote_subitem_provider', $id_re_quote_subitem_provider, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote_subitem_provider = new ReQuoteSubitemProvider($result['id'], $result['id_re_quote_subitem'], $result['provider'], $result['price']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_subitem_provider;
  }

  public static function update_re_quote_subitem_provider($connection, $provider, $price, $id_re_quote_subitem_provider){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_subitem_providers SET provider = :provider, price = :price WHERE id = :id_re_quote_subitem_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':provider', $provider, PDO::PARAM_STR);
        $sentence-> bindValue(':price', $price, PDO::PARAM_STR);
        $sentence-> bindValue(':id_re_quote_subitem_provider', $id_re_quote_subitem_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_subitem_provider($connection, $id_re_quote_subitem_provider){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM re_quote_subitem_providers WHERE id = :id_re_quote_subitem_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_re_quote_subitem_provider', $id_re_quote_subitem_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
