<?php

class RepositorioItem {

    public static function insertar_item($conexion, $item) {
        $item_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO item(id_rfq, id_usuario, brand, part_number, description, quantity) VALUES(:id_rfq, :id_usuario, :brand, :part_number, :description, :quantity)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $item->obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia-> bindParam(':id_usuario', $item-> obtener_id_usuario(), PDO::PARAM_STR);
                $sentencia-> bindParam(':brand', $item-> obtener_brand(), PDO::PARAM_STR);
                $sentencia-> bindParam(':part_number', $item-> obtener_part_number(), PDO::PARAM_STR);
                $sentencia->bindParam(':description', $item->obtener_description(), PDO::PARAM_STR);
                $sentencia->bindParam(':quantity', $item->obtener_quantity(), PDO::PARAM_STR);
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
}
?>
