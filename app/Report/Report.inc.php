<?php
class Report{
  public static function profit_report($connection, $month, $year){
    $quotes = self::get_profit_report($connection, $month, $year);
    ?>
    <table class="table table-bordered table-hover regular_table">
      <thead>
        <tr>
          <th>DATE</th>
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
            ?>
            <tr>
              <td style="width: 100px;"><?php echo $quote-> obtener_fecha_submitted(); ?></td>
              <td>
                <a target="_blank" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>">
                  <?php echo $quote-> obtener_id(); ?>
                </a>
              </td>
              <td><?php echo $quote-> obtener_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_total_price() - $quote-> obtener_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_total_price() - $quote-> obtener_total_cost())/$quote-> obtener_total_price()), 2) . '%'; ?>
              </td>
              <td><?php echo $re_quote-> get_total_cost(); ?></td>
              <td><?php echo $quote-> obtener_total_price(); ?></td>
              <td>
                <?php echo $quote-> obtener_total_price() - $re_quote-> get_total_cost(); ?>
                <br>
                <?php echo number_format(100*(($quote-> obtener_total_price() - $re_quote-> get_total_cost())/$quote-> obtener_total_price()), 2) . '%'; ?>
              </td>
              <td><?php echo $quote-> obtener_total_fulfillment() + $quote-> obtener_total_services_fulfillment(); ?></td>
              <td><?php echo $quote-> obtener_total_price() + $total_services; ?></td>
              <td>
                <?php echo !is_null($quote-> obtener_services_fulfillment_profit()) && !is_null($quote-> obtener_fulfillment_profit()) ? (double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit() : '0'; ?>
                <br>
                <?php echo !is_null($quote-> obtener_services_fulfillment_profit()) && !is_null($quote-> obtener_fulfillment_profit()) ? number_format(100*(((double)$quote-> obtener_services_fulfillment_profit() + (double)$quote-> obtener_fulfillment_profit())/$quote-> obtener_total_price()), 2) . '%' : '0'; ?>
              </td>
              <td><?php echo $quote-> obtener_type_of_bid() == 'Services' ? 'RFP' : 'RFQ'; ?></td>
            </tr>
            <?php
          }
          ?>
      </tbody>
    </table>
    <?php
  }

  public static function get_profit_report($connection, $month, $year){
    $quotes = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM rfq WHERE fullfillment = 1 AND MONTH(fecha_submitted) = :month AND YEAR(fecha_submitted) = :year';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':month', $month, PDO::PARAM_STR);
        $sentence-> bindParam(':year', $year, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $quotes[] = new Rfq($row['id'], $row['id_usuario'], $row['usuario_designado'], $row['canal'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['status'], $row['completado'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['fecha_completado'], $row['fecha_submitted'], $row['fecha_award'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fullfillment'], $row['fulfillment_date'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
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
