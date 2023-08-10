<?php
class RepositorioRfq {
  public static function insertar_cotizacion($conexion, $cotizacion) {
    $cotizacion_insertada = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, fullfillment, fulfillment_date, contract_number, fulfillment_profit, services_fulfillment_profit, total_fulfillment, total_services_fulfillment, invoice, invoice_date, multi_year_project, submitted_invoice, submitted_invoice_date, fulfillment_pending, fulfillment_shipping_cost, fulfillment_shipping, type_of_contract, net30_fulfillment, sales_commission, city, zip_code, state, client) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :fullfillment, :fulfillment_date, :contract_number, :fulfillment_profit, :services_fulfillment_profit, :total_fulfillment, :total_services_fulfillment, :invoice, :invoice_date, :multi_year_project, :submitted_invoice, :submitted_invoice_date, :fulfillment_pending, :fulfillment_shipping_cost, :fulfillment_shipping, :type_of_contract, :net30_fulfillment, :sales_commission, :city, :zip_code, :state, :client)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_usuario', $cotizacion->obtener_id_usuario(), PDO::PARAM_STR);
        $sentencia->bindValue(':usuario_designado', $cotizacion->obtener_usuario_designado(), PDO::PARAM_STR);
        $sentencia->bindValue(':canal', $cotizacion->obtener_canal(), PDO::PARAM_STR);
        $sentencia->bindValue(':email_code', $cotizacion->obtener_email_code(), PDO::PARAM_STR);
        $sentencia->bindValue(':type_of_bid', $cotizacion->obtener_type_of_bid(), PDO::PARAM_STR);
        $sentencia->bindValue(':issue_date', $cotizacion->obtener_issue_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':end_date', $cotizacion->obtener_end_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':status', $cotizacion->obtener_status(), PDO::PARAM_STR);
        $sentencia->bindValue(':completado', $cotizacion->obtener_completado(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_cost', $cotizacion->obtener_total_cost(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_price', $cotizacion->obtener_total_price(), PDO::PARAM_STR);
        $sentencia->bindValue(':comments', $cotizacion->obtener_comments(), PDO::PARAM_STR);
        $sentencia->bindValue(':award', $cotizacion->obtener_award(), PDO::PARAM_STR);
        $sentencia->bindValue(':fecha_completado', $cotizacion->obtener_fecha_completado(), PDO::PARAM_STR);
        $sentencia->bindValue(':fecha_submitted', $cotizacion->obtener_fecha_submitted(), PDO::PARAM_STR);
        $sentencia->bindValue(':fecha_award', $cotizacion->obtener_fecha_award(), PDO::PARAM_STR);
        $sentencia->bindValue(':payment_terms', $cotizacion->obtener_payment_terms(), PDO::PARAM_STR);
        $sentencia->bindValue(':address', $cotizacion->obtener_address(), PDO::PARAM_STR);
        $sentencia->bindValue(':ship_to', $cotizacion->obtener_ship_to(), PDO::PARAM_STR);
        $sentencia->bindValue(':expiration_date', $cotizacion->obtener_expiration_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':ship_via', $cotizacion->obtener_ship_via(), PDO::PARAM_STR);
        $sentencia->bindValue(':taxes', $cotizacion->obtener_taxes(), PDO::PARAM_STR);
        $sentencia->bindValue(':profit', $cotizacion->obtener_profit(), PDO::PARAM_STR);
        $sentencia->bindValue(':additional', $cotizacion->obtener_additional(), PDO::PARAM_STR);
        $sentencia->bindValue(':shipping', $cotizacion->obtener_shipping(), PDO::PARAM_STR);
        $sentencia->bindValue(':shipping_cost', $cotizacion->obtener_shipping_cost(), PDO::PARAM_STR);
        $sentencia->bindValue(':fullfillment', $cotizacion->obtener_fullfillment(), PDO::PARAM_STR);
        $sentencia->bindValue(':fulfillment_date', $cotizacion->obtener_fulfillment_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':contract_number', $cotizacion->obtener_contract_number(), PDO::PARAM_STR);
        $sentencia->bindValue(':fulfillment_profit', $cotizacion->obtener_fulfillment_profit(), PDO::PARAM_STR);
        $sentencia->bindValue(':services_fulfillment_profit', $cotizacion->obtener_services_fulfillment_profit(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_fulfillment', $cotizacion->obtener_total_fulfillment(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_services_fulfillment', $cotizacion->obtener_total_services_fulfillment(), PDO::PARAM_STR);
        $sentencia->bindValue(':invoice', $cotizacion->obtener_invoice(), PDO::PARAM_STR);
        $sentencia->bindValue(':invoice_date', $cotizacion->obtener_invoice_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':multi_year_project', $cotizacion->obtener_multi_year_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':submitted_invoice', $cotizacion->obtener_submitted_invoice(), PDO::PARAM_STR);
        $sentencia->bindValue(':submitted_invoice_date', $cotizacion->obtener_submitted_invoice_date(), PDO::PARAM_STR);
        $sentencia->bindValue(':fulfillment_pending', $cotizacion->obtener_fulfillment_pending(), PDO::PARAM_STR);
        $sentencia->bindValue(':fulfillment_shipping_cost', $cotizacion->obtener_fulfillment_shipping_cost(), PDO::PARAM_STR);
        $sentencia->bindValue(':fulfillment_shipping', $cotizacion->obtener_fulfillment_shipping(), PDO::PARAM_STR);
        $sentencia->bindValue(':type_of_contract', $cotizacion->obtener_type_of_contract(), PDO::PARAM_STR);
        $sentencia->bindValue(':net30_fulfillment', $cotizacion->obtener_net30_fulfillment(), PDO::PARAM_STR);
        $sentencia->bindValue(':sales_commission', $cotizacion->obtener_sales_commission(), PDO::PARAM_STR);
        $sentencia->bindValue(':city', $cotizacion->obtener_city(), PDO::PARAM_STR);
        $sentencia->bindValue(':zip_code', $cotizacion->obtener_zip_code(), PDO::PARAM_STR);
        $sentencia->bindValue(':state', $cotizacion->obtener_state(), PDO::PARAM_STR);
        $sentencia->bindValue(':client', $cotizacion->obtener_client(), PDO::PARAM_STR);
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

  public static function insert_calc($connection, $id_items, $id_subitems, $total_prices, $subitem_total_prices, $unit_prices, $subitem_unit_prices, $additionals, $additional_subitems) {
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
        $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND email_code = :email_code';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':email_code', $email_code, PDO::PARAM_STR);
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

  public static function array_to_object($sentence) {
    $objects = [];
    while ($result = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Rfq($result['id'], $result['id_usuario'], $result['usuario_designado'], $result['canal'], $result['email_code'], $result['type_of_bid'], $result['issue_date'], $result['end_date'], $result['status'], $result['completado'], $result['total_cost'], $result['total_price'], $result['comments'], $result['award'], $result['fecha_completado'], $result['fecha_submitted'], $result['fecha_award'], $result['payment_terms'], $result['address'], $result['ship_to'], $result['expiration_date'], $result['ship_via'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping'], $result['shipping_cost'], $result['fullfillment'], $result['fulfillment_date'], $result['contract_number'], $result['fulfillment_profit'], $result['services_fulfillment_profit'], $result['total_fulfillment'], $result['total_services_fulfillment'], $result['invoice'], $result['invoice_date'], $result['multi_year_project'], $result['submitted_invoice'], $result['submitted_invoice_date'], $result['fulfillment_pending'], $result['fulfillment_shipping_cost'], $result['fulfillment_shipping'], $result['type_of_contract'], $result['net30_fulfillment'], $result['net30_shipping'], $result['sales_commission'], $result['sales_commission_comment'], $result['services_payment_term'], $result['city'], $result['zip_code'], $result['state'], $result['client'], $result['deleted'], $result['set_side'], $result['poc'], $result['co'], $result['estimated_delivery_date'], $result['shipping_address'], $result['special_requirements'], $result['file_document'], $result['accounting'], $result['gsa'], $result['client_payment_terms']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $result = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new Rfq($result['id'], $result['id_usuario'], $result['usuario_designado'], $result['canal'], $result['email_code'], $result['type_of_bid'], $result['issue_date'], $result['end_date'], $result['status'], $result['completado'], $result['total_cost'], $result['total_price'], $result['comments'], $result['award'], $result['fecha_completado'], $result['fecha_submitted'], $result['fecha_award'], $result['payment_terms'], $result['address'], $result['ship_to'], $result['expiration_date'], $result['ship_via'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping'], $result['shipping_cost'], $result['fullfillment'], $result['fulfillment_date'], $result['contract_number'], $result['fulfillment_profit'], $result['services_fulfillment_profit'], $result['total_fulfillment'], $result['total_services_fulfillment'], $result['invoice'], $result['invoice_date'], $result['multi_year_project'], $result['submitted_invoice'], $result['submitted_invoice_date'], $result['fulfillment_pending'], $result['fulfillment_shipping_cost'], $result['fulfillment_shipping'], $result['type_of_contract'], $result['net30_fulfillment'], $result['sales_commission'], $result['sales_commission_comment'], $result['services_payment_term'], $result['city'], $result['zip_code'], $result['state'], $result['client'], $result['deleted'], $result['set_side'], $result['poc'], $result['co'], $result['estimated_delivery_date'], $result['shipping_address'], $result['special_requirements'], $result['file_document'], $result['accounting'], $result['gsa'], $result['client_payment_terms']);

    return $object;
  }

  public static function getSlavesQuotes($conexion, $id_parent) {
    $data = [];
    if (isset($conexion)) {
      try {
        $sql = 'SELECT id FROM rfq WHERE deleted = 0 AND multi_year_project = :multi_year_project';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':multi_year_project', $id_parent, PDO::PARAM_STR);
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

  public static function getCreatedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $canal) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'rfq.type_of_bid' : ($sort_column_index == 3 ? 'rfq.issue_date' : ($sort_column_index == 4 ? 'rfq.end_date' : 'rfq.email_code'))));
    $sort_column = $sort_column == 'rfq.issue_date' ? 'STR_TO_DATE(rfq.issue_date, "%m/%d/%Y")' : $sort_column;
    $sort_column = $sort_column == 'rfq.end_date' ? 'STR_TO_DATE(rfq.end_date, "%m/%d/%Y %H:%i")' : $sort_column;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT rfq.id, 
        usuarios.nombre_usuario, 
        rfq.type_of_bid, 
        rfq.issue_date, 
        rfq.end_date, 
        rfq.email_code, 
        CASE
          WHEN type_of_bid = "Services" THEN "true"
          WHEN type_of_bid = "Audio Visual" THEN "true"
          WHEN type_of_bid = "Computers" THEN "true"
          ELSE "false"
        END AS rfp,
        NULL AS options,
        comments
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 AND 
        rfq.canal = :canal AND 
        rfq.completado = 0 AND 
        rfq.status = 0 AND 
        rfq.award = 0 
        AND (rfq.comments = "Working on it" OR rfq.comments = "No comments" OR rfq.comments = "") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search) 
        ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
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

  public static function getTotalCreatedQuotesByChannelCount($conexion, $canal) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 0 AND 
        status = 0 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments" OR comments = "")';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredCreatedQuotesByChannelCount($conexion, $canal, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 0 AND 
        rfq.status = 0 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments" OR comments = "") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getCompletedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $canal) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'rfq.type_of_bid' : ($sort_column_index == 3 ? 'rfq.fecha_completado' : 'rfq.email_code')));
    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id, 
        usuarios.nombre_usuario, 
        rfq.type_of_bid, 
        DATE_FORMAT(fecha_completado, '%m/%d/%Y') as fecha_completado, 
        rfq.email_code, 
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 AND 
        rfq.canal = :canal AND 
        rfq.completado = 1 AND 
        rfq.status = 0 AND 
        rfq.award = 0 
        AND (rfq.comments = 'Working on it' OR rfq.comments = 'No comments') AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search) 
        ORDER BY $sort_column $sort_direction LIMIT $start, $length";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
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

  public static function getTotalCompletedQuotesByChannelCount($conexion, $canal) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        status = 0 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments")';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredCompletedQuotesByChannelCount($conexion, $canal, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        rfq.status = 0 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getSubmittedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $canal) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'rfq.type_of_bid' : ($sort_column_index == 3 ? 'rfq.fecha_submitted' : 'rfq.email_code')));
    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id, 
        usuarios.nombre_usuario, 
        rfq.type_of_bid, 
        DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted, 
        rfq.email_code, 
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 AND 
        rfq.canal = :canal AND 
        rfq.completado = 1 AND 
        rfq.status = 1 AND 
        rfq.award = 0 
        AND (rfq.comments = 'Working on it' OR rfq.comments = 'No comments') AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search) 
        ORDER BY $sort_column $sort_direction LIMIT $start, $length";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
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

  public static function getTotalSubmittedQuotesByChannelCount($conexion, $canal) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        status = 1 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments")';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredSubmittedQuotesByChannelCount($conexion, $canal, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        rfq.status = 1 AND 
        award = 0 
        AND (comments = "Working on it" OR comments = "No comments") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getAwardQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $canal) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'rfq.type_of_bid' : ($sort_column_index == 3 ? 'rfq.fecha_award' : 'rfq.email_code')));
    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id, 
        usuarios.nombre_usuario, 
        rfq.type_of_bid, 
        DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award, 
        rfq.email_code, 
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 AND 
        rfq.canal = :canal AND 
        rfq.completado = 1 AND 
        rfq.status = 1 AND 
        rfq.award = 1 
        AND (rfq.comments = 'Working on it' OR rfq.comments = 'No comments') AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search) 
        ORDER BY $sort_column $sort_direction LIMIT $start, $length";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
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

  public static function getTotalAwardQuotesByChannelCount($conexion, $canal) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        status = 1 AND 
        award = 1 
        AND (comments = "Working on it" OR comments = "No comments")';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredAwardQuotesByChannelCount($conexion, $canal, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 AND 
        canal = :canal AND 
        completado = 1 AND 
        rfq.status = 1 AND 
        award = 1 
        AND (comments = "Working on it" OR comments = "No comments") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR rfq.type_of_bid LIKE :search OR rfq.email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function obtener_cotizacion_por_id($conexion, $id_rfq) {
    $cotizacion_recuperada = null;
    if (isset($conexion)) {
      try {
        $sql = "SELECT * FROM rfq WHERE id = :id_rfq";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $cotizacion_recuperada = self::single_result_to_object($sentencia);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $cotizacion_recuperada;
  }

  public static function getIds($conexion, $searchTerm, $id_rfq) {
    $data = [];
    $searchTerm = '%' . $searchTerm . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id
        FROM rfq 
        WHERE deleted = 0 AND 
        id != {$id_rfq} AND 
        id LIKE :searchTerm";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
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

  public static function check_fullfillment($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET fullfillment = 1 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_services_payment_term($conexion, $payment_term, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET services_payment_term = :services_payment_term WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':services_payment_term', $payment_term, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_type_of_contract($conexion, $type_of_contract, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET type_of_contract = :type_of_contract WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':type_of_contract', $type_of_contract, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_sales_commission($conexion, $sales_commission, $sales_commission_comment, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET sales_commission = :sales_commission, sales_commission_comment = :sales_commission_comment WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':sales_commission', $sales_commission, PDO::PARAM_STR);
        $sentencia->bindValue(':sales_commission_comment', $sales_commission_comment, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update_fulfillment_shipping($connection, $fulfillment_shipping, $fulfillment_shipping_cost, $id_rfq) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE rfq SET fulfillment_shipping = :fulfillment_shipping, fulfillment_shipping_cost = :fulfillment_shipping_cost WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':fulfillment_shipping', $fulfillment_shipping, PDO::PARAM_STR);
        $sentence->bindValue(':fulfillment_shipping_cost', $fulfillment_shipping_cost, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function save_checklist(
    $conexion,
    $ship_to,
    $designated_user,
    $email_code,
    $canal,
    $contract_number,
    $city,
    $zip_code,
    $state,
    $client,
    $set_side,
    $poc,
    $co,
    $estimated_delivery_date,
    $file_document,
    $accounting,
    $shipping_address,
    $special_requirements,
    $gsa,
    $client_payment_terms,
    $id_rfq
  ) {
    $cotizacion_editada = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET 
        ship_to = :ship_to, 
        usuario_designado = :usuario_designado, 
        email_code = :email_code, 
        canal = :canal, 
        contract_number = :contract_number, 
        city = :city, 
        zip_code = :zip_code, 
        state = :state, 
        client = :client, 
        set_side = :set_side, 
        poc = :poc, 
        co = :co, 
        estimated_delivery_date = :estimated_delivery_date, 
        file_document = :file_document,
        accounting = :accounting,
        shipping_address = :shipping_address,
        special_requirements = :special_requirements,
        gsa = :gsa,
        client_payment_terms = :client_payment_terms
        WHERE id = :id_rfq
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':usuario_designado', $designated_user, PDO::PARAM_STR);
        $sentencia->bindValue(':ship_to', $ship_to, PDO::PARAM_STR);
        $sentencia->bindValue(':email_code', $email_code, PDO::PARAM_STR);
        $sentencia->bindValue(':canal', $canal, PDO::PARAM_STR);
        $sentencia->bindValue(':contract_number', $contract_number, PDO::PARAM_STR);
        $sentencia->bindValue(':city', $city, PDO::PARAM_STR);
        $sentencia->bindValue(':zip_code', $zip_code, PDO::PARAM_STR);
        $sentencia->bindValue(':state', $state, PDO::PARAM_STR);
        $sentencia->bindValue(':client', $client, PDO::PARAM_STR);
        $sentencia->bindValue(':set_side', $set_side, PDO::PARAM_STR);
        $sentencia->bindValue(':poc', $poc, PDO::PARAM_STR);
        $sentencia->bindValue(':co', $co, PDO::PARAM_STR);
        $sentencia->bindValue(':estimated_delivery_date', $estimated_delivery_date, PDO::PARAM_STR);
        $sentencia->bindValue(':file_document', $file_document, PDO::PARAM_STR);
        $sentencia->bindValue(':accounting', $accounting, PDO::PARAM_STR);
        $sentencia->bindValue(':shipping_address', $shipping_address, PDO::PARAM_STR);
        $sentencia->bindValue(':special_requirements', $special_requirements, PDO::PARAM_STR);
        $sentencia->bindValue(':gsa', $gsa, PDO::PARAM_STR);
        $sentencia->bindValue(':client_payment_terms', $client_payment_terms, PDO::PARAM_STR);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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

  public static function save_information(
    $conexion,
    $expiration_date,
    $fecha_completado,
    $address,
    $ship_via,
    $type_of_bid,
    $issue_date,
    $end_date,
    $designated_user,
    $email_code,
    $channel,
    $ship_to,
    $comments,
    $id_rfq
  ) {
    $cotizacion_editada = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET 
        expiration_date = :expiration_date, 
        fecha_completado = :fecha_completado, 
        address = :address, 
        ship_via = :ship_via, 
        type_of_bid = :type_of_bid, 
        issue_date = :issue_date, 
        end_date = :end_date,
        usuario_designado = :usuario_designado, 
        email_code = :email_code, 
        canal = :canal, 
        ship_to = :ship_to, 
        comments = :comments 
        WHERE id = :id_rfq
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':ship_via', $ship_via, PDO::PARAM_STR);
        $sentencia->bindValue(':address', $address, PDO::PARAM_STR);
        $sentencia->bindValue(':fecha_completado', $fecha_completado, PDO::PARAM_STR);
        $sentencia->bindValue(':expiration_date', $expiration_date, PDO::PARAM_STR);
        $sentencia->bindValue(':type_of_bid', $type_of_bid, PDO::PARAM_STR);
        $sentencia->bindValue(':issue_date', $issue_date, PDO::PARAM_STR);
        $sentencia->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $sentencia->bindValue(':usuario_designado', $designated_user, PDO::PARAM_STR);
        $sentencia->bindValue(':email_code', $email_code, PDO::PARAM_STR);
        $sentencia->bindValue(':canal', $channel, PDO::PARAM_STR);
        $sentencia->bindValue(':ship_to', $ship_to, PDO::PARAM_STR);
        $sentencia->bindValue(':comments', $comments, PDO::PARAM_STR);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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

  public static function update_variables($connection, $payment_terms, $taxes, $profit, $total_cost, $total_price, $additional, $shipping, $shipping_cost, $id_rfq) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE rfq SET payment_terms = :payment_terms, taxes = :taxes, profit = :profit, total_cost = :total_cost, total_price = :total_price, additional = :additional, shipping = :shipping, shipping_cost = :shipping_cost WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':payment_terms', $payment_terms, PDO::PARAM_STR);
        $sentence->bindValue(':taxes', $taxes, PDO::PARAM_STR);
        $sentence->bindValue(':profit', $profit, PDO::PARAM_STR);
        $sentence->bindValue(':total_cost', $total_cost, PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $total_price, PDO::PARAM_STR);
        $sentence->bindValue(':additional', $additional, PDO::PARAM_STR);
        $sentence->bindValue(':shipping', $shipping, PDO::PARAM_STR);
        $sentence->bindValue(':shipping_cost', $shipping_cost, PDO::PARAM_STR);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_fulfillment_profit_and_total($connection, $id_rfq) {
    if (isset($connection)) {
      $total_profit = 0;
      $total_cost = 0;
      $quote = self::obtener_cotizacion_por_id($connection, $id_rfq);
      $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
      $net30_fulfillment = $quote->obtener_total_price() * 0.029 * $quote->obtener_net30_fulfillment();
      $total_cost += array_sum(explode('|', $quote->obtener_fulfillment_shipping_cost()));
      foreach ($items as $i => $item) {
        $total_profit += $item->obtener_fulfillment_profit();
        $total_cost += FulfillmentItemRepository::get_total_cost($connection, $item->obtener_id());
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item->obtener_id());
        foreach ($subitems as $j => $subitem) {
          $total_profit += $subitem->obtener_fulfillment_profit();
          $total_cost += FulfillmentSubitemRepository::get_total_cost($connection, $subitem->obtener_id());
        }
      }
      $total_cost += $net30_fulfillment;
      try {
        $sql = 'UPDATE rfq SET fulfillment_profit = :fulfillment_profit, total_fulfillment = :total_fulfillment WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $sentence->bindValue(':total_fulfillment', $total_cost, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_fulfillment_total_from_to($connection, $id_rfq, $from, $to) {
    if (isset($connection)) {
      $total_cost = 0;
      $quote = self::obtener_cotizacion_por_id($connection, $id_rfq);
      $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
      $net30_fulfillment = $quote->obtener_total_price() * 0.029 * $quote->obtener_net30_fulfillment();
      // $total_cost += array_sum(explode('|', $quote-> obtener_fulfillment_shipping_cost()));
      foreach ($items as $i => $item) {
        $total_cost += FulfillmentItemRepository::get_total_cost_from_to($connection, $item->obtener_id(), $from, $to);
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item->obtener_id());
        foreach ($subitems as $j => $subitem) {
          $total_cost += FulfillmentSubitemRepository::get_total_cost_from_to($connection, $subitem->obtener_id(), $from, $to);
        }
      }
    }
    return $total_cost;
  }

  public static function set_services_fulfillment_profit_and_total($connection, $id_rfq) {
    if (isset($connection)) {
      $total_cost = 0;
      $total_profit = 0;
      $services = ServiceRepository::get_services($connection, $id_rfq);
      foreach ($services as $i => $service) {
        $total_profit += $service->get_fulfillment_profit();
        $total_cost += FulfillmentServiceRepository::get_total_cost($connection, $service->get_id());
      }
      try {
        $sql = 'UPDATE rfq SET services_fulfillment_profit = :services_fulfillment_profit, total_services_fulfillment = :total_services_fulfillment WHERE id = :id_rfq';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->bindValue(':services_fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $sentence->bindValue(':total_services_fulfillment', $total_cost, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_services_fulfillment_total_from_to($connection, $id_rfq, $from, $to) {
    if (isset($connection)) {
      $total_cost = 0;
      $services = ServiceRepository::get_services($connection, $id_rfq);
      foreach ($services as $i => $service) {
        $total_cost += FulfillmentServiceRepository::get_total_cost_from_to($connection, $service->get_id(), $from, $to);
      }
    }

    return $total_cost;
  }

  public static function getNoBidQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'email_code' : ($sort_column_index == 3 ? 'type_of_bid' : 'comments')));
    if (isset($conexion)) {
      try {
        $sql = 'SELECT rfq.id, 
        usuarios.nombre_usuario, 
        email_code, 
        type_of_bid,
        comments,
        NULL AS options 
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 
        AND (comments = "No Bid" OR comments = "Manufacturer in the Bid" OR comments = "Expired due date" OR comments = "Supplier did not provide a quote" OR comments = "Others") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search OR comments LIKE :search) 
        ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
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

  public static function getTotalNoBidQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 
        AND (comments = "No Bid" OR comments = "Manufacturer in the Bid" OR comments = "Expired due date" OR comments = "Supplier did not provide a quote" OR comments = "Others")';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredNoBidQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 
        AND (comments = "No Bid" OR comments = "Manufacturer in the Bid" OR comments = "Expired due date" OR comments = "Supplier did not provide a quote" OR comments = "Others") AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search OR comments LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getCancelledQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'email_code' : 'type_of_bid'));
    if (isset($conexion)) {
      try {
        $sql = 'SELECT rfq.id, 
        usuarios.nombre_usuario, 
        email_code, 
        type_of_bid,
        NULL AS options 
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 
        AND comments = "Cancelled" AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search) 
        ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
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

  public static function getTotalCancelledQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 
        AND comments = "Cancelled"';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredCancelledQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 
        AND comments = "Cancelled" AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getDeletedQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'email_code' : 'type_of_bid'));
    if (isset($conexion)) {
      try {
        $sql = 'SELECT rfq.id, 
        usuarios.nombre_usuario, 
        email_code, 
        type_of_bid,
        NULL AS options 
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 1 AND  
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search) 
        ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
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

  public static function getTotalDeletedQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 1 
        ';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredDeletedQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 1 AND  
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getNotSubmittedQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    $sort_column = $sort_column_index == 0 ? 'rfq.id' : ($sort_column_index == 1 ? 'nombre_usuario' : ($sort_column_index == 2 ? 'email_code' : 'type_of_bid'));
    if (isset($conexion)) {
      try {
        $sql = 'SELECT rfq.id, 
        usuarios.nombre_usuario, 
        email_code, 
        type_of_bid,
        NULL AS options 
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 
        AND comments = "Not submitted" AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search) 
        ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
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

  public static function getTotalNotSubmittedQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        WHERE deleted = 0 
        AND comments = "Not submitted"';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredNotSubmittedQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*)
        FROM rfq 
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id 
        WHERE deleted = 0 
        AND comments = "Not submitted" AND 
        (rfq.id LIKE :search OR nombre_usuario LIKE :search OR type_of_bid LIKE :search OR email_code LIKE :search)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function escribir_cotizacion_resultado_busqueda($cotizacion) {
    if (!isset($cotizacion)) {
      return;
    }
    ?>
    <tr <?php if ($cotizacion->obtener_comments() == 'QuickBooks') {
          echo 'class="quickbooks"';
        } ?>>
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
      <td>$ <?php echo number_format($cotizacion->obtener_quote_total_price(), 2); ?></td>
      <?php
      if ($cotizacion->obtener_canal() != 'FedBid') {
        if ($cotizacion->obtener_canal() != 'GSA-Buy') {
      ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
        <?php
        } else {
        ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
        <?php
        }
      } else {
        ?><td></td><?php
                  }
                    ?>
    </tr>
  <?php
  }

  public static function obtener_resultados_busqueda($conexion, $termino_busqueda) {
    $cotizaciones = [];
    $termino_busqueda = '%' . trim($termino_busqueda) . '%';
    if (isset($conexion)) {
      try {
        $sql = '(SELECT * FROM rfq WHERE (contract_number LIKE :termino_busqueda OR id LIKE :termino_busqueda OR email_code LIKE :termino_busqueda OR total_price LIKE :termino_busqueda OR address LIKE :termino_busqueda OR ship_to LIKE :termino_busqueda)) UNION (SELECT rfq.* FROM rfq INNER JOIN item ON rfq.id = item.id_rfq WHERE (item.brand LIKE :termino_busqueda OR item.brand_project LIKE :termino_busqueda OR item.part_number LIKE :termino_busqueda OR item.part_number_project LIKE :termino_busqueda OR item.description LIKE :termino_busqueda OR item.description_project LIKE :termino_busqueda)) UNION (SELECT rfq.* FROM rfq INNER JOIN item ON rfq.id = item.id_rfq INNER JOIN subitems ON item.id = subitems.id_item WHERE (subitems.brand LIKE :termino_busqueda OR subitems.brand_project LIKE :termino_busqueda OR subitems.part_number LIKE :termino_busqueda OR subitems.part_number_project LIKE :termino_busqueda OR subitems.description LIKE :termino_busqueda OR subitems.description_project LIKE :termino_busqueda))';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':termino_busqueda', $termino_busqueda, PDO::PARAM_STR);
        $sentencia->execute();
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

  public static function getSearchedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $search_term) {
    $data = [];
    $search_term = '%' . $search_term . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'sq.id';
        break;
      case 1:
        $sort_column = 'sq.nombre_usuario';
        break;
      case 2:
        $sort_column = 'sq.type_of_bid';
        break;
      case 3:
        $sort_column = 'sq.comments';
        break;
      default:
        $sort_column = 'sq.id';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT sq.id,
          sq.email_code,
          sq.nombre_usuario,
          sq.type_of_bid,
          sq.comments,
          sq.total_price,
          NULL AS options
        FROM (
            SELECT r.id,
              r.email_code,
              u.nombre_usuario,
              r.type_of_bid,
              r.comments,
              r.contract_number,
              r.city,
              r.zip_code,
              r.state,
              r.client,
              r.shipping_address,
              r.special_requirements,
              COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)),0) AS total_price
            FROM rfq r
              LEFT JOIN usuarios u ON r.usuario_designado = u.id
              LEFT JOIN services s ON r.id = s.id_rfq
            WHERE r.deleted = 0
            GROUP BY r.id
          ) as sq
        WHERE sq.email_code LIKE :search_term
          OR sq.type_of_bid LIKE :search_term
          OR sq.comments LIKE :search_term
          OR sq.total_price LIKE :search_term
          OR sq.contract_number LIKE :search_term
          OR sq.city LIKE :search_term
          OR sq.zip_code LIKE :search_term
          OR sq.state LIKE :search_term
          OR sq.client LIKE :search_term
          OR sq.shipping_address LIKE :search_term
          OR sq.special_requirements LIKE :search_term
          OR sq.id LIKE :search_term
          OR sq.id IN (
            SELECT i.id_rfq
            FROM item i
            WHERE i.provider_menor LIKE :search_term
              OR i.brand LIKE :search_term
              OR i.brand_project LIKE :search_term
              OR i.part_number LIKE :search_term
              OR i.part_number_project LIKE :search_term
              OR i.description LIKE :search_term
              OR i.description_project LIKE :search_term
              OR i.comments LIKE :search_term
          )
          OR sq.id IN (
            SELECT s.id_rfq
            FROM services s
            WHERE s.description LIKE :search_term
          )
          OR sq.id IN (
            SELECT i.id_rfq
            FROM subitems si
              JOIN item i ON si.id_item = i.id
            WHERE si.provider_menor LIKE :search_term
              OR si.brand LIKE :search_term
              OR si.brand_project LIKE :search_term
              OR si.part_number LIKE :search_term
              OR si.part_number_project LIKE :search_term
              OR si.description LIKE :search_term
              OR si.description_project LIKE :search_term
              OR si.comments LIKE :search_term
          )
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search_term', $search_term, PDO::PARAM_STR);
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

  public static function getTotalSearchedQuotesByChannelCount($conexion, $search_term) {
    $search_term = '%' . $search_term . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(r.id)
        FROM rfq r
        LEFT JOIN usuarios u ON r.usuario_designado = u.id
        WHERE r.deleted = 0 
          AND r.email_code LIKE :search_term
          OR r.type_of_bid LIKE :search_term
          OR r.comments LIKE :search_term
          OR r.contract_number LIKE :search_term
          OR r.city LIKE :search_term
          OR r.zip_code LIKE :search_term
          OR r.state LIKE :search_term
          OR r.client LIKE :search_term
          OR r.shipping_address LIKE :search_term
          OR r.special_requirements LIKE :search_term
          OR r.id LIKE :search_term
          OR r.id IN (
            SELECT i.id_rfq
            FROM item i
            WHERE i.provider_menor LIKE :search_term
              OR i.brand LIKE :search_term
              OR i.brand_project LIKE :search_term
              OR i.part_number LIKE :search_term
              OR i.part_number_project LIKE :search_term
              OR i.description LIKE :search_term
              OR i.description_project LIKE :search_term
              OR i.comments LIKE :search_term
          )
          OR r.id IN (
            SELECT s.id_rfq
            FROM services s
            WHERE s.description LIKE :search_term
          )
          OR r.id IN (
            SELECT i.id_rfq
            FROM subitems si
              JOIN item i ON si.id_item = i.id
            WHERE si.provider_menor LIKE :search_term
              OR si.brand LIKE :search_term
              OR si.brand_project LIKE :search_term
              OR si.part_number LIKE :search_term
              OR si.part_number_project LIKE :search_term
              OR si.description LIKE :search_term
              OR si.description_project LIKE :search_term
              OR si.comments LIKE :search_term
          )
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search_term', $search_term, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getAnnualAwardsAmountByMonth($connection, $year) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT SUM(COALESCE(quotes.total_amount, 0)) as total_price
        FROM (
            SELECT 1 AS month
            UNION
            SELECT 2 AS month
            UNION
            SELECT 3 AS month
            UNION
            SELECT 4 AS month
            UNION
            SELECT 5 AS month
            UNION
            SELECT 6 AS month
            UNION
            SELECT 7 AS month
            UNION
            SELECT 8 AS month
            UNION
            SELECT 9 AS month
            UNION
            SELECT 10 AS month
            UNION
            SELECT 11 AS month
            UNION
            SELECT 12 AS month
          ) AS months
          LEFT JOIN (
            SELECT SUM(COALESCE(s.total_price, 0)) + COALESCE(r.total_price, 0) AS total_amount,
              MONTH(r.fecha_award) AS month_award_date
            FROM rfq r
              LEFT JOIN services s ON r.id = s.id_rfq
            WHERE r.award = 1
              AND r.deleted = 0
              AND YEAR(r.fecha_award) = {$year}
            GROUP BY r.id
          ) as quotes ON quotes.month_award_date = months.month
        GROUP BY months.month;
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

  public static function getAnnualAwardsByMonth($connection, $year) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          months.month,
          COUNT(r.id) AS total_quotes
        FROM 
          (SELECT 1 AS month 
          UNION SELECT 2 AS month 
          UNION SELECT 3 AS month 
          UNION SELECT 4 AS month 
          UNION SELECT 5 AS month 
          UNION SELECT 6 AS month 
          UNION SELECT 7 AS month 
          UNION SELECT 8 AS month 
          UNION SELECT 9 AS month 
          UNION SELECT 10 AS month 
          UNION SELECT 11 AS month 
          UNION SELECT 12 AS month) AS months
          LEFT JOIN rfq r ON 
          MONTH(r.fecha_award) = months.month AND 
          r.award = 1 AND
          r.deleted = 0 AND
          YEAR(r.fecha_award) = " . $year . " 
        GROUP BY months.month
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

  public static function getTotalAnnualAwardsAmount($connection, $year) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT SUM(COALESCE(quotes.total_amount,0)) as total_amount
        FROM (
          SELECT SUM(COALESCE(s.total_price, 0)) + COALESCE(r.total_price, 0) AS total_amount
          FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            WHERE r.award = 1 AND 
            r.deleted = 0 AND
            YEAR(r.fecha_award) =  {$year}
            GROUP BY r.id
        ) as quotes;
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getTotalAnnualAwards($connection, $year) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(r.id) AS total_quotes
        FROM rfq r
        WHERE r.award = 1 AND 
        r.deleted = 0 AND
        YEAR(r.fecha_award) = {$year}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function actualizar_fecha_y_submitted($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET status = 1, fecha_submitted = NOW() WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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

  public static function check_completed($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET completado = 1 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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

  public static function guardar_total_price_total_cost_fedbid($conexion, $total_cost_fedbid, $total_price_fedbid, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET total_cost = :total_cost_fedbid, total_price = :total_price_fedbid WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':total_cost_fedbid', $total_cost_fedbid, PDO::PARAM_STR);
        $sentencia->bindValue(':total_price_fedbid', $total_price_fedbid, PDO::PARAM_STR);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function guardar_total_price_chemonics($conexion, $total_price_chemonics, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET total_price = :total_price_chemonics WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':total_price_chemonics', $total_price_chemonics, PDO::PARAM_STR);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_quote($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET deleted = 1 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }

  public static function restore_quote($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET deleted = 0 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }

  public static function print_fulfillment_quotes() {
    Conexion::abrir_conexion();
    $quotes = self::get_all_fulfillment_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if (count($quotes)) {
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
            <th>TYPE OF CONTRACT</th>
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

  public static function print_fulfillment_quote($quote) {
    if (!isset($quote)) {
      return;
    }
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $quote->obtener_id());
    Conexion::cerrar_conexion();
    $fulfillment_date = RepositorioComment::mysql_datetime_to_english_format($quote->obtener_fulfillment_date());
    $award_date = RepositorioComment::mysql_datetime_to_english_format($quote->obtener_fecha_award());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>" class="btn-block">
          <?php echo $quote->obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote->obtener_email_code(); ?></td>
      <td><?php echo $quote->print_channel(); ?></td>
      <td><?php echo isset($re_quote) ? '$' . $re_quote->get_total_price() : 'No Re-Quote'; ?></td>
      <td><?php echo $fulfillment_date; ?></td>
      <td><?php echo $award_date; ?></td>
      <td><?php echo $quote->obtener_type_of_contract(); ?></td>
    </tr>
    <?php
  }

  public static function get_all_fulfillment_quotes($connection) {
    $quotes = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND fullfillment = 1 AND (invoice IS NULL OR invoice = 0)';
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        $quotes = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_invoice_quotes() {
    Conexion::abrir_conexion();
    $quotes = self::get_all_invoice_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if (count($quotes)) {
    ?>
      <table class="invoice_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>INVOICE DATE</th>
            <th>TYPE OF CONTRACT</th>
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

  public static function print_invoice_quote($quote) {
    if (!isset($quote)) {
      return;
    }
    $invoice_date = RepositorioComment::mysql_datetime_to_english_format($quote->obtener_invoice_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>" class="btn-block">
          <?php echo $quote->obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote->obtener_email_code(); ?></td>
      <td><?php echo $quote->print_channel(); ?></td>
      <td><?php echo $invoice_date; ?></td>
      <td><?php echo $quote->obtener_type_of_contract(); ?></td>
    </tr>
    <?php
  }

  public static function get_all_invoice_quotes($connection) {
    $quotes = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND invoice = 1 AND (submitted_invoice IS NULL OR submitted_invoice = 0)';
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        $quotes = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_submitted_invoice_quotes() {
    Conexion::abrir_conexion();
    $quotes = self::get_all_submitted_invoice_quotes(Conexion::obtener_conexion());
    Conexion::cerrar_conexion();
    if (count($quotes)) {
    ?>
      <table class="invoice_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>SUBMITTED INVOICE DATE</th>
            <th>TYPE OF CONTRACT</th>
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

  public static function print_submitted_invoice_quote($quote) {
    if (!isset($quote)) {
      return;
    }
    $submitted_invoice_date = RepositorioComment::mysql_datetime_to_english_format($quote->obtener_submitted_invoice_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>" class="btn-block">
          <?php echo $quote->obtener_id(); ?>
        </a>
      </td>
      <td><?php echo $quote->obtener_email_code(); ?></td>
      <td><?php echo $quote->print_channel(); ?></td>
      <td><?php echo $submitted_invoice_date; ?></td>
      <td><?php echo $quote->obtener_type_of_contract(); ?></td>
    </tr>
<?php
  }

  public static function get_all_submitted_invoice_quotes($connection) {
    $quotes = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND submitted_invoice = 1';
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        $quotes = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function remove_award($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET award = 0 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_fulfillment($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET fullfillment = 0 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_invoice($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET invoice = 0 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_submitted_invoice($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET submitted_invoice = 0 WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_relation($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET multi_year_project = null WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function linkQuote($conexion, $master, $slave) {
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET multi_year_project = {$master} WHERE id = {$slave}";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function mark_unmark_as_pending($conexion, $id_rfq, $value) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET fulfillment_pending = :value WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':value', $value, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function save_net_30($conexion, $id_rfq, $value) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET net30_fulfillment = :net30_fulfillment WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':net30_fulfillment', $value, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_providers_name($connection, $id_rfq) {
    $providers_name = [];
    $items = RepositorioItem::obtener_items_por_id_rfq($connection, $id_rfq);
    foreach ($items as $i => $item) {
      $providers = RepositorioProvider::obtener_providers_por_id_item($connection, $item->obtener_id());
      foreach ($providers as $j => $provider) {
        array_push($providers_name, $provider->obtener_provider());
      }
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($connection, $item->obtener_id());
      foreach ($subitems as $k => $subitem) {
        $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem($connection, $subitem->obtener_id());
        foreach ($providers_subitem as $l => $provider_subitem) {
          array_push($providers_name, $provider_subitem->obtener_provider());
        }
      }
    }
    $providers_name = array_unique($providers_name);
    return $providers_name;
  }
}
?>