<?php

class RepositorioUsuario {

    public static function insertar_usuario($conexion, $usuario) {
        $usuario_insertado = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO usuarios(nombre_usuario, password, nombres, apellidos, cargo) VALUES(:nombre_usuario, :password, :nombres, :apellidos, :cargo)';

                $sentencia = $conexion->prepare($sql);

                $sentencia->bindParam(':nombre_usuario', $usuario->obtener_nombre_usuario(), PDO::PARAM_STR);
                $sentencia->bindParam(':password', $usuario->obtener_password(), PDO::PARAM_STR);
                $sentencia->bindParam(':nombres', $usuario->obtener_nombres(), PDO::PARAM_STR);
                $sentencia->bindParam(':apellidos', $usuario->obtener_apellidos(), PDO::PARAM_STR);
                $sentencia->bindParam(':cargo', $usuario->obtener_cargo(), PDO::PARAM_STR);

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

                $resultado = $sentencia->fetch();

                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo']);
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

                $resultado = $sentencia->fetch();

                if (!empty($resultado)) {
                    $usuario = new Usuario($resultado['id'], $resultado['nombre_usuario'], $resultado['password'], $resultado['nombres'], $resultado['apellidos'], $resultado['cargo']);
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

                $resultado = $sentencia->fetchAll();

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

    public static function nombre_completo_existe($conexion, $apellidos, $nombres) {
        $nombre_completo_existe = true;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE nombres = :nombres AND apellidos = :apellidos";

                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':nombres', $nombres, PDO::PARAM_STR);
                $sentencia->bindParam(':apellidos', $apellidos, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

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

                $resultado = $sentencia->fetch();

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

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios [] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo']);
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
            <td><?php echo $usuario->obtener_nombres(); ?></td>
            <td><?php echo $usuario->obtener_apellidos(); ?></td>
            <td class='text-center'>
                <form method="post" action="<?php echo ELIMINAR_USUARIO; ?>">
                    <input type="hidden" name="id_usuario" value="<?php echo $usuario->obtener_id(); ?>">
                    <button type="submit" class="btn btn-sm btn-warning" name="eliminar_usuario">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php
    }

    public static function escribir_usuarios() {
        Conexion::abrir_conexion();
        $usuarios = self::obtener_todos_usuarios(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();

        if (count($usuarios)) {
            ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="myTable">
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
    
    public static function eliminar_usuario($conexion, $id_usuario){
        $usuario_eliminado = false;
        if(isset($conexion)){
            try{
                $sql = "DELETE FROM usuarios WHERE id = :id_usuario";
                
                $sentencia1 = $conexion-> prepare($sql);
                $sentencia1-> bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
                $resultado = $sentencia1-> execute();
                
                if($resultado){
                    $usuario_eliminado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        
        return $usuario_eliminado;
    }

    public static function obtener_usuarios_por_cargo($conexion, $cargo) {
        $usuarios = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE cargo = :cargo";

                $sentencia = $conexion->prepare($sql);
                $sentencia-> bindParam(':cargo', $cargo, PDO::PARAM_STR);
                
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios [] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $usuarios;
    }
    
    public static function obtener_usuarios_rfq($conexion) {
        $usuarios = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM usuarios WHERE cargo = 4 OR cargo = 3 ";

                $sentencia = $conexion->prepare($sql);
                
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios [] = new Usuario($fila['id'], $fila['nombre_usuario'], $fila['password'], $fila['nombres'], $fila['apellidos'], $fila['cargo']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $usuarios;
    }
    
    public static function obtener_cotizaciones_por_usuario($conexion, $id_usuario, $tipo){
        $cotizaciones = 0;
        $cotizaciones_pasadas = 0;
        if(isset($conexion)){
            try{
                $sql = 'SELECT COUNT(*) as cotizaciones FROM rfq WHERE usuario_designado = :usuario_designado AND ' . $tipo . '= 1 AND MONTH(fecha_completado) = MONTH(CURDATE())';
                $sql1 = 'SELECT COUNT(*) as cotizaciones_pasadas FROM rfq WHERE usuario_designado = :usuario_designado AND ' . $tipo . '= 1 AND MONTH(fecha_completado) = MONTH(CURDATE())-1';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':usuario_designado', $id_usuario, PDO::PARAM_STR);
                $sentencia->execute();
                
                $sentencia1 = $conexion-> prepare($sql1);
                $sentencia1-> bindParam(':usuario_designado', $id_usuario, PDO::PARAM_STR);
                $sentencia1->execute();
                
                $resultado = $sentencia->fetch();
                $resultado1 = $sentencia1->fetch();

                if (!empty($resultado)) {
                    $cotizaciones = $resultado['cotizaciones'];
                }
                if(!empty($resultado1)){
                    $cotizaciones_pasadas = $resultado1['cotizaciones_pasadas'];
                }
                
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return array($cotizaciones, $cotizaciones_pasadas);
    }

        public static function obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas(){
        $nombres_usuario = array();
        $cotizaciones_completadas = array();
        $cotizaciones_completadas_pasadas = array();
        $cotizaciones_ganadas = array();
        $cotizaciones_ganadas_pasadas = array();
        $cotizaciones_sometidas = array();
        $cotizaciones_sometidas_pasadas = array();
        Conexion::abrir_conexion();
        $usuarios = RepositorioUsuario::obtener_usuarios_rfq(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();
        
        if(count($usuarios)){
            for($i = 0; $i < count($usuarios); $i++){
                $usuario = $usuarios[$i];
                $nombres_usuario[$i] = $usuario-> obtener_nombre_usuario();
                Conexion::abrir_conexion();
                list($cotizaciones_completadas[$i], $cotizaciones_completadas_pasadas[$i]) = RepositorioUsuario::obtener_cotizaciones_por_usuario(Conexion::obtener_conexion(), $usuario-> obtener_id(), 'completado');
                list($cotizaciones_ganadas[$i], $cotizaciones_ganadas_pasadas[$i]) = RepositorioUsuario::obtener_cotizaciones_por_usuario(Conexion::obtener_conexion(), $usuario-> obtener_id(), 'award');
                list($cotizaciones_sometidas[$i], $cotizaciones_sometidas_pasadas[$i]) = RepositorioUsuario::obtener_cotizaciones_por_usuario(Conexion::obtener_conexion(), $usuario-> obtener_id(), 'status');
                Conexion::cerrar_conexion();
            }
        }
        return array($nombres_usuario, $cotizaciones_completadas, $cotizaciones_completadas_pasadas, $cotizaciones_ganadas, $cotizaciones_ganadas_pasadas, $cotizaciones_sometidas, $cotizaciones_sometidas_pasadas);
    }
    
}
?>
