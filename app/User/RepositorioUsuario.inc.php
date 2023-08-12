<?php
class RepositorioUsuario {
  public static function insertar_usuario($conexion, $usuario) {
    $usuario_insertado = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO usuarios(nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email) VALUES(:nombre_usuario, :password, :nombres, :apellidos, :cargo, :email, :status, :hash_recover_email)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':nombre_usuario', $usuario->obtener_nombre_usuario(), PDO::PARAM_STR);
        $sentencia->bindValue(':password', $usuario->obtener_password(), PDO::PARAM_STR);
        $sentencia->bindValue(':nombres', $usuario->obtener_nombres(), PDO::PARAM_STR);
        $sentencia->bindValue(':apellidos', $usuario->obtener_apellidos(), PDO::PARAM_STR);
        $sentencia->bindValue(':cargo', $usuario->obtener_cargo_string(), PDO::PARAM_STR);
        $sentencia->bindValue(':email', $usuario->obtener_email(), PDO::PARAM_STR);
        $sentencia->bindValue(':status', $usuario->obtener_status(), PDO::PARAM_STR);
        $sentencia->bindValue(':hash_recover_email', $usuario->obtener_hash_recover_email(), PDO::PARAM_STR);
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
        $sentencia->bindValue(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
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
        $sentencia->bindValue(':email', $email, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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
        $sentencia->bindValue(':password', $password, PDO::PARAM_STR);
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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
        $sentencia->bindValue(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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
    $exists = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $sentencia->execute();
        if ($sentencia->fetchColumn() == 0) {
          $exists = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $exists;
  }

  public static function email_existe($conexion, $email) {
    $exists = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':email', $email, PDO::PARAM_STR);
        $sentencia->execute();
        if ($sentencia->fetchColumn() == 0) {
          $exists = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $exists;
  }

  public static function usernameExistMoreThan2($conexion, $username, $id) {
    $exists = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT COUNT(nombre_usuario) FROM usuarios WHERE nombre_usuario = :username AND id != :id";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':username', $username, PDO::PARAM_STR);
        $sentencia->bindValue(':id', $id, PDO::PARAM_STR);
        $sentencia->execute();
        if ($sentencia->fetchColumn() == 0) {
          $exists = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $exists;
  }

  public static function url_secreta_existe($conexion, $url_secreta) {
    $url_secreta_existe = true;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE hash_recover_email = :hash_recover_email";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
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
        $sentencia->bindValue(':hash_recover_email', $url_secreta, PDO::PARAM_STR);
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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
        $sentencia->bindValue(':nombres', $nombres, PDO::PARAM_STR);
        $sentencia->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
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

  public static function getUsers($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'u.id';
        break;
      case 1:
        $sort_column = 'role_names';
        break;
      case 2:
        $sort_column = 'nombres';
        break;
      case 3:
        $sort_column = 'apellidos';
        break;
      case 4:
        $sort_column = 'nombre_usuario';
        break;
      case 5:
        $sort_column = 'status';
        break;
      default:
        $sort_column = 'u.id';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT u.id, 
        GROUP_CONCAT(r.name) AS role_names, 
        nombres, 
        apellidos, 
        nombre_usuario, 
        CASE
          WHEN status = 1 THEN 'Enabled'
          WHEN status = 0 THEN 'Disabled'
        END AS status,
        NULL AS options
        FROM usuarios u
        LEFT JOIN roles r ON FIND_IN_SET(r.id, u.cargo)
        WHERE u.id LIKE :search OR 
        cargo LIKE :search OR 
        nombres LIKE :search OR 
        apellidos LIKE :search OR
        nombre_usuario LIKE :search
        GROUP BY u.id
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
        while ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getTotalUsersCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(*)
        FROM usuarios
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredUsersCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(*)
        FROM usuarios 
        WHERE id LIKE :search OR 
        cargo LIKE :search OR 
        nombres LIKE :search OR 
        apellidos LIKE :search OR
        nombre_usuario LIKE :search
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
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

  public static function disable_user($conexion, $id_usuario) {
    $usuario_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE usuarios SET status = 0 WHERE id = :id_usuario';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);
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

  public static function getAllRfqUsers($conexion) {
    $usuarios = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM usuarios WHERE cargo LIKE '%3%'";
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

  public static function getQuotesByUserAndMonth($connection, $type, $month) {
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
          COUNT(r.id) AS total_quotes
        FROM 
          usuarios u 
          LEFT JOIN rfq r ON r.usuario_designado = u.id 
          AND DATE_FORMAT(r." . $date . ", '%Y-%m') = '" . $month . "'
          AND r." . $status . " = 1
          WHERE u.cargo LIKE '%3%' AND u.status = 1
        GROUP BY 
          u.id;
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

  public static function edit_user($conexion, $username, $nombres, $apellidos, $cargo, $email, $id_user) {
    $edited_user = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE usuarios SET nombre_usuario = :nombre_usuario, nombres = :nombres, apellidos = :apellidos, cargo = :cargo, email = :email WHERE id = :id_user";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':nombre_usuario', $username, PDO::PARAM_STR);
        $sentencia->bindValue(':nombres', $nombres, PDO::PARAM_STR);
        $sentencia->bindValue(':apellidos', $apellidos, PDO::PARAM_STR);
        $sentencia->bindValue(':cargo', $cargo, PDO::PARAM_STR);
        $sentencia->bindValue(':email', $email, PDO::PARAM_STR);
        $sentencia->bindValue(':id_user', $id_user, PDO::PARAM_STR);
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

  public static function update_password($conexion, $password, $id_user) {
    if (isset($conexion)) {
      try {
        $sql = "UPDATE usuarios SET password = :password WHERE id = :id_user";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':password', $password, PDO::PARAM_STR);
        $sentencia->bindValue(':id_user', $id_user, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
