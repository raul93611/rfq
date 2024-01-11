<?php
class Report {
  public static function getAnnualQuotesAmountsByMonth($connection, $year, $type) {
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
      case 'invoice':
        $date = 'invoice_date';
        $status = 'invoice';
        break;

      default:
        $date = 'fecha_completado';
        $status = 'completado';
        break;
    }
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          months.month,
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit
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
          MONTH(r." . $date . ") = months.month AND 
          r." . $status . " = 1 AND
          r.deleted = 0 AND
          YEAR(r." . $date . ") = " . $year . " 
          LEFT JOIN services s ON 
          r.id = s.id_rfq AND 
          MONTH(r." . $date . ") = months.month AND 
          YEAR(r." . $date . ") = " . $year . "
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

  public static function getAnnualReQuotesAmountsByMonth($connection, $year, $type) {
    $data = [];
    switch ($type) {
      case 'invoice':
        $date = 'invoice_date';
        $status = 'invoice';
        break;

      default:
        $date = 'invoice_date';
        $status = 'invoice';
        break;
    }
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          months.month,
          COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit
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
          MONTH(r." . $date . ") = months.month AND 
          r." . $status . " = 1 AND
          r.deleted = 0 AND
          YEAR(r." . $date . ") = " . $year . " 
          LEFT JOIN services s ON 
          r.id = s.id_rfq AND 
          MONTH(r." . $date . ") = months.month AND 
          YEAR(r." . $date . ") = " . $year . "
          LEFT JOIN re_quotes rq ON 
          r.id = rq.id_rfq AND 
          MONTH(r." . $date . ") = months.month AND 
          YEAR(r." . $date . ") = " . $year . "
          LEFT JOIN re_quote_services rqs ON 
          rq.id = rqs.id_re_quote AND 
          MONTH(r." . $date . ") = months.month AND 
          YEAR(r." . $date . ") = " . $year . "
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

  public static function getAnnualFulfillmentAmountsByMonth($connection, $year, $type) {
    $data = [];
    switch ($type) {
      case 'invoice':
        $date = 'invoice_date';
        $status = 'invoice';
        break;

      default:
        $date = 'invoice_date';
        $status = 'invoice';
        break;
    }
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          months.month,
          COALESCE(SUM(COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)), 0) AS total_cost,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)), 0) as profit
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
          MONTH(r." . $date . ") = months.month AND 
          r." . $status . " = 1 AND
          r.deleted = 0 AND
          YEAR(r." . $date . ") = " . $year . " 
          LEFT JOIN services s ON 
          r.id = s.id_rfq AND 
          MONTH(r." . $date . ") = months.month AND 
          YEAR(r." . $date . ") = " . $year . "
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

  public static function getAwardReport(
    $connection,
    $start,
    $length,
    $search,
    $sort_column_index,
    $sort_direction,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'r.id';
        break;
      case 1:
        $sort_column = 'fecha_award';
        break;
      case 2:
        $sort_column = 'contract_number';
        break;
      case 3:
        $sort_column = 'code';
        break;
      case 4:
        $sort_column = 'nombre_usuario';
        break;
      case 5:
        $sort_column = 'canal';
        break;
      case 6:
        $sort_column = 'type_of_bid';
        break;
      case 7:
        $sort_column = 'total_cost';
        break;
      case 8:
        $sort_column = 'total_price';
        break;
      case 9:
        $sort_column = 'profit';
        break;
      case 10:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'r.id';
        break;
    }
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            MONTH(fecha_award) = {$month} AND 
            YEAR(fecha_award) = {$year} AND 
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = {$quarter} AND
            YEAR(fecha_award) = {$year} AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_award, '%m/%d/%Y') as fecha_award,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = {$year} AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getAwardReportCount($connection, $type, $quarter, $month, $year) {
    $month = (int)$month;
    $year = (int)$year;
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND 
            MONTH(fecha_award) = {$month} AND 
            YEAR(fecha_award) = {$year} AND
            award = 1  
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = {$quarter} AND
            YEAR(fecha_award) = {$year}";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = {$year}";
            break;
        }
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getFilteredAwardReportCount($connection, $search, $type, $quarter, $month, $year) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            MONTH(fecha_award) = {$month} AND 
            YEAR(fecha_award) = {$year} AND 
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = {$quarter} AND
            YEAR(fecha_award) = {$year} AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = {$year} AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getSubmittedReport(
    $connection,
    $start,
    $length,
    $search,
    $sort_column_index,
    $sort_direction,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'r.id';
        break;
      case 1:
        $sort_column = 'fecha_submitted';
        break;
      case 2:
        $sort_column = 'code';
        break;
      case 3:
        $sort_column = 'nombre_usuario';
        break;
      case 4:
        $sort_column = 'canal';
        break;
      case 5:
        $sort_column = 'type_of_bid';
        break;
      case 6:
        $sort_column = 'total_cost';
        break;
      case 7:
        $sort_column = 'total_price';
        break;
      case 8:
        $sort_column = 'profit';
        break;
      case 9:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'r.id';
        break;
    }
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            MONTH(fecha_submitted) = {$month} AND 
            YEAR(fecha_submitted) = {$year} AND 
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted,  
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            QUARTER(fecha_submitted) = {$quarter} AND
            YEAR(fecha_submitted) = {$year} AND
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fecha_submitted, '%m/%d/%Y') as fecha_submitted,  
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            YEAR(fecha_submitted) = {$year} AND
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getSubmittedReportCount($connection, $type, $quarter, $month, $year) {
    $month = (int)$month;
    $year = (int)$year;
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND 
            MONTH(fecha_submitted) = {$month} AND 
            YEAR(fecha_submitted) = {$year} AND
            r.status = 1  
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            QUARTER(fecha_submitted) = {$quarter} AND
            YEAR(fecha_submitted) = {$year}";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            YEAR(fecha_submitted) = {$year}";
            break;
        }
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getFilteredSubmittedReportCount($connection, $search, $type, $quarter, $month, $year) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            MONTH(fecha_submitted) = {$month} AND 
            YEAR(fecha_submitted) = {$year} AND 
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            QUARTER(fecha_submitted) = {$quarter} AND
            YEAR(fecha_submitted) = {$year} AND
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            r.status = 1 AND
            YEAR(fecha_submitted) = {$year} AND
            (r.id LIKE :search OR 
            fecha_submitted LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getFulfillmentReport(
    $connection,
    $start,
    $length,
    $search,
    $sort_column_index,
    $sort_direction,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'r.id';
        break;
      case 1:
        $sort_column = 'fulfillment_date';
        break;
      case 2:
        $sort_column = 'contract_number';
        break;
      case 3:
        $sort_column = 'code';
        break;
      case 4:
        $sort_column = 'nombre_usuario';
        break;
      case 5:
        $sort_column = 'canal';
        break;
      case 6:
        $sort_column = 'type_of_bid';
        break;
      case 7:
        $sort_column = 'total_cost';
        break;
      case 8:
        $sort_column = 'total_price';
        break;
      case 9:
        $sort_column = 'profit';
        break;
      case 10:
        $sort_column = 'total_cost_requote';
        break;
      case 11:
        $sort_column = 'total_price_requote';
        break;
      case 12:
        $sort_column = 'profit_requote';
        break;
      case 13:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'r.id';
        break;
    }
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            MONTH(fulfillment_date) = {$month} AND 
            YEAR(fulfillment_date) = {$year} AND 
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote, 
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            QUARTER(fulfillment_date) = {$quarter} AND
            YEAR(fulfillment_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') as fulfillment_date,  
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote, 
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            YEAR(fulfillment_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getFulfillmentReportCount($connection, $type, $quarter, $month, $year) {
    $month = (int)$month;
    $year = (int)$year;
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND 
            MONTH(fulfillment_date) = {$month} AND 
            YEAR(fulfillment_date) = {$year} AND
            fullfillment = 1  
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            QUARTER(fulfillment_date) = {$quarter} AND
            YEAR(fulfillment_date) = {$year}";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            YEAR(fulfillment_date) = {$year}";
            break;
        }
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getFilteredFulfillmentReportCount($connection, $search, $type, $quarter, $month, $year) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            MONTH(fulfillment_date) = {$month} AND 
            YEAR(fulfillment_date) = {$year} AND 
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            QUARTER(fulfillment_date) = {$quarter} AND
            YEAR(fulfillment_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            fullfillment = 1 AND
            YEAR(fulfillment_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getAccountsPayableFulfillmentReport(
    $connection,
    $start,
    $length,
    $search,
    $sort_column_index,
    $sort_direction,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'id';
        break;
      case 1:
        $sort_column = 'provider';
        break;
      case 2:
        $sort_column = 'real_cost';
        break;
      case 3:
        $sort_column = 'payment_term';
        break;
      default:
        $sort_column = 'r.id';
        break;
    }
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search  
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length};
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search  
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length};
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT id, provider, real_cost, payment_term 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search  
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length};
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getAccountsPayableFulfillmentReportCount($connection, $type, $quarter, $month, $year) {
    $month = (int)$month;
    $year = (int)$year;
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT SUM(total) 
            FROM (
            SELECT COUNT(r.id) AS total FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment;
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT SUM(total) 
            FROM (
            SELECT COUNT(r.id) AS total FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment;
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT SUM(total) 
            FROM (
            SELECT COUNT(r.id) AS total FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT COUNT(r.id) AS total FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment;
            ";
            break;
        }
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getFilteredAccountsPayableFulfillmentReportCount($connection, $search, $type, $quarter, $month, $year) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(id)
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND MONTH(fulfillment_date) = {$month}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search;
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(id) 
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND QUARTER(fulfillment_date) = {$quarter}
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search;
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(id)
            FROM (
            SELECT r.id, fi.provider, fi.real_cost, fi.payment_term FROM `fulfillment_items` fi
            LEFT JOIN item i ON fi.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fsi.provider, fsi.real_cost, fsi.payment_term FROM `fulfillment_subitems` fsi
            LEFT JOIN subitems si ON fsi.id_subitem = si.id
            LEFT JOIN item i ON si.id_item = i.id
            LEFT JOIN rfq r ON i.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}

            UNION ALL

            SELECT r.id, fs.provider, fs.real_cost, fs.payment_term FROM `fulfillment_services` fs
            LEFT JOIN services s ON fs.id_service = s.id
            LEFT JOIN rfq r ON s.id_rfq = r.id
            WHERE r.fullfillment = 1
            AND r.deleted = 0
            AND YEAR(fulfillment_date) = {$year}
            ) as accounts_payable_fulfillment
            WHERE id LIKE :search OR 
            provider LIKE :search OR
            real_cost LIKE :search OR
            payment_term LIKE :search;
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getSalesCommissionReport(
    $connection,
    $start,
    $length,
    $search,
    $sort_column_index,
    $sort_direction,
    $type,
    $quarter,
    $month,
    $year
  ) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'r.id';
        break;
      case 1:
        $sort_column = 'invoice_date';
        break;
      case 2:
        $sort_column = 'contract_number';
        break;
      case 3:
        $sort_column = 'code';
        break;
      case 4:
        $sort_column = 'nombre_usuario';
        break;
      case 5:
        $sort_column = 'state';
        break;
      case 6:
        $sort_column = 'client';
        break;
      case 7:
        $sort_column = 'total_cost';
        break;
      case 8:
        $sort_column = 'total_price';
        break;
      case 9:
        $sort_column = 'profit';
        break;
      case 10:
        $sort_column = 'total_cost_requote';
        break;
      case 11:
        $sort_column = 'total_price_requote';
        break;
      case 12:
        $sort_column = 'profit_requote';
        break;
      case 10:
        $sort_column = 'total_cost_fulfillment';
        break;
      case 11:
        $sort_column = 'total_price_fulfillment';
        break;
      case 12:
        $sort_column = 'profit_fulfillment';
        break;
      case 13:
        $sort_column = 'type_of_contract';
        break;
      case 14:
        $sort_column = 'sales_commission';
        break;
      default:
        $sort_column = 'r.id';
        break;
    }
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
            r.contract_number, 
            r.email_code, 
            u.nombre_usuario,
            r.state, 
            r.client, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit_fulfillment,
            r.type_of_contract,
            CASE r.sales_commission
              WHEN 'Other commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(r.total_fulfillment, 0)) * 0.03, 2) 
              WHEN 'Same commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(rq.total_cost, 0)) * 0.03, 2) 
              WHEN 'No commission' THEN 0
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            invoice = 1 AND
            MONTH(invoice_date) = {$month} AND 
            YEAR(invoice_date) = {$year} AND 
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
            r.contract_number, 
            r.email_code, 
            u.nombre_usuario,
            r.state, 
            r.client, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit_fulfillment,
            r.type_of_contract,
            CASE r.sales_commission
            	WHEN 'Other commission' THEN ROUND((COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0))) * 0.03, 2)
              WHEN 'Same commission' THEN ROUND((COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0)) * 0.03, 2)
              WHEN 'No commission' THEN 0
            END as sales_commission
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            invoice = 1 AND
            QUARTER(invoice_date) = {$quarter} AND
            YEAR(invoice_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
          case 'yearly':
            $sql = "
            SELECT r.id, 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
            r.contract_number, 
            r.email_code, 
            u.nombre_usuario,
            r.state, 
            r.client, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit, 
            COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) AS total_cost_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_requote,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit_requote,
            COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price_fulfillment,
            COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit_fulfillment,
            r.type_of_contract,
            CASE r.sales_commission
            	WHEN 'Other commission' THEN ROUND((COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0))) * 0.03, 2)
              WHEN 'Same commission' THEN ROUND((COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0)) * 0.03, 2)
              WHEN 'No commission' THEN 0
            END as sales_commission
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            LEFT JOIN usuarios u ON r.usuario_designado = u.id
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
            WHERE deleted = 0 AND
            invoice = 1 AND
            YEAR(invoice_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getSalesCommissionReportCount($connection, $type, $quarter, $month, $year) {
    $month = (int)$month;
    $year = (int)$year;
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND 
            MONTH(invoice_date) = {$month} AND 
            YEAR(invoice_date) = {$year} AND
            invoice = 1  
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            invoice = 1 AND
            QUARTER(invoice_date) = {$quarter} AND
            YEAR(invoice_date) = {$year}";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            invoice = 1 AND
            YEAR(invoice_date) = {$year}";
            break;
        }
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getFilteredSalesCommissionReportCount($connection, $search, $type, $quarter, $month, $year) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            invoice = 1 AND
            MONTH(invoice_date) = {$month} AND 
            YEAR(invoice_date) = {$year} AND 
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search)
            ";
            break;
          case 'quarterly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            invoice = 1 AND
            QUARTER(invoice_date) = {$quarter} AND
            YEAR(invoice_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search)
            ";
            break;
          case 'yearly':
            $sql = "
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            invoice = 1 AND
            YEAR(invoice_date) = {$year} AND
            (r.id LIKE :search OR 
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') LIKE :search OR
            r.contract_number LIKE :search OR
            r.email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            r.state LIKE :search OR
            r.client LIKE :search OR 
            r.type_of_contract LIKE :search)
            ";
            break;
        }
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }
}
