<?php

class RepositorioItem {

    public static function insertar_item($conexion, $item) {
        $item_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO item(id_rfq, id_usuario, brand, brand_project, part_number, part_number_project, description, description_project, quantity, comments) VALUES(:id_rfq, :id_usuario, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :comments)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $item->obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia-> bindParam(':id_usuario', $item-> obtener_id_usuario(), PDO::PARAM_STR);
                $sentencia-> bindParam(':brand', $item-> obtener_brand(), PDO::PARAM_STR);
                $sentencia-> bindParam(':brand_project', $item-> obtener_brand_project(), PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number', $item-> obtener_part_number(), PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number_project', $item-> obtener_part_number_project(), PDO::PARAM_STR);
                $sentencia-> bindParam(':description', $item->obtener_description(), PDO::PARAM_STR);
                $sentencia-> bindParam(':description_project', $item-> obtener_description_project(), PDO::PARAM_STR);
                $sentencia-> bindParam(':quantity', $item->obtener_quantity(), PDO::PARAM_STR);
                $sentencia-> bindParam(':comments', $item->obtener_comments(), PDO::PARAM_STR);
                $resultado = $sentencia->execute();

                if ($resultado) {
                    $item_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $item_insertado;
    }
    
    public static function obtener_items_por_id_rfq($conexion, $id_rfq){
        $items = [];
        if(isset($conexion)){
            try{
                $sql = 'SELECT * FROM item WHERE id_rfq = :id_rfq';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                $sentencia-> execute();
                
                $resultado = $sentencia-> fetchall();
                
                if(count($resultado)){
                    foreach ($resultado as $fila){
                        $items[] = new Item($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['comments']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $items;
    }
    
    public static function escribir_item($item, $i){
        if(!isset($item)){
            return;
        }
        Conexion::abrir_conexion();
        $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
        Conexion::cerrar_conexion();
        echo '<tr>';
        echo '<td><a href="' . ADD_PROVIDER . '/' . $item-> obtener_id() . '" class="btn btn-warning btn-block">Add Provider</a><br><a href="' . EDIT_ITEM . '/' . $item-> obtener_id() . '" class="btn btn-warning btn-block">Edit item</a></td>';
        echo '<td>' . $i . '</td>';
        echo '<td><b>Brand:</b>' . $item-> obtener_brand_project() . '<br><b>Part #:</b>' . $item-> obtener_part_number_project() . '<br><b>Description:</b>' . nl2br($item->obtener_description_project()) . '</td>';
        echo '<td><b>Brand:</b>' . $item-> obtener_brand() . '<br><b>Part #:</b>' . $item-> obtener_part_number() . '<br><b>Description:</b>' . nl2br($item->obtener_description()) . '</td>';
        echo '<td>' . $item-> obtener_quantity() . '</td>';
        echo '<td><div class="row"><div class="col-6">';
        for($i = 0; $i < count($providers); $i++){
            $provider = $providers[$i];
            echo '<a href="' . EDIT_PROVIDER . '/' . $provider-> obtener_id() . '"><b>'.$provider-> obtener_provider().':</b></a><br>';
        }
        echo '</div><div class="col-6">';
        for($i = 0; $i < count($providers); $i++){
            $provider = $providers[$i];
            echo '$ ' . $provider-> obtener_price().'<br>';
        }
        echo '</div></div></td>';
        echo '<td>';
        for($i = 0; $i < count($providers); $i++){
            $provider = $providers[$i];
            $precios[$i] = $provider-> obtener_price(); 
        }
        if(!empty($precios)){
            $best_unit_price = min($precios); 
            echo '$ ' . $best_unit_price;
        }
        echo '</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>' . nl2br($item-> obtener_comments()) . '</td>';
        echo '</tr>';
    }
    
    public static function escribir_items($id_rfq){
        Conexion::abrir_conexion();
        $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
        $items = self::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
        Conexion::cerrar_conexion();
        
        if(count($items)){
            echo '<br><h2>Items:</h2>';
            echo '<div class="row">';
            echo '<div class="col">';
            if($cotizacion-> obtener_taxes() != 0){
                echo '<label>Taxes (%):</label><input type="number" step=".01" name="taxes" id="taxes" class="form-control" value="'. $cotizacion-> obtener_taxes() .'"><br><button type="button" id="calculate" class="btn btn-primary">Calculate</button>';
            }else{
                echo '<label>Taxes (%):</label><input type="number" step=".01" name="taxes" id="taxes" class="form-control" value="0"><br><button type="button" id="calculate" class="btn btn-primary">Calculate</button>';
            }
            
            echo '</div><div class="col">';
            
            if($cotizacion-> obtener_profit() != 0){
                echo '<label>Profit (%):</label><input type="number" step=".01" name="profit" id="profit" class="form-control" value="'. $cotizacion-> obtener_profit() .'">';
            }else{
                echo '<label>Profit (%):</label><input type="number" step=".01" name="profit" id="profit" class="form-control" value="0">';
            }
            
            echo '</div></div><br>';
            echo '<table id="tabla_items" class="table table-bordered table-hover">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="options">Options</th>';
            echo '<th id="numeracion">#</th>';
            echo '<th class="description">PROJECT SPECIFICATIONS</th>';
            echo '<th class="description">E-LOGIC PROPOSAL</th>';
            echo '<th class="options">QTY</th>';
            echo '<th>PROVIDERS</th>';
            echo '<th class="options">BEST UNIT COST</th>';
            echo '<th class="options">TOTAL COST</th>';
            echo '<th class="options">PRICE FOR CLIENT</th>';
            echo '<th class="options">TOTAL PRICE</th>';
            echo '<th>COMMENTS</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="items">';
            for($i = 0; $i < count($items); $i++){
                $item = $items[$i];
                self::escribir_item($item, $i+1);
            }
            echo '<td colspan="7" class="display-4"><b><h4>TOTAL:</h4></b></td>';
            echo '<td id="total1"></td>';
            echo '<td></td>';
            echo '<td id="total2"></td>';
            echo '<td></td>';
            echo '</tbody>';
            echo '</table>';
            echo '<input type="hidden" id="total_cost" name="total_cost" value="">';
            echo '<input type="hidden" id="total_price" name="total_price" value="">';
        }
    }
    
    public static function obtener_item_por_id($conexion, $id_item){
        $item = null;
        if(isset($conexion)){
            try{
                $sql = 'SELECT * FROM item WHERE id = :id_item';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
                $sentencia-> execute();
                
                $resultado = $sentencia-> fetch();
                
                if(!empty($resultado)){
                    $item = new Item($resultado['id'], $resultado['id_rfq'], $resultado['id_usuario'], $resultado['brand'], $resultado['brand_project'], $resultado['part_number'], $resultado['part_number_project'], $resultado['description'], $resultado['description_project'], $resultado['quantity'], $resultado['comments']);
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $item;
    }
    
    public static function actualizar_item($conexion, $id_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments){
        $item_editado = false;
        
        if(isset($conexion)){
            try{
                $sql = 'UPDATE item SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments WHERE id = :id_item';
                $sentencia = $conexion-> prepare($sql);
                
                $sentencia-> bindParam(':brand', $brand, PDO::PARAM_STR);
                $sentencia-> bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number', $part_number, PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
                $sentencia-> bindParam(':description', $description, PDO::PARAM_STR);
                $sentencia-> bindParam(':description_project', $description_project, PDO::PARAM_STR);
                $sentencia-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
                $sentencia-> bindParam(':comments', $comments, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
                
                $sentencia-> execute();
                
                if($sentencia){
                    $item_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $item_editado;
    }
}
?>
