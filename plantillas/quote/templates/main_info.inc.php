<div class="row">
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-4">
        <b>Contract Number:</b>
      </div>
      <div class="col-md-8">
        <?= $cotizacion_recuperada->obtener_contract_number(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Code:</b>
      </div>
      <div class="col-md-8">
        <?= $cotizacion_recuperada->obtener_email_code(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Channel:</b>
      </div>
      <div class="col-md-8">
        <?= $cotizacion_recuperada->print_channel(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Designated User:</b>
      </div>
      <div class="col-md-8">
        <?= $usuario_designado->getFullName(); ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <b>Client:</b>
      </div>
      <div class="col-md-8">
        <?= $cotizacion_recuperada->obtener_client(); ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="row">
      <div class="col-md-3">
        <b>Address:</b>
      </div>
      <div class="col-md-9">
        <?= nl2br($cotizacion_recuperada->obtener_address()); ?>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3">
        <b>Ship To:</b>
      </div>
      <div class="col-md-9">
        <?= nl2br($cotizacion_recuperada->obtener_ship_to()); ?>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 mt-2">
    <a href="<?= CHECKLIST . $cotizacion_recuperada->obtener_id(); ?>" class="btn btn-primary"><i class="fas fa-clipboard-list"></i> Checklist</a>
  </div>
  <div class="col-md-12 mt-2">
    <a href="<?= INFORMATION . $cotizacion_recuperada->obtener_id(); ?>" class="btn btn-primary"><i class="fas fa-clipboard-list"></i> Information</a>
  </div>
</div>