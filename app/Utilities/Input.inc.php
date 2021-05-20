<?php
class Input{
  public static function print_input_file($path){
    ?>
    <label>Documents:</label>
    <?php
    if (is_dir($path)) {
      $gestor = opendir($path);
      $carpeta = @scandir($path);
      if(count($carpeta) <= 2){
      }
      $archivos = [];
      while (($archivo = readdir($gestor)) !== false) {
        $ruta_completa = $path . "/" . $archivo;
        if ($archivo != "." && $archivo != "..") {
          $archivos[] = $archivo;
        }
      }
      $archivos = implode(',', $archivos);
      closedir($gestor);
      ?>
      <input type="hidden" id="archivos" value="<?php echo $archivos; ?>">
      <?php
    }
    ?>
    <input type="file" id="archivos_ejemplo" multiple name="archivos_ejemplo[]">
    <?php
  }

  public static function print_designated_user($quote){
    if ($quote->obtener_completado() || $quote-> obtener_status()) {
      Database::open_connection();
      $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote-> obtener_usuario_designado());
      Database::close_connection();
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
        Database::open_connection();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Database::get_connection());
        Database::close_connection();
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
    Database::open_connection();
    $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Database::get_connection());
    Database::close_connection();
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
        $canal = 'findrfp';
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
    return $canal;
  }

  public static function save_files($path, $files, $temp_files){
    mkdir($path, 0777);
    $documents = array_filter($files);
    $total = count($documents);
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
}
?>
