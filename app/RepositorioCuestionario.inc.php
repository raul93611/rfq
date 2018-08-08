<?php
class RepositorioCuestionario{
    public static function insertar_cuestionario($conexion, $cuestionario){
        $cuestionario_insertado = false;
        if(isset($conexion)){
            try{
                $sql = 'INSERT INTO cuestionario(id_rfq, reach_objectives, cost_objectives, time_objectives, quality_objectives, reach_goals, cost_goals, time_goals, quality_goals, locations) VALUES(:id_rfq, :reach_objectives, :cost_objectives, :time_objectives, :quality_objectives, :reach_goals, :cost_goals, :time_goals, :quality_goals, :locations)';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_rfq', $cuestionario-> obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia-> bindParam(':reach_objectives', $cuestionario-> obtener_reach_objectives(), PDO::PARAM_STR);
                $sentencia-> bindParam(':cost_objectives', $cuestionario-> obtener_cost_objectives(), PDO::PARAM_STR);
                $sentencia-> bindParam(':time_objectives', $cuestionario-> obtener_time_objectives(), PDO::PARAM_STR);
                $sentencia-> bindParam(':quality_objectives', $cuestionario-> obtener_quality_objectives(), PDO::PARAM_STR);
                $sentencia-> bindParam(':reach_goals', $cuestionario-> obtener_reach_goals(), PDO::PARAM_STR);
                $sentencia-> bindParam(':cost_goals', $cuestionario-> obtener_cost_goals(), PDO::PARAM_STR);
                $sentencia-> bindParam(':time_goals', $cuestionario-> obtener_time_goals(), PDO::PARAM_STR);
                $sentencia-> bindParam(':quality_goals', $cuestionario-> obtener_quality_goals(), PDO::PARAM_STR);
                $sentencia-> bindParam(':locations', $cuestionario-> obtener_locations(), PDO::PARAM_STR);
                $resultado = $sentencia-> execute();
                if($resultado){
                    $cuestionario_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cuestionario_insertado;
    }

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

    public static function actualizar_cuestionario($conexion, $id_cuestionario, $reach_objectives, $cost_objectives, $time_objectives, $quality_objectives, $reach_goals, $cost_goals, $time_goals, $quality_goals, $locations){
        $cuestionario_editado = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE cuestionario SET reach_objectives = :reach_objectives, cost_objectives = :cost_objectives, time_objectives = :time_objectives, quality_objectives = :quality_objectives, reach_goals = :reach_goals, cost_goals = :cost_goals, time_goals = :time_goals, quality_goals = :quality_goals, locations = :locations WHERE id = :id_cuestionario';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':reach_objectives', $reach_objectives, PDO::PARAM_STR);
                $sentencia-> bindParam(':cost_objectives', $cost_objectives, PDO::PARAM_STR);
                $sentencia-> bindParam(':time_objectives', $time_objectives, PDO::PARAM_STR);
                $sentencia-> bindParam(':quality_objectives', $quality_objectives, PDO::PARAM_STR);
                $sentencia-> bindParam(':reach_goals', $reach_goals, PDO::PARAM_STR);
                $sentencia-> bindParam(':cost_goals', $cost_goals, PDO::PARAM_STR);
                $sentencia-> bindParam(':time_goals', $time_goals, PDO::PARAM_STR);
                $sentencia-> bindParam(':quality_goals', $quality_goals, PDO::PARAM_STR);
                $sentencia-> bindParam(':locations', $locations, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);
                $sentencia-> execute();
                if($sentencia){
                    $cuestionario_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cuestionario_editado;
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
