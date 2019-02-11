<?php
if($cargo == 5 && $_SESSION['id_usuario'] != $cotizacion_recuperada-> obtener_usuario_designado()){
  Redireccion::redirigir1(PERFIL);
}
?>
<input type="hidden" name="id_rfq" value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
<div class="card-body">
  <?php
  if($cotizacion_recuperada-> obtener_award()){
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="contract_number">Contract number:</label>
          <input type="text" class="form-control form-control-sm" name="contract_number" value="<?php echo $cotizacion_recuperada-> obtener_contract_number(); ?>">
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="row">
    <div class="col-md-3">
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" value="<?php echo $cotizacion_recuperada->obtener_email_code(); ?>">
        <input type="hidden" name="email_code_original" value="<?php echo $cotizacion_recuperada-> obtener_email_code(); ?>">
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="type_of_bid">Type of bid:</label>
        <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Audio Visual'){echo 'selected';} ?>>Audio Visual</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Back up Batteries'){echo 'selected';} ?>>Back up Batteries</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Cameras'){echo 'selected';} ?>>Cameras</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Computer Peripherals'){echo 'selected';} ?>>Computer Peripherals</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Computers'){echo 'selected';} ?>>Computers</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Medical'){echo 'selected';} ?>>Medical</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Miscellaneous'){echo 'selected';} ?>>Miscellaneous</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Monitors & Televisions'){echo 'selected';} ?>>Monitors & Televisions</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Office Supplies'){echo 'selected';} ?>>Office Supplies</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Peripherals'){echo 'selected';} ?>>Peripherals</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Portable Radios'){echo 'selected';} ?>>Portable Radios</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Printers'){echo 'selected';} ?>>Printers</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Servers'){echo 'selected';} ?>>Servers</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Software'){echo 'selected';} ?>>Software</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Tactical'){echo 'selected';} ?>>Tactical</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Tools'){echo 'selected';} ?>>Tools</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Scanners'){echo 'selected';} ?>>Scanners</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Projectors'){echo 'selected';} ?>>Projectors</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Video Cameras'){echo 'selected';} ?>>Video Cameras</option>
          <option <?php if($cotizacion_recuperada-> obtener_type_of_bid() == 'Phones'){echo 'selected';} ?>>Phones</option>
        </select>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="issue_date">Issue date:</label>
        <input type="text" class="form-control form-control-sm" id="issue_date" name="issue_date" value="<?php echo $cotizacion_recuperada->obtener_issue_date(); ?>">
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="end_date">End date:</label>
        <input type="text" class="form-control form-control-sm" id="end_date" name="end_date" value="<?php echo $cotizacion_recuperada->obtener_end_date(); ?>">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="
    <?php
    if($cotizacion_recuperada-> obtener_rfp()){
      echo 'col-md-3';
    }else{
      echo 'col-md-4';
    }
    ?>
    ">
      <div class="form-group">
        <label for="id">Proposal:</label>
        <input type="text" class="form-control form-control-sm" disabled value="<?php echo $cotizacion_recuperada->obtener_id(); ?>">
      </div>
    </div>
    <div class="
    <?php
    if($cotizacion_recuperada-> obtener_rfp()){
      echo 'col-md-3';
    }else{
      echo 'col-md-4';
    }
    ?>
    ">
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'GSA-Buy'){echo 'selected';} ?>>GSA-Buy</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FedBid'){echo 'selected';} ?>>FedBid</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'E-mails'){echo 'selected';} ?>>E-mails</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Mailbox'){echo 'selected';} ?>>Mailbox</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FindFRP'){echo 'selected';} ?>>FindFRP</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Embassies'){echo 'selected';} ?>>Embassies</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'FBO'){echo 'selected';} ?>>FBO</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Chemonics'){echo 'selected';} ?>>Chemonics</option>
          <option <?php if($cotizacion_recuperada-> obtener_canal() == 'Ebay & Amazon'){echo 'selected';} ?>>Ebay & Amazon</option>
        </select>
      </div>
    </div>
    <div class="
    <?php
    if($cotizacion_recuperada-> obtener_rfp()){
      echo 'col-md-3';
    }else{
      echo 'col-md-4';
    }
    ?>
    ">
      <?php

      if ($cotizacion_recuperada->obtener_completado() || $cotizacion_recuperada-> obtener_status()) {
        Conexion::abrir_conexion();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion_recuperada-> obtener_usuario_designado());
        Conexion::cerrar_conexion();
        ?>
        <label for="usuario_designado">Designated user:</label>
        <input type="text" class="form-control form-control-sm" value="<?php echo $usuario->obtener_nombre_usuario(); ?>" disabled>
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
            <select id="usuario_designado" class="form-control form-control-sm" name="usuario_designado">
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
    <?php
    if($cotizacion_recuperada-> obtener_rfp()){
      ?>
      <div class="col-md-3">
        <div class="form-group">
          <label for="rfp">Proposal RFP:</label>
          <input type="text" class="form-control form-control-sm" disabled value="<?php echo $cotizacion_recuperada-> obtener_rfp(); ?>">
        </div>
      </div>
      <?php
    }
    ?>
  </div>
  <label>Documents:</label>
  <?php
  $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion_recuperada->obtener_id();
  if (is_dir($ruta)) {
    $gestor = opendir($ruta);
    $carpeta = @scandir($ruta);
    if(count($carpeta) <= 2){
    }
    $archivos = [];
    while (($archivo = readdir($gestor)) !== false) {
      $ruta_completa = $ruta . "/" . $archivo;
      if ($archivo != "." && $archivo != "..") {
        $archivos[] = $archivo;
      }
    }
    $archivos = implode(',', $archivos);
    ?>
    <input type="hidden" id="archivos" value="<?php echo $archivos; ?>">
    <?php
    closedir($gestor);
  }
  ?>
  <input type="file" id="archivos_ejemplo" multiple name="archivos_ejemplo[]">
  <br>
  <?php
  RepositorioItem::escribir_items($cotizacion_recuperada->obtener_id());
  Conexion::abrir_conexion();
  $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $cotizacion_recuperada->obtener_id());
  Conexion::cerrar_conexion();
  if(count($items)){
    ?>
    <br>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <textarea class="form-control form-control-sm" rows="3" id="shipping" name="shipping" placeholder="Enter shipping ..."><?php echo $cotizacion_recuperada->obtener_shipping(); ?></textarea>
          <input type="hidden" name="shipping_original" value="<?php echo $cotizacion_recuperada->obtener_shipping(); ?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="<?php echo $cotizacion_recuperada->obtener_shipping_cost(); ?>">
          <input type="hidden" name="shipping_cost_original" value="<?php echo $cotizacion_recuperada->obtener_shipping_cost(); ?>">
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
    case 'Mailbox':
      $canal = 'mailbox';
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
    case 'Chemonics':
      $canal = 'chemonics';
      break;
    case 'Ebay & Amazon':
      $canal = 'ebay_amazon';
      break;
  }
  ?>
