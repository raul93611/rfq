<?php
class RepositorioProjectRisk{
    public static function insertar_project_risk($conexion, $project_risk){
        $project_risk_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO project_risks(id_cuestionario, description) VALUES(:id_cuestionario, :description)';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $project_risk->obtener_id_cuestionario(), PDO::PARAM_STR);
                $sentencia->bindParam(':description', $project_risk->obtener_description(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                if ($resultado) {
                    $project_risk_insertado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $project_risk_insertado;
    }
    
    public static function obtener_project_risks_de_un_cuestionario($conexion, $id_cuestionario) {
        $project_risks = [];
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM project_risks WHERE id_cuestionario = :id_cuestionario';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_cuestionario', $id_cuestionario, PDO::PARAM_STR);

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $project_risks[] = new ProjectRisk($fila['id'], $fila['id_cuestionario'], $fila['description']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $project_risks;
    }

    public static function escribir_project_risk($project_risk, $i) {
        if (!isset($project_risk)) {
            return;
        }
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $project_risk-> obtener_description(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_project_risks($id_cuestionario) {
        Conexion::abrir_conexion();
        $project_risks = self::obtener_project_risks_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
        Conexion::cerrar_conexion();

        if (count($project_risks)) {
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DESCRIPTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for ($i = 0; $i < count($project_risks); $i++) {
                        $project_risk = $project_risks[$i];
                        self::escribir_project_risk($project_risk, $i + 1);
                    }
                    ?>
                </tbody>
            </table> 
            <?php
        }
    }
}
?>
