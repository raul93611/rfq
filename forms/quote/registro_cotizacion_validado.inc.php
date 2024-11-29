<div class="card-body">
  <div class="row">
    <?php $validador->mostrar_error_email_code(); ?>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" placeholder="Code" autofocus required <?php $validador->mostrar_email_code(); ?>>
        <small class="form-text text-muted">Enter the unique code for this bid or project.</small>
      </div>
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date" placeholder="Issue date" required <?php $validador->mostrar_issue_date(); ?>>
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
            <?php foreach ($usuarios as $usuario) : ?>
              <option <?= $validador->obtener_usuario_designado() == $usuario->obtener_nombre_usuario() ? 'selected' : ''; ?>><?= $usuario->obtener_nombre_usuario(); ?></option>
            <?php endforeach; ?>
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
            <option <?= $validador->obtener_type_of_bid() == $type_of_bid->get_type_of_bid() ? 'selected' : ''; ?>><?= $type_of_bid->get_type_of_bid(); ?></option>
          <?php
          }
          ?>
        </select>
        <small class="form-text text-muted">Choose the type of bid (e.g., Open, Closed, Sealed).</small>
      </div>
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" placeholder="End date" required <?php $validador->mostrar_end_date(); ?>>
        <small class="form-text text-muted">Specify the end date for this bid or opportunity.</small>
        <?php $validador->mostrar_error_end_date(); ?>
      </div>
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option <?= $validador->obtener_canal() == 'GSA-Buy' ? 'selected' : ''; ?>>GSA-Buy</option>
          <option value="FedBid" <?= $validador->obtener_canal() == 'FedBid' ? 'selected' : ''; ?>>Unison</option>
          <option <?= $validador->obtener_canal() == 'E-mails' ? 'selected' : ''; ?>>E-mails</option>
          <option <?= $validador->obtener_canal() == 'Mailbox' ? 'selected' : ''; ?>>Mailbox</option>
          <option <?= $validador->obtener_canal() == 'FindFRP' ? 'selected' : ''; ?>>FindFRP</option>
          <option <?= $validador->obtener_canal() == 'Embassies' ? 'selected' : ''; ?>>Embassies</option>
          <option value="FBO" <?= $validador->obtener_canal() == 'FBO' ? 'selected' : ''; ?>>SAM</option>
          <option <?= $validador->obtener_canal() == 'Chemonics' ? 'selected' : ''; ?>>Chemonics</option>
          <option <?= $validador->obtener_canal() == 'Ebay & Amazon' ? 'selected' : ''; ?>>Ebay & Amazon</option>
          <option <?= $validador->obtener_canal() == 'Stars III' ? 'selected' : ''; ?>>Stars III</option>
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
    <input type="file" id="archivos_crear" multiple name="documentos[]">
    <small class="form-text text-muted">Upload relevant documents for this bid or project.</small>
  </div>
</div>
<div class="card-footer">
  <button type="submit" class="btn btn-success" name="registrar_cotizacion"><i class="fa fa-check"></i> Save</button>
  <a href="<?= PERFIL; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
</div>