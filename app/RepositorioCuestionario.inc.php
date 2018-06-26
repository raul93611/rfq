<?php
class RepositorioCuestionario{
    public static function insertar_cuestionario($conexion, $cuestionario){
        $cuestionario_insertado = false;
        
        if(isset($conexion)){
            try{
                $sql = 'INSERT INTO cuestionario(id_rfq) VALUES(:id_rfq)';
                
                $sentencia = $conexion-> prepare($sql);
                
                $sentencia-> bindParam(':id_rfq', $cuestionario-> obtener_id_rfq(), PDO::PARAM_STR);
                
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
}
?>
