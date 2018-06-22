<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body table-responsive">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="email_code">Email-code:</label>
                <input type="text" class="form-control" id="email_code" disabled value="<?php echo $cotizacion_recuperada->obtener_email_code(); ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="type_of_bid">Type of bid:</label>
                <input type="text" class="form-control" id="email_code" disabled value="<?php echo $cotizacion_recuperada->obtener_type_of_bid(); ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="issue_date">Issue date:</label>
                <input type="text" class="form-control" id="issue_date" disabled value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="end_date">End date:</label>
                <input type="text" class="form-control" id="end_date" disabled value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
            </div>
        </div>
    </div>
    <?php
    
    if ($cotizacion_recuperada->obtener_completado()) {
        Conexion::abrir_conexion();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
        Conexion::cerrar_conexion();
        ?>
        <label for="usuario_designado">Designated user:</label>
        <input type="text" class="form-control" value="<?php echo $usuario->obtener_nombre_usuario(); ?>" disabled>
        <?php
    } else {
        ?>
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
        <?php
    }
    ?>

    <label>Documents:</label>
    <?php
    $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
    if (is_dir($ruta)) {
        $gestor = opendir($ruta);
        echo '<div class="list-group">';
        while (($archivo = readdir($gestor)) !== false) {
            $ruta_completa = $ruta . "/" . $archivo;
            if ($archivo != "." && $archivo != "..") {
                $archivo_url = str_replace(' ', '%20', $archivo);
                echo '<a download class="list-group-item list-group-item-action" href="' . DOCS . $cotizacion_recuperada->obtener_id() . '/' . $archivo_url . '">' . $archivo . "</a>";
            }
        }
        closedir($gestor);
        echo "</div>";
    }
    RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
    switch ($cotizacion_recuperada->obtener_canal()) {
        case 'GSA-Buy':
            $canal = 'gsa_buy';
            break;
        case 'FedBid':
            $canal = 'fedbid';
            break;
        case 'E-mails':
            $canal = 'emails';
            break;
        case 'FindFRP':
            $canal = 'findfrp';
            break;
        case 'Embassies':
            $canal = 'embassies';
            break;
        case 'FBO':
            $canal = 'fbo';
            break;
    }
    ?>
</div>
<div class="card-footer">
    <button type="submit" onclick="alert('Estas seguro?');" class="btn btn-primary" name="guardar_cambios_cotizacion">Save</button>
    <a class="btn btn-primary float-right" href="<?php echo ADD_ITEM . '/' . $cotizacion_recuperada->obtener_id(); ?>">Add item</a>
    <?php
    if($cotizacion_recuperada-> obtener_status()){
        echo '<a class="btn btn-primary" href="' . SUBMITTED . $canal . '">Go back</a>';
    }else if($cotizacion_recuperada-> obtener_completado()){
        echo '<a class="btn btn-primary" href="' . COMPLETADOS . $canal . '">Go back</a>';
    }else {
        echo '<a class="btn btn-primary" href="' . COTIZACIONES . $canal . '">Go back</a>';
    }
    ?>
</div>