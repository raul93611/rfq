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
    
    public static function obtener_provider_por_id($conexion, $id_provider){
        $provider = null;
        
        if(isset($conexion)){
            try{
                $sql = 'SELECT * FROM provider WHERE id = :id_provider';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
                $sentencia-> execute();
                
                $resultado = $sentencia-> fetch();
                
                if(!empty($resultado)){
                    $provider = new Provider($resultado['id'], $resultado['id_item'], $resultado['provider'], $resultado['price']);
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $provider;
    }
    
    public static function actualizar_provider($conexion, $id_provider, $provider, $price){
        $provider_editado = false;
        
        if(isset($conexion)){
            try{
                $sql = 'UPDATE provider SET provider = :provider, price = :price WHERE id = :id_provider';
                $sentencia = $conexion-> prepare($sql);
                
                $sentencia-> bindParam(':provider', $provider, PDO::PARAM_STR);
                $sentencia-> bindParam(':price', $price, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
                
                $sentencia-> execute();
                
                if($sentencia){
                    $provider_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $provider_editado;
    }
    
    public static function actualizar_precio($conexion, $id_provider, $operacion, $price){
        $provider_editado = false;
        
        if(isset($conexion)){
            try{
                if($operacion == 'sumar'){
                    $sql = 'UPDATE provider SET price = price + :price WHERE id = :id_provider';
                }else if($operacion == 'restar'){
                    $sql = 'UPDATE provider SET price = price - :price WHERE id = :id_provider';
                }
                
                $sentencia = $conexion-> prepare($sql);
                
                $sentencia-> bindParam(':price', $price, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_provider', $id_provider, PDO::PARAM_STR);
                
                $sentencia-> execute();
                
                if($sentencia){
                    $provider_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $provider_editado;
    }
}
?>
