<?php

class RepositorioItem {

    public static function insertar_item($conexion, $item) {
        $item_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO item(id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_rfq, :id_usuario, :provider_menor, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $item->obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia->bindParam(':id_usuario', $item->obtener_id_usuario(), PDO::PARAM_STR);
                $sentencia->bindParam(':provider_menor', $item->obtener_provider_menor(), PDO::PARAM_STR);
                $sentencia->bindParam(':brand', $item->obtener_brand(), PDO::PARAM_STR);
                $sentencia->bindParam(':brand_project', $item->obtener_brand_project(), PDO::PARAM_STR);
                $sentencia->bindParam(':part_number', $item->obtener_part_number(), PDO::PARAM_STR);
                $sentencia->bindParam(':part_number_project', $item->obtener_part_number_project(), PDO::PARAM_STR);
                $sentencia->bindParam(':description', $item->obtener_description(), PDO::PARAM_STR);
                $sentencia->bindParam(':description_project', $item->obtener_description_project(), PDO::PARAM_STR);
                $sentencia->bindParam(':quantity', $item->obtener_quantity(), PDO::PARAM_STR);
                $sentencia->bindParam(':unit_price', $item->obtener_unit_price(), PDO::PARAM_STR);
                $sentencia->bindParam(':total_price', $item->obtener_total_price(), PDO::PARAM_STR);
                $sentencia->bindParam(':comments', $item->obtener_comments(), PDO::PARAM_STR);
                $sentencia->bindParam(':website', $item->obtener_website(), PDO::PARAM_STR);
                $sentencia->bindParam(':additional', $item->obtener_additional(), PDO::PARAM_STR);
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

    public static function actualizar_provider_menor_item($conexion, $provider_menor, $id_item){
        $item_editado = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE item SET provider_menor = :provider_menor WHERE id = :id_item';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':provider_menor', $provider_menor, PDO::PARAM_STR);
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

    public static function obtener_items_por_id_rfq($conexion, $id_rfq) {
        $items = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM item WHERE id_rfq = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetchall();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $items[] = new Item($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['provider_menor'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['unit_price'], $fila['total_price'], $fila['comments'], $fila['website'], $fila['additional']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $items;
    }

    public static function escribir_item($item, $i) {
        if (!isset($item)) {
            return;
        }
        $j = $i;
        Conexion::abrir_conexion();
        $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        Conexion::cerrar_conexion();
        echo '<tr>';
        echo '<td><a href="' . ADD_PROVIDER . '/' . $item->obtener_id() . '" class="btn btn-warning btn-block"><i class="fa fa-plus-circle"></i> Add Provider</a><br><a href="' . EDIT_ITEM . '/' . $item->obtener_id() . '" class="btn btn-warning btn-block"><i class="fa fa-edit"></i> Edit item</a></td>';
        echo '<td>' . $i . '</td>';
        echo '<td><b>Brand:</b>' . $item->obtener_brand_project() . '<br><b>Part #:</b>' . $item->obtener_part_number_project() . '<br><b>Description:</b>' . nl2br($item->obtener_description_project()) . '</td>';
        echo '<td><b>Brand:</b>' . $item->obtener_brand() . '<br><b>Part #:</b>' . $item->obtener_part_number() . '<br><b>Description:</b>' . nl2br($item->obtener_description()) . '</td>';
        echo '<td class="estrechar"><a target="_blank" href="'. $item-> obtener_website() .'">'. $item-> obtener_website() .'</a></td>';
        echo '<td>' . $item->obtener_quantity() . '</td>';
        echo '<td><div class="row"><div class="col-6">';
        for ($i = 0; $i < count($providers); $i++) {
            $provider = $providers[$i];
            echo '<a href="' . EDIT_PROVIDER . '/' . $provider->obtener_id() . '"><b>' . $provider->obtener_provider() . ':</b></a><br>';
        }
        echo '</div><div class="col-6">';
        for ($i = 0; $i < count($providers); $i++) {
            $provider = $providers[$i];
            echo '$ ' . $provider->obtener_price() . '<br>';
        }
        echo '</div></div></td>';
        if($item-> obtener_additional() != 0){
            echo '<td><input type="text" id="add_cost'.$j.'" size="10" value="'.$item-> obtener_additional().'"></td>';
        }else{
            echo '<td><input type="text" id="add_cost'.$j.'" size="10" value="0"></td>';
        }

        echo '<td>';
        for ($i = 0; $i < count($providers); $i++) {
            $provider = $providers[$i];
            $precios[$i] = $provider->obtener_price();
        }
        if (!empty($precios)) {
            $best_unit_price = min($precios);
            for($i = 0;$i < count($precios); $i++){
                if($best_unit_price == $precios[$i]){
                    Conexion::abrir_conexion();
                    self::actualizar_provider_menor_item(Conexion::obtener_conexion(), $providers[$i]->obtener_id(), $item-> obtener_id());
                    Conexion::cerrar_conexion();
                }
            }
            echo '$ ' . $best_unit_price;
        }
        echo '</td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td>' . nl2br($item->obtener_comments()) . '</td>';
        echo '</tr>';
    }

    public static function escribir_items($id_rfq) {
        Conexion::abrir_conexion();
        $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
        $items = self::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
        Conexion::cerrar_conexion();

        if (count($items)) {
            echo '<br><h2>Items:</h2>';
            echo '<div class="row">';
            echo '<div class="col">';
            if ($cotizacion->obtener_taxes() != 0) {
                echo '<label>Taxes (%):</label><input type="number" step=".01" name="taxes" id="taxes" class="form-control" value="' . $cotizacion->obtener_taxes() . '">';
            } else {
                echo '<label>Taxes (%):</label><input type="number" step=".01" name="taxes" id="taxes" class="form-control" value="0">';
            }

            echo '</div><div class="col">';

            if ($cotizacion->obtener_profit() != 0) {
                echo '<label>Profit (%):</label><input type="number" step=".01" name="profit" id="profit" class="form-control" value="' . $cotizacion->obtener_profit() . '">';
            } else {
                echo '<label>Profit (%):</label><input type="number" step=".01" name="profit" id="profit" class="form-control" value="0">';
            }

            echo '</div><div class="col">';

            if($cotizacion-> obtener_additional() != 0){
                echo '<label>Additional general ($):</label><input type="text" name="additional_general" id="additional_general" class="form-control" value="' . $cotizacion->obtener_additional() . '">';
            }else{
                echo '<label>Additional general ($):</label><input type="text" name="additional_general" id="additional_general" class="form-control" value="0">';
            }

            echo '</div><div class="col">';

            echo '<label>Payment terms:</label><div class="form-group">
                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" id="net_30" value="Net 30" name="payment_terms"';
                    if ($cotizacion->obtener_payment_terms() == 'Net 30') {
                        echo 'checked';
                    }
                    echo '><label class="form-check-label" for="net_30">Net 30</label>
                </div>
                <div class="form-check-inline">
                    <input class="form-check-input" type="radio" id="net_30cc" value="Net 30/CC" name="payment_terms"';

                    if ($cotizacion->obtener_payment_terms() == 'Net 30/CC') {
                        echo 'checked';
                    }

            echo '><label class="form-check-label" for="net_30cc">Net 30/CC</label>
                </div>
            </div>';

            echo '</div></div><br>';
            echo '<table id="tabla_items" class="table table-bordered table-hover">';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="options">Options</th>';
            echo '<th id="numeracion">#</th>';
            echo '<th class="description">PROJECT SPECIFICATIONS</th>';
            echo '<th class="description">E-LOGIC PROPOSAL</th>';
            echo '<th class="options">WEBSITE</th>';
            echo '<th class="options">QTY</th>';
            echo '<th id="provider">PROVIDERS</th>';
            echo '<th class="options">ADDITIONAL</th>';
            echo '<th class="options">BEST UNIT COST</th>';
            echo '<th class="options">TOTAL COST</th>';
            echo '<th class="options">PRICE FOR CLIENT</th>';
            echo '<th class="options">TOTAL PRICE</th>';
            echo '<th class="description">COMMENTS</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="items">';
            for ($i = 0; $i < count($items); $i++) {
                $item = $items[$i];
                self::escribir_item($item, $i + 1);
            }
            echo '<td colspan="9" class="display-4"><b><h4>TOTAL:</h4></b></td>';
            echo '<td id="total1"></td>';
            echo '<td></td>';
            echo '<td id="total2"></td>';
            echo '</tbody>';
            echo '</table>';
            $id_items = '';
            for($i = 0; $i < count($items); $i++){
                $item = $items[$i];
                if($i == 0){
                    $id_items = $id_items . $item-> obtener_id();
                }else{
                    $id_items = $id_items . ',' . $item-> obtener_id();
                }
            }
            echo '<input type="hidden" id="id_items" name="id_items" value="'.$id_items.'">';
            echo '<input type="hidden" id="partes_total_price" name="partes_total_price" value="">';
            echo '<input type="hidden" id="additional" name="additional" value="">';
            echo '<input type="hidden" id="unit_prices" name="unit_prices" value="">';
            echo '<input type="hidden" id="total_cost" name="total_cost" value="">';
            echo '<input type="hidden" id="total_price" name="total_price" value="">';
        }
    }

    public static function obtener_item_por_id($conexion, $id_item) {
        $item = null;
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM item WHERE id = :id_item';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_item', $id_item, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetch();

                if (!empty($resultado)) {
                    $item = new Item($resultado['id'], $resultado['id_rfq'], $resultado['id_usuario'], $resultado['provider_menor'], $resultado['brand'], $resultado['brand_project'], $resultado['part_number'], $resultado['part_number_project'], $resultado['description'], $resultado['description_project'], $resultado['quantity'], $resultado['unit_price'], $resultado['total_price'], $resultado['comments'], $resultado['website'], $resultado['additional']);
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $item;
    }

    public static function actualizar_item($conexion, $id_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website) {
        $item_editado = false;

        if (isset($conexion)) {
            try {
                $sql = 'UPDATE item SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_item';
                $sentencia = $conexion->prepare($sql);

                $sentencia->bindParam(':brand', $brand, PDO::PARAM_STR);
                $sentencia->bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
                $sentencia->bindParam(':part_number', $part_number, PDO::PARAM_STR);
                $sentencia->bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
                $sentencia->bindParam(':description', $description, PDO::PARAM_STR);
                $sentencia->bindParam(':description_project', $description_project, PDO::PARAM_STR);
                $sentencia->bindParam(':quantity', $quantity, PDO::PARAM_STR);
                $sentencia->bindParam(':comments', $comments, PDO::PARAM_STR);
                $sentencia->bindParam(':website', $website, PDO::PARAM_STR);
                $sentencia->bindParam(':id_item', $id_item, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $item_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $item_editado;
    }

    public static function insertar_calculos($conexion, $unit_price, $total_price, $additional, $id_item){
        $item_editado = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE item SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_item';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
                $sentencia-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
                $sentencia-> bindParam(':additional', $additional, PDO::PARAM_STR);
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
