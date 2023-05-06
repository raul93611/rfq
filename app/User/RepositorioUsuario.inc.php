<?php
class RepositorioUsuario {
  public static function insertar_usuario($conexion, $usuario) {
    $usuario_insertado = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO usuarios(nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES(:nombre_usuario, :password, :nombres, :apellidos, :cargo, :email, :status, :hash_recover_email)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':nombre_usuario', $usuario->obtener_nombre_usuario(), PDO::PARAM_STR);
        $sentencia->bindParam(':password', $usuario->obtener_password(), PDO::PARAM_STR);
        $sentencia->bindParam(':nombres', $usuario->obtener_nombres(), PDO::PARAM_STR);
        $sentencia->bindParam(':apellidos', $usuario->obtener_apellidos(), PDO::PARAM_STR);
        $sentencia->bindParam(':cargo', $usuario->obtener_cargo_string(), PDO::PARAM_STR);
        $sentencia->bindParam(':email', $usuario->obtener_email(), PDO::PARAM_STR);
        $sentencia->bindParam(':status', $usuario->obtener_status(), PDO::PARAM_STR);
        $sentencia->bindParam(':hash_recover_email', $usuario->obtener_hash_recover_email(), PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        if ($resultado) {
          $usuario_insertado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_insertado;
  }

  public static function obtener_usuario_por_nombre_usuario($conexion, $nombre_usuario) {
    $usuario = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo'], $resultado['email'], $resultado['status'], $resultado['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function obtener_usuario_por_email($conexion, $email) {
    $usuario = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE email LIKE :email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':email', $email, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo'], $resultado['email'], $resultado['status'], $resultado['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function eliminar_hash_recover_email($conexion, $id_usuario) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET hash_recover_email = "" WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_clave($conexion, $password, $id_usuario) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET password = :password WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':password', $password, PDO::PARAM_STR);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_usuario_por_url_secreta($conexion, $url_secreta) {
    $usuario = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE hash_recover_email = :hash_recover_email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo'], $resultado['email'], $resultado['status'], $resultado['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function obtener_usuario_por_id($conexion, $id_usuario) {
    $usuario = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE id = :id_usuario";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo'], $resultado['email'], $resultado['status'], $resultado['hash_recover_email']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario;
  }

  public static function nombre_usuario_existe($conexion, $nombre_usuario) {
    $nombre_usuario_existe = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
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

  public static function email_existe($conexion, $email) {
    $email_existe = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE email LIKE :email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':email', $email, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
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

  public static function url_secreta_existe($conexion, $url_secreta) {
    $url_secreta_existe = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE hash_recover_email = :hash_recover_email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
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

  public static function guardar_url_secreta($conexion, $id_usuario, $url_secreta) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET hash_recover_email = :hash_recover_email WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function nombre_completo_existe($conexion, $apellidos, $nombres) {
    $nombre_completo_existe = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE nombres = :nombres AND apellidos = :apellidos";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
        $sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
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

  public static function contar_usuarios($conexion) {
    $total_usuarios = 0;
    if (isset($conexion)) {
      try {
        $sql = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE cargo != 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $total_usuarios = $resultado['total_usuarios'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total_usuarios;
  }

  public static function obtener_todos_usuarios($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo != 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $usuarios[] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo'], $fila['email'], $fila['status'], $fila['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function get_enable_users($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo != 1 AND status = 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $usuarios[] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo'], $fila['email'], $fila['status'], $fila['hash_recover_email']);
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
      <td><?php echo $usuario->obtener_id(); ?></td>
      <td><?php echo $usuario->obtener_role(); ?></td>
      <td><?php echo $usuario->obtener_nombres(); ?></td>
      <td><?php echo $usuario->obtener_apellidos(); ?></td>
      <td class='text-center'>
        <?php
        if ($usuario->obtener_status()) {
          echo '<a href="' . DISABLE_USER . $usuario->obtener_id() . '" class="btn btn-block btn-sm btn-danger"><i class="fa fa-ban"></i> Disable</a>';
        } else {
          echo '<a href="' . ENABLE_USER . $usuario->obtener_id() . '" class="btn btn-block btn-sm btn-success"><i class="fa fa-check"></i> Enable</a>';
        }
        ?>
        <br>
        <a class="btn btn-sm btn-block btn-info" href="<?php echo EDIT_USER . $usuario->obtener_id(); ?>"><i class="fas fa-highlighter"></i> Edit</a>
      </td>
    </tr>
    <?php
  }

  public static function disable_user($conexion, $id_usuario) {
    $usuario_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET status = 0 WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        if ($resultado) {
          $usuario_editado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_editado;
  }

  public static function enable_user($conexion, $id_usuario) {
    $usuario_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET status = 1 WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        if ($resultado) {
          $usuario_editado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuario_editado;
  }

  public static function escribir_usuarios() {
    Conexion::abrir_conexion();
    $usuarios = self::obtener_todos_usuarios(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if (count($usuarios)) {
    ?>
      <table id="tabla_usuarios" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th id="id">Id</th>
            <th>Role</th>
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

  public static function obtener_usuarios_rfq($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo LIKE '%3%' AND status = 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $usuarios[] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo'], $fila['email'], $fila['status'], $fila['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function get_fulfillment_users($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo LIKE '%2%' AND status = 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $usuarios[] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo'], $fila['email'], $fila['status'], $fila['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function get_accounting_users($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo LIKE '%4%' AND status = 1";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $usuarios[] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo'], $fila['email'], $fila['status'], $fila['hash_recover_email']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $usuarios;
  }

  public static function getQuotesByUserAndLastCurrentMonth($connection, $type) {
    $data = [];
    switch ($type) {
      case 'completed':
        $date = 'fecha_completado';
        $status = 'completado';
        break;
      case 'award':
        $date = 'fecha_award';
        $status = 'award';
        break;
      default:
        $date = 'fecha_completado';
        $status = 'completado';
        break;
    }
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          u.nombre_usuario AS user_name, 
          COUNT(r.id) AS total_quotes,
          COUNT(r2.id) AS total_quotes_past_month
        FROM 
          usuarios u 
          LEFT JOIN rfq r ON r.id_usuario = u.id AND MONTH(r." . $date . ") = MONTH(CURDATE()) AND YEAR(r." . $date . ") = YEAR(CURDATE()) AND r." . $status . " = 1
          LEFT JOIN rfq r2 ON r2.id_usuario = u.id AND MONTH(r2." . $date . ") = MONTH(CURDATE() - INTERVAL 1 MONTH) AND YEAR(r2." . $date . ") = YEAR(CURDATE() - INTERVAL 1 MONTH) AND r2." . $status . " = 1
          WHERE u.cargo LIKE '%3%' AND u.status = 1
        GROUP BY 
          u.id
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function edit_user($conexion, $password, $username, $nombres, $apellidos, $cargo, $email, $id_user) {
    $edited_user = false;
    if (isset($conexion)) {
      try {
        if (empty($password)) {
          $sql = "UPDATE usuarios SET nombre_usuario = :nombre_usuario, nombres = :nombres, apellidos = :apellidos, cargo = :cargo, email = :email WHERE id = :id_user";
          $sentencia = $conexion->prepare($sql);
          $sentencia->bindParam(':nombre_usuario', $username, PDO::PARAM_STR);
          $sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
          $sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
          $sentencia->bindParam(':cargo', $cargo, PDO::PARAM_STR);
          $sentencia->bindParam(':email', $email, PDO::PARAM_STR);
          $sentencia->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        } else {
          $sql = "UPDATE usuarios SET password = :password, nombre_usuario = :nombre_usuario, nombres = :nombres, apellidos = :apellidos, cargo = :cargo, email = :email WHERE id = :id_user";
          $sentencia = $conexion->prepare($sql);
          $sentencia->bindParam(':password', $password, PDO::PARAM_STR);
          $sentencia->bindParam(':nombre_usuario', $username, PDO::PARAM_STR);
          $sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
          $sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
          $sentencia->bindParam(':cargo', $cargo, PDO::PARAM_STR);
          $sentencia->bindParam(':email', $email, PDO::PARAM_STR);
          $sentencia->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        }
        $sentencia->execute();

        if ($sentencia) {
          $edited_user = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $edited_user;
  }
}
?>