<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" placeholder="Code" autofocus required <?php $validador->mostrar_email_code(); ?>>
        <?php $validador->mostrar_error_email_code(); ?>
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="form-control form-control-sm" id="issue_date" name="issue_date" placeholder="Issue date" required <?php $validador-> mostrar_issue_date(); ?>>
        <?php $validador->mostrar_error_issue_date(); ?>
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
          <select id="usuario_designado" class="form-control form-control-sm" name="usuario_designado">
            <?php
            foreach ($usuarios as $usuario) {
              ?>
              <option <?php if($validador-> obtener_usuario_designado() == $usuario-> obtener_nombre_usuario()){echo 'selected';} ?>><?php echo $usuario->obtener_nombre_usuario(); ?></option>
              <?php
            }
            ?>
          </select>
          <?php
        }
        ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
          <option <?php if($validador-> obtener_type_of_bid() == 'Audio Visual'){echo 'selected';} ?>>Audio Visual</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Back up Batteries'){echo 'selected';} ?>>Back up Batteries</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Cameras'){echo 'selected';} ?>>Cameras</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Computer Peripherals'){echo 'selected';} ?>>Computer Peripherals</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Computers'){echo 'selected';} ?>>Computers</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Medical'){echo 'selected';} ?>>Medical</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Miscellaneous'){echo 'selected';} ?>>Miscellaneous</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Monitors & Televisions'){echo 'selected';} ?>>Monitors & Televisions</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Office Supplies'){echo 'selected';} ?>>Office Supplies</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Peripherals'){echo 'selected';} ?>>Peripherals</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Portable Radios'){echo 'selected';} ?>>Portable Radios</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Printers'){echo 'selected';} ?>>Printers</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Servers'){echo 'selected';} ?>>Servers</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Software'){echo 'selected';} ?>>Software</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Tactical'){echo 'selected';} ?>>Tactical</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Tools'){echo 'selected';} ?>>Tools</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Scanners'){echo 'selected';} ?>>Scanners</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Projectors'){echo 'selected';} ?>>Projectors</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Video Cameras'){echo 'selected';} ?>>Video Cameras</option>
          <option <?php if($validador-> obtener_type_of_bid() == 'Phones'){echo 'selected';} ?>>Phones</option>
        </select>
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" placeholder="End date" required <?php $validador-> mostrar_end_date(); ?>>
        <?php $validador->mostrar_error_end_date(); ?>
      </div>
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option <?php if($validador-> obtener_canal() == 'GSA-Buy'){echo 'selected';} ?>>GSA-Buy</option>
          <option <?php if($validador-> obtener_canal() == 'FedBid'){echo 'selected';} ?>>FedBid</option>
          <option <?php if($validador-> obtener_canal() == 'E-mails'){echo 'selected';} ?>>E-mails</option>
          <option <?php if($validador-> obtener_canal() == 'Mailbox'){echo 'selected';} ?>>Mailbox</option>
          <option <?php if($validador-> obtener_canal() == 'FindFRP'){echo 'selected';} ?>>FindFRP</option>
          <option <?php if($validador-> obtener_canal() == 'Embassies'){echo 'selected';} ?>>Embassies</option>
          <option <?php if($validador-> obtener_canal() == 'FBO'){echo 'selected';} ?>>FBO</option>
          <option <?php if($validador-> obtener_canal() == 'Chemonics'){echo 'selected';} ?>>Chemonics</option>
          <option <?php if($validador-> obtener_canal() == 'Ebay & Amazon'){echo 'selected';} ?>>Ebay & Amazon</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <input type="file" id="archivos_crear" multiple name="documentos[]">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="registrar_cotizacion"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo PERFIL; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>
