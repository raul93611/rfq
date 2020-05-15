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
      Conexion::abrir_conexion();
      $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $quote-> obtener_usuario_designado());
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
    }
    return $canal;
  }
}
?>
