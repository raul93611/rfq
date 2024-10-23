<div class="card-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" placeholder="Code" autofocus required>
        <small class="form-text text-muted">Enter the unique code for this bid or project.</small>
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" placeholder="Issue date" required>
        <small class="form-text text-muted">Specify the date when this bid was issued.</small>
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
              <option><?= $usuario->obtener_nombre_usuario(); ?></option>
            <?php
            }
            ?>
          </select>
          <small class="form-text text-muted">Select the user responsible for managing this bid.</small>
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
            <option><?= $type_of_bid->get_type_of_bid(); ?></option>
          <?php
          }
          ?>
        </select>
        <small class="form-text text-muted">Choose the type of bid (e.g., Open, Closed, Sealed).</small>
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" placeholder="End date" required>
        <small class="form-text text-muted">Specify the end date for this bid or opportunity.</small>
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
        <small class="form-text text-muted">Select the platform or channel through which this bid was received.</small>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="reference_url">Reference URL:</label>
    <input type="text" class="form-control form-control-sm" id="reference_url" name="reference_url" placeholder="Reference URL">
    <small class="form-text text-muted">Provide the URL to reference the bid or opportunity.</small>
  </div>
  <div class="form-group">
    <label for="documents">Upload documents:</label><br>
    <input type="file" id="archivos_crear" multiple name="documentos[]">
    <small class="form-text text-muted">Upload relevant documents for this bid or project.</small>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="registrar_cotizacion"><i class="fa fa-check"></i> Save</button>
  <a href="<?= PERFIL; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>