<?php

class RepositorioRfq {

    public static function insertar_cotizacion($conexion, $cotizacion) {
        $cotizacion_insertada = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit)';

                $sentencia = $conexion->prepare($sql);

                $sentencia->bindParam(':id_usuario', $cotizacion->obtener_id_usuario(), PDO::PARAM_STR);
                $sentencia->bindParam(':usuario_designado', $cotizacion->obtener_usuario_designado(), PDO::PARAM_STR);
                $sentencia->bindParam(':canal', $cotizacion->obtener_canal(), PDO::PARAM_STR);
                $sentencia->bindParam(':email_code', $cotizacion->obtener_email_code(), PDO::PARAM_STR);
                $sentencia->bindParam(':type_of_bid', $cotizacion->obtener_type_of_bid(), PDO::PARAM_STR);
                $sentencia->bindParam(':issue_date', $cotizacion->obtener_issue_date(), PDO::PARAM_STR);
                $sentencia->bindParam(':end_date', $cotizacion->obtener_end_date(), PDO::PARAM_STR);
                $sentencia->bindParam(':status', $cotizacion->obtener_status(), PDO::PARAM_STR);
                $sentencia->bindParam(':completado', $cotizacion->obtener_completado(), PDO::PARAM_STR);
                $sentencia->bindParam(':total_cost', $cotizacion->obtener_total_cost(), PDO::PARAM_STR);
                $sentencia->bindParam(':total_price', $cotizacion->obtener_total_price(), PDO::PARAM_STR);
                $sentencia->bindParam(':comments', $cotizacion->obtener_comments(), PDO::PARAM_STR);
                $sentencia->bindParam(':award', $cotizacion->obtener_award(), PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_completado', $cotizacion->obtener_fecha_completado(), PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_submitted', $cotizacion->obtener_fecha_submitted(), PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_award', $cotizacion->obtener_fecha_award(), PDO::PARAM_STR);
                $sentencia->bindParam(':payment_terms', $cotizacion->obtener_payment_terms(), PDO::PARAM_STR);
                $sentencia->bindParam(':address', $cotizacion->obtener_address(), PDO::PARAM_STR);
                $sentencia->bindParam(':ship_to', $cotizacion->obtener_ship_to(), PDO::PARAM_STR);
                $sentencia->bindParam(':expiration_date', $cotizacion->obtener_expiration_date(), PDO::PARAM_STR);
                $sentencia->bindParam(':ship_via', $cotizacion->obtener_ship_via(), PDO::PARAM_STR);
                $sentencia->bindParam(':taxes', $cotizacion->obtener_taxes(), PDO::PARAM_STR);
                $sentencia->bindParam(':profit', $cotizacion->obtener_profit(), PDO::PARAM_STR);

                $resultado = $sentencia->execute();

                $id = $conexion->lastInsertId();

                if ($resultado) {
                    $cotizacion_insertada = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }

        return array($cotizacion_insertada, $id);
    }

    public static function email_code_existe($conexion, $email_code) {
        $email_code_existe = true;
        if (isset($conexion)) {
            try {
                $sql = 'SELECT * FROM rfq WHERE email_code = :email_code';

                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':email_code', $email_code, PDO::PARAM_STR);

                $sentencia->execute();
                $resultado = $sentencia->fetchall();

                if (count($resultado)) {
                    $email_code_existe = true;
                } else {
                    $email_code_existe = false;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $email_code_existe;
    }

    public static function obtener_cotizaciones_por_canal_usuario_cargo($conexion, $canal, $id_usuario, $cargo) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {

                if ($cargo < 4) {
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 0 ORDER BY end_date DESC";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                } else if ($cargo == 4) {
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND usuario_designado = :id_usuario AND completado = 0 ORDER BY end_date DESC";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
                }

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones;
    }

    public static function escribir_cotizacion($cotizacion) {
        if (!isset($cotizacion)) {
            return;
        }
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion-> obtener_id(); ?>" class="btn-block">
                    <?php echo $cotizacion->obtener_email_code(); ?>
                </a>
            </td>
            <td>
                <?php
                Conexion::abrir_conexion();
                $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
                Conexion::cerrar_conexion();
                echo $usuario->obtener_nombre_usuario();
                ?>
            </td>
            <td><?php echo $cotizacion->obtener_type_of_bid(); ?></td>
            <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
            <td><?php echo $cotizacion->obtener_end_date(); ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_por_canal_usuario_cargo($canal, $id_usuario, $cargo) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_por_canal_usuario_cargo(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>E-MAIL CODE</th>
                        <th>DESIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>PROPOSAL</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones">
                    <?php
                    foreach ($cotizaciones as $cotizacion) {
                        self::escribir_cotizacion($cotizacion);
                    }
                    ?>
                </tbody>
            </table>    
            <?php
        }
    }

    public static function obtener_cotizacion_por_id($conexion, $id_rfq) {
        $cotizacion_recuperada = null;
        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM rfq WHERE id = :id_rfq";

                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetch();

                if (!empty($resultado)) {
                    $cotizacion_recuperada = new Rfq($resultado['id'], $resultado['id_usuario'], $resultado['usuario_designado'], $resultado['canal'], $resultado['email_code'], $resultado['type_of_bid'], $resultado['issue_date'], $resultado['end_date'], $resultado['status'], $resultado['completado'], $resultado['total_cost'], $resultado['total_price'], $resultado['comments'], $resultado['award'], $resultado['fecha_completado'], $resultado['fecha_submitted'], $resultado['fecha_award'], $resultado['payment_terms'], $resultado['address'], $resultado['ship_to'], $resultado['expiration_date'], $resultado['ship_via'], $resultado['taxes'], $resultado['profit']);
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizacion_recuperada;
    }

    public static function actualizar_usuario_designado($conexion, $usuario_designado, $id_rfq) {
        $cotizacion_editada = false;
        if (isset($conexion)) {
            try {
                $sql = "UPDATE rfq SET usuario_designado = :usuario_designado WHERE id = :id_rfq";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':usuario_designado', $usuario_designado, PDO::PARAM_STR);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();
                
                if ($sentencia) {
                    $cotizacion_editada = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizacion_editada;
    }
    
    public static function actualizar_taxes_profit($conexion, $taxes, $profit, $total_cost, $total_price, $id_rfq){
        $cotizacion_editada = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE rfq SET taxes = :taxes, profit = :profit, total_cost = :total_cost, total_price = :total_price WHERE id = :id_rfq';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':taxes', $taxes, PDO::PARAM_STR);
                $sentencia-> bindParam(':profit', $profit, PDO::PARAM_STR);
                $sentencia-> bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
                $sentencia-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                
                $sentencia-> execute();
                
                if($sentencia){
                    $cotizacion_editada = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizacion_editada;
    }

    public static function obtener_cotizaciones_completadas_por_canal($conexion, $canal) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM rfq WHERE completado = 1 AND canal = :canal ORDER BY fecha_completado DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones;
    }

    public static function escribir_cotizacion_completada($cotizacion) {
        if (!isset($cotizacion)) {
            return;
        }
        $partes_fecha_completado = explode('-', $cotizacion-> obtener_fecha_completado());
        $fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion-> obtener_id(); ?>" class="btn-block">
                    <?php echo $cotizacion->obtener_email_code(); ?>
                </a>
            </td>
            <td>
                <?php
                Conexion::abrir_conexion();
                $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
                Conexion::cerrar_conexion();
                echo $usuario->obtener_nombre_usuario();
                ?>
            </td>
            <td><?php echo $cotizacion->obtener_type_of_bid(); ?></td>
            <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
            <td><?php echo $cotizacion->obtener_end_date(); ?></td>
            <td><?php echo '$ '.$cotizacion->obtener_total_price(); ?></td>
            <td><?php echo $fecha_completado; ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
            <td class="text-center"><a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_completadas_por_canal($canal) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_completadas_por_canal(Conexion::obtener_conexion(), $canal);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>E-MAIL CODE</th>
                        <th>DEDIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>AMOUNT</th>
                        <th>COMPLETED DATE</th>
                        <th>PROPOSAL</th>
                        <th>COMMENTS</th>
                        <th>GENERATE PROPOSAL</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones_completados">
                    <?php
                    foreach ($cotizaciones as $cotizacion) {
                        self::escribir_cotizacion_completada($cotizacion);
                    }
                    ?>
                </tbody>
            </table>    
            <?php
        }
    }
    
    public static function obtener_cotizaciones_submitted_por_canal($conexion, $canal) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND canal = :canal ORDER BY fecha_completado DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones;
    }
    
    public static function escribir_cotizacion_submitted($cotizacion) {
        if (!isset($cotizacion)) {
            return;
        }
        $partes_fecha_submitted = explode('-', $cotizacion-> obtener_fecha_submitted());
        $fecha_submitted = $partes_fecha_submitted[1] . '/' . $partes_fecha_submitted[2] . '/' . $partes_fecha_submitted[0];
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion-> obtener_id(); ?>" class="btn-block">
                    <?php echo $cotizacion->obtener_email_code(); ?>
                </a>
            </td>
            <td>
                <?php
                Conexion::abrir_conexion();
                $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
                Conexion::cerrar_conexion();
                echo $usuario->obtener_nombre_usuario();
                ?>
            </td>
            <td><?php echo $cotizacion->obtener_type_of_bid(); ?></td>
            <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
            <td><?php echo $cotizacion->obtener_end_date(); ?></td>
            <td><?php echo '$ '.$cotizacion->obtener_total_price(); ?></td>
            <td><?php echo $fecha_submitted; ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
        </tr>
        <?php
    }
    
    public static function escribir_cotizaciones_submitted_por_canal($canal) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_submitted_por_canal(Conexion::obtener_conexion(), $canal);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>E-MAIL CODE</th>
                        <th>DEDIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>AMOUNT</th>
                        <th>SUBMITTED DATE</th>
                        <th>PROPOSAL</th>
                        <th>COMMENTS</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones_completados">
                    <?php
                    foreach ($cotizaciones as $cotizacion) {
                        self::escribir_cotizacion_submitted($cotizacion);
                    }
                    ?>
                </tbody>
            </table>    
            <?php
        }
    }

    public static function obtener_cotizaciones_ganadas_por_mes($conexion) {
        $cotizaciones_mes = array();

        if (isset($conexion)) {
            try {
                for ($i = 1; $i <= 12; $i++) {
                    $sql = 'SELECT COUNT(*) as cotizaciones_mes FROM rfq WHERE award = 1 AND MONTH(fecha_completado) =' . $i . ' AND YEAR(fecha_completado) = YEAR(CURDATE())';
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->execute();
                    $resultado = $sentencia->fetch();
                    if (!empty($resultado)) {
                        $cotizaciones_mes[$i - 1] = $resultado['cotizaciones_mes'];
                    } else {
                        $cotizaciones_mes[$i - 1] = 0;
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones_mes;
    }

    public static function obtener_monto_cotizaciones_ganadas_por_mes($conexion) {
        $monto_cotizaciones_mes = array();

        if (isset($conexion)) {
            try {
                for ($i = 1; $i <= 12; $i++) {
                    $sql = 'SELECT SUM(total_price) as monto FROM rfq WHERE award = 1 AND MONTH(fecha_completado) =' . $i . ' AND YEAR(fecha_completado) = YEAR(CURDATE())';
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->execute();
                    $resultado = $sentencia->fetch();

                    if (is_null($resultado['monto'])) {
                        $monto_cotizaciones_mes[$i - 1] = 0;
                    } else {
                        $monto_cotizaciones_mes[$i - 1] = $resultado['monto'];
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $monto_cotizaciones_mes;
    }
    
    public static function actualizar_rfq_2($conexion, $comments, $ship_via, $address, $payment_terms, $ship_to, $id_rfq){
        $rfq_editado = false;
        if(isset($conexion)){
            try{
                $sql = 'UPDATE rfq SET comments = :comments, ship_via = :ship_via, address = :address, payment_terms = :payment_terms, ship_to = :ship_to WHERE id = :id_rfq';
                $sentencia = $conexion-> prepare($sql);
                $sentencia-> bindParam(':comments', $comments, PDO::PARAM_STR);
                $sentencia-> bindParam(':ship_via', $ship_via, PDO::PARAM_STR);
                $sentencia-> bindParam(':address', $address, PDO::PARAM_STR);
                $sentencia-> bindParam(':payment_terms', $payment_terms, PDO::PARAM_STR);
                $sentencia-> bindParam(':ship_to', $ship_to, PDO::PARAM_STR);
                $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                
                $sentencia-> execute();
                
                if($sentencia){
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }
    
    public static function actualizar_fecha_y_submitted($conexion, $id_rfq){
       $rfq_editado = false;
       if(isset($conexion)){
           try{
               $sql = 'UPDATE rfq SET status = 1, fecha_submitted = NOW() WHERE id = :id_rfq';
               $sentencia = $conexion-> prepare($sql);
               $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
               
               $sentencia-> execute();
               
               if($sentencia){
                   $rfq_editado = true;
               }
           } catch (PDOException $ex) {
               print 'ERROR:' . $ex->getMessage() . '<br>';
           }
       }
       return $rfq_editado;
    }

    public static function actualizar_fecha_y_completado($conexion, $id_rfq){
       $rfq_editado = false;
       if(isset($conexion)){
           try{
               $sql = 'UPDATE rfq SET completado = 1, fecha_completado = NOW(), expiration_date = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE id = :id_rfq';
               $sentencia = $conexion-> prepare($sql);
               $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
               
               $sentencia-> execute();
               
               if($sentencia){
                   $rfq_editado = true;
               }
           } catch (PDOException $ex) {
               print 'ERROR:' . $ex->getMessage() . '<br>';
           }
       }
       return $rfq_editado;
    }
}
?>
