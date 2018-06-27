<?php 
class RepositorioOutOfScope{
    public static function insertar_out_of_scope($conexion, $out_of_scope){
        $out_of_scope_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO out_of_scopes(id_cuestionario, requirement) VALUES(:id_cuestionario, :requirement)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $out_of_scope->obtener_id_cuestionario(), PDO::PARAM_STR);
                $sentencia->bindParam(':requirement', $out_of_scope->obtener_requirement(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                if ($resultado) {
                    $out_of_scope_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $out_of_scope_insertado;
    }
    
    public static function obtener_out_of_scopes_de_un_cuestionario($conexion, $id_cuestionario) {
        $out_of_scopes = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM out_of_scopes WHERE id_cuestionario = :id_cuestionario';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $out_of_scopes[] = new OutOfScope($fila['id'], $fila['id_cuestionario'], $fila['requirement']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $out_of_scopes;
    }

    public static function escribir_out_of_scope($out_of_scope, $i) {
        if (!isset($out_of_scope)) {
            return;
        }
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $out_of_scope-> obtener_requirement(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_out_of_scopes($id_cuestionario) {
        Conexion::abrir_conexion();
        $out_of_scopes = self::obtener_out_of_scopes_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
        Conexion::cerrar_conexion();

        if (count($out_of_scopes)) {
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
                    for ($i = 0; $i < count($out_of_scopes); $i++) {
                        $out_of_scope = $out_of_scopes[$i];
                        self::escribir_out_of_scope($out_of_scope, $i + 1);
                    }
                    ?>
                </tbody>
            </table> 
            <?php
        }
    }
}
?>
