<?php
class Input {
  public static function print_designated_user($quote) {
    if ($quote->obtener_completado() || $quote->obtener_status()) {
      Conexion::abrir_conexion();
      $usuario = self::get_designated_user($quote);
      Conexion::cerrar_conexion();
?>
      <div class="form-group">
        <label for="usuario_designado">Designated user:</label>
        <input type="text" name="usuario_designado" class="form-control form-control-sm" value="<?= $usuario; ?>" readonly>
        <input type="hidden" name="designated_user_original" value="<?= $usuario; ?>">
      </div>
    <?php
    } else {
    ?>
      <div class="form-group">
        <?php
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        $designated_user = self::get_designated_user($quote);
        Conexion::cerrar_conexion();
        ?>
        <?php
        if (count($usuarios)) {
        ?>
          <label for="usuario_designado">Designated user:</label>
          <input type="hidden" name="designated_user_original" value="<?= $designated_user; ?>">
          <select id="usuario_designado" class="form-control form-control-sm" name="usuario_designado">
            <?php
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
        <?php
        }
        ?>
      </div>
    <?php
    }
  }

  public static function get_designated_user($quote) {
    Conexion::abrir_conexion();
    $usuarios = RepositorioUsuario::getAllRfqUsers(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
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
    <select name="year" class="custom-select">
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
    $data = htmlspecialchars($data);
    return $data;
  }
}
?>