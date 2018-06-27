<?php
class RepositorioProjectMilestone{
    public static function insertar_project_milestone($conexion, $project_milestone){
        $project_milestone_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO project_milestones(id_cuestionario, date_milestone, description) VALUES(:id_cuestionario, :date_milestone, :description)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $project_milestone->obtener_id_cuestionario(), PDO::PARAM_STR);
                $sentencia-> bindParam(':date_milestone', $project_milestone-> obtener_date_milestone(), PDO::PARAM_STR);
                $sentencia->bindParam(':description', $project_milestone->obtener_description(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                if ($resultado) {
                    $project_milestone_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $project_milestone_insertado;
    }
    
    public static function obtener_project_milestones_de_un_cuestionario($conexion, $id_cuestionario) {
        $project_milestones = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM project_milestones WHERE id_cuestionario = :id_cuestionario';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $project_milestones[] = new ProjectMilestone($fila['id'], $fila['id_cuestionario'], $fila['date_milestone'], $fila['description']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $project_milestones;
    }

    public static function escribir_project_milestone($project_milestone, $i) {
        if (!isset($project_milestone)) {
            return;
        }
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $project_milestone-> obtener_date_milestone(); ?></td>
            <td><?php echo $project_milestone-> obtener_description(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_project_milestones($id_cuestionario) {
        Conexion::abrir_conexion();
        $project_milestones = self::obtener_project_milestones_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
        Conexion::cerrar_conexion();

        if (count($project_milestones)) {
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DATE</th>
                        <th>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($project_milestones); $i++) {
                        $project_milestone = $project_milestones[$i];
                        self::escribir_project_milestone($project_milestone, $i + 1);
                    }
                    ?>
                </tbody>
            </table> 
            <?php
        }
    }
}
?>
