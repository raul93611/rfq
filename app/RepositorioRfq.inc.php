<?php
class RepositorioRfq {
  public static function insertar_cotizacion($conexion, $cotizacion) {
    $cotizacion_insertada = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, rfp, fullfillment, contract_number) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :rfp, :fullfillment, :contract_number)';
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
        $sentencia-> bindParam(':rfp', $cotizacion-> obtener_rfp(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fullfillment', $cotizacion-> obtener_fullfillment(), PDO::PARAM_STR);
        $sentencia-> bindParam(':contract_number', $cotizacion-> obtener_contract_number(), PDO::PARAM_STR);
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

  public static function insertar_cotizacion_fullfillment($conexion, $cotizacion) {
    $cotizacion_insertada = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO rfq(id, id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, rfp, fullfillment, contract_number) VALUES(:id, :id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :rfp, :fullfillment, :contract_number)';
        $sentencia = $conexion->prepare($sql);
        $sentencia-> bindParam(':id', $cotizacion-> obtener_id(), PDO::PARAM_STR);
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
        $sentencia-> bindParam(':rfp', $cotizacion-> obtener_rfp(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fullfillment', $cotizacion-> obtener_fullfillment(), PDO::PARAM_STR);
        $sentencia-> bindParam(':contract_number', $cotizacion-> obtener_contract_number(), PDO::PARAM_STR);
        $resultado = $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function email_code_existe($conexion, $email_code) {
    $email_code_existe = true;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM rfq WHERE email_code = :email_code';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':email_code', $email_code, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
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

  public static function get_all_submitted_quotes_between_dates($conexion, $date_from, $date_to){
    $quotes = [];
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE status = 1 AND completado = 1 AND fecha_submitted BETWEEN :date_from AND :date_to';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $sentencia-> bindParam(':date_to', $date_to, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $quotes[] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function get_all_award_quotes_between_dates($conexion, $date_from, $date_to){
    $quotes = [];
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE award =1 AND status = 1 AND completado = 1 AND fecha_award BETWEEN :date_from AND :date_to';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $sentencia-> bindParam(':date_to', $date_to, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $quotes[] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }
/*****************************************************************************************************************************/
/*****************************************************QUOTES*******************************************************************/
/*****************************************************************************************************************************/
  public static function obtener_cotizaciones_por_canal_usuario_cargo($conexion, $canal, $id_usuario, $cargo) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        if ($cargo < 5) {
          $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 0 AND status = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
          $sentencia = $conexion->prepare($sql);
          $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        } else if ($cargo > 4) {
          $sql = "SELECT * FROM rfq WHERE canal = :canal AND usuario_designado = :id_usuario AND completado = 0 AND status = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
          $sentencia = $conexion->prepare($sql);
          $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
          $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        }
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizaciones;
  }

  public static function escribir_cotizacion($cotizacion, $cargo, $id_usuario) {
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
      <?php
      if($cargo < 4){
        ?>
        <td class="text-center">
          <?php
          if(!$cotizacion-> obtener_rfp()){
            ?>
            <a href="<?php echo DELETE_QUOTE . '/' . $cotizacion->obtener_id(); ?>" class="delete_quote_button btn btn-sm btn-danger"><i class="fa fa-times"></i></a>
            <?php
          }
          ?>
        </td>
        <?php
      }
      ?>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_por_canal_usuario_cargo($canal, $id_usuario, $cargo) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_por_canal_usuario_cargo(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table id="tabla_quotes" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DESIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>PROPOSAL</th>
            <?php if($cargo < 5){echo '<th>ELIMINAR</th>';} ?>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($cotizaciones as $cotizacion) {
            self::escribir_cotizacion($cotizacion, $cargo, $id_usuario);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }
/******************************************************************************************************************/
/******************************************************************************************************************/
/******************************************************************************************************************/
  public static function obtener_cotizacion_por_id($conexion, $id_rfq) {
    $cotizacion_recuperada = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE id = :id_rfq";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $cotizacion_recuperada = new Rfq($resultado['id'], $resultado['id_usuario'], $resultado['usuario_designado'], $resultado['canal'], $resultado['email_code'], $resultado['type_of_bid'], $resultado['issue_date'], $resultado['end_date'], $resultado['status'], $resultado['completado'], $resultado['total_cost'], $resultado['total_price'], $resultado['comments'], $resultado['award'], $resultado['fecha_completado'], $resultado['fecha_submitted'], $resultado['fecha_award'], $resultado['payment_terms'], $resultado['address'], $resultado['ship_to'], $resultado['expiration_date'], $resultado['ship_via'], $resultado['taxes'], $resultado['profit'], $resultado['additional'], $resultado['shipping'], $resultado['shipping_cost'], $resultado['rfp'], $resultado['fullfillment'], $resultado['contract_number']);
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

  public static function check_fullfillment($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET fullfillment = 1 WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function establecer_id_rfp($conexion, $id_project, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET rfp = :id_project WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_project', $id_project, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function establecer_no_bid($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET comments = "No Bid" WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function establecer_no_comments($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET comments = "No comments" WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_end_date_address_ship_to($conexion, $end_date, $address, $ship_to, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET end_date = :end_date, address = :address, ship_to = :ship_to WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $sentencia-> bindParam(':address', $address, PDO::PARAM_STR);
        $sentencia-> bindParam(':ship_to', $ship_to, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function quitar_checks($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET completado = 0, status = 0, award = 0 WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_rfq_inicio($conexion, $email_code, $type_of_bid, $issue_date, $end_date, $canal, $contract_number, $id_rfq) {
    $cotizacion_editada = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET email_code = :email_code, type_of_bid = :type_of_bid, issue_date = :issue_date, end_date = :end_date, canal = :canal, contract_number = :contract_number WHERE id = :id_rfq";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':email_code', $email_code, PDO::PARAM_STR);
        $sentencia->bindParam(':type_of_bid', $type_of_bid, PDO::PARAM_STR);
        $sentencia->bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
        $sentencia->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $sentencia-> bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia-> bindParam(':contract_number', $contract_number, PDO::PARAM_STR);
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
/***********************************************************************************************************************/
/**************************************************COMPLETADOS************************************************************/
/***********************************************************************************************************************/
  public static function obtener_cotizaciones_completadas_por_canal($conexion, $canal, $id_usuario, $cargo) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 1 AND status = 0 AND award = 0 AND (comments = 'No comments' OR comments = 'Working on it') ORDER BY fecha_completado DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones[] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
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
      <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
      <td><?php echo $cotizacion->obtener_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($cotizacion->obtener_total_price(), 2); ?></td>
      <td><?php echo $fecha_completado; ?></td>
      <td><?php echo $cotizacion->obtener_id(); ?></td>
      <td><?php echo $cotizacion->obtener_comments(); ?></td>
      <?php
      if($cotizacion-> obtener_canal() != 'FedBid'){
        if ($cotizacion->obtener_canal() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td><?php if($cotizacion-> obtener_rfp()){echo '<span class="text-info">Yes</span>';}else{echo '<span class="text-danger">No</span>';} ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_completadas_por_canal($canal, $id_usuario, $cargo) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_completadas_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>COMPLETED DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($canal != 'FedBid'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <td>RFP</td>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($cotizaciones as $cotizacion){
            self::escribir_cotizacion_completada($cotizacion);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }
/***************************************************************************************************************************************/
/********************************************************SOMETIDOS**********************************************************************/
/***************************************************************************************************************************************/
  public static function obtener_cotizaciones_submitted_por_canal($conexion, $canal, $id_usuario, $cargo) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 0 AND canal = :canal AND comments = 'No comments' ORDER BY fecha_submitted DESC LIMIT 100";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
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
      <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
      <td><?php echo $cotizacion->obtener_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($cotizacion->obtener_total_price(), 2); ?></td>
      <td><?php echo $fecha_submitted; ?></td>
      <td><?php echo $cotizacion->obtener_id(); ?></td>
      <td><?php echo $cotizacion->obtener_comments(); ?></td>
      <?php
      if($cotizacion-> obtener_canal() != 'FedBid'){
        if ($cotizacion->obtener_canal() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td><?php if($cotizacion-> obtener_rfp()){echo '<span class="text-info">Yes</span>';}else{echo '<span class="text-danger">No</span>';} ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_submitted_por_canal($canal, $id_usuario, $cargo) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_submitted_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table  id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>SUBMITTED DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($canal != 'FedBid'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <td>RFP</td>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($cotizaciones as $cotizacion){
            self::escribir_cotizacion_submitted($cotizacion);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }
/***********************************************************************************************************************/
/**************************************************GANADOS**************************************************************/
/***********************************************************************************************************************/
  public static function obtener_cotizaciones_award_por_canal($conexion, $canal, $id_usuario, $cargo) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 1 AND fullfillment = 0 AND canal = :canal AND (comments = 'No comments' OR comments = 'QuickBooks') ORDER BY fecha_award DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
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
    <tr <?php if($cotizacion->obtener_comments() == 'QuickBooks'){echo 'class="quickbooks"';} ?>>
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
      <td><?php echo $cotizacion->obtener_issue_date(); ?></td>
      <td><?php echo $cotizacion->obtener_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($cotizacion->obtener_total_price(), 2); ?></td>
      <td><?php echo $fecha_award; ?></td>
      <td><?php echo $cotizacion->obtener_id(); ?></td>
      <td><?php echo $cotizacion->obtener_comments(); ?></td>
      <?php
      if($cotizacion-> obtener_canal() != 'FedBid' && $cotizacion-> obtener_canal() != 'Chemonics' && $cotizacion-> obtener_canal() != 'Ebay & Amazon'){
        if ($cotizacion->obtener_canal() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td><?php if($cotizacion-> obtener_rfp()){echo '<span class="text-info">Yes</span>';}else{echo '<span class="text-danger">No</span>';} ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_award_por_canal($canal, $id_usuario, $cargo) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_award_por_canal(Conexion::obtener_conexion(), $canal, $id_usuario, $cargo);
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>AWARD DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($canal != 'FedBid' && $canal != 'Chemonics' && $canal != 'Ebay & Amazon'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
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
/*************************************************************************************************************/
/*******************************************NO BID************************************************************/
/*************************************************************************************************************/
  public static function obtener_cotizaciones_no_bid($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'No Bid' OR comments = 'Manufacturer in the Bid' OR comments = 'Expired due date' OR comments = 'Supplier did not provide a quote' OR comments = 'Others' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
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
      <table id="tabla" class="table table-bordered table-responsive-md">
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
        <tbody>
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
/*************************************************************************************************************/
/*******************************************CANCELLED************************************************************/
/*************************************************************************************************************/
  public static function obtener_cotizaciones_cancelled($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'Cancelled' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizaciones;
  }

  public static function escribir_cotizaciones_cancelled() {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_cancelled(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
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
        <tbody>
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
/**************************************************************************************************************************/
/****************************************************NO SUBMITTED**********************************************************/
/**************************************************************************************************************************/
  public static function obtener_cotizaciones_no_submitted($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'Not submitted' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizaciones;
  }

  public static function escribir_tabla_cotizaciones_no_submitted(){
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_no_submitted(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if(count($cotizaciones)){
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
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
        <tbody>
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
/**************************************************************************************************************************************/
/***************************************************RESULTADO DE BUSQUEDA**************************************************************/
/**************************************************************************************************************************************/
  public static function escribir_cotizacion_resultado_busqueda($cotizacion) {
    if (!isset($cotizacion)) {
      return;
    }
    ?>
    <tr <?php if($cotizacion->obtener_comments() == 'QuickBooks'){echo 'class="quickbooks"';} ?>>
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
      <td><?php echo $cotizacion->obtener_id(); ?></td>
      <td><?php echo $cotizacion->obtener_comments(); ?></td>
      <td>$ <?php echo $cotizacion-> obtener_total_price(); ?></td>
      <?php
      if($cotizacion-> obtener_canal() != 'FedBid'){
        if ($cotizacion->obtener_canal() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }else{
        ?><td></td><?php
      }
      ?>
    </tr>
    <?php
  }

  public static function obtener_resultados_busqueda($conexion, $termino_busqueda, $cargo, $usuario_designado) {
    $cotizaciones = [];
    $termino_busqueda = '%' . trim($termino_busqueda) . '%';
    if (isset($conexion)) {
      try {
        if($cargo <= 3){
          $sql = 'SELECT * FROM rfq WHERE (contract_number LIKE :termino_busqueda OR id LIKE :termino_busqueda OR email_code LIKE :termino_busqueda OR total_price LIKE :termino_busqueda OR address LIKE :termino_busqueda OR ship_to LIKE :termino_busqueda)';
          $sentencia = $conexion-> prepare($sql);
          $sentencia-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia->execute();
          $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
          $sql1 = 'SELECT * FROM rfq INNER JOIN item ON rfq.id = item.id_rfq WHERE (item.brand LIKE :termino_busqueda OR item.brand_project LIKE :termino_busqueda OR item.part_number LIKE :termino_busqueda OR item.part_number_project LIKE :termino_busqueda OR item.description LIKE :termino_busqueda OR item.description_project LIKE :termino_busqueda)';
          $sentencia1 = $conexion-> prepare($sql1);
          $sentencia1-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia1-> execute();
          $resultado1 = $sentencia1-> fetchAll(PDO::FETCH_ASSOC);
          $sql2 = 'SELECT * FROM rfq INNER JOIN item ON rfq.id = item.id_rfq INNER JOIN subitems ON item.id = subitems.id_item WHERE (subitems.brand LIKE :termino_busqueda OR subitems.brand_project LIKE :termino_busqueda OR subitems.part_number LIKE :termino_busqueda OR subitems.part_number_project LIKE :termino_busqueda OR subitems.description LIKE :termino_busqueda OR subitems.description_project LIKE :termino_busqueda)';
          $sentencia2 = $conexion-> prepare($sql2);
          $sentencia2-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia2-> execute();
          $resultado2 = $sentencia2-> fetchAll(PDO::FETCH_ASSOC);

          if (count($resultado)) {
            foreach ($resultado as $fila) {
              $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
            }
          }

          if(count($resultado1)){
            foreach ($resultado1 as $fila1) {
              Conexion::abrir_conexion();
              $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $fila1['id_rfq']);
              Conexion::cerrar_conexion();
              $cotizaciones[] = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_fecha_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment(), $cotizacion-> obtener_contract_number());
            }
          }

          if(count($resultado2)){
            foreach ($resultado2 as $fila2) {
              Conexion::abrir_conexion();
              $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $fila2['id_rfq']);
              Conexion::cerrar_conexion();
              $cotizaciones[] = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_fecha_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment(), $cotizacion-> obtener_contract_number());
            }
          }
        }else if($cargo >= 4){
          $sql = 'SELECT * FROM rfq WHERE (completado = 1 OR status = 1 OR usuario_designado = :usuario_designado) AND (id LIKE :termino_busqueda OR email_code LIKE :termino_busqueda OR total_price LIKE :termino_busqueda)';
          $sentencia = $conexion-> prepare($sql);
          $sentencia-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia-> bindParam(':usuario_designado', $usuario_designado, PDO::PARAM_STR);
          $sentencia->execute();
          $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
          $sql1 = 'SELECT * FROM rfq INNER JOIN item ON rfq.id = item.id_rfq WHERE (rfq.completado = 1 OR rfq.status = 1 OR rfq.usuario_designado = :usuario_designado) AND (item.part_number LIKE :termino_busqueda OR item.part_number_project LIKE :termino_busqueda OR item.description LIKE :termino_busqueda OR item.description_project LIKE :termino_busqueda)';
          $sentencia1 = $conexion-> prepare($sql1);
          $sentencia1-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia1-> bindParam(':usuario_designado', $usuario_designado, PDO::PARAM_STR);
          $sentencia1-> execute();
          $resultado1 = $sentencia1-> fetchAll(PDO::FETCH_ASSOC);
          $sql2 = 'SELECT * FROM rfq INNER JOIN item ON rfq.id = item.id_rfq INNER JOIN subitems ON item.id = subitems.id_item WHERE (rfq.completado = 1 OR rfq.status = 1 OR rfq.usuario_designado = :usuario_designado) AND (subitems.part_number LIKE :termino_busqueda OR subitems.part_number_project LIKE :termino_busqueda OR subitems.description LIKE :termino_busqueda OR subitems.description_project LIKE :termino_busqueda)';
          $sentencia2 = $conexion-> prepare($sql2);
          $sentencia2-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
          $sentencia2-> bindParam(':usuario_designado', $usuario_designado, PDO::PARAM_STR);
          $sentencia2-> execute();
          $resultado2 = $sentencia2-> fetchAll(PDO::FETCH_ASSOC);

          if (count($resultado)) {
            foreach ($resultado as $fila) {
              $cotizaciones [] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
            }
          }

          if(count($resultado1)){
            foreach ($resultado1 as $fila1) {
              Conexion::abrir_conexion();
              $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $fila1['id_rfq']);
              Conexion::cerrar_conexion();
              $cotizaciones[] = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_fecha_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment(), $cotizacion-> obtener_contract_number());
            }
          }

          if(count($resultado2)){
            foreach ($resultado2 as $fila2) {
              Conexion::abrir_conexion();
              $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $fila2['id_rfq']);
              Conexion::cerrar_conexion();
              $cotizaciones[] = new Rfq($cotizacion-> obtener_id(), $cotizacion-> obtener_id_usuario(), $cotizacion-> obtener_usuario_designado(), $cotizacion-> obtener_canal(), $cotizacion-> obtener_email_code(), $cotizacion-> obtener_type_of_bid(), $cotizacion-> obtener_issue_date(), $cotizacion-> obtener_end_date(), $cotizacion-> obtener_status(), $cotizacion-> obtener_completado(), $cotizacion-> obtener_total_cost(), $cotizacion-> obtener_total_price(), $cotizacion-> obtener_comments(), $cotizacion-> obtener_award(), $cotizacion-> obtener_fecha_completado(), $cotizacion-> obtener_fecha_submitted(), $cotizacion-> obtener_fecha_award(), $cotizacion-> obtener_payment_terms(), $cotizacion-> obtener_address(), $cotizacion-> obtener_ship_to(), $cotizacion-> obtener_expiration_date(), $cotizacion-> obtener_ship_via(), $cotizacion-> obtener_taxes(), $cotizacion-> obtener_profit(), $cotizacion-> obtener_additional(), $cotizacion-> obtener_shipping(), $cotizacion-> obtener_shipping_cost(), $cotizacion-> obtener_rfp(), $cotizacion-> obtener_fullfillment(), $cotizacion-> obtener_contract_number());
            }
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizaciones;
  }

  public static function escribir_resultados_busqueda($cotizaciones) {
    ?>
    <table id="tabla_busqueda" class="table table-bordered table-responsive-md">
      <thead>
        <tr>
          <th>CODE</th>
          <th>DEDIGNATED USER</th>
          <th>TYPE OF BID</th>
          <th>PROPOSAL</th>
          <th>COMMENTS</th>
          <th>AMOUNT</th>
          <th>GENERATE PROPOSAL</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($cotizaciones as $cotizacion) {
          self::escribir_cotizacion_resultado_busqueda($cotizacion);
        }
        ?>
      </tbody>
    </table>
    <?php
  }
/*************************************************************************************************************************/
/*********************************************REPORTES********************************************************************/
/*************************************************************************************************************************/
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
/******************************************************************************************************************************/
/******************************************************************************************************************************/
/******************************************************************************************************************************/
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

  public static function guardar_total_price_total_cost_fedbid($conexion, $total_cost_fedbid, $total_price_fedbid, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET total_cost = :total_cost_fedbid, total_price = :total_price_fedbid WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':total_cost_fedbid', $total_cost_fedbid,PDO::PARAM_STR);
        $sentencia-> bindParam(':total_price_fedbid', $total_price_fedbid, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function guardar_total_price_chemonics($conexion, $total_price_chemonics, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET total_price = :total_price_chemonics WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':total_price_chemonics', $total_price_chemonics, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_quote($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'DELETE FROM rfq WHERE id = :id_rfq';
        $sentencia= $conexion->prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }
    /*******************************************************************************************************************/
    /***********************************************RFP CONNECTION*****************************************************/
    /*******************************************************************************************************************/
  public static function quote_rfq_exists($conexion, $id_project){
    $quote_rfq_exists = true;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE rfp = :id_project';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_project', $id_project, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
        if(count($resultado)){
          $quote_rfq_exists = true;
        }else{
          $quote_rfq_exists = false;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quote_rfq_exists;
  }

  public static function obtener_cotizacion_por_id_project($conexion, $id_project){
    $rfp_quote = null;
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE rfp = :id_project';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_project', $id_project, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetch(PDO::FETCH_ASSOC);
        if(!empty($resultado)){
          $rfp_quote = new Rfq($resultado['id'], $resultado['id_usuario'], $resultado['usuario_designado'], $resultado['canal'], $resultado['email_code'], $resultado['type_of_bid'], $resultado['issue_date'], $resultado['end_date'], $resultado['status'], $resultado['completado'], $resultado['total_cost'], $resultado['total_price'], $resultado['comments'], $resultado['award'], $resultado['fecha_completado'], $resultado['fecha_submitted'], $resultado['fecha_award'], $resultado['payment_terms'], $resultado['address'], $resultado['ship_to'], $resultado['expiration_date'], $resultado['ship_via'], $resultado['taxes'], $resultado['profit'], $resultado['additional'], $resultado['shipping'], $resultado['shipping_cost'], $resultado['rfp'], $resultado['fullfillment'], $resultado['contract_number']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $rfp_quote;
  }

  public static function obtener_todas_cotizaciones_rfp($conexion){
    $rfp_quotes = [];
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE rfp != 0 AND fullfillment = 1';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> execute();
        $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
        if(count($resultado)){
          foreach ($resultado as $fila) {
            $rfp_quotes[] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $rfp_quotes;
  }

    /*****************************************************************************************************************************************/
    /*****************************************************COTIZACIONES RFP********************************************************************/
    /*****************************************************************************************************************************************/
  public static function obtener_cotizaciones_rfp($conexion, $cargo, $id_usuario){
    $rfp_quotes = [];
    if (isset($conexion)) {
      try {
        if ($cargo < 4) {
          $sql = "SELECT * FROM rfq WHERE rfp != 0 AND canal = '' AND completado = 0 AND status = 0 AND award = 0 ORDER BY id DESC";
          $sentencia = $conexion->prepare($sql);
        } else if ($cargo > 3) {
          $sql = "SELECT * FROM rfq WHERE rfp != 0 AND canal = '' AND usuario_designado = :id_usuario AND completado = 0 AND status = 0 AND award = 0 ORDER BY id DESC";
          $sentencia = $conexion->prepare($sql);
          $sentencia->bindParam(':id_usuario', $id_usuario, PDO::PARAM_STR);
        }
        $sentencia->execute();
        $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $rfp_quotes[] = new Rfq($fila['id'], $fila['id_usuario'], $fila['usuario_designado'], $fila['canal'], $fila['email_code'], $fila['type_of_bid'], $fila['issue_date'], $fila['end_date'], $fila['status'], $fila['completado'], $fila['total_cost'], $fila['total_price'], $fila['comments'], $fila['award'], $fila['fecha_completado'], $fila['fecha_submitted'], $fila['fecha_award'], $fila['payment_terms'], $fila['address'], $fila['ship_to'], $fila['expiration_date'], $fila['ship_via'], $fila['taxes'], $fila['profit'], $fila['additional'], $fila['shipping'], $fila['shipping_cost'], $fila['rfp'], $fila['fullfillment'], $fila['contract_number']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $rfp_quotes;
  }

  public static function escribir_cotizaciones_rfp($cargo, $id_usuario) {
    Conexion::abrir_conexion();
    $cotizaciones_rfp = self::obtener_cotizaciones_rfp(Conexion::obtener_conexion(), $cargo, $id_usuario);
    Conexion::cerrar_conexion();
    if (count($cotizaciones_rfp)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
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
        <tbody>
          <?php
          foreach ($cotizaciones_rfp as $cotizacion) {
            self::escribir_cotizacion_no_bid($cotizacion);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_final_quote($id_rfq){
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if($cotizacion-> obtener_canal() == 'FedBid'){
      ?>
      <div class="row">
        <div class="col-md-6">
          <label>Total cost:</label>
        </div>
        <div class="col-md-6">
          $ <?php echo $cotizacion-> obtener_total_cost(); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label>Total price:</label>
        </div>
        <div class="col-md-6">
          $ <?php echo $cotizacion-> obtener_total_price(); ?>
        </div>
      </div>
      <?php
    }else{
      ?>
      <div class="row">
        <div class="col-md-3">
          <b>Taxes:</b> <?php echo $cotizacion-> obtener_taxes(); ?>%<br>
        </div>
        <div class="col-md-3">
          <b>Profit:</b> <?php echo $cotizacion-> obtener_profit(); ?>%<br>
        </div>
        <div class="col-md-3">
          <b>Additional general:</b> $ <?php echo $cotizacion-> obtener_additional(); ?>
        </div>
        <div class="col-md-3">
          <b>Payment terms:</b> <?php echo $cotizacion-> obtener_payment_terms(); ?>
        </div>
      </div>
      <br>
      <?php
      if (count($items)) {
        ?>
        <table class="table table-bordered" style="width:100%;">
          <tr>
            <th class="quantity">#</th>
            <th style="width:200px;">PROJECT ESPC.</th>
            <th style="width:200px;">E-LOGIC PROP.</th>
            <th>WEBSITE</th>
            <th class="quantity">QTY</th>
            <th>PROVIDER</th>
            <th>ADDITIONAL</th>
            <th>BEST UNIT COST</th>
            <th>TOTAL COST</th>
            <th>PRICE FOR CLIENT</th>
            <th>TOTAL PRICE</th>
          </tr>
        <?php
        $a = 1;
        if($cotizacion-> obtener_payment_terms() == 'Net 30/CC'){
          $payment_terms = 1.0299;
        }else{
          $payment_terms = 1;
        }
        for ($i = 0; $i < count($items); $i++) {
          $item = $items[$i];
          ?>
          <tr>
            <td><?php echo $a; ?></td>
            <td><b>Brand name:</b><?php echo $item-> obtener_brand_project(); ?><br><b>Part number:</b><?php echo $item-> obtener_part_number_project(); ?><br><b>Item description:</b><?php echo nl2br(mb_substr($item-> obtener_description_project(), 0, 150)); ?></td>
            <td><b>Brand name:</b><?php echo $item->obtener_brand(); ?><br><b>Part number:</b><?php echo $item->obtener_part_number(); ?><br><b> Item description:</b><br><?php echo nl2br(mb_substr($item->obtener_description(), 0, 150)); ?></td>
            <td><a target="_blank" href="<?php echo $item-> obtener_website(); ?>">Provider Website</a></td>
            <td style="text-align:right;"><?php echo $item->obtener_quantity(); ?></td>
            <td style="width: 200px;">
          <?php
          Conexion::abrir_conexion();
          $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
          Conexion::cerrar_conexion();
          if(count($providers)){
            Conexion::abrir_conexion();
            $provider_menor = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $item-> obtener_provider_menor());
            Conexion::cerrar_conexion();
            if(count($providers)){
              foreach ($providers as $provider) {
                ?>
                <div class="row">
                  <div class="col-md-6">
                    <b><?php echo $provider-> obtener_provider(); ?>:</b>
                  </div>
                  <div class="col-md-6">
                    $ <?php echo number_format($provider-> obtener_price(), 2); ?>
                  </div>
                </div>
                <?php
              }
            }
            ?>
            </td>
            <td>$ <?php echo number_format($item-> obtener_additional(), 2); ?></td>
            <td>$
            <?php
            $best_unit_price = $provider_menor-> obtener_price()*$payment_terms*(1+($cotizacion-> obtener_taxes()/100)) + $item-> obtener_additional() + $cotizacion-> obtener_additional();
            echo number_format($best_unit_price, 2);
            ?>
            </td>
            <td>$ <?php echo number_format(round($best_unit_price, 2) * $item-> obtener_quantity(), 2); ?></td>
            <td style="text-align:right;">$ <?php echo number_format($item->obtener_unit_price(), 2); ?></td>
            <td style="text-align:right;">$ <?php echo number_format($item->obtener_total_price(), 2); ?></td>
            <?php
          }else{
            ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php
          }
          ?>
        </tr>
          <?php
          Conexion::abrir_conexion();
          $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
          Conexion::cerrar_conexion();
          for($j = 0; $j < count($subitems); $j++){
            $subitem = $subitems[$j];
            ?>
        <tr>
          <td></td>
          <td><b>Brand name:</b><?php echo $subitem-> obtener_brand_project(); ?><br><b>Part number:</b><?php echo $subitem-> obtener_part_number_project(); ?><br><b>Item description:</b><br><?php echo nl2br(mb_substr($subitem->obtener_description_project(), 0, 150)); ?></td>
          <td><b>Brand name:</b><?php echo $subitem->obtener_brand(); ?><br><b>Part number:</b> <?php echo $item->obtener_part_number(); ?><br><b> Item description:</b><br> <?php echo nl2br(mb_substr($item->obtener_description(), 0, 150)); ?></td>
          <td><a target="_blank" href="<?php echo $subitem-> obtener_website(); ?>">Provider Website</a></td>
          <td style="text-align:right;"><?php echo $subitem-> obtener_quantity(); ?></td>
            <?php
              Conexion::abrir_conexion();
              $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
              Conexion::cerrar_conexion();
              if(count($providers_subitem)){
                ?>
          <td>
                <?php
                  Conexion::abrir_conexion();
                  $provider_subitem_menor = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $subitem-> obtener_provider_menor());
                  Conexion::cerrar_conexion();
                  if(count($providers_subitem)){
                    foreach ($providers_subitem as $provider_subitem) {
                      ?>
                      <div class="row">
                        <div class="col-md-6">
                          <b><?php echo $provider_subitem-> obtener_provider(); ?>:</b>
                        </div>
                        <div class="col-md-6">
                          $ <?php echo $provider_subitem-> obtener_price(); ?>
                        </div>
                      </div>
                      <?php
                    }
                  }
                  ?>
          </td>
          <td>$ <?php echo number_format($subitem-> obtener_additional(), 2); ?></td>
          <td>$
                  <?php
                  $best_unit_price = $provider_subitem_menor-> obtener_price()*$payment_terms*(1+($cotizacion-> obtener_taxes()/100)) + $subitem-> obtener_additional() + $cotizacion-> obtener_additional();
                  echo number_format($best_unit_price, 2);
                  ?>
          </td>
          <td>$ <?php echo number_format(round($best_unit_price, 2) * $subitem-> obtener_quantity(), 2); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($subitem-> obtener_unit_price(), 2); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($subitem-> obtener_total_price(), 2); ?></td>
                  <?php
                }else{
                  ?>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
                  <?php
                }
                ?>
        </tr>
                <?php
              }
              $a++;
            }
      ?>
        <tr>
          <td style="border:none;"></td>
          <td colspan="8" style="font-size:10pt;"><?php echo nl2br($cotizacion->obtener_shipping()); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($cotizacion->obtener_shipping_cost(), 2); ?></td>
        </tr>
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="font-size:12pt;">TOTAL:</td>
          <td>$ <?php echo number_format($cotizacion-> obtener_total_cost(), 2); ?></td>
          <td></td>
          <td style="font-size:12pt;text-align:right;">$ <?php echo number_format($cotizacion->obtener_total_price(), 2); ?></td>
        </tr>
      </table>
      <?php
      }
    }
  }

  public static function print_fulfillment_quotes(){
    Conexion::abrir_conexion();
    $quotes = self::get_all_fulfillment_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if(count($quotes)){
      ?>
      <table class="fulfillment_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>AMOUNT(RE-QUOTE)</th>
            <th>FULFILLMENT DATE</th>
            <th>AWARD DATE</th>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_fulfillment_quote($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_fulfillment_quote($quote){
    if(!isset($quote)){
      return;
    }
    ConnectionFullFillment::open_connection();
    $rfq_fullfillment_part = RfqFullFillmentPartRepository::get_rfq_fullfillment_part_by_id_rfq(ConnectionFullFillment::get_connection(), $quote-> obtener_id());
    ConnectionFullFillment::close_connection();
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $quote-> obtener_id());
    Conexion::cerrar_conexion();
    $fulfillment_date = RepositorioRfqFullFillmentComment::mysql_datetime_to_english_format($rfq_fullfillment_part-> get_fullfillment_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>" class="btn-block">
          <?php echo $quote-> obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote-> obtener_email_code(); ?></td>
      <td><?php echo $quote-> obtener_canal(); ?></td>
      <td>$ <?php echo $re_quote-> get_total_price() ?></td>
      <td><?php echo $fulfillment_date; ?></td>
      <td><?php echo $quote-> obtener_fecha_award(); ?></td>
      <td><?php if($quote-> obtener_rfp()){echo '<span class="text-info">Yes</span>';}else{echo '<span class="text-danger">No</span>';} ?></td>
    </tr>
    <?php
  }

  public static function get_all_fulfillment_quotes($connection){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE fullfillment = 1';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $quotes[] = new Rfq($row['id'], $row['id_usuario'], $row['usuario_designado'], $row['canal'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['status'], $row['completado'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['fecha_completado'], $row['fecha_submitted'], $row['fecha_award'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['rfp'], $row['fullfillment'], $row['contract_number']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function remove_award($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET award = 0 WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_fulfillment($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET fullfillment = 0 WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
