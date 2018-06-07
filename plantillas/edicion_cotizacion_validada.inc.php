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
    <div class="form-group">
        <label for="payment_terms">Payment terms:</label>
        <select id="payment_terms" class="form-control" name="payment_terms">
            <option <?php if($cotizacion_recuperada-> obtener_payment_terms() == 'Net 30'){echo 'selected';} ?>>Net 30</option>
            <option <?php if($cotizacion_recuperada-> obtener_payment_terms() == 'Net 30/CC'){echo 'selected';} ?>>Net 30/CC</option>
        </select>
    </div>
    <div class="form-group">
        <label for="address">Address:</label>
        <textarea class="form-control" rows="5" placeholder="Enter address ..." id="address" name="address"><?php echo $cotizacion_recuperada-> obtener_address(); ?></textarea>
    </div>
    <div class="form-group">
        <label for="ship_to">Ship to:</label>
        <textarea class="form-control" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?php echo $cotizacion_recuperada-> obtener_ship_to(); ?></textarea>
    </div>
    <div class="form-group">
        <label for="ship_via">Ship via:</label>
        <select id="ship_via" class="form-control" name="ship_via">
            <option <?php if($cotizacion_recuperada-> obtener_ship_via() == 'GROUND'){echo 'selected';} ?>>GROUND</option>
            <option <?php if($cotizacion_recuperada-> obtener_ship_via() == 'BEST WAY'){echo 'selected';} ?>>BEST WAY</option>
        </select>
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
    <br>
    <div class="form-group">
        <input type="file" name="documentos[]" multiple class="btn btn-primary btn-block">
    </div>
    <div class="form-group">
        <label for="comments">Comments:</label>
        <select id="comments" class="form-control" name="comments">
            <option <?php if($cotizacion_recuperada-> obtener_comments() == 'No comments'){echo 'selected';} ?>>No comments</option>
            <option <?php if($cotizacion_recuperada-> obtener_comments() == 'No Bid'){echo 'selected';} ?>>No Bid</option>
            <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Manufactured in the Bid'){echo 'selected';} ?>>Manufacturer in the Bid</option>
            <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Expired due date'){echo 'selected';} ?>>Expired due date</option>
            <option <?php if($cotizacion_recuperada-> obtener_comments() == 'Supplier did not provide a quote'){echo 'selected';} ?>>Supplier did not provide a quote</option>
        </select>
    </div>
    <?php
    RepositorioEquipo::escribir_equipos($cotizacion_recuperada-> obtener_id());
    ?>
    <br>
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
    <input type="hidden" name="completado_antiguo" value="<?php echo $cotizacion_recuperada-> obtener_completado(); ?>">
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
</div>
<div class="card-footer">
    <button type="submit"  onclick="alert('Estas seguro?');" class="btn btn-primary" name="guardar_cambios_cotizacion">Save</button>
</div>


