<?php

class RepositorioEquipo {

    public static function insertar_equipo($conexion, $equipo) {
        $equipo_insertado = false;
        if (isset($conexion)) {
            try {
                $conexion-> beginTransaction();
                
                $sql1 = 'INSERT INTO equipo(id_rfq, description, quantity, unit_price, total) VALUES(:id_rfq, :description, :quantity, :unit_price, :total)';
                $sentencia1 = $conexion->prepare($sql1);
                $sentencia1->bindParam(':id_rfq', $equipo->obtener_id_rfq(), PDO::PARAM_STR);
                $sentencia1->bindParam(':description', $equipo->obtener_description(), PDO::PARAM_STR);
                $sentencia1->bindParam(':quantity', $equipo->obtener_quantity(), PDO::PARAM_STR);
                $sentencia1->bindParam(':unit_price', $equipo->obtener_unit_price(), PDO::PARAM_STR);
                $sentencia1->bindParam(':total', $equipo->obtener_total(), PDO::PARAM_STR);
                $resultado1 = $sentencia1->execute();
                
                $sql2 = 'UPDATE rfq SET amount = amount + :total WHERE id = :id_rfq';
                $sentencia2 = $conexion-> prepare($sql2);
                $sentencia2-> bindParam(':total', $equipo-> obtener_total(), PDO::PARAM_STR);
                $sentencia2-> bindParam(':id_rfq', $equipo-> obtener_id_rfq(), PDO::PARAM_STR);
                $resultado2 = $sentencia2-> execute();
                
                if($resultado1 && $resultado2){
                    $equipo_insertado = true;
                }
                $conexion-> commit();
                
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
                $conexion-> rollBack();
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
                        $equipos[] = new Equipo($fila['id'], $fila['id_rfq'], $fila['description'], $fila['quantity'], $fila['unit_price'], $fila['total']);
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
            <td><?php echo nl2br($equipo-> obtener_description()); ?></td>
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
                        <th>Description</th>
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
