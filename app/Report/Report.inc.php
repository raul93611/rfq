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
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit
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
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(rqs.total_price, 0) + COALESCE(rq.total_cost, 0)), 0) as profit
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
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
          COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)), 0) as profit
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

  public static function profit_report($connection, $type, $quarter, $month, $year) {
    $quotes = self::get_profit_report($connection, $type, $quarter, $month, $year);
    $total['total_cost'] = 0;
    $total['total_price'] = 0;
    $total['re_quote_total_cost'] = 0;
    $total['fulfillment_total_cost'] = 0;
?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
      <i class="fas fa-square text-warning"></i> Re-Quote <br>
      <i class="fas fa-square text-success"></i> Fulfillment <br>
      <i class="fas fa-square text-danger"></i> Multi-year project
    </div>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>INVOICE DATE</th>
          <th>PROPOSAL #</th>
          <th class="bg-primary">TOTAL COST</th>
          <th class="bg-primary">TOTAL PRICE</th>
          <th class="bg-primary">PROFIT</th>
          <th class="bg-warning">TOTAL COST</th>
          <th class="bg-warning">TOTAL PRICE</th>
          <th class="bg-warning">PROFIT</th>
          <th class="bg-success">TOTAL COST</th>
          <th class="bg-success">TOTAL PRICE</th>
          <th class="bg-success">PROFIT</th>
          <th>TYPE</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($quotes as $key => $quote) {
          $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote->obtener_id());
          $total['total_cost'] += $quote->obtener_total_cost();
          $total['total_price'] += $quote->obtener_quote_total_price();
          $total['re_quote_total_cost'] += $re_quote->get_total_cost();
          $total['fulfillment_total_cost'] += $quote->obtener_total_fulfillment() + $quote->obtener_total_services_fulfillment();
        ?>
          <tr class="<?php echo $quote->obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
            <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_invoice_date()); ?></td>
            <td>
              <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
                <?php echo $quote->obtener_id(); ?>
              </a>
            </td>
            <td><?php echo $quote->obtener_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_profit(); ?>
              <br>
              <?php echo number_format($quote->obtener_quote_profit_percentage(), 2) . '%'; ?>
            </td>
            <td><?php echo $re_quote->get_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_total_price() - $re_quote->get_total_cost(); ?>
              <br>
              <?php echo $quote->obtener_quote_total_price() ? number_format(100 * (($quote->obtener_quote_total_price() - $re_quote->get_total_cost()) / $quote->obtener_quote_total_price()), 2) . '%' : '0%'; ?>
            </td>
            <td><?php echo $quote->obtener_total_fulfillment() + $quote->obtener_total_services_fulfillment(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo number_format($quote->obtener_real_fulfillment_profit(), 2); ?>
              <br>
              <?php echo number_format($quote->obtener_real_fulfillment_profit_percentage(), 2) . '%'; ?>
            </td>
            <td><?php echo $quote->obtener_type_of_contract(); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div class=" row my-3">
      <div class="col-md-4">
        <i class="fas fa-square text-primary"></i> Total Cost: $ <?php echo number_format($total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit: $ <?php echo number_format($total_profit = $total['total_price'] - $total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($total_profit / $total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($re_quote_total_profit / $total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-success"></i> Total Cost: $ <?php echo number_format($total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit: $ <?php echo number_format($fulfillment_total_profit = $total['total_price'] - $total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($fulfillment_total_profit / $total['total_price']), 2) : 0; ?>
      </div>
    </div>
  <?php
  }

  public static function get_profit_report($connection, $type, $quarter, $month, $year) {
    $quotes = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND invoice = 1 AND MONTH(invoice_date) = :month AND YEAR(invoice_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':month', $month, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'quarterly':
            switch ($quarter) {
              case 1:
                $period = 'BETWEEN 1 AND 3';
                break;
              case 2:
                $period = 'BETWEEN 4 AND 6';
                break;
              case 3:
                $period = 'BETWEEN 7 AND 9';
                break;
              case 4:
                $period = 'BETWEEN 10 AND 12';
                break;
            }
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND invoice = 1 AND MONTH(invoice_date) ' . $period . ' AND YEAR(invoice_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND invoice = 1 AND YEAR(invoice_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
        }
        $sentence->execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
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
            $sql = '
            SELECT r.id, 
            fecha_award, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            MONTH(fecha_award) = :month AND 
            YEAR(fecha_award) = :year AND 
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search) 
            GROUP BY r.id
            ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':month', $month, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'quarterly':
            $sql = '
            SELECT r.id, 
            fecha_award, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = ' . $quarter . ' AND
            YEAR(fecha_award) = :year AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = '
            SELECT r.id, 
            fecha_award, 
            contract_number, 
            email_code, 
            u.nombre_usuario,
            canal, 
            type_of_bid, 
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) AS total_cost,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) AS total_price,
            COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_price, 0)), 0) - COALESCE(SUM(COALESCE(s.total_price, 0) + COALESCE(r.total_cost, 0)), 0) as profit,  
            type_of_contract 
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = :year AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            GROUP BY r.id
            ORDER BY ' . $sort_column . ' ' . $sort_direction . ' LIMIT ' . $start . ', ' . $length;
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
        }
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
            MONTH(fecha_award) = " . $month . " AND 
            YEAR(fecha_award) = " . $year . " AND
            award = 1  
            ";
            $sentence = $connection->prepare($sql);
            break;
          case 'quarterly':
            $sql = '
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = ' . $quarter . ' AND
            YEAR(fecha_award) = ' . $year;
            $sentence = $connection->prepare($sql);
            break;
          case 'yearly':
            $sql = '
            SELECT COUNT(r.id)
            FROM rfq r
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = ' . $year;
            $sentence = $connection->prepare($sql);
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
            $sql = '
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            MONTH(fecha_award) = :month AND 
            YEAR(fecha_award) = :year AND 
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':month', $month, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'quarterly':
            $sql = '
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            QUARTER(fecha_award) = ' . $quarter . ' AND
            YEAR(fecha_award) = :year AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = '
            SELECT COUNT(r.id)
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            JOIN usuarios u ON r.usuario_designado = u.id
            WHERE deleted = 0 AND
            award = 1 AND
            YEAR(fecha_award) = :year AND
            (r.id LIKE :search OR 
            fecha_award LIKE :search OR
            contract_number LIKE :search OR
            email_code LIKE :search OR
            nombre_usuario LIKE :search OR
            canal LIKE :search OR
            type_of_bid LIKE :search OR 
            type_of_contract LIKE :search)
            ';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':search', $search, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
        }
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function submitted_report($connection, $type, $quarter, $month, $year) {
    $quotes = self::get_submitted_report($connection, $type, $quarter, $month, $year);
    $total['total_cost'] = 0;
    $total['total_price'] = 0;
  ?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
    </div>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>SUBMITTED DATE</th>
          <th>PROPOSAL #</th>
          <th>CODE</th>
          <th>DESIGNATED USER</th>
          <th>CHANNEL</th>
          <th>TYPE OF BID</th>
          <th class="bg-primary">TOTAL COST</th>
          <th class="bg-primary">TOTAL PRICE</th>
          <th class="bg-primary">PROFIT</th>
          <th>TYPE</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($quotes as $key => $quote) {
          $total['total_cost'] += $quote->obtener_total_cost();
          $total['total_price'] += $quote->obtener_quote_total_price();
        ?>
          <tr>
            <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_submitted()); ?></td>
            <td>
              <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
                <?php echo $quote->obtener_id(); ?>
              </a>
            </td>
            <td><?php echo $quote->obtener_email_code(); ?></td>
            <td><?php echo $quote->obtener_designated_username(); ?></td>
            <td><?php echo $quote->print_channel(); ?></td>
            <td><?php echo $quote->obtener_type_of_bid(); ?></td>
            <td><?php echo $quote->obtener_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_profit(); ?>
              <br>
              <?php echo number_format($quote->obtener_quote_profit_percentage(), 2) . '%'; ?>
            </td>
            <td><?php echo $quote->obtener_type_of_contract(); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div class=" row my-3">
      <div class="col-md-12">
        <i class="fas fa-square text-primary"></i> Total Cost: $ <?php echo number_format($total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit: $ <?php echo number_format($total_profit = $total['total_price'] - $total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($total_profit / $total['total_price']), 2) : 0; ?>
      </div>
    </div>
  <?php
  }

  public static function get_submitted_report($connection, $type, $quarter, $month, $year) {
    $quotes = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND status = 1 AND MONTH(fecha_submitted) = :month AND YEAR(fecha_submitted) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':month', $month, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'quarterly':
            switch ($quarter) {
              case 1:
                $period = 'BETWEEN 1 AND 3';
                break;
              case 2:
                $period = 'BETWEEN 4 AND 6';
                break;
              case 3:
                $period = 'BETWEEN 7 AND 9';
                break;
              case 4:
                $period = 'BETWEEN 10 AND 12';
                break;
            }
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND status = 1 AND MONTH(fecha_submitted) ' . $period . ' AND YEAR(fecha_submitted) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND status = 1 AND YEAR(fecha_submitted) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
        }
        $sentence->execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function re_quote_report($connection, $type, $quarter, $month, $year) {
    $quotes = self::get_re_quote_report($connection, $type, $quarter, $month, $year);
    $total['total_cost'] = 0;
    $total['total_price'] = 0;
    $total['re_quote_total_cost'] = 0;
  ?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
      <i class="fas fa-square text-warning"></i> Re-Quote <br>
      <i class="fas fa-square text-danger"></i> Multi-year project
    </div>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>FULFILLMENT DATE</th>
          <th>PROPOSAL #</th>
          <th>DESIGNATED USER</th>
          <th>CHANNEL</th>
          <th>CODE</th>
          <th>TYPE OF BID</th>
          <th>AWARD DATE</th>
          <th>CONTRACT NUMBER</th>
          <th class="bg-primary">TOTAL COST</th>
          <th class="bg-primary">TOTAL PRICE</th>
          <th class="bg-primary">PROFIT</th>
          <th class="bg-warning">TOTAL COST</th>
          <th class="bg-warning">TOTAL PRICE</th>
          <th class="bg-warning">PROFIT</th>
          <th>TYPE</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($quotes as $key => $quote) {
          $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote->obtener_id());
          $total['total_cost'] += $quote->obtener_total_cost();
          $total['total_price'] += $quote->obtener_quote_total_price();
          $total['re_quote_total_cost'] += $re_quote->get_total_cost();
        ?>
          <tr class="<?php echo $quote->obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
            <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fulfillment_date()); ?></td>
            <td>
              <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
                <?php echo $quote->obtener_id(); ?>
              </a>
            </td>
            <td><?php echo $quote->obtener_designated_username(); ?></td>
            <td><?php echo $quote->print_channel(); ?></td>
            <td><?php echo $quote->obtener_email_code(); ?></td>
            <td><?php echo $quote->obtener_type_of_bid(); ?></td>
            <td><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_award()); ?></td>
            <td><?php echo $quote->obtener_contract_number(); ?></td>
            <td><?php echo $quote->obtener_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_profit(); ?>
              <br>
              <?php echo number_format($quote->obtener_quote_profit_percentage(), 2) . '%'; ?>
            </td>
            <td><?php echo $re_quote->get_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_total_price() - $re_quote->get_total_cost(); ?>
              <br>
              <?php echo number_format(100 * (($quote->obtener_quote_total_price() - $re_quote->get_total_cost()) / $quote->obtener_quote_total_price()), 2) . '%'; ?>
            </td>
            <td><?php echo $quote->obtener_type_of_contract(); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div class=" row my-3">
      <div class="col-md-6">
        <i class="fas fa-square text-primary"></i> Total Cost: $ <?php echo number_format($total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit: $ <?php echo number_format($total_profit = $total['total_price'] - $total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($total_profit / $total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-6">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ?  number_format(100 * ($re_quote_total_profit / $total['total_price']), 2) : 0; ?>
      </div>
    </div>
  <?php
  }

  public static function get_re_quote_report($connection, $type, $quarter, $month, $year) {
    $quotes = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND fullfillment = 1 AND MONTH(fulfillment_date) = :month AND YEAR(fulfillment_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':month', $month, PDO::PARAM_STR);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'quarterly':
            switch ($quarter) {
              case 1:
                $period = 'BETWEEN 1 AND 3';
                break;
              case 2:
                $period = 'BETWEEN 4 AND 6';
                break;
              case 3:
                $period = 'BETWEEN 7 AND 9';
                break;
              case 4:
                $period = 'BETWEEN 10 AND 12';
                break;
            }
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND fullfillment = 1 AND MONTH(fulfillment_date) ' . $period . ' AND YEAR(fulfillment_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND fullfillment = 1 AND YEAR(fulfillment_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
        }
        $sentence->execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function fulfillment_pending_report($connection) {
    $quotes = self::get_fulfillment_pending_report($connection);
    $total['total_cost'] = 0;
    $total['total_price'] = 0;
    $total['re_quote_total_cost'] = 0;
  ?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
      <i class="fas fa-square text-warning"></i> Re-Quote <br>
      <i class="fas fa-square text-danger"></i> Multi-year project
    </div>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>FULFILLMENT DATE</th>
          <th>PROPOSAL #</th>
          <th>DESIGNATED USER</th>
          <th>CHANNEL</th>
          <th>CODE</th>
          <th>TYPE OF BID</th>
          <th>AWARD DATE</th>
          <th>CONTRACT NUMBER</th>
          <th class="bg-primary">TOTAL COST</th>
          <th class="bg-primary">TOTAL PRICE</th>
          <th class="bg-primary">PROFIT</th>
          <th class="bg-warning">TOTAL COST</th>
          <th class="bg-warning">TOTAL PRICE</th>
          <th class="bg-warning">PROFIT</th>
          <th>TYPE</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($quotes as $key => $quote) {
          $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote->obtener_id());
          $total['total_cost'] += $quote->obtener_total_cost();
          $total['total_price'] += $quote->obtener_quote_total_price();
          $total['re_quote_total_cost'] += $re_quote->get_total_cost();
        ?>
          <tr class="<?php echo $quote->obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
            <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fulfillment_date()); ?></td>
            <td>
              <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
                <?php echo $quote->obtener_id(); ?>
              </a>
            </td>
            <td><?php echo $quote->obtener_designated_username(); ?></td>
            <td><?php echo $quote->print_channel(); ?></td>
            <td><?php echo $quote->obtener_email_code(); ?></td>
            <td><?php echo $quote->obtener_type_of_bid(); ?></td>
            <td><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_award()); ?></td>
            <td><?php echo $quote->obtener_contract_number(); ?></td>
            <td><?php echo $quote->obtener_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_profit(); ?>
              <br>
              <?php echo number_format($quote->obtener_quote_profit_percentage(), 2) . '%'; ?>
            </td>
            <td><?php echo $re_quote->get_total_cost(); ?></td>
            <td><?php echo $quote->obtener_quote_total_price(); ?></td>
            <td>
              <?php echo $quote->obtener_quote_total_price() - $re_quote->get_total_cost(); ?>
              <br>
              <?php echo number_format(100 * (($quote->obtener_quote_total_price() - $re_quote->get_total_cost()) / $quote->obtener_quote_total_price()), 2) . '%'; ?>
            </td>
            <td><?php echo $quote->obtener_type_of_contract(); ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
    <div class=" row my-3">
      <div class="col-md-6">
        <i class="fas fa-square text-primary"></i> Total Cost: $ <?php echo number_format($total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit: $ <?php echo number_format($total_profit = $total['total_price'] - $total['total_cost'], 2); ?> <br>
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($total_profit / $total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-6">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100 * ($re_quote_total_profit / $total['total_price']), 2) : 0; ?>
      </div>
    </div>
<?php
  }

  public static function get_fulfillment_pending_report($connection) {
    $quotes = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM rfq WHERE deleted = 0 AND fulfillment_pending = 1';
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }
}
?>