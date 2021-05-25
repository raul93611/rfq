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
    if ($quote->get_complete() || $quote-> get_submitted()) {
      Database::open_connection();
      $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote-> get_assigned_user());
      Database::close_connection();
      ?>
      <label for="assigned_user">Designated user:</label>
      <input type="text" name="assigned_user" class="form-control form-control-sm" value="<?php echo $usuario->obtener_nombre_usuario(); ?>" readonly>
      <input type="hidden" name="designated_user_original" value="<?php echo $usuario->obtener_nombre_usuario(); ?>">
      <input type="hidden" value="<?php echo $usuario-> obtener_nombre_usuario(); ?>" name="assigned_user">
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
          <label for="assigned_user">Designated user:</label>
          <select id="assigned_user" class="form-control form-control-sm" name="assigned_user">
            <?php
            foreach ($usuarios as $usuario) {
              ?>
              <option <?php
              if ($usuario->get_id() == $quote->get_assigned_user()) {
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
      if ($usuario->get_id() == $quote->get_assigned_user()) {
        $assigned_user = $usuario-> obtener_nombre_usuario();
      }
    }

    return $assigned_user;
  }

  public static function translate_channel($channel){
    switch ($channel) {
      case 'GSA-Buy':
        $channel = 'gsa_buy';
        break;
      case 'FedBid':
        $channel = 'fedbid';
        break;
      case 'E-mails':
        $channel = 'emails';
        break;
      case 'Mailbox':
        $channel = 'mailbox';
        break;
      case 'FindFRP':
        $channel = 'findrfp';
        break;
      case 'Embassies':
        $channel = 'embassies';
        break;
      case 'FBO':
        $channel = 'fbo';
        break;
      case 'Chemonics':
        $channel = 'chemonics';
        break;
      case 'Ebay & Amazon':
        $channel = 'ebay_amazon';
        break;
    }
    return $channel;
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
