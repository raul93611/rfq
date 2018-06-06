<?php

class RepositorioEquipo {

    public static function insertar_equipo($conexion, $equipo) {
        $equipo_insertado = false;
        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO equipo(id_rfq, brand, part_number, description, quantity, unit_price, total) VALUES(:id_rfq, :brand, :part_number, :description, :quantity, :unit_price, :total)';

                $sentencia = $conexion->prepare($sql);

                $sentencia->bindParam(':id_rfq', $equipo->obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia->bindParam(':brand', $equipo->obtener_brand(), PDO::PARAM_STR);
                $sentencia->bindParam(':part_number', $equipo->obtener_part_number(), PDO::PARAM_STR);
                $sentencia->bindParam(':description', $equipo->obtener_description(), PDO::PARAM_STR);
                $sentencia->bindParam(':quantity', $equipo->obtener_quantity(), PDO::PARAM_STR);
                $sentencia->bindParam(':unit_price', $equipo->obtener_unit_price(), PDO::PARAM_STR);
                $sentencia->bindParam(':total', $equipo->obtener_total(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                if ($resultado) {
                    $equipo_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $equipo_insertado;
    }

    public static function obtener_equipos_por_rfq($conexion, $id_rfq) {
        $equipos = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM equipo WHERE id_rfq = :id_rfq';

                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $equipos[] = new Equipo($fila['id'], $fila['id_rfq'], $fila['brand'], $fila['part_number'], $fila['description'], $fila['quantity'], $fila['unit_price'], $fila['total']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $equipos;
    }

    public static function escribir_equipo($equipo) {
        if (!isset($equipo)) {
            return;
        }
        ?>
        <tr>
            <td><?php echo $equipo->obtener_brand(); ?></td>
            <td><?php echo $equipo->obtener_part_number(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_equipos($id_rfq) {
        Conexion::abrir_conexion();
        $equipos = self::obtener_equipos_por_rfq(Conexion::obtener_conexion(), $id_rfq);
        Conexion::cerrar_conexion();

        if (count($equipos)) {
            ?>
            <label>Equipment:</label>
            <table class="table table-bordered table-hover">
                <thead class="bg-warning">
                    <tr>
                        <th>Brand</th>
                        <th>Part #</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                    <?php
                    foreach ($equipos as $equipo) {
                        self::escribir_equipo($equipo);
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    }

}
?>
