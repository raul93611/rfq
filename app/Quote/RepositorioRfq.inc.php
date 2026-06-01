<?php
class RepositorioRfq {
  public static function insertar_cotizacion($conexion, $cotizacion) {
    $cotizacion_insertada = false;
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO rfq(id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date, status, completado, total_cost, total_price, comments, award, fecha_completado, fecha_submitted, fecha_award, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, fullfillment, fulfillment_date, contract_number, fulfillment_profit, services_fulfillment_profit, total_fulfillment, total_services_fulfillment, invoice, invoice_date, multi_year_project, submitted_invoice, submitted_invoice_date, fulfillment_pending, fulfillment_shipping_cost, fulfillment_shipping, type_of_contract, net30_fulfillment, sales_commission, city, zip_code, state, client, reference_url, priority, name, site_visit, resumes, qa_deadline) VALUES(:id_usuario, :usuario_designado, :canal, :email_code, :type_of_bid, :issue_date, :end_date, :status, :completado, :total_cost, :total_price, :comments, :award, :fecha_completado, :fecha_submitted, :fecha_award, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :fullfillment, :fulfillment_date, :contract_number, :fulfillment_profit, :services_fulfillment_profit, :total_fulfillment, :total_services_fulfillment, :invoice, :invoice_date, :multi_year_project, :submitted_invoice, :submitted_invoice_date, :fulfillment_pending, :fulfillment_shipping_cost, :fulfillment_shipping, :type_of_contract, :net30_fulfillment, :sales_commission, :city, :zip_code, :state, :client, :reference_url, :priority, :name, :site_visit, :resumes, STR_TO_DATE(:qa_deadline, \'%m/%d/%Y\'))';
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
        $sentencia->bindValue(':reference_url', $cotizacion->getReferenceUrl(), PDO::PARAM_STR);
        $sentencia->bindValue(':priority', $cotizacion->getPriority(), PDO::PARAM_STR);
        $sentencia->bindValue(':name', $cotizacion->getName(), PDO::PARAM_STR);
        $sentencia->bindValue(':site_visit', $cotizacion->getSiteVisit(), PDO::PARAM_INT);
        $sentencia->bindValue(':resumes', $cotizacion->getResumes(), PDO::PARAM_INT);
        $sentencia->bindValue(':qa_deadline', $cotizacion->getQaDeadline(), PDO::PARAM_STR);
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
      $objects[] = new Rfq($result);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $result = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new Rfq($result);

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

  public static function getInvoiceAcceptance($connection, $id) {
    if (isset($connection)) {
      try {
        $sql = "SELECT invoice_acceptance FROM rfq WHERE id = :id";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }

    return $sentence->fetchColumn();
  }

  public static function updateInvoiceAcceptance($connection, $id, $invoice_acceptance) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE rfq SET invoice_acceptance = :invoice_acceptance WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->bindValue(':invoice_acceptance', $invoice_acceptance, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getCreatedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $canal) {
    $data = [];
    $search = '%' . $search . '%';
    // Define a mapping of index → column
    $columns = [
      0 => 'rfq.id',
      1 => 'nombre_usuario',
      2 => 'rfq.type_of_bid',
      3 => 'rfq.issue_date',
      4 => 'rfq.end_date',
      5 => 'rfq.email_code',
      6 => 'rfq.priority'
    ];

    // Use the mapping safely (fallback to rfq.id if index not found)
    $sort_column = isset($columns[$sort_column_index]) ? $columns[$sort_column_index] : 'rfq.id';

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
        rfq.priority,
        CASE
          WHEN type_of_bid = "Services" THEN "true"
          WHEN type_of_bid = "Audio Visual" THEN "true"
          WHEN type_of_bid = "Computers" THEN "true"
          ELSE "false"
        END AS rfp,
        NULL AS options,
        comments,
        rfq.sheet_sync_status
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
    // Define a mapping of index → column
    $columns = [
      0 => 'rfq.id',
      1 => 'nombre_usuario',
      2 => 'rfq.type_of_bid',
      3 => 'rfq.fecha_completado',
      4 => 'rfq.email_code',
      5 => 'rfq.priority'
    ];

    // Use the mapping safely (fallback to rfq.id if index not found)
    $sort_column = $columns[$sort_column_index] ?? 'rfq.id';

    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id,
        usuarios.nombre_usuario,
        rfq.type_of_bid,
        DATE_FORMAT(fecha_completado, '%m/%d/%Y') as fecha_completado,
        rfq.email_code,
        rfq.priority,
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments,
        rfq.sheet_sync_status
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
    // Define a mapping of index → column
    $columns = [
      0 => 'rfq.id',
      1 => 'nombre_usuario',
      2 => 'rfq.type_of_bid',
      3 => 'rfq.fecha_submitted',
      4 => 'rfq.email_code',
      5 => 'rfq.priority'
    ];

    // Use the mapping safely (fallback to rfq.id if index not found)
    $sort_column = $columns[$sort_column_index] ?? 'rfq.id';

    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id,
        usuarios.nombre_usuario,
        rfq.type_of_bid,
        DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted,
        rfq.email_code,
        rfq.priority,
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments,
        rfq.sheet_sync_status
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
    // Define a mapping of index → column
    $columns = [
      0 => 'rfq.id',
      1 => 'nombre_usuario',
      2 => 'rfq.type_of_bid',
      3 => 'rfq.fecha_award',
      4 => 'rfq.email_code',
      5 => 'rfq.priority'
    ];

    // Use the mapping safely (fallback to rfq.id if index not found)
    $sort_column = $columns[$sort_column_index] ?? 'rfq.id';

    if (isset($conexion)) {
      try {
        $sql = "SELECT rfq.id,
        usuarios.nombre_usuario,
        rfq.type_of_bid,
        DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award,
        rfq.email_code,
        rfq.priority,
        CASE
          WHEN type_of_bid = 'Services' THEN 'true'
          WHEN type_of_bid = 'Audio Visual' THEN 'true'
          WHEN type_of_bid = 'Computers' THEN 'true'
          ELSE 'false'
        END AS rfp,
        NULL AS options,
        comments,
        rfq.sheet_sync_status
        FROM rfq
        LEFT JOIN usuarios ON rfq.usuario_designado = usuarios.id
        WHERE rfq.deleted = 0 AND 
        rfq.canal = :canal AND 
        rfq.completado = 1 AND 
        rfq.status = 1 AND 
        rfq.award = 1 AND
        rfq.fullfillment = 0 AND
        rfq.invoice = 0 AND
        rfq.submitted_invoice = 0
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
        award = 1 AND
        rfq.fullfillment = 0 AND
        rfq.invoice = 0 AND
        rfq.submitted_invoice = 0
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
        award = 1 AND
        rfq.fullfillment = 0 AND
        rfq.invoice = 0 AND
        rfq.submitted_invoice = 0
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

  public static function set_sales_commission($conexion, $invoice_date, $sales_commission, $sales_commission_comment, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = "
        UPDATE rfq SET 
        invoice_date = STR_TO_DATE(:invoice_date, '%m/%d/%Y'), 
        sales_commission = :sales_commission, 
        sales_commission_comment = :sales_commission_comment 
        WHERE id = :id_rfq
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':invoice_date', $invoice_date, PDO::PARAM_STR);
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
    $bpa,
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
        estimated_delivery_date = STR_TO_DATE(:estimated_delivery_date, '%m/%d/%Y'), 
        file_document = :file_document,
        accounting = :accounting,
        shipping_address = :shipping_address,
        special_requirements = :special_requirements,
        gsa = :gsa,
        client_payment_terms = :client_payment_terms,
        bpa = :bpa
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
        $sentencia->bindValue(':bpa', $bpa, PDO::PARAM_STR);
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
    $reference_url,
    $priority,
    $id_rfq,
    $site_visit = null,
    $resumes = null,
    $qa_deadline = null
  ) {
    $cotizacion_editada = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE rfq SET
        expiration_date = STR_TO_DATE(:expiration_date, '%m/%d/%Y'),
        fecha_completado = STR_TO_DATE(:fecha_completado, '%m/%d/%Y'),
        address = :address,
        ship_via = :ship_via,
        type_of_bid = :type_of_bid,
        issue_date = :issue_date,
        end_date = :end_date,
        usuario_designado = :usuario_designado,
        email_code = :email_code,
        canal = :canal,
        ship_to = :ship_to,
        comments = :comments,
        reference_url = :reference_url,
        priority = :priority,
        site_visit = :site_visit,
        resumes = :resumes,
        qa_deadline = STR_TO_DATE(:qa_deadline, '%m/%d/%Y')
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
        $sentencia->bindValue(':reference_url', $reference_url, PDO::PARAM_STR);
        $sentencia->bindValue(':priority', $priority, PDO::PARAM_STR);
        $sentencia->bindValue(':site_visit', $site_visit, PDO::PARAM_INT);
        $sentencia->bindValue(':resumes', $resumes, PDO::PARAM_INT);
        $sentencia->bindValue(':qa_deadline', $qa_deadline, PDO::PARAM_STR);
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
      $total_cost += array_sum(explode('|', empty($quote->obtener_fulfillment_shipping_cost()) ? 0 : $quote->obtener_fulfillment_shipping_cost()));
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

  public static function set_services_fulfillment_profit_and_total($connection, $id_rfq) {
    if (isset($connection)) {
      $total_cost = 0;
      $total_profit = 0;
      $quote = self::obtener_cotizacion_por_id($connection, $id_rfq);
      $services = ServiceRepository::get_services($connection, $id_rfq);
      $net30_fulfillment = $quote->getTotalQuoteServices() * 0.029 * $quote->getNet30FulfillmentServices();
      foreach ($services as $i => $service) {
        $total_profit += $service->get_fulfillment_profit();
        $total_cost += FulfillmentServiceRepository::get_total_cost($connection, $service->get_id());
      }
      $total_cost += $net30_fulfillment;
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

  public static function getFulfillmentQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'id';
        break;
      case 1:
        $sort_column = 'email_code';
        break;
      case 2:
        $sort_column = 'canal';
        break;
      case 3:
        $sort_column = 'rfq.fulfillment_date';
        break;
      case 4:
        $sort_column = 'rfq.fecha_award';
        break;
      case 5:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'id';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        email_code, 
        CASE
          WHEN canal = 'FedBid' THEN 'Unison'
          WHEN canal = 'FBO' THEN 'SAM'
          ELSE canal
        END AS canal,
        DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date, 
        DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award, 
        type_of_contract  
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        (invoice IS NULL OR invoice = 0) AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR 
        DATE_FORMAT(fecha_award, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getTotalFulfillmentQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        (invoice IS NULL OR invoice = 0)
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredFulfillmentQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        (invoice IS NULL OR invoice = 0) AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR 
        DATE_FORMAT(fecha_award, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getInvoiceQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'id';
        break;
      case 1:
        $sort_column = 'email_code';
        break;
      case 2:
        $sort_column = 'canal';
        break;
      case 3:
        $sort_column = 'rfq.invoice_date';
        break;
      case 4:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'id';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        email_code, 
        CASE
          WHEN canal = 'FedBid' THEN 'Unison'
          WHEN canal = 'FBO' THEN 'SAM'
          ELSE canal
        END AS canal,
        DATE_FORMAT(invoice_date, '%m/%d/%Y') as invoice_date, 
        type_of_contract  
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        (submitted_invoice IS NULL OR submitted_invoice = 0) AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(invoice_date, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getTotalInvoiceQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        (submitted_invoice IS NULL OR submitted_invoice = 0)
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredInvoiceQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        (submitted_invoice IS NULL OR submitted_invoice = 0) AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(invoice_date, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getSubmittedInvoiceQuotes($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'id';
        break;
      case 1:
        $sort_column = 'email_code';
        break;
      case 2:
        $sort_column = 'canal';
        break;
      case 3:
        $sort_column = 'rfq.submitted_invoice_date';
        break;
      case 4:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'id';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        email_code, 
        CASE
          WHEN canal = 'FedBid' THEN 'Unison'
          WHEN canal = 'FBO' THEN 'SAM'
          ELSE canal
        END AS canal,
        DATE_FORMAT(submitted_invoice_date, '%m/%d/%Y') as submitted_invoice_date, 
        type_of_contract  
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        submitted_invoice = 1 AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(submitted_invoice_date, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getTotalSubmittedInvoiceQuotesCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        submitted_invoice = 1
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredSubmittedInvoiceQuotesCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM rfq 
        WHERE rfq.deleted = 0 AND 
        fullfillment = 1 AND 
        invoice = 1 AND
        submitted_invoice = 1 AND
        (id LIKE :search OR 
        email_code LIKE :search OR 
        canal LIKE :search OR 
        DATE_FORMAT(submitted_invoice_date, '%m/%d/%Y') LIKE :search OR 
        type_of_contract LIKE :search) 
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

  public static function getSearchedQuotesByChannel($conexion, $start, $length, $search, $sort_column_index, $sort_direction, $search_term) {
    $data = [];
    $search_term = '%' . $search_term . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'sq.id';
        break;
      case 1:
        $sort_column = 'sq.email_code';
        break;
      case 2:
        $sort_column = 'sq.contract_number';
        break;
      case 3:
        $sort_column = 'sq.nombre_usuario';
        break;
      case 4:
        $sort_column = 'sq.type_of_bid';
        break;
      case 5:
        $sort_column = 'sq.comments';
        break;
      case 6:
        $sort_column = 'sq.total_price';
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
          sq.contract_number,
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
              COALESCE(SUM(COALESCE(s.total_price, 0)) + COALESCE(r.total_price, 0),0) AS total_price
            FROM rfq r
              LEFT JOIN usuarios u ON r.usuario_designado = u.id
              LEFT JOIN services s ON r.id = s.id_rfq
            WHERE r.deleted = 0
            GROUP BY r.id
          ) as sq
        WHERE (
          sq.nombre_usuario LIKE :search_term
          OR sq.email_code LIKE :search_term
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
          OR EXISTS (
            SELECT 1
            FROM item i
            WHERE i.id_rfq = sq.id
              AND (i.provider_menor LIKE :search_term
                OR i.brand LIKE :search_term
                OR i.brand_project LIKE :search_term
                OR i.part_number LIKE :search_term
                OR i.part_number_project LIKE :search_term
                OR i.description LIKE :search_term
                OR i.description_project LIKE :search_term
                OR i.comments LIKE :search_term)
          )
          OR EXISTS (
            SELECT 1
            FROM services svc
            WHERE svc.id_rfq = sq.id
              AND svc.description LIKE :search_term
          )
          OR EXISTS (
            SELECT 1
            FROM subitems si
              JOIN item i ON si.id_item = i.id
            WHERE i.id_rfq = sq.id
              AND (si.provider_menor LIKE :search_term
                OR si.brand LIKE :search_term
                OR si.brand_project LIKE :search_term
                OR si.part_number LIKE :search_term
                OR si.part_number_project LIKE :search_term
                OR si.description LIKE :search_term
                OR si.description_project LIKE :search_term
                OR si.comments LIKE :search_term)
          )
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
          AND (
            u.nombre_usuario LIKE :search_term
            OR r.email_code LIKE :search_term
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
            OR EXISTS (
              SELECT 1
              FROM item i
              WHERE i.id_rfq = r.id
                AND (i.provider_menor LIKE :search_term
                  OR i.brand LIKE :search_term
                  OR i.brand_project LIKE :search_term
                  OR i.part_number LIKE :search_term
                  OR i.part_number_project LIKE :search_term
                  OR i.description LIKE :search_term
                  OR i.description_project LIKE :search_term
                  OR i.comments LIKE :search_term)
            )
            OR EXISTS (
              SELECT 1
              FROM services s
              WHERE s.id_rfq = r.id
                AND s.description LIKE :search_term
            )
            OR EXISTS (
              SELECT 1
              FROM subitems si
                JOIN item i ON si.id_item = i.id
              WHERE i.id_rfq = r.id
                AND (si.provider_menor LIKE :search_term
                  OR si.brand LIKE :search_term
                  OR si.brand_project LIKE :search_term
                  OR si.part_number LIKE :search_term
                  OR si.part_number_project LIKE :search_term
                  OR si.description LIKE :search_term
                  OR si.description_project LIKE :search_term
                  OR si.comments LIKE :search_term)
            )
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

  public static function getSearchedInvoices($conexion, $start, $length, $sort_column_index, $sort_direction, $search_term) {
    $data = [];
    $search_term = '%' . $search_term . '%';
    switch ($sort_column_index) {
      case 0: $sort_column = 'inv.name'; break;
      case 1: $sort_column = 'inv.created_at'; break;
      case 2: $sort_column = 'r.email_code'; break;
      case 3: $sort_column = 'u.nombre_usuario'; break;
      default: $sort_column = 'inv.created_at'; break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT inv.id,
          inv.name AS invoice_name,
          DATE_FORMAT(inv.created_at, '%m/%d/%Y') AS invoice_date,
          r.id AS quote_id,
          r.email_code,
          u.nombre_usuario
        FROM invoices inv
        JOIN rfq r ON inv.id_rfq = r.id
        LEFT JOIN usuarios u ON r.usuario_designado = u.id
        WHERE r.deleted = 0
          AND inv.name LIKE :search_term
        ORDER BY {$sort_column} {$sort_direction}
        LIMIT {$start}, {$length}
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

  public static function getTotalSearchedInvoicesCount($conexion, $search_term) {
    $search_term = '%' . $search_term . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(inv.id)
        FROM invoices inv
        JOIN rfq r ON inv.id_rfq = r.id
        WHERE r.deleted = 0
          AND inv.name LIKE :search_term
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

  public static function getAnnualAwardsDataByMonthBothYears($connection, $current_year, $past_year) {
    $empty_month = ['total_quotes' => 0, 'total_price' => 0];
    $result = [
      'current_by_month' => array_fill(0, 12, $empty_month),
      'past_by_month'    => array_fill(0, 12, $empty_month),
    ];
    if (!isset($connection)) return $result;
    try {
      $past_start = "{$past_year}-01-01";
      $curr_end   = ($current_year + 1) . "-01-01";
      $sql = "
      SELECT
        MONTH(r.fecha_award) AS month,
        YEAR(r.fecha_award)  AS year,
        COUNT(r.id)          AS total_quotes,
        SUM(COALESCE(s_totals.services_total, 0) + COALESCE(r.total_price, 0)) AS total_price
      FROM rfq r
      LEFT JOIN (
        SELECT id_rfq, SUM(COALESCE(total_price, 0)) AS services_total
        FROM services
        GROUP BY id_rfq
      ) s_totals ON r.id = s_totals.id_rfq
      WHERE r.award = 1
        AND r.deleted = 0
        AND r.fecha_award >= ?
        AND r.fecha_award < ?
      GROUP BY YEAR(r.fecha_award), MONTH(r.fecha_award)
      ";
      $sentence = $connection->prepare($sql);
      $sentence->execute([$past_start, $curr_end]);
      while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
        $idx = (int)$row['month'] - 1;
        $key = (int)$row['year'] === (int)$current_year ? 'current_by_month' : 'past_by_month';
        $result[$key][$idx] = [
          'total_quotes' => (int)$row['total_quotes'],
          'total_price'  => (float)$row['total_price'],
        ];
      }
    } catch (PDOException $ex) {
      print 'ERROR:' . $ex->getMessage() . '<br>';
    }
    return $result;
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

  public static function check_invoice($conexion, $id_rfq) {
    $rfq_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET invoice = 1 WHERE id = :id_rfq';
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

  public static function destroyQuote($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $conexion->beginTransaction(); // Start transaction

        // Delete audit trails and comments
        RepositorioCuestionario::delete_cuestionario_por_id_rfq($conexion, $id_rfq);
        AuditTrailRepository::delete_audit_trails($conexion, $id_rfq);
        RepositorioComment::delete_all_comments($conexion, $id_rfq);

        // Fetch items related to the RFQ
        $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
        if (count($items)) {
          foreach ($items as $item) {
            // Fetch and delete subitems
            $subitems = RepositorioSubitem::obtener_subitems_por_id_item($conexion, $item->obtener_id());
            if (count($subitems)) {
              foreach ($subitems as $subitem) {
                RepositorioSubitem::delete_subitem($conexion, $subitem->obtener_id());
              }
            }
            // Delete the item
            RepositorioItem::delete_item($conexion, $item->obtener_id());
          }
        }

        // Delete the RFQ
        $sql = 'DELETE FROM rfq WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
        $sentencia->execute();

        // Path where RFQ folders are stored
        $folderPath = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
        if (file_exists($folderPath)) {
          self::deleteFolder($folderPath);
        }

        $conexion->commit(); // Commit transaction
      } catch (PDOException $ex) {
        $conexion->rollBack(); // Rollback on error
        throw new Exception("Error deleting RFQ: " . $ex->getMessage());
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

  public static function save_net_30_services($conexion, $id_rfq, $value) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE rfq SET net30_fulfillment_services = :net30_fulfillment_services WHERE id = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':net30_fulfillment_services', $value, PDO::PARAM_STR);
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

  public static function copyRfq($conexion, $id_rfq) {
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);

    // Create a copy of the RFQ
    $cotizacion_copia = new Rfq([
      'id' => '',
      'id_usuario' => $_SESSION['user']->obtener_id(),
      'usuario_designado' => $cotizacion->obtener_usuario_designado(),
      'canal' => $cotizacion->obtener_canal(),
      'email_code' => $cotizacion->obtener_email_code() . '(copia)',
      'type_of_bid' => $cotizacion->obtener_type_of_bid(),
      'issue_date' => $cotizacion->obtener_issue_date(),
      'end_date' => $cotizacion->obtener_end_date(),
      'status' => 0,
      'completado' => 0,
      'total_cost' => $cotizacion->obtener_total_cost(),
      'total_price' => $cotizacion->obtener_total_price(),
      'comments' => $cotizacion->obtener_comments(),
      'award' => 0,
      'fecha_completado' => null,
      'fecha_submitted' => null,
      'fecha_award' => null,
      'payment_terms' => $cotizacion->obtener_payment_terms(),
      'address' => $cotizacion->obtener_address(),
      'ship_to' => $cotizacion->obtener_ship_to(),
      'expiration_date' => null,
      'ship_via' => $cotizacion->obtener_ship_via(),
      'taxes' => $cotizacion->obtener_taxes(),
      'profit' => $cotizacion->obtener_profit(),
      'additional' => $cotizacion->obtener_additional(),
      'shipping' => $cotizacion->obtener_shipping(),
      'shipping_cost' => $cotizacion->obtener_shipping_cost(),
      'fullfillment' => 0,
      'fulfillment_date' => null,
      'contract_number' => $cotizacion->obtener_contract_number(),
      'fulfillment_profit' => null,
      'services_fulfillment_profit' => null,
      'total_fulfillment' => 0,
      'total_services_fulfillment' => 0,
      'invoice' => 0,
      'invoice_date' => null,
      'multi_year_project' => $id_rfq,
      'submitted_invoice' => 0,
      'submitted_invoice_date' => null,
      'fulfillment_pending' => 0,
      'fulfillment_shipping_cost' => 0,
      'fulfillment_shipping' => null,
      'type_of_contract' => null,
      'net30_fulfillment' => null,
      'sales_commission' => null,
      'sales_commission_comment' => null,
      'services_payment_term' => 'Net 30',
      'city' => $cotizacion->obtener_city(),
      'zip_code' => $cotizacion->obtener_zip_code(),
      'state' => $cotizacion->obtener_state(),
      'client' => $cotizacion->obtener_client(),
      'deleted' => 0,
      'set_side' => $cotizacion->getSetSide(),
      'poc' => $cotizacion->getPoc(),
      'co' => $cotizacion->getCo(),
      'estimated_delivery_date' => $cotizacion->getEstimatedDeliveryDate(),
      'shipping_address' => $cotizacion->getShippingAddress(),
      'special_requirements' => $cotizacion->getSpecialRequirements(),
      'file_document' => implode('|', $cotizacion->getFileDocument()),
      'accounting' => implode('|', $cotizacion->getAccounting()),
      'gsa' => $cotizacion->getGsa(),
      'client_payment_terms' => $cotizacion->getClientPaymentTerms(),
      'net30_fulfillment_services' => 'Net 30',
      'bpa' => $cotizacion->getBpa(),
      'reference_url' => $cotizacion->getReferenceUrl(),
      'priority' => null,
    ]);

    // Insert the copied RFQ
    list($cotizacion_insertada, $id_rfq_copia) = RepositorioRfq::insertar_cotizacion($conexion, $cotizacion_copia);

    // Log the action in the audit trail
    AuditTrailRepository::quote_status_audit_trail($conexion, 'Copied', $id_rfq_copia);

    return $id_rfq_copia;
  }

  public static function copyRfqFiles($id_rfq, $id_rfq_copia) {
    $rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
    $rfq_copia_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq_copia;
    Input::copy_files($rfq_directory, $rfq_copia_directory);
  }

  public static function copyItems($conexion, $id_rfq, $id_rfq_copia) {
    $items = RepositorioItem::obtener_items_por_id_rfq($conexion, $id_rfq);
    $duplicated_rooms = RoomRepository::getAll($conexion, $id_rfq_copia);

    if (!empty($items)) {
      foreach ($items as $item) {
        // Map room names to IDs for faster lookup
        $room_map = [];
        foreach ($duplicated_rooms as $duplicated_room) {
          $room_map[$duplicated_room->getName()] = $duplicated_room->getId();
        }

        $roomId = $item->getIdRoom();
        if (!empty($roomId)) {
          $original_room = RoomRepository::getById($conexion, $roomId);

          if ($original_room) {
            $selected_duplicated_room_id = $room_map[$original_room->getName()] ?? null;
          } else {
            // Handle the case where the original room does not exist
            $selected_duplicated_room_id = null; // Or handle this in another way (e.g., log an error)
          }
        } else {
          // Handle the case where the room ID is null or empty
          $selected_duplicated_room_id = null; // Or handle this in another way (e.g., log an error)
        }


        // Copy item
        $item_copia = new Item(
          '',
          $id_rfq_copia,
          $item->obtener_id_usuario(),
          $item->obtener_provider_menor(),
          $item->obtener_brand(),
          $item->obtener_brand_project(),
          $item->obtener_part_number(),
          $item->obtener_part_number_project(),
          $item->obtener_description(),
          $item->obtener_description_project(),
          $item->obtener_quantity(),
          $item->obtener_unit_price(),
          $item->obtener_total_price(),
          $item->obtener_comments(),
          $item->obtener_website(),
          $item->obtener_additional(),
          $item->obtener_fulfillment_profit(),
          $selected_duplicated_room_id
        );
        $item_copia_id = RepositorioItem::insertar_item($conexion, $item_copia);

        // Copy subitems and providers
        self::copySubitems($conexion, $item, $item_copia_id);
        self::copyProviders($conexion, $item, $item_copia_id);
      }
    }
  }

  private static function copySubitems($conexion, $item, $item_copia_id) {
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item($conexion, $item->obtener_id());
    foreach ($subitems as $subitem) {
      $subitem_copia = new Subitem(
        '',
        $item_copia_id,
        $subitem->obtener_provider_menor(),
        $subitem->obtener_brand(),
        $subitem->obtener_brand_project(),
        $subitem->obtener_part_number(),
        $subitem->obtener_part_number_project(),
        $subitem->obtener_description(),
        $subitem->obtener_description_project(),
        $subitem->obtener_quantity(),
        $subitem->obtener_unit_price(),
        $subitem->obtener_total_price(),
        $subitem->obtener_comments(),
        $subitem->obtener_website(),
        $subitem->obtener_additional(),
        $subitem->obtener_fulfillment_profit()
      );
      $subitem_copia_id = RepositorioSubitem::insertar_subitem($conexion, $subitem_copia);

      // Copy provider subitems
      self::copyProviderSubitems($conexion, $subitem, $subitem_copia_id);
    }
  }

  private static function copyProviders($conexion, $item, $item_copia_id) {
    $providers = RepositorioProvider::obtener_providers_por_id_item($conexion, $item->obtener_id());
    foreach ($providers as $provider) {
      $provider_copia = new Provider(
        '',
        $item_copia_id,
        $provider->obtener_provider(),
        $provider->obtener_price()
      );
      RepositorioProvider::insertar_provider($conexion, $provider_copia);
    }
  }

  private static function copyProviderSubitems($conexion, $subitem, $subitem_copia_id) {
    $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem($conexion, $subitem->obtener_id());
    foreach ($providers_subitem as $provider_subitem) {
      $provider_subitem_copia = new ProviderSubitem(
        '',
        $subitem_copia_id,
        $provider_subitem->obtener_provider(),
        $provider_subitem->obtener_price()
      );
      RepositorioProviderSubitem::insertar_provider_subitem($conexion, $provider_subitem_copia);
    }
  }

  public static function copyRfqData($conexion, $id_rfq) {
    $quote = RepositorioRfq::obtener_cotizacion_por_id($conexion, $id_rfq);
    $id_rfq_copia = RepositorioRfq::copyRfq($conexion, $id_rfq);
    RoomRepository::copyRooms($conexion, $id_rfq, $id_rfq_copia);
    RepositorioRfq::copyRfqFiles($id_rfq, $id_rfq_copia);
    RepositorioRfq::copyItems($conexion, $id_rfq, $id_rfq_copia);

    if ($quote->isServices()) {
      ServiceRepository::copyServices($conexion, $id_rfq, $id_rfq_copia);
    }

    return $id_rfq_copia;
  }

  public static function cleanUpRfqFolders($conexion) {
    if (isset($conexion)) {
      try {
        // Fetch all existing RFQ IDs from the database
        $sql = 'SELECT id FROM rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $rfqIds = $sentencia->fetchAll(PDO::FETCH_COLUMN);

        // Path where RFQ folders are stored
        $rfqFolderPath = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/';

        // Get all folders in the RFQ directory
        $allFolders = array_diff(scandir($rfqFolderPath), ['.', '..']); // Exclude '.' and '..'

        // Loop through each folder and check if it corresponds to a valid RFQ ID
        foreach ($allFolders as $folder) {
          $folderPath = $rfqFolderPath . $folder;

          // If the folder name is not in the list of RFQ IDs, delete it
          if (is_dir($folderPath) && !in_array($folder, $rfqIds)) {
            self::deleteFolder($folderPath);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR: ' . $ex->getMessage() . '<br>';
      }
    }
  }

  private static function deleteFolder($folderPath) {
    // Recursively delete all files and folders within the given path
    $files = array_diff(scandir($folderPath), ['.', '..']);
    foreach ($files as $file) {
      $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
      if (is_dir($filePath)) {
        self::deleteFolder($filePath); // Recursive call for subfolders
      } else {
        unlink($filePath); // Delete file
      }
    }
    rmdir($folderPath); // Delete the folder itself
  }
}
