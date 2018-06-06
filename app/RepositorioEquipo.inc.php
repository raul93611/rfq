<?php
class RepositorioEquipo{
    public static function insertar_equipo($conexion, $equipo){
        $equipo_insertado = false;
        if(isset($conexion)){
            try{
                $sql = 'INSERT INTO equipo(id_rfq, brand, part_number, description, quantity, unit_price, total) VALUES(:id_rfq, :brand, :part_number, :description, :quantity, :unit_price, :total)';
                
                $sentencia = $conexion-> prepare($sql);
                
                $sentencia-> bindParam(':id_rfq', $equipo-> obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia-> bindParam(':brand', $equipo-> obtener_brand(), PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number', $equipo-> obtener_part_number(), PDO::PARAM_STR);
                $sentencia-> bindParam(':description', $equipo-> obtener_description(), PDO::PARAM_STR);
                $sentencia-> bindParam(':quantity', $equipo-> obtener_quantity(), PDO::PARAM_STR);
                $sentencia-> bindParam(':unit_price', $equipo-> obtener_unit_price(), PDO::PARAM_STR);
                $sentencia-> bindParam(':total', $equipo-> obtener_total(), PDO::PARAM_STR);
                
                $resultado = $sentencia-> execute();
                
                if($resultado){
                    $equipo_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $equipo_insertado;
    }
}
?>
