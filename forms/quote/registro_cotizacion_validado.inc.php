<div class="card-body user-form">
  <div class="row">
    <?php $validador->mostrar_error_email_code(); ?>
  </div>

  <div class="form-group">
    <label for="name" style="font-weight:600;">Description</label>
    <textarea class="form-control form-control-lg" id="name" name="name" rows="3"
              placeholder="e.g. Westpine Middle School AV Refresh (upgrade and installation of audio-visual equipment across 3 classrooms)"><?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
    <small class="form-text text-muted">
      <i class="fas fa-table mr-1" style="color:#2db4e8;"></i>Synced to the SharePoint sheet — include a short title and scope summary.
    </small>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="email_code">Code</label>
        <input type="text" class="form-control" id="email_code" name="email_code" placeholder="e.g. BID-2026-001" autofocus required <?php $validador->mostrar_email_code(); ?>>
        <small class="form-text text-muted">Unique code for this bid or project.</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="type_of_bid">Type of Bid</label>
        <select class="form-control" name="type_of_bid" id="type_of_bid">
          <?php
          Conexion::abrir_conexion();
          $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
          Conexion::cerrar_conexion();
          foreach ($type_of_bids as $type_of_bid) : ?>
            <option <?= $validador->obtener_type_of_bid() == $type_of_bid->get_type_of_bid() ? 'selected' : ''; ?>><?= $type_of_bid->get_type_of_bid(); ?></option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Open, Closed, Sealed, etc.</small>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="issue_date">Issue Date</label>
        <input type="text" class="date form-control" id="issue_date" name="issue_date" placeholder="MM/DD/YYYY" required <?php $validador->mostrar_issue_date(); ?>>
        <small class="form-text text-muted">Date the bid was issued.</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="end_date">End Date</label>
        <input type="text" class="form-control" id="end_date" name="end_date" placeholder="MM/DD/YYYY" required <?php $validador->mostrar_end_date(); ?>>
        <small class="form-text text-muted">Deadline for this bid.</small>
        <?php $validador->mostrar_error_end_date(); ?>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        if (count($usuarios)) : ?>
          <label for="usuario_designado">Designated User</label>
          <select id="usuario_designado" class="form-control" name="usuario_designado">
            <?php foreach ($usuarios as $usuario) : ?>
              <option <?= $validador->obtener_usuario_designado() == $usuario->obtener_nombre_usuario() ? 'selected' : ''; ?>><?= $usuario->obtener_nombre_usuario(); ?></option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">User responsible for this bid.</small>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="canal">Channel</label>
        <select class="form-control" name="canal" id="canal">
          <option <?= $validador->obtener_canal() == 'GSA-Buy'       ? 'selected' : ''; ?>>GSA-Buy</option>
          <option value="FedBid" <?= $validador->obtener_canal() == 'FedBid'    ? 'selected' : ''; ?>>Unison</option>
          <option <?= $validador->obtener_canal() == 'E-mails'       ? 'selected' : ''; ?>>E-mails</option>
          <option <?= $validador->obtener_canal() == 'Mailbox'       ? 'selected' : ''; ?>>Mailbox</option>
          <option <?= $validador->obtener_canal() == 'FindFRP'       ? 'selected' : ''; ?>>FindFRP</option>
          <option <?= $validador->obtener_canal() == 'Embassies'     ? 'selected' : ''; ?>>Embassies</option>
          <option value="FBO" <?= $validador->obtener_canal() == 'FBO'          ? 'selected' : ''; ?>>SAM</option>
          <option <?= $validador->obtener_canal() == 'Chemonics'     ? 'selected' : ''; ?>>Chemonics</option>
          <option <?= $validador->obtener_canal() == 'Ebay & Amazon' ? 'selected' : ''; ?>>Ebay &amp; Amazon</option>
          <option <?= $validador->obtener_canal() == 'Stars III'     ? 'selected' : ''; ?>>Stars III</option>
        </select>
        <small class="form-text text-muted">Platform through which this bid was received.</small>
      </div>
    </div>
  </div>

  <div class="form-row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="reference_url">Reference URL</label>
        <input type="text" class="form-control" id="reference_url" name="reference_url" placeholder="https://...">
        <small class="form-text text-muted">Link to the bid or opportunity source.</small>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="priority_level">Priority Level</label>
        <select class="form-control" name="priority_level" id="priority_level">
          <?php
          Conexion::abrir_conexion();
          $priority_levels = PriorityLevelRepository::getAll(Conexion::obtener_conexion());
          Conexion::cerrar_conexion();
          foreach ($priority_levels as $priority_level) : ?>
            <option value="<?= $priority_level->getWeight(); ?>"><?= $priority_level->getName(); ?></option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">High, Medium, Low, etc.</small>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label>Documents</label>
    <input type="file" id="archivos_crear" multiple name="documentos[]">
    <small class="form-text text-muted">Upload relevant documents for this bid.</small>
  </div>
</div>

<div class="card-footer d-flex justify-content-end" style="gap:8px;background:transparent;border-top:1px solid #f0f2f5;">
  <a href="<?= PERFIL; ?>" class="btn btn-secondary btn-sm"><i class="fa fa-times mr-1"></i>Cancel</a>
  <button type="submit" class="btn btn-primary btn-sm" name="registrar_cotizacion"><i class="fa fa-check mr-1"></i>Save Quote</button>
</div>
