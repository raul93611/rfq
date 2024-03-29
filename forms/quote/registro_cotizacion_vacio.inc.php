<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" placeholder="Code" autofocus required>
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" placeholder="Issue date" required>
      </div>
      <div class="form-group">
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        if (count($usuarios)) {
        ?>
          <label for="usuario_designado">Designated user:</label>
          <select id="usuario_designado" class="form-control form-control-sm" name="usuario_designado">
            <?php
            foreach ($usuarios as $usuario) {
            ?>
              <option><?php echo $usuario->obtener_nombre_usuario(); ?></option>
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
          <?php
          Conexion::abrir_conexion();
          $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
          Conexion::cerrar_conexion();
          foreach ($type_of_bids as $key => $type_of_bid) {
          ?>
            <option><?php echo $type_of_bid->get_type_of_bid(); ?></option>
          <?php
          }
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" placeholder="End date" required>
      </div>
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option>GSA-Buy</option>
          <option value="FedBid">Unison</option>
          <option>E-mails</option>
          <option>Embassies</option>
          <option value="FBO">SAM</option>
          <option>Ebay & Amazon</option>
          <option>Seaport</option>
          <option>Stars III</option>
        </select>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="documents">Upload documents:</label><br>
    <input type="file" id="archivos_crear" multiple name="documentos[]">
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="registrar_cotizacion"><i class="fa fa-check"></i> Save</button>
  <a href="<?php echo PERFIL; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>