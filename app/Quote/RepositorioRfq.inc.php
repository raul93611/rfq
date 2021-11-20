<?php
class RepositorioRfq {
  public static function insertar_cotizacion($conexion, $cotizacion) {
    $cotizacion_insertada = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, fullfillment, fulfillment_date, contract_number, fulfillment_profit, services_fulfillment_profit, total_fulfillment, total_services_fulfillment, invoice, invoice_date, multi_year_project, submitted_invoice, submitted_invoice_date, fulfillment_pending, fulfillment_shipping_cost, fulfillment_shipping) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :fullfillment, :fulfillment_date, :contract_number, :fulfillment_profit, :services_fulfillment_profit, :total_fulfillment, :total_services_fulfillment, :invoice, :invoice_date, :multi_year_project, :submitted_invoice, :submitted_invoice_date, :fulfillment_pending, :fulfillment_shipping_cost, :fulfillment_shipping)';
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
        $sentencia-> bindParam(':fullfillment', $cotizacion-> obtener_fullfillment(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fulfillment_date', $cotizacion-> obtener_fulfillment_date(), PDO::PARAM_STR);
        $sentencia-> bindParam(':contract_number', $cotizacion-> obtener_contract_number(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fulfillment_profit', $cotizacion-> obtener_fulfillment_profit(), PDO::PARAM_STR);
        $sentencia-> bindParam(':services_fulfillment_profit', $cotizacion-> obtener_services_fulfillment_profit(), PDO::PARAM_STR);
        $sentencia-> bindParam(':total_fulfillment', $cotizacion-> obtener_total_fulfillment(), PDO::PARAM_STR);
        $sentencia-> bindParam(':total_services_fulfillment', $cotizacion-> obtener_total_services_fulfillment(), PDO::PARAM_STR);
        $sentencia-> bindParam(':invoice', $cotizacion-> obtener_invoice(), PDO::PARAM_STR);
        $sentencia-> bindParam(':invoice_date', $cotizacion-> obtener_invoice_date(), PDO::PARAM_STR);
        $sentencia-> bindParam(':multi_year_project', $cotizacion-> obtener_multi_year_project(), PDO::PARAM_STR);
        $sentencia-> bindParam(':submitted_invoice', $cotizacion-> obtener_submitted_invoice(), PDO::PARAM_STR);
        $sentencia-> bindParam(':submitted_invoice_date', $cotizacion-> obtener_submitted_invoice_date(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fulfillment_pending', $cotizacion-> obtener_fulfillment_pending(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fulfillment_shipping_cost', $cotizacion-> obtener_fulfillment_shipping_cost(), PDO::PARAM_STR);
        $sentencia-> bindParam(':fulfillment_shipping', $cotizacion-> obtener_fulfillment_shipping(), PDO::PARAM_STR);
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

  public static function insert_calc($connection, $id_items, $id_subitems, $total_prices, $subitem_total_prices, $unit_prices, $subitem_unit_prices, $additionals, $additional_subitems){
    $id_items = explode(',', $id_items);
    $id_subitems = explode(',', $id_subitems);
    $total_prices = explode(',', $total_prices);
    $subitem_total_prices = explode(',', $subitem_total_prices);
    $unit_prices = explode(',', $unit_prices);
    $subitem_unit_prices = explode(',', $subitem_unit_prices);
    $additionals = explode(',', $additionals);
    $additional_subitems = explode(',', $additional_subitems);
    foreach ($id_items as $i => $id_item) {
      RepositorioItem::insertar_calculos($connection, $unit_prices[$i], $total_prices[$i], $additionals[$i], $id_item);
    }
    foreach ($id_subitems as $i => $id_subitem) {
      RepositorioSubitem::insertar_calculos($connection, $subitem_unit_prices[$i], $subitem_total_prices[$i], $additional_subitems[$i], $id_subitem);
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

  public static function array_to_object($sentence){
    $objects = [];
    while ($result = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Rfq($result['id'], $result['id_usuario'], $result['usuario_designado'], $result['canal'], $result['email_code'], $result['type_of_bid'], $result['issue_date'], $result['end_date'], $result['status'], $result['completado'], $result['total_cost'], $result['total_price'], $result['comments'], $result['award'], $result['fecha_completado'], $result['fecha_submitted'], $result['fecha_award'], $result['payment_terms'], $result['address'], $result['ship_to'], $result['expiration_date'], $result['ship_via'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping'], $result['shipping_cost'], $result['fullfillment'], $result['fulfillment_date'], $result['contract_number'], $result['fulfillment_profit'], $result['services_fulfillment_profit'], $result['total_fulfillment'], $result['total_services_fulfillment'], $result['invoice'], $result['invoice_date'], $result['multi_year_project'], $result['submitted_invoice'], $result['submitted_invoice_date'], $result['fulfillment_pending'], $result['fulfillment_shipping_cost'], $result['fulfillment_shipping']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $result = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new Rfq($result['id'], $result['id_usuario'], $result['usuario_designado'], $result['canal'], $result['email_code'], $result['type_of_bid'], $result['issue_date'], $result['end_date'], $result['status'], $result['completado'], $result['total_cost'], $result['total_price'], $result['comments'], $result['award'], $result['fecha_completado'], $result['fecha_submitted'], $result['fecha_award'], $result['payment_terms'], $result['address'], $result['ship_to'], $result['expiration_date'], $result['ship_via'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping'], $result['shipping_cost'], $result['fullfillment'], $result['fulfillment_date'], $result['contract_number'], $result['fulfillment_profit'], $result['services_fulfillment_profit'], $result['total_fulfillment'], $result['total_services_fulfillment'], $result['invoice'], $result['invoice_date'], $result['multi_year_project'], $result['submitted_invoice'], $result['submitted_invoice_date'], $result['fulfillment_pending'], $result['fulfillment_shipping_cost'], $result['fulfillment_shipping']);

    return $object;
  }

  public static function get_child_quotes($conexion, $id_parent){
    $quotes = [];
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM rfq WHERE multi_year_project = :multi_year_project';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':multi_year_project', $id_parent, PDO::PARAM_STR);
        $sentencia-> execute();
        $quotes = self::array_to_object($sentencia);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
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
        $quotes = self::array_to_object($sentencia);
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
        $quotes = self::array_to_object($sentencia);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function obtener_cotizaciones_por_canal_usuario_cargo($conexion, $canal) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 0 AND status = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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
            <?php echo $cotizacion->obtener_id(); ?>
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
      <td><?php echo $cotizacion->obtener_email_code(); ?></td>
      <td class="text-center"><?php if($cotizacion-> obtener_type_of_bid() == 'Services'){echo '<i class="text-success fas fa-check"></i>';}else{echo '<i class="text-danger fas fa-times"></i>';} ?></td>
      <td class="text-center">
        <a href="<?php echo DELETE_QUOTE . '/' . $cotizacion->obtener_id(); ?>" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>
      </td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_por_canal_usuario_cargo($canal) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_por_canal_usuario_cargo(Conexion::obtener_conexion(), $canal);
    Conexion::cerrar_conexion();
    if (count($cotizaciones)) {
      ?>
      <table id="tabla_quotes" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>DESIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>CODE</th>
            <th>RFP</th>
            <th>OPTIONS</th>
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

  public static function obtener_cotizacion_por_id($conexion, $id_rfq) {
    $cotizacion_recuperada = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE id = :id_rfq";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizacion_recuperada = self::single_result_to_object($sentencia);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizacion_recuperada;
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

  public static function update_fulfillment_shipping($connection, $fulfillment_shipping, $fulfillment_shipping_cost, $id_rfq){
    if(isset($connection)){
      try{
        $sql = 'UPDATE rfq SET fulfillment_shipping = :fulfillment_shipping, fulfillment_shipping_cost = :fulfillment_shipping_cost WHERE id = :id_rfq';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> bindParam(':fulfillment_shipping', $fulfillment_shipping, PDO::PARAM_STR);
        $sentence-> bindParam(':fulfillment_shipping_cost', $fulfillment_shipping_cost, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_rfq_inicio($conexion, $expiration_date, $fecha_completado, $ship_to, $address, $ship_via, $comments, $designated_user, $email_code, $type_of_bid, $issue_date, $end_date, $canal, $contract_number, $id_rfq) {
    $cotizacion_editada = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET expiration_date = :expiration_date, fecha_completado = :fecha_completado, ship_to = :ship_to, address = :address, ship_via = :ship_via, comments = :comments, usuario_designado = :usuario_designado, email_code = :email_code, type_of_bid = :type_of_bid, issue_date = :issue_date, end_date = :end_date, canal = :canal, contract_number = :contract_number WHERE id = :id_rfq";
        $sentencia = $conexion->prepare($sql);
        $sentencia-> bindParam(':usuario_designado', $designated_user, PDO::PARAM_STR);
        $sentencia-> bindParam(':comments', $comments, PDO::PARAM_STR);
        $sentencia-> bindParam(':ship_via', $ship_via, PDO::PARAM_STR);
        $sentencia-> bindParam(':address', $address, PDO::PARAM_STR);
        $sentencia-> bindParam(':ship_to', $ship_to, PDO::PARAM_STR);
        $sentencia-> bindParam(':fecha_completado', $fecha_completado, PDO::PARAM_STR);
        $sentencia-> bindParam(':expiration_date', $expiration_date, PDO::PARAM_STR);
        $sentencia-> bindParam(':email_code', $email_code, PDO::PARAM_STR);
        $sentencia-> bindParam(':type_of_bid', $type_of_bid, PDO::PARAM_STR);
        $sentencia-> bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
        $sentencia-> bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $sentencia-> bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia-> bindParam(':contract_number', $contract_number, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
        if ($sentencia) {
          $cotizacion_editada = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizacion_editada;
  }

  public static function update_variables($connection, $payment_terms, $taxes, $profit, $total_cost, $total_price, $additional, $shipping, $shipping_cost, $id_rfq) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE rfq SET payment_terms = :payment_terms, taxes = :taxes, profit = :profit, total_cost = :total_cost, total_price = :total_price, additional = :additional, shipping = :shipping, shipping_cost = :shipping_cost WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindParam(':payment_terms', $payment_terms, PDO::PARAM_STR);
        $sentence->bindParam(':taxes', $taxes, PDO::PARAM_STR);
        $sentence->bindParam(':profit', $profit, PDO::PARAM_STR);
        $sentence->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
        $sentence->bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $sentence->bindParam(':additional', $additional, PDO::PARAM_STR);
        $sentence->bindParam(':shipping', $shipping, PDO::PARAM_STR);
        $sentence->bindParam(':shipping_cost', $shipping_cost, PDO::PARAM_STR);
        $sentence->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_fulfillment_profit_and_total($connection, $id_rfq){
    if (isset($connection)) {
      $total_profit = 0;
      $total_cost = 0;
      $quote = self::obtener_cotizacion_por_id($connection, $id_rfq);
      $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
      $total_cost += $quote-> obtener_fulfillment_shipping_cost();
      foreach ($items as $i => $item) {
        $total_profit += $item-> obtener_fulfillment_profit();
        $total_cost += FulfillmentItemRepository::get_total_cost($connection, $item-> obtener_id());
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item-> obtener_id());
        foreach ($subitems as $j => $subitem) {
          $total_profit += $subitem-> obtener_fulfillment_profit();
          $total_cost += FulfillmentSubitemRepository::get_total_cost($connection, $subitem-> obtener_id());
        }
      }
      try {
        $sql = 'UPDATE rfq SET fulfillment_profit = :fulfillment_profit, total_fulfillment = :total_fulfillment WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindParam(':fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $sentence->bindParam(':total_fulfillment', $total_cost, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_services_fulfillment_profit_and_total($connection, $id_rfq){
    if (isset($connection)) {
      $total_cost = 0;
      $total_profit = 0;
      $services = ServiceRepository::get_services($connection, $id_rfq);
      foreach ($services as $i => $service) {
        $total_profit += $service-> get_fulfillment_profit();
        $total_cost += FulfillmentServiceRepository::get_total_cost($connection, $service-> get_id());
      }
      try {
        $sql = 'UPDATE rfq SET services_fulfillment_profit = :services_fulfillment_profit, total_services_fulfillment = :total_services_fulfillment WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindParam(':services_fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $sentence->bindParam(':total_services_fulfillment', $total_cost, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_cotizaciones_completadas_por_canal($conexion, $canal) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE canal = :canal AND completado = 1 AND status = 0 AND award = 0 AND (comments = 'No comments' OR comments = 'Working on it') ORDER BY fecha_completado DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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
      <td class="text-center"><?php echo $cotizacion-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_completadas_por_canal($canal) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_completadas_por_canal(Conexion::obtener_conexion(), $canal);
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

  public static function obtener_cotizaciones_submitted_por_canal($conexion, $canal) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 0 AND canal = :canal AND comments = 'No comments' ORDER BY fecha_submitted DESC LIMIT 100";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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
      <td class="text-center"><?php echo $cotizacion-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_submitted_por_canal($canal) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_submitted_por_canal(Conexion::obtener_conexion(), $canal);
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

  public static function obtener_cotizaciones_award_por_canal($conexion, $canal) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE completado = 1 AND status = 1 AND award = 1 AND fullfillment = 0 AND canal = :canal AND (comments = 'No comments' OR comments = 'QuickBooks') ORDER BY fecha_award DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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
      <td class="text-center"><?php echo $cotizacion-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function escribir_cotizaciones_award_por_canal($canal) {
    Conexion::abrir_conexion();
    $cotizaciones = self::obtener_cotizaciones_award_por_canal(Conexion::obtener_conexion(), $canal);
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

  public static function obtener_cotizaciones_no_bid($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'No Bid' OR comments = 'Manufacturer in the Bid' OR comments = 'Expired due date' OR comments = 'Supplier did not provide a quote' OR comments = 'Others' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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

  public static function obtener_cotizaciones_cancelled($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'Cancelled' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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

  public static function obtener_cotizaciones_no_submitted($conexion) {
    $cotizaciones = [];
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE comments = 'Not submitted' ORDER BY id DESC";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $cotizaciones = self::array_to_object($sentencia);
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
        $sql = '(SELECT * FROM rfq WHERE (contract_number LIKE :termino_busqueda OR id LIKE :termino_busqueda OR email_code LIKE :termino_busqueda OR total_price LIKE :termino_busqueda OR address LIKE :termino_busqueda OR ship_to LIKE :termino_busqueda)) UNION (SELECT rfq.* FROM rfq INNER JOIN item ON rfq.id = item.id_rfq WHERE (item.brand LIKE :termino_busqueda OR item.brand_project LIKE :termino_busqueda OR item.part_number LIKE :termino_busqueda OR item.part_number_project LIKE :termino_busqueda OR item.description LIKE :termino_busqueda OR item.description_project LIKE :termino_busqueda)) UNION (SELECT rfq.* FROM rfq INNER JOIN item ON rfq.id = item.id_rfq INNER JOIN subitems ON item.id = subitems.id_item WHERE (subitems.brand LIKE :termino_busqueda OR subitems.brand_project LIKE :termino_busqueda OR subitems.part_number LIKE :termino_busqueda OR subitems.part_number_project LIKE :termino_busqueda OR subitems.description LIKE :termino_busqueda OR subitems.description_project LIKE :termino_busqueda))';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
        $sentencia-> execute();
        $cotizaciones = self::array_to_object($sentencia);
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

  public static function obtener_monto_cotizaciones_ganadas_por_mes($conexion) {
    $amount = array();
    $past_amount = array();
    if (isset($conexion)) {
      try {
        for ($i = 1; $i <= 12; $i++) {
          $sql1 = 'SELECT SUM(total_price) as amount, COUNT(*) as awards FROM rfq WHERE award = 1 AND MONTH(fecha_award) =' . $i . ' AND YEAR(fecha_award) = YEAR(CURDATE())';
          $sql2 = 'SELECT SUM(total_price) as past_amount, COUNT(*) as past_awards FROM rfq WHERE award = 1 AND MONTH(fecha_award) =' . $i . ' AND YEAR(fecha_award) = YEAR(DATE_SUB(NOW(),INTERVAL 1 YEAR))';
          $sentencia1 = $conexion->prepare($sql1);
          $sentencia2 = $conexion->prepare($sql2);
          $sentencia1-> execute();
          $sentencia2-> execute();
          $resultado1 = $sentencia1-> fetch(PDO::FETCH_ASSOC);
          $resultado2 = $sentencia2-> fetch(PDO::FETCH_ASSOC);

          $amount[$i - 1] = is_null($resultado1['amount']) ? 0 : $resultado1['amount'];
          $awards[$i - 1] = is_null($resultado1['awards']) ? 0 : $resultado1['awards'];
          $past_amount[$i - 1] = is_null($resultado2['past_amount']) ? 0 : $resultado2['past_amount'];
          $past_awards[$i - 1] = is_null($resultado2['past_awards']) ? 0 : $resultado2['past_awards'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($amount, $past_amount, $awards, $past_awards);
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

  public static function check_fulfillment_and_date($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET fullfillment = 1, fulfillment_date = NOW() WHERE id = :id_rfq';
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

  public static function check_invoice_and_date($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET invoice = 1, invoice_date = NOW() WHERE id = :id_rfq';
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

  public static function check_submitted_invoice_and_date($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET submitted_invoice = 1, submitted_invoice_date = NOW() WHERE id = :id_rfq';
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
          <td colspan="9" style="font-size:10pt;"><?php echo nl2br($cotizacion->obtener_shipping()); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($cotizacion->obtener_shipping_cost(), 2); ?></td>
        </tr>
        <tr>
          <td style="border:none;"></td>
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
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $quote-> obtener_id());
    Conexion::cerrar_conexion();
    $fulfillment_date = RepositorioComment::mysql_datetime_to_english_format($quote-> obtener_fulfillment_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>" class="btn-block">
          <?php echo $quote-> obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote-> obtener_email_code(); ?></td>
      <td><?php echo $quote-> print_channel(); ?></td>
      <td><?php echo isset($re_quote) ? '$' . $re_quote-> get_total_price() : 'No Re-Quote'; ?></td>
      <td><?php echo $fulfillment_date; ?></td>
      <td><?php echo $quote-> obtener_fecha_award(); ?></td>
      <td class="text-center"><?php echo $quote-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function get_all_fulfillment_quotes($connection){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND invoice IS NULL OR invoice = 0';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $quotes = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_invoice_quotes(){
    Conexion::abrir_conexion();
    $quotes = self::get_all_invoice_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if(count($quotes)){
      ?>
      <table class="invoice_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>INVOICE DATE</th>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_invoice_quote($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_invoice_quote($quote){
    if(!isset($quote)){
      return;
    }
    $invoice_date = RepositorioComment::mysql_datetime_to_english_format($quote-> obtener_invoice_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>" class="btn-block">
          <?php echo $quote-> obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote-> obtener_email_code(); ?></td>
      <td><?php echo $quote-> print_channel(); ?></td>
      <td><?php echo $invoice_date; ?></td>
      <td class="text-center"><?php echo $quote-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function get_all_invoice_quotes($connection){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE invoice = 1';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $quotes = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_submitted_invoice_quotes(){
    Conexion::abrir_conexion();
    $quotes = self::get_all_submitted_invoice_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if(count($quotes)){
      ?>
      <table class="invoice_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>SUBMITTED INVOICE DATE</th>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_submitted_invoice_quote($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_submitted_invoice_quote($quote){
    if(!isset($quote)){
      return;
    }
    $submitted_invoice_date = RepositorioComment::mysql_datetime_to_english_format($quote-> obtener_submitted_invoice_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>" class="btn-block">
          <?php echo $quote-> obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote-> obtener_email_code(); ?></td>
      <td><?php echo $quote-> print_channel(); ?></td>
      <td><?php echo $submitted_invoice_date; ?></td>
      <td class="text-center"><?php echo $quote-> obtener_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function get_all_submitted_invoice_quotes($connection){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE submitted_invoice = 1';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $quotes = self::array_to_object($sentence);
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

  public static function remove_relation($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE rfq SET multi_year_project = null WHERE id = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function mark_unmark_as_pending($conexion, $id_rfq, $value){
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET fulfillment_pending = :value WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindParam(':value', $value, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_providers_name($connection, $id_rfq){
    $providers_name = [];
    $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
    foreach ($items as $i => $item) {
      $providers = RepositorioProvider::obtener_providers_por_id_item($connection, $item-> obtener_id());
      foreach ($providers as $j => $provider) {
        array_push($providers_name, $provider-> obtener_provider());
      }
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item-> obtener_id());
      foreach ($subitems as $k => $subitem) {
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem($connection, $subitem-> obtener_id());
        foreach ($providers_subitem as $l => $provider_subitem) {
          array_push($providers_name, $provider_subitem-> obtener_provider());
        }
      }
    }
    $providers_name = array_unique($providers_name);
    return $providers_name;
  }
}
?>
