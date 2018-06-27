<?php

class RepositorioHighLevelRequirement {

    public static function insertar_high_level_requirement($conexion, $high_level_requirement) {
        $high_level_requirement_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO high_level_requirements(id_cuestionario, requirement) VALUES(:id_cuestionario, :requirement)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $high_level_requirement->obtener_id_cuestionario(), PDO::PARAM_STR);
                $sentencia->bindParam(':requirement', $high_level_requirement->obtener_requirement(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                if ($resultado) {
                    $high_level_requirement_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $high_level_requirement_insertado;
    }

    public static function obtener_high_level_requirements_de_un_cuestionario($conexion, $id_cuestionario) {
        $high_level_requirements = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM high_level_requirements WHERE id_cuestionario = :id_cuestionario';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $high_level_requirements[] = new HighLevelRequirement($fila['id'], $fila['id_cuestionario'], $fila['requirement']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $high_level_requirements;
    }

    public static function escribir_high_level_requirement($high_level_requirement, $i) {
        if (!isset($high_level_requirement)) {
            return;
        }
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $high_level_requirement-> obtener_requirement(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_high_level_requirements($id_cuestionario) {
        Conexion::abrir_conexion();
        $high_level_requirements = self::obtener_high_level_requirements_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
        Conexion::cerrar_conexion();

        if (count($high_level_requirements)) {
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>REQUIREMENT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($high_level_requirements); $i++) {
                        $high_level_requirement = $high_level_requirements[$i];
                        self::escribir_high_level_requirement($high_level_requirement, $i + 1);
                    }
                    ?>
                </tbody>
            </table> 
            <?php
        }
    }

}
?>
