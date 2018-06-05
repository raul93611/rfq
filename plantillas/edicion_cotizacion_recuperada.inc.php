<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
    <div class="form-group">
        <label for="email_code">Email-code:</label>
        <input type="text" class="form-control" id="email_code" disabled value="<?php echo $cotizacion_recuperada->obtener_email_code(); ?>">
    </div>
    <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <input type="text" class="form-control" id="email_code" disabled value="<?php echo $cotizacion_recuperada->obtener_type_of_bid(); ?>">
    </div>
    <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="date" class="form-control" id="issue_date" disabled value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
    </div>
    <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control" id="end_date" disabled value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
    </div>
    <?php
    if ($cargo == 3) {
        ?>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" name="status" value="si" 
            <?php
            if ($cotizacion_recuperada->obtener_status()) {
                echo 'checked';
            }
            ?>
                   id="status">
            <label class="form-check-label" for="status">Submitted</label>
        </div>
        <?php
    }
    ?>

    <div class="form-check
    <?php
    if ($cotizacion_recuperada->obtener_completado()) {
        echo 'disabled';
    }
    ?>
         ">
        <input type="checkbox" class="form-check-input" name="completado" value="si" 
        <?php
        if ($cotizacion_recuperada->obtener_completado()) {
            echo 'checked disabled';
        }
        ?>
               id="completado">
        <label class="form-check-label" for="completado">Completed</label>
    </div>
    <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" value="<?php echo $cotizacion_recuperada->obtener_amount(); ?>">
    </div>
    <div class="form-group">
        <label for="proposal">Proposal:</label>
        <input type="number" class="form-control" id="proposal" name="proposal" min="0" value="<?php echo $cotizacion_recuperada->obtener_proposal(); ?>">
    </div>
    <div class="form-group">
        <label for="comments">Comments:</label>
        <input type="text" class="form-control" id="comments" name="comments" placeholder="Comments" value="<?php echo $cotizacion_recuperada->obtener_comments(); ?>">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="award" value="si" 
        <?php
        if ($cotizacion_recuperada->obtener_award()) {
            echo 'checked';
        }
        ?>
               id="award">
        <label class="form-check-label" for="award">Award</label>
    </div>


    <div class="form-group">
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        ?>
        <?php
        if (count($usuarios)) {
            ?>
            <label for="usuario_designado">Designated user:</label>
            <select id="usuario_designado" class="form-control" name="usuario_designado">
                <?php
                foreach ($usuarios as $usuario) {
                    ?>
                    <option <?php
                    if ($usuario->obtener_id() == $cotizacion_recuperada->obtener_usuario_designado()) {
                        echo 'selected';
                    }
                    ?>><?php echo $usuario->obtener_nombre_usuario(); ?></option>
                        <?php
                    }
                    ?>
            </select>   
            <?php
        }
        ?>
    </div>
    <label>Documents:</label>
    <?php
    $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
    if (is_dir($ruta)) {
        $gestor = opendir($ruta);
        echo '<div class="list-group">';

        while (($archivo = readdir($gestor)) !== false) {

            $ruta_completa = $ruta . "/" . $archivo;
            // Se muestran todos los archivos y carpetas excepto "." y ".."
            if ($archivo != "." && $archivo != "..") {
                // Si es un directorio se recorre recursivamente
                $archivo_url = str_replace(' ', '%20', $archivo);
                echo '<a download class="list-group-item list-group-item-action" href="' . DOCS . $cotizacion_recuperada->obtener_id() . '/' . $archivo_url . '">' . $archivo . "</a>";
            }
        }

        // Cierra el gestor de directorios
        closedir($gestor);
        echo "</div>";
    }
    ?>
    <div class="form-group">
        <input type="file" name="documentos[]" multiple class="btn btn-primary btn-block">
    </div>
</div>
<div class="card-footer">
    <button type="submit" onclick="alert('Estas seguro?');" class="btn btn-primary" name="guardar_cambios_cotizacion">Save</button>
</div>

