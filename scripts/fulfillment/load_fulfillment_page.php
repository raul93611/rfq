<?php
Database::open_connection();
$quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
$items_exists = ItemRepository::items_exists(Database::get_connection(), $id_quote);
$total_services = ServiceRepository::get_total(Database::get_connection(), $id_quote);
Database::close_connection();
?>
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
  </div>
  <div id="fulfillment_box" class="card-body">
    <?php
    if($items_exists){
      FulfillmentRepository::items_list($id_quote);
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
    if($quote-> get_type_of_bid() == 'Services'){
      FulfillmentRepository::services_list($id_quote);
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
      <div class="col-md-6">
        <h3 class="text-info text-center">Total: $ <?php echo number_format($quote-> get_total_price() + $total_services, 2); ?></h3>
      </div>
      <div class="col-md-6">
        <h3 class="text-info text-center">Total profit: $ <?php echo number_format($quote-> get_services_fulfillment_profit() + $quote-> get_fulfillment_profit(), 2); ?></h3>
      </div>
    </div>
  </div>
</div>
