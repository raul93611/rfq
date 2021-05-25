<?php
class RepositorioUsuario {
  public static function insertar_usuario($database, $usuario) {
    $usuario_insertado = false;
    if (isset($database)) {
      try {
        $sql = 'INSERT INTO usuarios(nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES(:nombre_usuario, :password, :nombres, :apellidos, :cargo, :email, :status, :hash_recover_email)';
        $query = $database->prepare($sql);
        $query->bindParam(':nombre_usuario', $usuario->obtener_nombre_usuario(), PDO::PARAM_STR);
        $query->bindParam(':password', $usuario->obtener_password(), PDO::PARAM_STR);
        $query->bindParam(':nombres', $usuario->obtener_nombres(), PDO::PARAM_STR);
        $query->bindParam(':apellidos', $usuario->obtener_apellidos(), PDO::PARAM_STR);
        $query->bindParam(':cargo', $usuario->obtener_cargo(), PDO::PARAM_STR);
        $query->bindParam(':email', $usuario->obtener_email(), PDO::PARAM_STR);
        $query-> bindParam(':status', $usuario-> get_submitted(), PDO::PARAM_STR);
        $query-> bindParam(':hash_recover_email', $usuario-> obtener_hash_recover_email(), PDO::PARAM_STR);
        $result = $query->execute();
        if ($result) {
          $usuario_insertado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_insertado;
  }

  public static function obtener_usuario_por_nombre_usuario($database, $nombre_usuario) {
    $usuario = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $query = $database->prepare($sql);
        $query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $usuario = new Usuario($result['id'], $result['nombre_usuario'], $result['password'], $result['nombres'], $result['apellidos'], $result['cargo'], $result['email'], $result['status'], $result['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function obtener_usuario_por_email($database, $email) {
    $usuario = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE email LIKE :email";
        $query = $database->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $usuario = new Usuario($result['id'], $result['nombre_usuario'], $result['password'], $result['nombres'], $result['apellidos'], $result['cargo'], $result['email'], $result['status'], $result['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function obtener_usuario_0($database) {
    $usuario = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo = 0";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $usuario = new Usuario($result['id'], $result['nombre_usuario'], $result['password'], $result['nombres'], $result['apellidos'], $result['cargo'], $result['email'], $result['status'], $result['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function eliminar_hash_recover_email($database, $id_user){
    if(isset($database)){
      try{
        $sql = 'UPDATE usuarios SET hash_recover_email = "" WHERE id = :id_user';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_clave($database, $password, $id_user){
    if(isset($database)){
      try{
        $sql = 'UPDATE usuarios SET password = :password WHERE id = :id_user';
        $query = $database-> prepare($sql);
        $query-> bindParam(':password', $password, PDO::PARAM_STR);
        $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_usuario_por_url_secreta($database, $url_secreta) {
    $usuario = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE hash_recover_email = :hash_recover_email";
        $query = $database->prepare($sql);
        $query->bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $usuario = new Usuario($result['id'], $result['nombre_usuario'], $result['password'], $result['nombres'], $result['apellidos'], $result['cargo'], $result['email'], $result['status'], $result['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function obtener_usuario_por_id($database, $id_user) {
    $usuario = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE id = :id_user";
        $query = $database->prepare($sql);
        $query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $usuario = new Usuario($result['id'], $result['nombre_usuario'], $result['password'], $result['nombres'], $result['apellidos'], $result['cargo'], $result['email'], $result['status'], $result['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function nombre_usuario_existe($database, $nombre_usuario) {
    $nombre_usuario_existe = true;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $query = $database->prepare($sql);
        $query->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          $nombre_usuario_existe = true;
        } else {
          $nombre_usuario_existe = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $nombre_usuario_existe;
  }

  public static function email_existe($database, $email) {
    $email_existe = true;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE email LIKE :email";
        $query = $database->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          $email_existe = true;
        } else {
          $email_existe = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $email_existe;
  }

  public static function url_secreta_existe($database, $url_secreta) {
    $url_secreta_existe = true;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE hash_recover_email = :hash_recover_email";
        $query = $database->prepare($sql);
        $query->bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          $url_secreta_existe = true;
        } else {
          $url_secreta_existe = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $url_secreta_existe;
  }

  public static function guardar_url_secreta($database, $id_user, $url_secreta){
    if(isset($database)){
      try{
        $sql = 'UPDATE usuarios SET hash_recover_email = :hash_recover_email WHERE id = :id_user';
        $query = $database-> prepare($sql);
        $query-> bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function nombre_completo_existe($database, $apellidos, $nombres) {
    $nombre_completo_existe = true;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombres = :nombres AND apellidos = :apellidos";
        $query = $database->prepare($sql);
        $query->bindParam(':nombres', $nombres, PDO::PARAM_STR);
        $query->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          $nombre_completo_existe = true;
        } else {
          $nombre_completo_existe = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $nombre_completo_existe;
  }

  public static function contar_usuarios($database) {
    $total_usuarios = 0;
    if (isset($database)) {
      try {
        $sql = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE cargo != 1";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $total_usuarios = $result['total_usuarios'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total_usuarios;
  }

  public static function get_all_users_level_3($database) {
    $usuarios = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo = 3 ORDER BY id";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $usuarios [] = new Usuario($row['id'], $row['nombre_usuario'], $row['password'], $row['nombres'], $row['apellidos'], $row['cargo'], $row['email'], $row['status'], $row['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function obtener_todos_usuarios($database) {
    $usuarios = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo != 1 AND cargo != 0 ORDER BY id";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $usuarios [] = new Usuario($row['id'], $row['nombre_usuario'], $row['password'], $row['nombres'], $row['apellidos'], $row['cargo'], $row['email'], $row['status'], $row['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function escribir_usuario($usuario) {
    if (!isset($usuario)) {
      return;
    }
    ?>
    <tr>
      <td><?php echo $usuario-> get_id(); ?></td>
      <td><?php echo $usuario-> obtener_cargo(); ?></td>
      <td><?php echo $usuario-> obtener_nombres(); ?></td>
      <td><?php echo $usuario-> obtener_apellidos(); ?></td>
      <td class='text-center'>
        <?php
        if($usuario-> get_submitted()){
          echo '<a href="' . DISABLE_USER . $usuario-> get_id() . '" class="btn btn-block btn-sm btn-danger"><i class="fa fa-ban"></i> Disable</a>';
        }else{
          echo '<a href="' . ENABLE_USER . $usuario-> get_id() . '" class="btn btn-block btn-sm btn-success"><i class="fa fa-check"></i> Enable</a>';
        }
        ?>
        <br>
        <a class="btn btn-sm btn-block btn-info" href="<?php echo EDIT_USER . $usuario-> get_id(); ?>"><i class="fas fa-highlighter"></i> Edit</a>
      </td>
    </tr>
    <?php
  }

  public static function disable_user($database, $id_user){
    $usuario_editado = false;
    if(isset($database)){
      try{
        $sql = 'UPDATE usuarios SET status = 0 WHERE id = :id_user';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $result = $query-> execute();
        if($result){
          $usuario_editado = true;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_editado;
  }

  public static function enable_user($database, $id_user){
    $usuario_editado = false;
    if(isset($database)){
      try{
        $sql = 'UPDATE usuarios SET status = 1 WHERE id = :id_user';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
        $result = $query-> execute();
        if($result){
          $usuario_editado = true;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_editado;
  }

  public static function escribir_usuarios() {
    Database::open_connection();
    $usuarios = self::obtener_todos_usuarios(Database::get_connection());
    Database::close_connection();
    if (count($usuarios)) {
      ?>
      <table id="tabla_usuarios" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th id="id">Id</th>
            <th>Level</th>
            <th>First names</th>
            <th>Last names</th>
            <th id="disable_user">Options</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($usuarios as $usuario) {
            self::escribir_usuario($usuario);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function obtener_usuarios_rfq($database) {
    $usuarios = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo > 2 AND status = 1";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $usuarios [] = new Usuario($row['id'], $row['nombre_usuario'], $row['password'], $row['nombres'], $row['apellidos'], $row['cargo'], $row['email'], $row['status'], $row['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function obtener_cotizaciones_por_usuario($database, $id_user, $tipo) {
    $quotes = 0;
    $quotees_pasadas = 0;
    if (isset($database)) {
      try {
        switch ($tipo) {
          case 'complete':
            $sql = 'SELECT COUNT(*) as quotes FROM quotes WHERE assigned_user = :assigned_user AND complete = 1 AND MONTH(completed_date) = MONTH(CURDATE()) AND YEAR(completed_date) = YEAR(CURDATE())';
            $sql1 = 'SELECT COUNT(*) as cotizaciones_pasadas FROM quotes WHERE assigned_user = :assigned_user AND complete = 1 AND MONTH(completed_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(completed_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))';
            break;
          case 'award':
            $sql = 'SELECT COUNT(*) as quotes FROM quotes WHERE assigned_user = :assigned_user AND award= 1 AND MONTH(award_date) = MONTH(CURDATE()) AND YEAR(award_date) = YEAR(CURDATE())';
            $sql1 = 'SELECT COUNT(*) as cotizaciones_pasadas FROM quotes WHERE assigned_user = :assigned_user AND award = 1 AND MONTH(award_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(award_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))';
            break;
          case 'status':
            $sql = 'SELECT COUNT(*) as quotes FROM quotes WHERE assigned_user = :assigned_user AND status = 1 AND MONTH(submitted_date) = MONTH(CURDATE()) AND YEAR(submitted_date) = YEAR(CURDATE())';
            $sql1 = 'SELECT COUNT(*) as cotizaciones_pasadas FROM quotes WHERE assigned_user = :assigned_user AND status = 1 AND MONTH(submitted_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(submitted_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))';
            break;
        }
        $query = $database->prepare($sql);
        $query->bindParam(':assigned_user', $id_user, PDO::PARAM_STR);
        $query->execute();

        $query1 = $database->prepare($sql1);
        $query1->bindParam(':assigned_user', $id_user, PDO::PARAM_STR);
        $query1->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result1 = $query1->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
          $quotes = $result['quotes'];
        }
        if (!empty($result1)) {
          $quotees_pasadas = $result1['cotizaciones_pasadas'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($quotes, $quotees_pasadas);
  }

  public static function obtener_cotizaciones_no_sometidas_por_usuario($database, $id_user) {
    $quotes = 0;
    $quotees_pasadas = 0;
    if (isset($database)) {
      try {
        $sql = 'SELECT COUNT(*) as quotes FROM quotes WHERE assigned_user = :assigned_user AND comments = "Not submitted" AND MONTH(completed_date) = MONTH(CURDATE()) AND YEAR(completed_date) = YEAR(CURDATE())';
        $sql1 = 'SELECT COUNT(*) as cotizaciones_pasadas FROM quotes WHERE assigned_user = :assigned_user AND comments = "Not submitted" AND MONTH(completed_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(completed_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))';
        $query = $database->prepare($sql);
        $query->bindParam(':assigned_user', $id_user, PDO::PARAM_STR);
        $query->execute();

        $query1 = $database->prepare($sql1);
        $query1->bindParam(':assigned_user', $id_user, PDO::PARAM_STR);
        $query1->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        $result1 = $query1->fetch(PDO::FETCH_ASSOC);

        if (!empty($result)) {
          $quotes = $result['quotes'];
        }
        if (!empty($result1)) {
          $quotees_pasadas = $result1['cotizaciones_pasadas'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($quotes, $quotees_pasadas);
  }

  public static function obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas() {
    $nombres_usuario = array();
    $quotees_completadas = array();
    $quotees_completadas_pasadas = array();
    $quotees_ganadas = array();
    $quotees_ganadas_pasadas = array();
    $quotees_sometidas = array();
    $quotees_sometidas_pasadas = array();
    $quotees_no_sometidas = array();
    $quotees_no_sometidas_pasadas = array();
    Database::open_connection();
    $usuarios = self::obtener_usuarios_rfq(Database::get_connection());
    Database::close_connection();

    if (count($usuarios)) {
      for ($i = 0; $i < count($usuarios); $i++) {
        $usuario = $usuarios[$i];
        $nombres_usuario[$i] = $usuario->obtener_nombre_usuario();
        Database::open_connection();
        list($quotees_completadas[$i], $quotees_completadas_pasadas[$i]) = self::obtener_cotizaciones_por_usuario(Database::get_connection(), $usuario->get_id(), 'complete');
        list($quotees_ganadas[$i], $quotees_ganadas_pasadas[$i]) = self::obtener_cotizaciones_por_usuario(Database::get_connection(), $usuario->get_id(), 'award');
        list($quotees_sometidas[$i], $quotees_sometidas_pasadas[$i]) = self::obtener_cotizaciones_por_usuario(Database::get_connection(), $usuario->get_id(), 'status');
        list($quotees_no_sometidas[$i], $quotees_no_sometidas_pasadas[$i]) = self::obtener_cotizaciones_no_sometidas_por_usuario(Database::get_connection(), $usuario->get_id());
        Database::close_connection();
      }
    }
    return array($nombres_usuario, $quotees_completadas, $quotees_completadas_pasadas, $quotees_ganadas, $quotees_ganadas_pasadas, $quotees_sometidas, $quotees_sometidas_pasadas, $quotees_no_sometidas, $quotees_no_sometidas_pasadas);
  }

  public static function edit_user($database, $password, $username, $nombres, $apellidos, $role, $email, $id_user) {
    $edited_user = false;
    if (isset($database)) {
      try {
        if(empty($password)){
          $sql = "UPDATE usuarios SET nombre_usuario = :nombre_usuario, nombres = :nombres, apellidos = :apellidos, cargo = :cargo, email = :email WHERE id = :id_user";
          $query = $database->prepare($sql);
          $query-> bindParam(':nombre_usuario', $username, PDO::PARAM_STR);
          $query-> bindParam(':nombres', $nombres, PDO::PARAM_STR);
          $query-> bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
          $query-> bindParam(':cargo', $role, PDO::PARAM_STR);
          $query-> bindParam(':email', $email, PDO::PARAM_STR);
          $query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        }else{
          $sql = "UPDATE usuarios SET password = :password, nombre_usuario = :nombre_usuario, nombres = :nombres, apellidos = :apellidos, cargo = :cargo, email = :email WHERE id = :id_user";
          $query = $database->prepare($sql);
          $query->bindParam(':password', $password, PDO::PARAM_STR);
          $query-> bindParam(':nombre_usuario', $username, PDO::PARAM_STR);
          $query-> bindParam(':nombres', $nombres, PDO::PARAM_STR);
          $query-> bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
          $query-> bindParam(':cargo', $role, PDO::PARAM_STR);
          $query-> bindParam(':email', $email, PDO::PARAM_STR);
          $query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        }
        $query->execute();

        if ($query) {
          $edited_user = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $edited_user;
  }

  public static function obtener_cotizaciones_completadas_por_usuario_y_mes($database){
    $usuarios = self::obtener_usuarios_rfq($database);
    $quotees_completadas_anual_usuarios = [];
    if(isset($database)){
      try{
        foreach ($usuarios as $usuario) {
          $quotees_completadas_anual_por_usuario = [];
          $id_user = $usuario-> get_id();
          for($i = 1; $i <= 12; $i++){
            $sql = 'SELECT COUNT(*) as cotizaciones_completadas_usuario_mes FROM quotes WHERE assigned_user = :id_user  AND complete = 1 AND MONTH(completed_date) = ' . $i . ' AND YEAR(completed_date) = YEAR(NOW())';
            $query = $database-> prepare($sql);
            $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query-> execute();
            $result = $query-> fetch(PDO::FETCH_ASSOC);
            if (!empty($result)) {
              $quotees_completadas_anual_por_usuario[$i - 1] = $result['cotizaciones_completadas_usuario_mes'];
            } else {
              $quotees_completadas_anual_por_usuario[$i - 1] = 0;
            }
          }
          $quotees_completadas_anual_usuarios[] = $quotees_completadas_anual_por_usuario;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotees_completadas_anual_usuarios;
  }

  public static function obtener_cotizaciones_ganadas_por_usuario_y_mes($database){
    $usuarios = self::obtener_usuarios_rfq($database);
    $quotees_ganadas_anual_usuarios = [];
    $quotees_ganadas_anual_usuarios_monto = [];
    if(isset($database)){
      try{
        foreach ($usuarios as $usuario) {
          $quotees_ganadas_anual_por_usuario = [];
          $quotees_ganadas_anual_por_usuario_monto = [];
          $id_user = $usuario-> get_id();
          for($i = 1; $i <= 12; $i++){
            $sql = 'SELECT COUNT(*) as cotizaciones_ganadas_usuario_mes FROM quotes WHERE assigned_user = :id_user  AND award = 1 AND MONTH(award_date) = ' . $i . ' AND YEAR(award_date) = YEAR(NOW())';
            $sql1 = 'SELECT SUM(total_price) as monto FROM quotes WHERE assigned_user = :id_user AND award = 1 AND MONTH(award_date) = ' . $i . ' AND YEAR(award_date) = YEAR(NOW())';
            $query = $database-> prepare($sql);
            $query1 = $database-> prepare($sql1);
            $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query1-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query-> execute();
            $query1-> execute();
            $result = $query-> fetch(PDO::FETCH_ASSOC);
            $result1 = $query1-> fetch(PDO::FETCH_ASSOC);
            if (!empty($result)) {
              $quotees_ganadas_anual_por_usuario[$i - 1] = $result['cotizaciones_ganadas_usuario_mes'];
            } else {
              $quotees_ganadas_anual_por_usuario[$i - 1] = 0;
            }
            if(is_null($result1['monto'])){
              $quotees_ganadas_anual_por_usuario_monto[$i - 1] = 0;
            }else{
              $quotees_ganadas_anual_por_usuario_monto[$i - 1] = $result1['monto'];
            }
          }
          $quotees_ganadas_anual_usuarios[] = $quotees_ganadas_anual_por_usuario;
          $quotees_ganadas_anual_usuarios_monto[] = $quotees_ganadas_anual_por_usuario_monto;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($quotees_ganadas_anual_usuarios, $quotees_ganadas_anual_usuarios_monto);
  }

  public static function obtener_cotizaciones_not_submitted_por_usuario_y_mes($database){
    $usuarios = self::obtener_usuarios_rfq($database);
    $quotees_not_submitted_anual_usuarios = [];
    if(isset($database)){
      try{
        foreach ($usuarios as $usuario) {
          $quotees_not_submitted_anual_por_usuario = [];
          $id_user = $usuario-> get_id();
          for($i = 1; $i <= 12; $i++){
            $sql = 'SELECT COUNT(*) as cotizaciones_not_submitted_usuario_mes FROM quotes WHERE assigned_user = :id_user  AND comments = "Not submitted" AND MONTH(completed_date) = ' . $i . ' AND YEAR(completed_date) = YEAR(NOW())';
            $query = $database-> prepare($sql);
            $query-> bindParam(':id_user', $id_user, PDO::PARAM_STR);
            $query-> execute();
            $result = $query-> fetch(PDO::FETCH_ASSOC);
            if (!empty($result)) {
              $quotees_not_submitted_anual_por_usuario[$i - 1] = $result['cotizaciones_not_submitted_usuario_mes'];
            } else {
              $quotees_not_submitted_anual_por_usuario[$i - 1] = 0;
            }
          }
          $quotees_not_submitted_anual_usuarios[] = $quotees_not_submitted_anual_por_usuario;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotees_not_submitted_anual_usuarios;
  }
}
?>
