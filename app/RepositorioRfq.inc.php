<?php

class RepositorioRfq {

    public static function insertar_cotizacion($conexion, $cotizacion) {
        $cotizacion_insertada = false;

        if (isset($conexion)) {
            try {
                $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost)';

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
                $sentencia->bindParam(':additional', $cotizacion->obtener_additional(), PDO::PARAM_STR);
                $sentencia->bindParam(':shipping', $cotizacion->obtener_shipping(), PDO::PARAM_STR);
                $sentencia->bindParam(':shipping_cost', $cotizacion->obtener_shipping_cost(), PDO::PARAM_STR);

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
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 0 AND status = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                } else if ($cargo == 4) {
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND usuario_designado = :id_usuario AND completado = 0 AND status = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
                }

                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost']);
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
        <tr <?php if($cotizacion->obtener_comments() == 'Working on it'){echo 'class="waiting_for"';} ?>>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion->obtener_id(); ?>" class="btn-block">
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
                        <th>CODE</th>
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
                    $cotizacion_recuperada = new Rfq($resultado['id'], $resultado['id_usuario'], $resultado['usuario_designado'], $resultado['canal'], $resultado['email_code'], $resultado['type_of_bid'], $resultado['issue_date'], $resultado['end_date'], $resultado['status'], $resultado['completado'], $resultado['total_cost'], $resultado['total_price'], $resultado['comments'], $resultado['award'], $resultado['fecha_completado'], $resultado['fecha_submitted'], $resultado['fecha_award'], $resultado['payment_terms'], $resultado['address'], $resultado['ship_to'], $resultado['expiration_date'], $resultado['ship_via'], $resultado['taxes'], $resultado['profit'], $resultado['additional'], $resultado['shipping'], $resultado['shipping_cost']);
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

    public static function actualizar_rfq_inicio($conexion, $email_code, $type_of_bid, $issue_date, $end_date, $id_rfq) {
        $cotizacion_editada = false;
        if (isset($conexion)) {
            try {
                $sql = "UPDATE rfq SET email_code = :email_code, type_of_bid = :type_of_bid, issue_date = :issue_date, end_date = :end_date WHERE id = :id_rfq";
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':email_code', $email_code, PDO::PARAM_STR);
                $sentencia->bindParam(':type_of_bid', $type_of_bid, PDO::PARAM_STR);
                $sentencia->bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
                $sentencia->bindParam(':end_date', $end_date, PDO::PARAM_STR);
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

    public static function actualizar_taxes_profit($conexion, $taxes, $profit, $total_cost, $total_price, $additional, $id_rfq) {
        $cotizacion_editada = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET taxes = :taxes, profit = :profit, total_cost = :total_cost, total_price = :total_price, additional = :additional WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':taxes', $taxes, PDO::PARAM_STR);
                $sentencia->bindParam(':profit', $profit, PDO::PARAM_STR);
                $sentencia->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
                $sentencia->bindParam(':total_price', $total_price, PDO::PARAM_STR);
                $sentencia->bindParam(':additional', $additional, PDO::PARAM_STR);
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

    public static function actualizar_shipping($conexion, $shipping, $shipping_cost, $id_rfq) {
        $cotizacion_editada = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET shipping = :shipping, shipping_cost = :shipping_cost WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':shipping', $shipping, PDO::PARAM_STR);
                $sentencia->bindParam(':shipping_cost', $shipping_cost, PDO::PARAM_STR);
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

    public static function obtener_cotizaciones_completadas_por_canal($conexion, $canal, $id_usuario, $cargo) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
                if ($cargo < 4) {
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 1 AND status = 0 AND award = 0 AND comments = 'No comments' ORDER BY fecha_completado DESC";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                } else if ($cargo == 4) {
                    $sql = "SELECT * FROM rfq WHERE canal = :canal AND usuario_designado = :id_usuario AND completado = 1 AND status = 0 AND award = 0 AND comments = 'No comments' ORDER BY fecha_completado DESC";
                    $sentencia = $conexion->prepare($sql);
                    $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                    $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
                }
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost']);
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
        $partes_fecha_completado = explode('-', $cotizacion->obtener_fecha_completado());
        $fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion->obtener_id(); ?>" class="btn-block">
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
            <td><?php echo '$ ' . $cotizacion->obtener_total_price(); ?></td>
            <td><?php echo $fecha_completado; ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
            <?php
            if ($cotizacion->obtener_canal() != 'GSA-Buy') {
                ?>
                <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
                <?php
            } else {
                ?>
                <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_completadas_por_canal($canal, $id_usuario, $cargo) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_completadas_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>CODE</th>
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

    public static function obtener_cotizaciones_submitted_por_canal($conexion, $canal, $id_usuario, $cargo) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
              if ($cargo < 4) {
                $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 0 AND canal = :canal AND comments = 'No comments' ORDER BY fecha_submitted DESC";
                  $sentencia = $conexion->prepare($sql);
                  $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
              } else if ($cargo == 4) {
                  $sql = "SELECT * FROM rfq WHERE canal = :canal AND usuario_designado = :id_usuario AND completado = 1 AND status = 1 AND award = 0 AND comments = 'No comments' ORDER BY fecha_submitted DESC";
                  $sentencia = $conexion->prepare($sql);
                  $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                  $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
              }
                $sentencia->execute();
                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost']);
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
        $partes_fecha_submitted = explode('-', $cotizacion->obtener_fecha_submitted());
        $fecha_submitted = $partes_fecha_submitted[1] . '/' . $partes_fecha_submitted[2] . '/' . $partes_fecha_submitted[0];
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion->obtener_id(); ?>" class="btn-block">
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
            <td><?php echo '$ ' . $cotizacion->obtener_total_price(); ?></td>
            <td><?php echo $fecha_submitted; ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
            <?php
            if ($cotizacion->obtener_canal() != 'GSA-Buy') {
                ?>
                <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
                <?php
            } else {
                ?>
                <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
                <?php
            }
            ?>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_submitted_por_canal($canal, $id_usuario, $cargo) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_submitted_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>DEDIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>AMOUNT</th>
                        <th>SUBMITTED DATE</th>
                        <th>PROPOSAL</th>
                        <th>COMMENTS</th>
                        <th>GENERATE PROPOSAL</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones_submitted">
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

    public static function obtener_cotizaciones_award_por_canal($conexion, $canal, $id_usuario, $cargo) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
              if ($cargo < 4) {
                  $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 1 AND canal = :canal AND comments = 'No comments' ORDER BY fecha_award DESC";
                  $sentencia = $conexion->prepare($sql);
                  $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
              } else if ($cargo == 4) {
                $sql = "SELECT * FROM rfq WHERE usuario_designado = :id_usuario AND completado = 1 AND status = 1 AND award = 1 AND canal = :canal AND comments = 'No comments' ORDER BY fecha_award DESC";
                  $sentencia = $conexion->prepare($sql);
                  $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
                  $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
              }
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones;
    }

    public static function escribir_cotizacion_award($cotizacion) {
        if (!isset($cotizacion)) {
            return;
        }
        $partes_fecha_award = explode('-', $cotizacion->obtener_fecha_award());
        $fecha_award = $partes_fecha_award[1] . '/' . $partes_fecha_award[2] . '/' . $partes_fecha_award[0];
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion->obtener_id(); ?>" class="btn-block">
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
            <td><?php echo '$ ' . $cotizacion->obtener_total_price(); ?></td>
            <td><?php echo $fecha_award; ?></td>
            <td><?php echo $cotizacion->obtener_id(); ?></td>
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_award_por_canal($canal, $id_usuario, $cargo) {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_award_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>DEDIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>AMOUNT</th>
                        <th>AWARD DATE</th>
                        <th>PROPOSAL</th>
                        <th>COMMENTS</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones_award">
                    <?php
                    foreach ($cotizaciones as $cotizacion) {
                        self::escribir_cotizacion_award($cotizacion);
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    }

    public static function obtener_cotizaciones_no_bid($conexion) {
        $cotizaciones = [];

        if (isset($conexion)) {
            try {
                $sql = "SELECT * FROM rfq WHERE comments = 'No Bid' OR comments = 'Manufacturer in the Bid' OR comments = 'Expired due date' OR comments = 'Supplier did not provide a quote' OR comments = 'Others' ORDER BY id DESC";
                $sentencia = $conexion->prepare($sql);
                $sentencia->execute();

                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost']);
                    }
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $cotizaciones;
    }

    public static function escribir_cotizacion_no_bid($cotizacion) {
        if (!isset($cotizacion)) {
            return;
        }
        ?>
        <tr>
            <td>
                <a href="<?php echo EDITAR_COTIZACION . '/' . $cotizacion->obtener_id(); ?>" class="btn-block">
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
            <td><?php echo $cotizacion->obtener_comments(); ?></td>
        </tr>
        <?php
    }

    public static function escribir_cotizaciones_no_bid() {
        Conexion::abrir_conexion();
        $cotizaciones = self::obtener_cotizaciones_no_bid(Conexion::obtener_conexion());
        Conexion::cerrar_conexion();

        if (count($cotizaciones)) {
            ?>
            <table class="table table-bordered table-striped table-responsive-md">
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>DEDIGNATED USER</th>
                        <th>TYPE OF BID</th>
                        <th>ISSUE DATE</th>
                        <th>END DATE</th>
                        <th>PROPOSAL</th>
                        <th>COMMENTS</th>
                    </tr>
                </thead>
                <tbody id="tabla_cotizaciones_no_bid">
                    <?php
                    foreach ($cotizaciones as $cotizacion) {
                        self::escribir_cotizacion_no_bid($cotizacion);
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
                    $sql = 'SELECT COUNT(*) as cotizaciones_mes FROM rfq WHERE award = 1 AND MONTH(fecha_award) =' . $i . ' AND YEAR(fecha_award) = YEAR(CURDATE())';
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
                    $sql = 'SELECT SUM(total_price) as monto FROM rfq WHERE award = 1 AND MONTH(fecha_award) =' . $i . ' AND YEAR(fecha_award) = YEAR(CURDATE())';
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

    public static function actualizar_rfq_2($conexion, $comments, $ship_via, $address, $ship_to, $id_rfq) {
        $rfq_editado = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET comments = :comments, ship_via = :ship_via, address = :address, ship_to = :ship_to WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':comments', $comments, PDO::PARAM_STR);
                $sentencia->bindParam(':ship_via', $ship_via, PDO::PARAM_STR);
                $sentencia->bindParam(':address', $address, PDO::PARAM_STR);
                $sentencia->bindParam(':ship_to', $ship_to, PDO::PARAM_STR);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }

    public static function actualizar_fecha_y_submitted($conexion, $id_rfq) {
        $rfq_editado = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET status = 1, fecha_submitted = NOW() WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }

    public static function actualizar_payment_terms($conexion, $payment_terms, $id_rfq) {
        $rfq_editado = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET payment_terms = :payment_terms WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':payment_terms', $payment_terms, PDO::PARAM_STR);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }

    public static function actualizar_fecha_y_completado($conexion, $fecha_completado , $expiration_date, $id_rfq) {
        $rfq_editado = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET fecha_completado = :fecha_completado, expiration_date = :expiration_date WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
                $sentencia->bindParam(':fecha_completado', $fecha_completado, PDO::PARAM_STR);
                $sentencia->bindParam(':expiration_date', $expiration_date, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }

    public static function check_completed($conexion, $id_rfq){
      $rfq_editado = false;
      if (isset($conexion)) {
          try {
              $sql = 'UPDATE rfq SET completado = 1 WHERE id = :id_rfq';
              $sentencia = $conexion->prepare($sql);
              $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

              $sentencia->execute();

              if ($sentencia) {
                  $rfq_editado = true;
              }
          } catch (PDOException $ex) {
              print 'ERROR:' . $ex->getMessage() . '<br>';
          }
      }
      return $rfq_editado;
    }

    public static function actualizar_fecha_y_award($conexion, $id_rfq) {
        $rfq_editado = false;
        if (isset($conexion)) {
            try {
                $sql = 'UPDATE rfq SET award = 1, fecha_award = NOW() WHERE id = :id_rfq';
                $sentencia = $conexion->prepare($sql);
                $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);

                $sentencia->execute();

                if ($sentencia) {
                    $rfq_editado = true;
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return $rfq_editado;
    }

    public static function obtener_comments($conexion){
        $no_bid = 0;
        $manufacturer_in_the_bid = 0;
        $expired_due_date = 0;
        $supplier_did_not_provide_a_quote = 0;
        $others = 0;

        if(isset($conexion)){
            try{
                $sql = 'SELECT COUNT(*) as no_bid FROM rfq WHERE comments = "No Bid" AND YEAR(fecha_completado) = YEAR(CURDATE())';
                $sql1 = 'SELECT COUNT(*) as manufacturer_in_bid FROM rfq WHERE comments = "Manufacturer in the Bid" AND YEAR(fecha_completado) = YEAR(CURDATE())';// AND YEAR(fecha_completado) = YEAR(CURDATE())
                $sql2 = 'SELECT COUNT(*) as expired_due_date FROM rfq WHERE comments = "Expired due date" AND YEAR(fecha_completado) = YEAR(CURDATE())';
                $sql3 = 'SELECT COUNT(*) as supplier_did_not_provide_a_quote FROM rfq WHERE comments = "Supplier did not provide a quote" AND YEAR(fecha_completado) = YEAR(CURDATE())';
                $sql4 = 'SELECT COUNT(*) as others FROM rfq WHERE comments = "Others" AND YEAR(fecha_completado) = YEAR(CURDATE())';

                $sentencia = $conexion-> prepare($sql);
                $sentencia1 = $conexion-> prepare($sql1);
                $sentencia2 = $conexion-> prepare($sql2);
                $sentencia3 = $conexion-> prepare($sql3);
                $sentencia4 = $conexion-> prepare($sql4);

                $sentencia-> execute();
                $sentencia1-> execute();
                $sentencia2-> execute();
                $sentencia3-> execute();
                $sentencia4-> execute();

                $resultado = $sentencia-> fetch();
                $resultado1 = $sentencia1-> fetch();
                $resultado2 = $sentencia2-> fetch();
                $resultado3 = $sentencia3-> fetch();
                $resultado4 = $sentencia4-> fetch();

                if(!empty($resultado)){
                    $no_bid = $resultado['no_bid'];
                }

                if(!empty($resultado1)){
                    $manufacturer_in_the_bid = $resultado1['manufacturer_in_bid'];
                }

                if(!empty($resultado2)){
                    $expired_due_date = $resultado2['expired_due_date'];
                }

                if(!empty($resultado3)){
                    $supplier_did_not_provide_a_quote = $resultado3['supplier_did_not_provide_a_quote'];
                }

                if(!empty($resultado4)){
                  $others = $resultado4['others'];
                }
            } catch (PDOException $ex) {
                print 'ERROR:' . $ex->getMessage() . '<br>';
            }
        }
        return array($no_bid, $manufacturer_in_the_bid, $expired_due_date, $supplier_did_not_provide_a_quote, $others);
    }

}
?>
