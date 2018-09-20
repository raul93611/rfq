<?php
class RepositorioProviderSubitem{
  public static function insertar_provider_subitem($conexion, $provider_subitem){
      if(isset($conexion)){
          try{
              $sql = 'INSERT INTO provider_subitems(id_subitem, provider, price) VALUES(:id_subitem, :provider, :price)';
              $sentencia = $conexion-> prepare($sql);
              $sentencia-> bindParam(':id_subitem', $provider_subitem-> obtener_id_subitem(), PDO::PARAM_STR);
              $sentencia-> bindParam(':provider', $provider_subitem-> obtener_provider(), PDO::PARAM_STR);
              $sentencia-> bindParam(':price', $provider_subitem-> obtener_price(), PDO::PARAM_STR);
              $sentencia-> execute();
          } catch (PDOException $ex) {
              print 'ERROR:' . $ex->getMessage() . '<br>';
          }
      }
  }

  public static function obtener_providers_subitem_por_id_subitem($conexion, $id_subitem){
      $providers_subitem = [];
      if(isset($conexion)){
          try{
              $sql = 'SELECT * FROM provider_subitems WHERE id_subitem = :id_subitem';
              $sentencia = $conexion-> prepare($sql);
              $sentencia-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
              $sentencia-> execute();
              $resultado = $sentencia-> fetchAll();
              if(count($resultado)){
                  foreach ($resultado as $fila){
                      $providers_subitem[] = new ProviderSubitem($fila['id'], $fila['id_subitem'], $fila['provider'], $fila['price']);
                  }
              }
          } catch (PDOException $ex) {
              print 'ERROR:' . $ex->getMessage() . '<br>';
          }
      }
      return $providers_subitem;
  }

  public static function obtener_provider_subitem_por_id($conexion, $id_provider_subitem){
      $provider_subitem = null;
      if(isset($conexion)){
          try{
              $sql = 'SELECT * FROM provider_subitems WHERE id = :id_provider_subitem';
              $sentencia = $conexion-> prepare($sql);
              $sentencia-> bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
              $sentencia-> execute();
              $resultado = $sentencia-> fetch();
              if(!empty($resultado)){
                  $provider_subitem = new ProviderSubitem($resultado['id'], $resultado['id_subitem'], $resultado['provider'], $resultado['price']);
              }
          } catch (PDOException $ex) {
              print 'ERROR:' . $ex->getMessage() . '<br>';
          }
      }
      return $provider_subitem;
  }

  public static function actualizar_provider_subitem($conexion, $id_provider_subitem, $provider, $price){
      if(isset($conexion)){
          try{
              $sql = 'UPDATE provider_subitems SET provider = :provider, price = :price WHERE id = :id_provider_subitem';
              $sentencia = $conexion-> prepare($sql);
              $sentencia-> bindParam(':provider', $provider, PDO::PARAM_STR);
              $sentencia-> bindParam(':price', $price, PDO::PARAM_STR);
              $sentencia-> bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
              $sentencia-> execute();
          } catch (PDOException $ex) {
              print 'ERROR:' . $ex->getMessage() . '<br>';
          }
      }
  }

  public static function delete_provider_subitem($conexion, $id_provider_subitem){
    if(isset($conexion)){
      try{
        $sql = 'DELETE FROM provider_subitems WHERE id = :id_provider_subitem';
        $sentencia = $conexion-> prepare($sql);
        $sentencia->bindParam(':id_provider_subitem', $id_provider_subitem, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
