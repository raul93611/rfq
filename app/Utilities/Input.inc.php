<?php
class Input {
  public static function print_designated_user($quote, $idPrefix = '') {
    ?>
      <?php $needsOwner = method_exists($quote, 'getPartnerNeedsAssignment') && $quote->getPartnerNeedsAssignment(); ?>
      <div class="form-group<?= $needsOwner ? ' pp-owner' : ''; ?>" <?= $needsOwner ? 'data-pp-owner' : ''; ?>>
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        $designated_user = self::get_designated_user($quote);
        // Partner quotes start on the default owner, who may not hold the RFQ role and so is not in
        // $usuarios. Resolve the name directly so the "original" value (and the option below) are real.
        if ($needsOwner && $designated_user === '') {
          $currentOwner = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote->obtener_usuario_designado());
          $designated_user = $currentOwner ? $currentOwner->obtener_nombre_usuario() : '';
        }
        Conexion::cerrar_conexion();
        ?>
        <?php
        if (count($usuarios)) {
        ?>
          <label for="<?= $idPrefix; ?>usuario_designado">Designated user:</label>
          <input type="hidden" name="designated_user_original" value="<?= $designated_user; ?>">
          <select id="<?= $idPrefix; ?>usuario_designado" class="form-control form-control-sm" name="usuario_designado">
            <?php
            // Partner quotes start on the default owner, who may not hold the RFQ role. Keep them
            // selectable so saving without choosing does not silently hand the quote to the first user.
            if ($needsOwner && $designated_user !== '' && !in_array($quote->obtener_usuario_designado(), array_map(function ($u) { return $u->obtener_id(); }, $usuarios))) {
              echo '<option selected>' . htmlspecialchars($designated_user, ENT_QUOTES, 'UTF-8') . '</option>';
            }
            foreach ($usuarios as $usuario) {
            ?>
              <option <?php
                      if ($usuario->obtener_id() == $quote->obtener_usuario_designado()) {
                        echo 'selected';
                      }
                      ?>><?= $usuario->obtener_nombre_usuario(); ?></option>
            <?php
            }
            ?>
          </select>
          <?php if ($needsOwner): ?>
            <div class="pp-hint"><i class="fas fa-exclamation-triangle"></i>Auto-assigned to the default owner. Choose who should own this quote.</div>
          <?php endif; ?>
        <?php
        }
        ?>
      </div>
    <?php
  }

  public static function get_designated_user($quote) {
    Conexion::abrir_conexion();
    $usuarios = RepositorioUsuario::getAllRfqUsers(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    $designated_user = '';
    foreach ($usuarios as $usuario) {
      if ($usuario->obtener_id() == $quote->obtener_usuario_designado()) {
        $designated_user = $usuario->obtener_nombre_usuario();
      }
    }

    return $designated_user;
  }

  public static function printable_channel($channel) {
    $canal = $channel;
    switch ($channel) {
      case 'FedBid':
        $canal = 'Unison';
        break;
      case 'FBO':
        $canal = 'SAM';
        break;
    }
    return $canal;
  }

  public static function sanitize_filename($filename) {
    return preg_replace('/[^a-z0-9-_\-\.]/i', '_', $filename);
  }

  public static function save_files($path, $files, $id_rfq) {
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }
    $documentos = array_filter($files['name']);
    $total = count($documentos);
    for ($i = 0; $i < $total; $i++) {
      $tmp_path = $files['tmp_name'][$i];
      $file = $files['name'][$i];
      if ($tmp_path != '') {
        $file = preg_replace('/[^a-z0-9-_\-\.]/i', '_', $file);
        $new_path = $path . '/' . $file;
        move_uploaded_file($tmp_path, $new_path);
      }
      if (!empty($file)) {
        Conexion::abrir_conexion();
        AuditTrailRepository::document_updated(Conexion::obtener_conexion(), 'uploaded', $file, $id_rfq);
        Conexion::cerrar_conexion();
      }
    }
  }

  public static function copy_files($initial_path, $destination_path) {
    mkdir($destination_path, 0777);
    if (is_dir($initial_path)) {
      $manager = opendir($initial_path);
      $folder = @scandir($initial_path);
      while (($file = readdir($manager)) !== false) {
        if ($file != '.' && $file != '..') {
          copy($initial_path . '/' . $file, $destination_path . '/' . $file);
        }
      }
      closedir($manager);
    }
  }

  public static function print_year_select($year) {
    ?>
    <select name="year" class="custom-select custom-select-sm">
      <?php
      for ($i = 2018; $i <= 2100; $i++) {
      ?>
        <option <?= $year == $i ? 'selected' : ''; ?>><?= $i; ?></option>
      <?php
      }
      ?>
    </select>
<?php
  }

  public static function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
  }
}
?>