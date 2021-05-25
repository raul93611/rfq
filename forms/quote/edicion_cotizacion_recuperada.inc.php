<input type="hidden" name="id_quote" value="<?php echo $quote->get_id(); ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-4">
      <h6><b>Contract Number:</b> <small><?php echo $quote-> get_contract_number(); ?></small></h6>
      <h6><b>Code:</b> <small><?php echo $quote-> get_email_code(); ?></small></h6>
      <h6><b>Type of Bid:</b> <small><?php echo $quote-> get_type_of_bid() ; ?></small></h6>
      <h6><b>Issue Date:</b> <small><?php echo $quote->get_issue_date(); ?></small></h6>
      <h6><b>End Date:</b> <small><?php echo $quote->get_end_date(); ?></small></h6>
      <h6><b>Channel:</b> <small><?php echo $quote-> get_channel() ?></small></h6>
      <h6><b>Designated User:</b> <small><?php echo Input::get_designated_user($quote); ?></small></h6>
      <h6><b>Completed Date:</b> <small><?php if($quote->get_completed_date() != '0000-00-00'){echo CommentRepository::mysql_date_to_english_format($quote->get_completed_date());} ?></small></h6>
      <h6><b>Expiration Date:</b> <small><?php if($quote->get_expiration_date() != '0000-00-00'){echo CommentRepository::mysql_date_to_english_format($quote->get_expiration_date());} ?></small></h6>
      <h6><b>Comments:</b> <small><?php echo $quote->get_comments(); ?></small></h6>
      <h6><b>Ship Via:</b> <small><?php echo $quote->get_ship_via(); ?></small></h6>
    </div>
    <div class="col-md-4">
      <h6><b>Address:</b></h6>
      <p><?php echo nl2br($quote->get_address()); ?></p>
    </div>
    <div class="col-md-4">
      <h6><b>Ship To:</b></h6>
      <p><?php echo nl2br($quote-> get_ship_to()); ?></p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <button type="button" id="quote_info_button" class="float-right btn btn-primary" name="button">Edit</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php
      $ruta = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $quote->get_id();
      Input::print_input_file($ruta);
      ?>
    </div>
  </div>
  <?php
  ItemRepository::print_items($quote->get_id());
  ?>
  <input type="hidden" name="id_quote" value="<?php echo $quote->get_id(); ?>">
  <?php
  if($quote-> get_channel() == 'FedBid'){
    ?>
    <div class="row">
      <div class="col-md-6">
        <label for="total_cost_fedbid">Total cost:</label>
        <input type="number" step=".01" name="total_cost_fedbid" class="form-control form-control-sm" value="<?php echo $quote-> get_total_cost(); ?>">
      </div>
      <div class="col-md-6">
        <label for="total_price_fedbid">Total price:</label>
        <input type="number" step=".01" name="total_price_fedbid" class="form-control form-control-sm" value="<?php echo $quote-> get_total_price(); ?>">
      </div>
    </div>
    <br>
    <?php
  }
  if($quote-> get_channel() == 'Chemonics' || $quote-> get_channel() == 'Ebay & Amazon'){
    ?>
    <div class="row">
      <div class="col-md-12">
        <label for="total_price_chemonics">Total price:</label>
        <input type="number" step=".01" name="total_price_chemonics" class="form-control form-control-sm" value="<?php echo $quote-> get_total_price(); ?>">
      </div>
    </div>
    <br>
    <?php
  }
  if($quote-> get_type_of_bid() == 'Services'){
    include_once 'plantillas/services/services.inc.php';
  }
  Database::open_connection();
  ?>
  <h3 class="text-center text-info">TOTAL: $ <?php echo number_format($quote-> get_total_price() + ServiceRepository::get_total(Database::get_connection(), $id_quote), 2); ?></h3>
  <?php
  $re_quote_exists = ReQuoteRepository::re_quote_exists(Database::get_connection(), $quote-> get_id());
  $items_exists = ItemRepository::items_exists(Database::get_connection(), $quote-> get_id());
  Database::close_connection();
  if($quote-> get_channel() == 'Chemonics' || $quote-> get_channel() == 'Ebay & Amazon'){
    if(!$quote->get_award()){
      ?>
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($quote->get_award()) { echo 'checked'; } ?> id="award">
        <label class="custom-control-label" for="award">Award</label>
      </div>
      <?php
    }
  }else{
    if($quote->get_complete() && $quote->get_submitted() && $quote->get_award() && !$quote-> get_fulfillment() && $_SESSION['role'] < 4){
      if(($items_exists && $re_quote_exists) || (!$items_exists && $quote-> get_type_of_bid() == 'Services')){
        ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="fulfillment" value="si" <?php if ($quote->get_fulfillment()) { echo 'checked'; } ?> id="fulfillment">
          <label class="custom-control-label" for="fulfillment">Fulfillment</label>
        </div>
        <?php
      }
    }else if ($quote->get_complete() && $quote->get_submitted() && !$quote->get_award() && $_SESSION['role'] < 4) {
      ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="award" value="si" <?php if ($quote->get_award()) { echo 'checked'; } ?> id="award">
          <label class="custom-control-label" for="award">Award</label>
        </div>
        <?php
      } else if ($quote->get_complete() && !$quote->get_submitted() && !$quote->get_award()) {
        ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="submitted" value="si" <?php if ($quote->get_submitted()) { echo 'checked'; } ?> id="submitted">
          <label class="custom-control-label" for="submitted">Submitted</label>
        </div>
        <?php
      } else if (!$quote->get_complete() && !$quote->get_submitted() && !$quote->get_award()) {
        ?>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" name="complete" value="si" <?php if ($quote->get_complete()) { echo 'checked';} ?> id="complete">
          <label class="custom-control-label" for="complete">Completed</label>
        </div>
      <?php
    }
  }
  ?>
  </div>
  <div class="card-footer footer_item" id="footer_lg">
    <?php
    $channel = Input::translate_channel($quote-> get_channel());
    if($quote-> get_fulfillment()){
      echo '<a class="btn btn-primary" id="go_back" href="' . FULFILLMENT_QUOTES . '"><i class="fa fa-reply"></i></a>';
    }else if ($quote->get_award() && ($quote->get_comments() == 'No comments' || $quote->get_comments() == 'Working on it' || $quote-> get_comments() == 'QuickBooks')) {
      echo '<a class="btn btn-primary" id="go_back" href="' . AWARD . $channel . '"><i class="fa fa-reply"></i></a>';
    } else if ($quote->get_submitted() && ($quote->get_comments() == 'No comments' || $quote->get_comments() == 'Working on it' || $quote-> get_comments() == 'QuickBooks')) {
      echo '<a class="btn btn-primary" id="go_back" href="' . SUBMITTED . $channel . '"><i class="fa fa-reply"></i></a>';
    } else if ($quote->get_complete() && ($quote->get_comments() == 'No comments' || $quote->get_comments() == 'Working on it' || $quote-> get_comments() == 'QuickBooks')) {
      echo '<a class="btn btn-primary" id="go_back" href="' . COMPLETE . $channel . '"><i class="fa fa-reply"></i></a>';
    } else if ($quote->get_comments() == 'No Bid' || $quote->get_comments() == 'Manufacturer in the Bid' || $quote->get_comments() == 'Expired due date' || $quote->get_comments() == 'Supplier did not provide a quote' || $quote->get_comments() == 'Others') {
      echo '<a class="btn btn-primary" id="go_back" href="' . NO_BID . '"><i class="fa fa-reply"></i></a>';
    }else if($quote-> get_comments() == 'No submitted'){
      echo '<a class="btn btn-primary" id="go_back" href="' . NO_SUBMITTED . '"><i class="fa fa-reply"></i></a>';
    }else if(!empty($quote-> get_channel())){
      echo '<a class="btn btn-primary" id="go_back" href="' . QUOTES . $channel . '"><i class="fa fa-reply"></i></a>';
    }
    ?>
    <button type="submit" class="btn btn-success" id="save_item" name="guardar_cambios_cotizacion"><i class="fa fa-check"></i> Save</button>
    <?php
    if($quote-> get_channel() != 'FedBid' && $quote-> get_channel() != 'Chemonics' && $quote-> get_channel() != 'Ebay & Amazon'){
      ?>
      <a class="btn btn-primary add_item_charter" href="<?php echo ADD_ITEM . '/' . $quote->get_id(); ?>"><i class="fa fa-plus-circle"></i> Add item</a>
      <?php
    }
    ?>
    <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
    <div class="btn-group dropup">
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Actions
      </button>
      <div class="dropdown-menu">
        <?php
        if($quote-> get_fulfillment() && $re_quote_exists){
          ?>
          <a class="dropdown-item" href="<?php echo TRACKING . $quote-> get_id(); ?>">Tracking</a>
          <?php
        }
        if($quote-> get_channel() != 'Chemonics' && $quote-> get_channel() != 'Ebay & Amazon'){
          if($quote-> get_award() && $items_exists){
            ?>
            <a href="<?php echo RE_QUOTE . $quote-> get_id(); ?>" class="dropdown-item">Re-quote</a>
            <?php
          }
        }
        if($quote-> get_fulfillment()){
          ?>
          <a href="<?php echo FULFILLMENT . $quote-> get_id(); ?>" class="dropdown-item">Fulfillment</a>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
