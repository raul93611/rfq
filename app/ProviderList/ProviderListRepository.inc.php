<?php
class ProviderListRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new ProviderList($row['id'], $row['company_name']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new ProviderList($row['id'], $row['company_name']);

    return $object;
  }

  public static function insert($connection, $provider){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO providers_list(company_name) VALUES(:company_name)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':company_name', $provider-> get_company_name(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update($connection, $provider, $id_provider){
    if(isset($connection)){
      try{
        $sql = 'UPDATE providers_list SET company_name = :company_name WHERE id = :id_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':company_name', $provider-> get_company_name(), PDO::PARAM_STR);
        $sentence-> bindValue(':id_provider', $id_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id_provider){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM providers_list WHERE id = :id_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_provider', $id_provider, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all($connection){
    $providers_list = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM providers_list ORDER BY company_name ASC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $providers_list = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $providers_list;
  }

  public static function get_one($connection, $id_provider){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM providers_list WHERE id = :id_provider';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_provider', $id_provider, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function validate_provider($provider){
    $name = Input::test_input($provider['name']);
    Conexion::abrir_conexion();
    $result = self::name_uniqueness(Conexion::obtener_conexion(), $name);
    Conexion::cerrar_conexion();
    $provider = $result ? new ProviderList('', $name) : false;

    return $provider;
  }

  public static function name_uniqueness($connection, $company_name){
    $unique = false;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM providers_list WHERE company_name = :company_name';
        $sentence = $connection->prepare($sql);
        $sentence-> bindValue(':company_name', $company_name, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        $unique = count($result) ? false : true;
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $unique;
  }
}
?>
