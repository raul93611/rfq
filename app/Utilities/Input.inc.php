<?php
class Input{
  public static function print_designated_user($quote){
    if ($quote->obtener_completado() || $quote-> obtener_status()) {
      Conexion::abrir_conexion();
      $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote-> obtener_usuario_designado());
      Conexion::cerrar_conexion();
      ?>
      <label for="usuario_designado">Designated user:</label>
      <input type="text" name="usuario_designado" class="form-control form-control-sm" value="<?php echo $usuario->obtener_nombre_usuario(); ?>" readonly>
      <input type="hidden" name="designated_user_original" value="<?php echo $usuario->obtener_nombre_usuario(); ?>">
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
              if ($usuario->obtener_id() == $quote->obtener_usuario_designado()) {
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
  }

  public static function get_designated_user($quote){
    Conexion::abrir_conexion();
    $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    foreach ($usuarios as $usuario) {
      if ($usuario->obtener_id() == $quote->obtener_usuario_designado()) {
        $designated_user = $usuario-> obtener_nombre_usuario();
      }
    }

    return $designated_user;
  }

  public static function translate_channel($channel){
    switch ($channel) {
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
      case 'Stars III':
        $canal = 'starsiii';
        break;
    }
    return $canal;
  }

  public static function inverse_translate_channel($channel){
    switch ($channel) {
      case 'gsa_buy':
        $canal = 'GSA-Buy';
        break;
      case 'fedbid':
        $canal = 'FedBid';
        break;
      case 'emails':
        $canal = 'E-mails';
        break;
      case 'mailbox':
        $canal = 'Mailbox';
        break;
      case 'findfrp':
        $canal = 'FindFRP';
        break;
      case 'embassies':
        $canal = 'Embassies';
        break;
      case 'fbo':
        $canal = 'FBO';
        break;
      case 'chemonics':
        $canal = 'Chemonics';
        break;
      case 'ebay_amazon':
        $canal = 'Ebay & Amazon';
        break;
      case 'starsiii':
        $canal = 'Stars III';
        break;
    }
    return $canal;
  }

  public static function save_files($path, $files, $temp_files){
    mkdir($path, 0777);
    $documentos = array_filter($files);
    $total = count($documentos);
    for ($i = 0; $i < $total; $i++) {
      $tmp_path = $temp_files[$i];
      $file = $files[$i];
      if ($tmp_path != '') {
        $file = preg_replace('/[^a-z0-9-_\-\.]/i','_',$file);
        $new_path = $path . '/' . $file;
        move_uploaded_file($tmp_path, $new_path);
      }
    }
  }

  public static function copy_files($initial_path, $destination_path){
    mkdir($destination_path, 0777);
    if(is_dir($initial_path)){
      $manager = opendir($initial_path);
      $folder = @scandir($initial_path);
      while(($file = readdir($manager)) !== false){
        if($file != '.' && $file != '..'){
          copy($initial_path . '/' . $file, $destination_path . '/' . $file);
        }
      }
      closedir($manager);
    }
  }

  public static function print_year_select($year){
    ?>
    <select name="year" class="custom-select">
    <?php
    for ($i=2018; $i<=2100; $i++) {
      ?>
      <option <?php echo $year == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
      <?php
    }
    ?>
    </select>
    <?php
  }
}
?>
