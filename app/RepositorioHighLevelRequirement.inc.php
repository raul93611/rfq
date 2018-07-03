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
            <td><a class="btn btn-warning" href="<?php echo EDIT_HIGH_LEVEL_REQUIREMENT . '/' . $high_level_requirement-> obtener_id(); ?>">Edit</a></td>
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
                        <th class="cuestionario">#</th>
                        <th>REQUIREMENT</th>
                        <th class="cuestionario"></th>
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
    
    public static function obtener_high_level_requirement_por_id($conexion, $id_high_level_requirement) {
        $high_level_requirement = null;
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM high_level_requirements WHERE id = :id_high_level_requirement';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_high_level_requirement', $id_high_level_requirement, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetch();

                if (!empty($resultado)) {
                    $high_level_requirement = new HighLevelRequirement($resultado['id'], $resultado['id_cuestionario'], $resultado['requirement']);
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $high_level_requirement;
    }
    
    public static function actualizar_high_level_requirement($conexion, $requirement, $id_high_level_requirement){
        $high_level_requirement_editado = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE high_level_requirements SET requirement = :requirement WHERE id = :id_high_level_requirement';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':requirement', $requirement, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_high_level_requirement', $id_high_level_requirement, PDO::PARAM_STR);
                $sentencia-> execute();
                
                if($sentencia){
                    $high_level_requirement_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $high_level_requirement_editado;
    }

}
?>
