<?php
class RepositorioProvider{
    public static function insertar_provider($conexion, $provider){
        $provider_insertado = false;
        if(isset($conexion)){
            try{
                $sql = 'INSERT INTO provider(id_item, provider, price) VALUES(:id_item, :provider, :price)';
                
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_item', $provider-> obtener_id_item(), PDO::PARAM_STR);
                $sentencia-> bindParam(':provider', $provider-> obtener_provider(), PDO::PARAM_STR);
                $sentencia-> bindParam(':price', $provider-> obtener_price(), PDO::PARAM_STR);
                
                $resultado = $sentencia-> execute();
                
                if($resultado){
                    $provider_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $provider_insertado;
    }
    
    public static function obtener_providers_por_id_item($conexion, $id_item){
        $providers = [];
        
        if(isset($conexion)){
            try{
                $sql = 'SELECT * FROM provider WHERE id_item = :id_item';
                
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
                $sentencia-> execute();
                
                $resultado = $sentencia-> fetchAll();
                
                if(count($resultado)){
                    foreach ($resultado as $fila){
                        $providers[] = new Provider($fila['id'], $fila['id_item'], $fila['provider'], $fila['price']);
                    }
                }
                
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $providers;
    }
}
?>
