<div id="fulfillment_page" class="col-md-12">
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
    </div>
    <div id="fulfillment_box" class="card-body">
      <?php
      if($items_exists){
        FulfillmentRepository::items_list($id_rfq);
      }else{
        ?>
        <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items to display!</h3>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-highlighter"></i> RFP</h3>
    </div>
    <div id="fulfillment_services_box" class="card-body">
      <?php
      if($quote-> obtener_type_of_bid() == 'Services'){
        FulfillmentRepository::services_list($id_rfq);
      }else{
        ?>
        <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <h3 class="text-info text-center">Total Price: $ <?php echo number_format($quote-> obtener_quote_total_price(), 2); ?></h3>
        </div>
        <div class="col-md-4">
          <h3 class="text-info text-center">Total profit: $ <?php echo number_format($quote-> obtener_real_fulfillment_profit(), 2); ?></h3>
        </div>
        <div class="col-md-4">
          <h3 class="text-info text-center">Total profit(%): <?php echo number_format($quote-> obtener_real_fulfillment_profit_percentage(), 2); ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>
