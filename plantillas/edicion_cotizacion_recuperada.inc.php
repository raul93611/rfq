<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body table-responsive">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="email_code">Code:</label>
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
    <div class="row">
      <div class="col">
        <div class="form-group">
            <label for="id">Proposal:</label>
            <input type="text" class="form-control" id="end_date" disabled value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
        </div>
      </div>
      <div class="col">
        <?php

        if ($cotizacion_recuperada->obtener_completado() || $cotizacion_recuperada-> obtener_status()) {
            Conexion::abrir_conexion();
            $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
            Conexion::cerrar_conexion();
            ?>
            <label for="usuario_designado">Designated user:</label>
            <input type="text" class="form-control" value="<?php echo $usuario->obtener_nombre_usuario(); ?>" disabled>
            <input type="hidden" value="<?php echo $usuario-> obtener_nombre_usuario(); ?>" name="usuario_designado">
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
      </div>
      <div class="col">
        <div class="form-group">
            <label for="completed_date">Completed date:</label>
            <input type="text" class="form-control" id="completed_date" name="completed_date"
            <?php
            if($cotizacion_recuperada->obtener_fecha_completado() != '0000-00-00'){
              $fecha_completado_formato = date('m/d/Y', strtotime($cotizacion_recuperada->obtener_fecha_completado()));
              echo 'value="' . $fecha_completado_formato . '"';
            }
            ?>>
        </div>
      </div>
      <div class="col">
        <div class="form-group">
            <label for="expiration_date">Expiration date:</label>
            <input type="text" class="form-control" id="expiration_date" name="expiration_date"
            <?php
            if($cotizacion_recuperada->obtener_expiration_date() != '0000-00-00'){
              $expiration_date_formato = date('m/d/Y', strtotime($cotizacion_recuperada->obtener_expiration_date()));
              echo 'value="' . $expiration_date_formato . '"';
            }
            ?>>
        </div>
      </div>
    </div>


    <label>Documents:</label>
    <?php
    $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
    if (is_dir($ruta)) {
        $gestor = opendir($ruta);
        echo '<div class="list-group">';
        $carpeta = @scandir($ruta);
        if(count($carpeta) <= 2){
          echo '<h3 class="text-center">No files!</h3>';
        }
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
    ?>
    <br>
    <div class="form-group">
        <input type="file" name="documentos[]" multiple class="btn btn-secondary btn-block">
    </div>
    <?php
    RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
    Conexion::abrir_conexion();
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
    Conexion::cerrar_conexion();
    if(count($items)){
      ?>
      <br>
      <div class="row">
        <label for="shipping">Shipping:</label>
        <div class="col">
          <div class="form-group">
              <input type="text" class="form-control" id="shipping" name="shipping" value="<?php echo $cotizacion_recuperada->obtener_shipping(); ?>">
          </div>
        </div>
        <div class="col">
          <div class="form-group">
              <input type="number" step=".01" class="form-control" id="shipping_cost" name="shipping_cost" value="<?php echo $cotizacion_recuperada->obtener_shipping_cost(); ?>">
          </div>
        </div>
      </div>
      <?php
    }
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
<!--</div>-->
<hr>
<!--<div class="card-footer">

    <?php
    /*if($cotizacion_recuperada-> obtener_award()){
        echo '<a class="btn btn-primary" href="' . AWARD . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
    }else if($cotizacion_recuperada-> obtener_status()){
        echo '<a class="btn btn-primary" href="' . SUBMITTED . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
    }else if($cotizacion_recuperada-> obtener_completado()){
        echo '<a class="btn btn-primary" href="' . COMPLETADOS . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
    }else {
        echo '<a class="btn btn-primary" href="' . COTIZACIONES . $canal . '"><i class="fa fa-backward"></i> Go back</a>';
    }*/
    ?>
</div>-->
