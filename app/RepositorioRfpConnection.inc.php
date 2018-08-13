<?php
class RepositorioRfpConnection{
  public static function insertar_rfp_connection($conexion, $rfp_connection){
    if(isset($conexion)){
      try{
        $sql = 'INSERT INTO rfp_connection(id_rfq, rfp) VALUES(:id_rfq, :rfp)';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $rfp_connection-> obtener_id_rfq(), PDO::PARAM_STR);
        $sentencia-> bindParam(':rfp', $rfp_connection-> obtener_rfp(), PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_rfp_connection_por_id_project($conexion, $id_project){
    $rfp_connection = null;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfp_connection WHERE rfp = :id_project';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_project', $id_project, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetch(PDO::FETCH_ASSOC);
        if(!empty($resultado)){
          $rfp_connection = new RfpConnection($resultado['id'], $resultado['id_rfq'], $resultado['rfp']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $rfp_connection;
  }
}
?>
