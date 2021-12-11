<?php
class Report{
  public static function profit_report($connection, $month, $year){
    $quotes = self::get_profit_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;
    $total['fulfillment_total_cost']= 0;
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
            $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_quote_total_price();
            $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
            $total['fulfillment_total_cost'] += $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment();
            ?>
            <tr class="<?php echo $quote-> obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_invoice_date()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_profit(); ?>
                <br>
                <?php echo number_format($quote-> obtener_quote_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $re_quote-> get_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_total_price() - $re_quote-> get_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_quote_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_quote_total_price()), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo number_format($quote-> obtener_real_fulfillment_profit(), 2); ?>
                <br>
                <?php echo number_format($quote-> obtener_real_fulfillment_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($total_profit/$total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($re_quote_total_profit/$total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-success"></i> Total Cost: $ <?php echo number_format($total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit: $ <?php echo number_format($fulfillment_total_profit = $total['total_price'] - $total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($fulfillment_total_profit/$total['total_price']), 2) : 0; ?>
      </div>
    </div>
    <?php
  }

  public static function get_profit_report($connection, $month, $year){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE invoice = 1 AND MONTH(invoice_date) = :month AND YEAR(invoice_date) = :year';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':month', $month, PDO::PARAM_STR);
        $sentence-> bindParam(':year', $year, PDO::PARAM_STR);
        $sentence-> execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function award_report($connection, $month, $year){
    $quotes = self::get_award_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
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
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_quote_total_price();
            ?>
            <tr>
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fecha_award()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_contract_number(); ?></td>
              <td><?php echo $quote-> obtener_email_code(); ?></td>
              <td><?php echo $quote-> obtener_designated_username(); ?></td>
              <td><?php echo $quote-> print_channel(); ?></td>
              <td><?php echo $quote-> obtener_type_of_bid(); ?></td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_profit(); ?>
                <br>
                <?php echo number_format($quote-> obtener_quote_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($total_profit/$total['total_price']), 2) : 0; ?>
      </div>
    </div>
    <?php
  }

  public static function get_award_report($connection, $month, $year){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE award = 1 AND MONTH(fecha_award) = :month AND YEAR(fecha_award) = :year';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':month', $month, PDO::PARAM_STR);
        $sentence-> bindParam(':year', $year, PDO::PARAM_STR);
        $sentence-> execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function submitted_report($connection, $month, $year){
    $quotes = self::get_submitted_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
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
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_quote_total_price();
            ?>
            <tr>
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fecha_submitted()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_email_code(); ?></td>
              <td><?php echo $quote-> obtener_designated_username(); ?></td>
              <td><?php echo $quote-> print_channel(); ?></td>
              <td><?php echo $quote-> obtener_type_of_bid(); ?></td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_profit(); ?>
                <br>
                <?php echo number_format($quote-> obtener_quote_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($total_profit/$total['total_price']), 2) : 0; ?>
      </div>
    </div>
    <?php
  }

  public static function get_submitted_report($connection, $month, $year){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE status = 1 AND MONTH(fecha_submitted) = :month AND YEAR(fecha_submitted) = :year';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':month', $month, PDO::PARAM_STR);
        $sentence-> bindParam(':year', $year, PDO::PARAM_STR);
        $sentence-> execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function re_quote_report($connection, $month, $year){
    $quotes = self::get_re_quote_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;
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
            $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_quote_total_price();
            $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
            ?>
            <tr class="<?php echo $quote-> obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fulfillment_date()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_designated_username(); ?></td>
              <td><?php echo $quote-> print_channel(); ?></td>
              <td><?php echo $quote-> obtener_email_code(); ?></td>
              <td><?php echo $quote-> obtener_type_of_bid(); ?></td>
              <td><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fecha_award()); ?></td>
              <td><?php echo $quote-> obtener_contract_number(); ?></td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_profit(); ?>
                <br>
                <?php echo number_format($quote-> obtener_quote_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $re_quote-> get_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_total_price() - $re_quote-> get_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_quote_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_quote_total_price()), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($total_profit/$total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-6">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ?  number_format(100*($re_quote_total_profit/$total['total_price']), 2) : 0; ?>
      </div>
    </div>
    <?php
  }

  public static function get_re_quote_report($connection, $month, $year){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND MONTH(fulfillment_date) = :month AND YEAR(fulfillment_date) = :year';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':month', $month, PDO::PARAM_STR);
        $sentence-> bindParam(':year', $year, PDO::PARAM_STR);
        $sentence-> execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function fulfillment_pending_report($connection){
    $quotes = self::get_fulfillment_pending_report($connection);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    $total['re_quote_total_cost']= 0;
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
            $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq($connection, $quote-> obtener_id());
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_quote_total_price();
            $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
            ?>
            <tr class="<?php echo $quote-> obtener_multi_year_project() ? 'bg-danger' : ''; ?>">
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fulfillment_date()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_designated_username(); ?></td>
              <td><?php echo $quote-> print_channel(); ?></td>
              <td><?php echo $quote-> obtener_email_code(); ?></td>
              <td><?php echo $quote-> obtener_type_of_bid(); ?></td>
              <td><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_fecha_award()); ?></td>
              <td><?php echo $quote-> obtener_contract_number(); ?></td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_profit(); ?>
                <br>
                <?php echo number_format($quote-> obtener_quote_profit_percentage(), 2) . '%'; ?>
              </td>
              <td><?php echo $re_quote-> get_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_quote_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_quote_total_price() - $re_quote-> get_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_quote_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_quote_total_price()), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($total_profit/$total['total_price']), 2) : 0; ?>
      </div>
      <div class="col-md-6">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo $total['total_price'] ? number_format(100*($re_quote_total_profit/$total['total_price']), 2) : 0; ?>
      </div>
    </div>
    <?php
  }

  public static function get_fulfillment_pending_report($connection){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE fulfillment_pending = 1';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $quotes = RepositorioRfq::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }
}
?>
