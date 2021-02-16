<?php
class RepositorioCuestionario{
  public static function obtener_cuestionario_por_id_rfq($conexion, $id_rfq){
    $cuestionario = null;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM cuestionario WHERE id_rfq = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();

        $resultado = $sentencia-> fetch();

        if(!empty($resultado)){
          $cuestionario = new Cuestionario($resultado['id'], $resultado['id_rfq'], $resultado['reach_objectives'], $resultado['cost_objectives'], $resultado['time_objectives'], $resultado['quality_objectives'], $resultado['reach_goals'], $resultado['cost_goals'], $resultado['time_goals'], $resultado['quality_goals'], $resultado['locations']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cuestionario;
  }

  public static function obtener_cuestionario_por_id($conexion, $id_cuestionario){
    $cuestionario = null;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM cuestionario WHERE id = :id_cuestionario';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);
        $sentencia-> execute();

        $resultado = $sentencia-> fetch();

        if(!empty($resultado)){
          $cuestionario = new Cuestionario($resultado['id'], $resultado['id_rfq'], $resultado['reach_objectives'], $resultado['cost_objectives'], $resultado['time_objectives'], $resultado['quality_objectives'], $resultado['reach_goals'], $resultado['cost_goals'], $resultado['time_goals'], $resultado['quality_goals'], $resultado['locations']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cuestionario;
  }

  public static function delete_cuestionario_por_id_rfq($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'DELETE FROM cuestionario WHERE id_rfq = :id_rfq';
        $sentencia= $conexion->prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }
}
?>
