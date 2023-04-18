<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
  </div>
  <div id="fulfillment_box" class="card-body">
    <?php
    FulfillmentRepository::items_list_invoice($invoice-> get_id_rfq(), $previous_date, $invoice-> get_created_at(), $total_items);
    ?>
  </div>
</div>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-highlighter"></i> RFP</h3>
  </div>
  <div id="fulfillment_services_box" class="card-body">
    <?php
    if($quote-> isServices()){
      FulfillmentRepository::services_list_invoice($invoice-> get_id_rfq(), $previous_date, $invoice-> get_created_at(), $total_services);
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
      <div class="col-md-12">
        <h3 class="text-info text-center">Total Price: $ <?php echo $total_items + $total_services; ?></h3>
      </div>
    </div>
  </div>
</div>