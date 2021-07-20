<?php
class Report{
  public static function profit_report($connection, $month, $year){
    $quotes = self::get_profit_report($connection, $month, $year);
    $total['total_cost']= 0;
    $total['total_price']= 0;
    ?>
    <div class="my-3">
      <i class="fas fa-square text-primary"></i> Quote <br>
      <i class="fas fa-square text-warning"></i> Re-Quote <br>
      <i class="fas fa-square text-success"></i> Fulfillment
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
            $total_services = ServiceRepository::get_total($connection, $quote-> obtener_id());
            $total['total_cost'] += $quote-> obtener_total_cost();
            $total['total_price'] += $quote-> obtener_total_price() + $total_services;
            $total['re_quote_total_cost'] += $re_quote-> get_total_cost();
            $total['fulfillment_total_cost'] += $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment();
            ?>
            <tr>
              <td style="width: 100px;"><?php echo RepositorioComment::mysql_date_to_english_format($quote-> obtener_invoice_date()); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_total_price() + $total_services; ?></td>
              <td>
                <?php echo $quote-> obtener_total_price() + $total_services - $quote-> obtener_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_total_price() + $total_services - $quote-> obtener_total_cost())/($total_services + $quote-> obtener_total_price())), 2) . '%'; ?>
              </td>
              <td><?php echo $re_quote-> get_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_total_price() + $total_services; ?></td>
              <td>
                <?php echo $quote-> obtener_total_price() + $total_services - $re_quote-> get_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_total_price() + $total_services - $re_quote-> get_total_cost())/($quote-> obtener_total_price() + $total_services)), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment(); ?></td>
              <td><?php echo $quote-> obtener_total_price() + $total_services; ?></td>
              <td>
                <?php echo !is_null($quote-> obtener_services_fulfillment_profit()) || !is_null($quote-> obtener_fulfillment_profit()) ? (double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit() : '0'; ?>
                <br>
                <?php echo !is_null($quote-> obtener_services_fulfillment_profit()) || !is_null($quote-> obtener_fulfillment_profit()) ? number_format(100*(((double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit())/($quote-> obtener_total_price() + $total_services)), 2) . '%' : '0'; ?>
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
        <i class="fas fa-square text-primary"></i> Total Profit(%): <?php echo number_format(100*($total_profit/$total['total_price']), 2); ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-warning"></i> Total Cost: $ <?php echo number_format($total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit: $ <?php echo number_format($re_quote_total_profit = $total['total_price'] - $total['re_quote_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-warning"></i> Total Profit(%): <?php echo number_format(100*($re_quote_total_profit/$total['total_price']), 2); ?>
      </div>
      <div class="col-md-4">
        <i class="fas fa-square text-success"></i> Total Cost: $ <?php echo number_format($total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Price: $ <?php echo number_format($total['total_price'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit: $ <?php echo number_format($fulfillment_total_profit = $total['total_price'] - $total['fulfillment_total_cost'], 2); ?> <br>
        <i class="fas fa-square text-success"></i> Total Profit(%): <?php echo number_format(100*($fulfillment_total_profit/$total['total_price']), 2); ?>
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
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $quotes[] = new Rfq($row['id'], $row['id_usuario'], $row['usuario_designado'], $row['canal'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['status'], $row['completado'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['fecha_completado'], $row['fecha_submitted'], $row['fecha_award'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fullfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment'], $row['invoice'], $row['invoice_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }
}
?>
