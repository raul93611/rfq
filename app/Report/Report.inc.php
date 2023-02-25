<?php
class Report {
  public static function profit_chart($connection, $type, $quarter, $month, $year) {
    $array_total_cost_quote = array();
    $array_total_price_quote = array();
    $array_total_profit_quote = array();
    $array_total_cost_requote = array();
    $array_total_price_requote = array();
    $array_total_profit_requote = array();
    $array_total_cost_fulfillment = array();
    $array_total_price_fulfillment = array();
    $array_total_profit_fulfillment = array();
    for ($i = 1; $i <= 12; $i++) {
      $quotes = self::get_profit_report($connection, 'monthly', $quarter, $i, $year);
      $total_cost_quote = 0;
      $total_price_quote = 0;
      $total_cost_requote = 0;
      $total_cost_fulfillment = 0;
      foreach ($quotes as $key => $quote) {
        $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote->obtener_id());
        $total_cost_quote += $quote->obtener_total_cost();
        $total_price_quote += $quote->obtener_quote_total_price();
        $total_cost_requote += $re_quote->get_total_cost();
        $total_cost_fulfillment += $quote->obtener_fulfillment_total_cost();
      }
      $array_total_cost_quote[] = $total_cost_quote;
      $array_total_price_quote[] = $total_price_quote;
      $array_total_profit_quote[] = $total_price_quote - $total_cost_quote;

      $array_total_cost_requote[] = $total_cost_requote;
      $array_total_price_requote[] = $total_price_quote;
      $array_total_profit_requote[] = $total_price_quote - $total_cost_requote;

      $array_total_cost_fulfillment[] = $total_cost_fulfillment;
      $array_total_price_fulfillment[] = $total_price_quote;
      $array_total_profit_fulfillment[] = $total_price_quote - $total_cost_fulfillment;
    }

    return array(
      $array_total_cost_quote,
      $array_total_price_quote,
      $array_total_profit_quote,
      $array_total_cost_requote,
      $array_total_price_requote,
      $array_total_profit_requote,
      $array_total_cost_fulfillment,
      $array_total_price_fulfillment,
      $array_total_profit_fulfillment,
    );
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
            $sql = 'SELECT * FROM rfq WHERE invoice = 1 AND MONTH(invoice_date) = :month AND YEAR(invoice_date) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE invoice = 1 AND MONTH(invoice_date) ' . $period . ' AND YEAR(invoice_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE invoice = 1 AND YEAR(invoice_date) = :year';
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

  public static function award_report($connection, $type, $quarter, $month, $year) {
    $quotes = self::get_award_report($connection, $type, $quarter, $month, $year);
    $total['total_cost'] = 0;
    $total['total_price'] = 0;
  ?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
    </div>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>AWARD DATE</th>
          <th>PROPOSAL #</th>
          <th>CONTRACT NUMBER</th>
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
            <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_award()); ?></td>
            <td>
              <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
                <?php echo $quote->obtener_id(); ?>
              </a>
            </td>
            <td><?php echo $quote->obtener_contract_number(); ?></td>
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

  public static function award_chart($connection, $type, $quarter, $month, $year) {
    $array_total_cost = array();
    $array_total_price = array();
    $array_total_profit = array();
    $array_total_profit_percentage = array();
    for ($i = 1; $i <= 12; $i++) {
      $quotes = self::get_award_report($connection, 'monthly', $quarter, $i, $year);
      $total_cost = 0;
      $total_price = 0;
      foreach ($quotes as $key => $quote) {
        $total_cost += $quote->obtener_total_cost();
        $total_price += $quote->obtener_quote_total_price();
      }
      $array_total_cost[] = $total_cost;
      $array_total_price[] = $total_price;
      $array_total_profit[] = $total_profit = $total_price - $total_cost;
      $array_total_profit_percentage[] = $total_price ? 100 * ($total_profit / $total_price) : 0;
    }

    return array($array_total_cost, $array_total_price, $array_total_profit, $array_total_profit_percentage);
  }

  public static function get_award_report($connection, $type, $quarter, $month, $year) {
    $quotes = [];
    if (isset($connection)) {
      try {
        switch ($type) {
          case 'monthly':
            $sql = 'SELECT * FROM rfq WHERE award = 1 AND MONTH(fecha_award) = :month AND YEAR(fecha_award) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE award = 1 AND MONTH(fecha_award) ' . $period . ' AND YEAR(fecha_award) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE award = 1 AND YEAR(fecha_award) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE status = 1 AND MONTH(fecha_submitted) = :month AND YEAR(fecha_submitted) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE status = 1 AND MONTH(fecha_submitted) ' . $period . ' AND YEAR(fecha_submitted) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE status = 1 AND YEAR(fecha_submitted) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND MONTH(fulfillment_date) = :month AND YEAR(fulfillment_date) = :year';
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
            $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND MONTH(fulfillment_date) ' . $period . ' AND YEAR(fulfillment_date) = :year';
            $sentence = $connection->prepare($sql);
            $sentence->bindParam(':year', $year, PDO::PARAM_STR);
            break;
          case 'yearly':
            $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND YEAR(fulfillment_date) = :year';
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
        $sql = 'SELECT * FROM rfq WHERE fulfillment_pending = 1';
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