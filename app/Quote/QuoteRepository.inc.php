<?php
class QuoteRepository {
  public static function insert($database, $quote) {
    $inserted_quote = false;
    if (isset($database)) {
      try {
        $sql = 'INSERT INTO quotes(id_user, assigned_user, channel, email_code, type_of_bid, issue_date, end_date, submitted, complete, total_cost, total_price, comments, award, completed_date, submitted_date, award_date, payment_terms, address, ship_to, expiration_date, ship_via, taxes, profit, additional, shipping, shipping_cost, fulfillment, fulfillment_date, contract_number) VALUES(:id_user, :assigned_user, :channel, :email_code, :type_of_bid, :issue_date, :end_date, :submitted, :complete, :total_cost, :total_price, :comments, :award, :completed_date, :submitted_date, :award_date, :payment_terms, :address, :ship_to, :expiration_date, :ship_via, :taxes, :profit, :additional, :shipping, :shipping_cost, :fulfillment, :fulfillment_date, :contract_number)';
        $query = $database->prepare($sql);
        $query->bindParam(':id_user', $quote->get_id_user(), PDO::PARAM_STR);
        $query->bindParam(':assigned_user', $quote->get_assigned_user(), PDO::PARAM_STR);
        $query->bindParam(':channel', $quote->get_channel(), PDO::PARAM_STR);
        $query->bindParam(':email_code', $quote->get_email_code(), PDO::PARAM_STR);
        $query->bindParam(':type_of_bid', $quote->get_type_of_bid(), PDO::PARAM_STR);
        $query->bindParam(':issue_date', $quote->get_issue_date(), PDO::PARAM_STR);
        $query->bindParam(':end_date', $quote->get_end_date(), PDO::PARAM_STR);
        $query->bindParam(':submitted', $quote->get_submitted(), PDO::PARAM_STR);
        $query->bindParam(':complete', $quote->get_complete(), PDO::PARAM_STR);
        $query->bindParam(':total_cost', $quote->get_total_cost(), PDO::PARAM_STR);
        $query->bindParam(':total_price', $quote->get_total_price(), PDO::PARAM_STR);
        $query->bindParam(':comments', $quote->get_comments(), PDO::PARAM_STR);
        $query->bindParam(':award', $quote->get_award(), PDO::PARAM_STR);
        $query->bindParam(':completed_date', $quote->get_completed_date(), PDO::PARAM_STR);
        $query->bindParam(':submitted_date', $quote->get_submitted_date(), PDO::PARAM_STR);
        $query->bindParam(':award_date', $quote->get_award_date(), PDO::PARAM_STR);
        $query->bindParam(':payment_terms', $quote->get_payment_terms(), PDO::PARAM_STR);
        $query->bindParam(':address', $quote->get_address(), PDO::PARAM_STR);
        $query->bindParam(':ship_to', $quote->get_ship_to(), PDO::PARAM_STR);
        $query->bindParam(':expiration_date', $quote->get_expiration_date(), PDO::PARAM_STR);
        $query->bindParam(':ship_via', $quote->get_ship_via(), PDO::PARAM_STR);
        $query->bindParam(':taxes', $quote->get_taxes(), PDO::PARAM_STR);
        $query->bindParam(':profit', $quote->get_profit(), PDO::PARAM_STR);
        $query->bindParam(':additional', $quote->get_additional(), PDO::PARAM_STR);
        $query->bindParam(':shipping', $quote->get_shipping(), PDO::PARAM_STR);
        $query->bindParam(':shipping_cost', $quote->get_shipping_cost(), PDO::PARAM_STR);
        $query-> bindParam(':fulfillment', $quote-> get_fulfillment(), PDO::PARAM_STR);
        $query-> bindParam(':fulfillment_date', $quote-> get_fulfillment_date(), PDO::PARAM_STR);
        $query-> bindParam(':contract_number', $quote-> get_contract_number(), PDO::PARAM_STR);
        $result = $query->execute();
        $id = $database->lastInsertId();
        if ($result) {
          $inserted_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($inserted_quote, $id);
  }

  public static function insert_calc($database, $id_items, $id_subitems, $total_prices, $subitem_total_prices, $unit_prices, $subitem_unit_prices, $additionals, $additional_subitems){
    $id_items = explode(',', $id_items);
    $id_subitems = explode(',', $id_subitems);
    $total_prices = explode(',', $total_prices);
    $subitem_total_prices = explode(',', $subitem_total_prices);
    $unit_prices = explode(',', $unit_prices);
    $subitem_unit_prices = explode(',', $subitem_unit_prices);
    $additionals = explode(',', $additionals);
    $additional_subitems = explode(',', $additional_subitems);
    foreach ($id_items as $i => $id_item) {
      ItemRepository::update_amounts($database, $unit_prices[$i], $total_prices[$i], $additionals[$i], $id_item);
    }
    foreach ($id_subitems as $i => $id_subitem) {
      RepositorioSubitem::update_amounts($database, $subitem_unit_prices[$i], $subitem_total_prices[$i], $additional_subitems[$i], $id_subitem);
    }
  }

  public static function email_code_exists($database, $email_code) {
    $email_code_exists = true;
    if (isset($database)) {
      try {
        $sql = 'SELECT * FROM quotes WHERE email_code = :email_code';
        $query = $database->prepare($sql);
        $query->bindParam(':email_code', $email_code, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          $email_code_exists = true;
        } else {
          $email_code_exists = false;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $email_code_exists;
  }

  public static function get_all_submitted_quotes_between_dates($database, $date_from, $date_to){
    $quotes = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM quotes WHERE submitted = 1 AND complete = 1 AND submitted_date BETWEEN :date_from AND :date_to';
        $query = $database-> prepare($sql);
        $query-> bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $query-> bindParam(':date_to', $date_to, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes[] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function get_all_award_quotes_between_dates($database, $date_from, $date_to){
    $quotes = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM quotes WHERE award =1 AND submitted = 1 AND complete = 1 AND award_date BETWEEN :date_from AND :date_to';
        $query = $database-> prepare($sql);
        $query-> bindParam(':date_from', $date_from, PDO::PARAM_STR);
        $query-> bindParam(':date_to', $date_to, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes[] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function get_all_by_channel_user_role($database, $channel, $id_user, $role) {
    $quotes = [];
    if (isset($database)) {
      try {
        if ($role < 5) {
          $sql = "SELECT * FROM quotes WHERE channel = :channel AND complete = 0 AND submitted = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
          $query = $database->prepare($sql);
          $query->bindParam(':channel', $channel, PDO::PARAM_STR);
        } else if ($role > 4) {
          $sql = "SELECT * FROM quotes WHERE channel = :channel AND assigned_user = :id_user AND complete = 0 AND submitted = 0 AND award = 0 AND (comments = 'Working on it' OR comments = 'No comments' OR comments = '') ORDER BY id DESC";
          $query = $database->prepare($sql);
          $query->bindParam(':channel', $channel, PDO::PARAM_STR);
          $query->bindParam(':id_user', $id_user, PDO::PARAM_STR);
        }
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_quote($quote, $role, $id_user) {
    if (!isset($quote)) {
      return;
    }
    ?>
    <tr <?php if($quote->get_comments() == 'Working on it'){echo 'class="waiting_for"';} ?>>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
            <?php echo $quote->get_id(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_type_of_bid(); ?></td>
      <td><?php echo $quote->get_issue_date(); ?></td>
      <td><?php echo $quote->get_end_date(); ?></td>
      <td><?php echo $quote->get_email_code(); ?></td>
      <td class="text-center"><?php if($quote-> get_type_of_bid() == 'Services'){echo '<i class="text-success fas fa-check"></i>';}else{echo '<i class="text-danger fas fa-times"></i>';} ?></td>
      <td class="text-center">
        <a href="<?php echo DELETE_QUOTE . '/' . $quote->get_id(); ?>" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>
      </td>
    </tr>
    <?php
  }

  public static function print_quotes_by_channel_user_role($channel, $id_user, $role) {
    Database::open_connection();
    $quotes = self::get_all_by_channel_user_role(Database::get_connection(), $channel, $id_user, $role);
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table id="tabla_quotes" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>DESIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>CODE</th>
            <th>RFP</th>
            <th>OPTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_quote($quote, $role, $id_user);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_by_id($database, $id_quote) {
    $quote = null;
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE id = :id_quote";
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $quote = new Quote($result['id'], $result['id_user'], $result['assigned_user'], $result['channel'], $result['email_code'], $result['type_of_bid'], $result['issue_date'], $result['end_date'], $result['submitted'], $result['complete'], $result['total_cost'], $result['total_price'], $result['comments'], $result['award'], $result['completed_date'], $result['submitted_date'], $result['award_date'], $result['payment_terms'], $result['address'], $result['ship_to'], $result['expiration_date'], $result['ship_via'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping'], $result['shipping_cost'], $result['fulfillment'], $result['fulfillment_date'], $result['contract_number'], $result['fulfillment_profit'], $result['services_fulfillment_profit'], $result['total_fulfillment'], $result['total_services_fulfillment']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quote;
  }

  public static function check_fullfillment($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'UPDATE quotes SET fulfillment = 1 WHERE id = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update_main_info_quote($database, $expiration_date, $completed_date, $ship_to, $address, $ship_via, $comments, $assigned_user, $email_code, $type_of_bid, $issue_date, $end_date, $channel, $contract_number, $id_quote) {
    $updated_quote = false;
    if (isset($database)) {
      try {
        $sql = "UPDATE quotes SET expiration_date = :expiration_date, completed_date = :completed_date, ship_to = :ship_to, address = :address, ship_via = :ship_via, comments = :comments, assigned_user = :assigned_user, email_code = :email_code, type_of_bid = :type_of_bid, issue_date = :issue_date, end_date = :end_date, channel = :channel, contract_number = :contract_number WHERE id = :id_quote";
        $query = $database->prepare($sql);
        $query-> bindParam(':assigned_user', $assigned_user, PDO::PARAM_STR);
        $query-> bindParam(':comments', $comments, PDO::PARAM_STR);
        $query-> bindParam(':ship_via', $ship_via, PDO::PARAM_STR);
        $query-> bindParam(':address', $address, PDO::PARAM_STR);
        $query-> bindParam(':ship_to', $ship_to, PDO::PARAM_STR);
        $query-> bindParam(':completed_date', $completed_date, PDO::PARAM_STR);
        $query-> bindParam(':expiration_date', $expiration_date, PDO::PARAM_STR);
        $query-> bindParam(':email_code', $email_code, PDO::PARAM_STR);
        $query-> bindParam(':type_of_bid', $type_of_bid, PDO::PARAM_STR);
        $query-> bindParam(':issue_date', $issue_date, PDO::PARAM_STR);
        $query-> bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $query-> bindParam(':channel', $channel, PDO::PARAM_STR);
        $query-> bindParam(':contract_number', $contract_number, PDO::PARAM_STR);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        if ($query) {
          $updated_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_quote;
  }

  public static function update_variables($database, $payment_terms, $taxes, $profit, $total_cost, $total_price, $additional, $shipping, $shipping_cost, $id_quote) {
    if (isset($database)) {
      try {
        $sql = 'UPDATE quotes SET payment_terms = :payment_terms, taxes = :taxes, profit = :profit, total_cost = :total_cost, total_price = :total_price, additional = :additional, shipping = :shipping, shipping_cost = :shipping_cost WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':payment_terms', $payment_terms, PDO::PARAM_STR);
        $query->bindParam(':taxes', $taxes, PDO::PARAM_STR);
        $query->bindParam(':profit', $profit, PDO::PARAM_STR);
        $query->bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
        $query->bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $query->bindParam(':additional', $additional, PDO::PARAM_STR);
        $query->bindParam(':shipping', $shipping, PDO::PARAM_STR);
        $query->bindParam(':shipping_cost', $shipping_cost, PDO::PARAM_STR);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_fulfillment_profit_and_total($database, $id_quote){
    if (isset($database)) {
      $total_profit = 0;
      $total_cost = 0;
      $items = ItemRepository::get_all_by_id_quote($database, $id_quote);
      foreach ($items as $i => $item) {
        $total_profit += $item-> get_fulfillment_profit();
        $total_cost += FulfillmentItemRepository::get_total_cost($database, $item-> get_id());
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item($database, $item-> get_id());
        foreach ($subitems as $j => $subitem) {
          $total_profit += $subitem-> get_fulfillment_profit();
          $total_cost += FulfillmentSubitemRepository::get_total_cost($database, $subitem-> get_id());
        }
      }
      try {
        $sql = 'UPDATE quotes SET fulfillment_profit = :fulfillment_profit, total_fulfillment = :total_fulfillment WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->bindParam(':fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $query->bindParam(':total_fulfillment', $total_cost, PDO::PARAM_STR);
        $query->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_services_fulfillment_profit_and_total($database, $id_quote){
    if (isset($database)) {
      $total_cost = 0;
      $total_profit = 0;
      $services = ServiceRepository::get_services($database, $id_quote);
      foreach ($services as $i => $service) {
        $total_profit += $service-> get_fulfillment_profit();
        $total_cost += FulfillmentServiceRepository::get_total_cost($database, $service-> get_id());
      }
      try {
        $sql = 'UPDATE quotes SET services_fulfillment_profit = :services_fulfillment_profit, total_services_fulfillment = :total_services_fulfillment WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->bindParam(':services_fulfillment_profit', $total_profit, PDO::PARAM_STR);
        $query->bindParam(':total_services_fulfillment', $total_cost, PDO::PARAM_STR);
        $query->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_completed_by_channel($database, $channel) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE channel = :channel AND complete = 1 AND submitted = 0 AND award = 0 AND (comments = 'No comments' OR comments = 'Working on it') ORDER BY completed_date DESC";
        $query = $database->prepare($sql);
        $query->bindParam(':channel', $channel, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes[] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_completed($quote) {
    if (!isset($quote)) {
      return;
    }
    $completed_date_parts = explode('-', $quote->get_completed_date());
    $completed_date = $completed_date_parts[1] . '/' . $completed_date_parts[2] . '/' . $completed_date_parts[0];
    ?>
    <tr <?php if($quote->get_comments() == 'Working on it'){echo 'class="waiting_for"';} ?>>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
            <?php echo $quote->get_email_code(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_issue_date(); ?></td>
      <td><?php echo $quote->get_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($quote->get_total_price(), 2); ?></td>
      <td><?php echo $completed_date; ?></td>
      <td><?php echo $quote->get_id(); ?></td>
      <td><?php echo $quote->get_comments(); ?></td>
      <?php
      if($quote-> get_channel() != 'FedBid'){
        if ($quote->get_channel() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td class="text-center"><?php echo $quote-> get_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function print_all_completed($channel) {
    Database::open_connection();
    $quotes = self::get_completed_by_channel(Database::get_connection(), $channel);
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>COMPLETED DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($channel != 'FedBid'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <td>RFP</td>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($quotes as $quote){
            self::print_completed($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_submitted_by_channel($database, $channel) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE complete = 1 AND submitted = 1 AND award = 0 AND channel = :channel AND comments = 'No comments' ORDER BY submitted_date DESC LIMIT 100";
        $query = $database->prepare($sql);
        $query->bindParam(':channel', $channel, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_submitted($quote) {
    if (!isset($quote)) {
      return;
    }
    $submitted_date_parts = explode('-', $quote->get_submitted_date());
    $submitted_date = $submitted_date_parts[1] . '/' . $submitted_date_parts[2] . '/' . $submitted_date_parts[0];
    ?>
    <tr>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
          <?php echo $quote->get_email_code(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_issue_date(); ?></td>
      <td><?php echo $quote->get_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($quote->get_total_price(), 2); ?></td>
      <td><?php echo $submitted_date; ?></td>
      <td><?php echo $quote->get_id(); ?></td>
      <td><?php echo $quote->get_comments(); ?></td>
      <?php
      if($quote-> get_channel() != 'FedBid'){
        if ($quote->get_channel() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td class="text-center"><?php echo $quote-> get_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function print_all_submitted($channel) {
    Database::open_connection();
    $quotes = self::get_submitted_by_channel(Database::get_connection(), $channel);
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table  id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>SUBMITTED DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($channel != 'FedBid'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <td>RFP</td>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach($quotes as $quote){
            self::print_submitted($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_award_by_channel($database, $channel) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE complete = 1 AND submitted = 1 AND award = 1 AND fulfillment = 0 AND channel = :channel AND (comments = 'No comments' OR comments = 'QuickBooks') ORDER BY award_date DESC";
        $query = $database->prepare($sql);
        $query->bindParam(':channel', $channel, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_award($quote) {
    if (!isset($quote)) {
      return;
    }
    $award_date_parts = explode('-', $quote->get_award_date());
    $award_date = $award_date_parts[1] . '/' . $award_date_parts[2] . '/' . $award_date_parts[0];
    ?>
    <tr <?php if($quote->get_comments() == 'QuickBooks'){echo 'class="quickbooks"';} ?>>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
          <?php echo $quote->get_email_code(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_issue_date(); ?></td>
      <td><?php echo $quote->get_end_date(); ?></td>
      <td><?php echo '$ ' . number_format($quote->get_total_price(), 2); ?></td>
      <td><?php echo $award_date; ?></td>
      <td><?php echo $quote->get_id(); ?></td>
      <td><?php echo $quote->get_comments(); ?></td>
      <?php
      if($quote-> get_channel() != 'FedBid' && $quote-> get_channel() != 'Chemonics' && $quote-> get_channel() != 'Ebay & Amazon'){
        if ($quote->get_channel() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }
      ?>
      <td class="text-center"><?php echo $quote-> get_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function print_all_award($channel) {
    Database::open_connection();
    $quotes = self::get_award_by_channel(Database::get_connection(), $channel);
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>ISSUE DATE</th>
            <th class="end_date_table">END DATE</th>
            <th class="cantidad">AMOUNT</th>
            <th>AWARD DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
            <?php if($channel != 'FedBid' && $channel != 'Chemonics' && $channel != 'Ebay & Amazon'){echo '<th>GENERATE PROPOSAL</th>';} ?>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_award($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_no_bids($database) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE comments = 'No Bid' OR comments = 'Manufacturer in the Bid' OR comments = 'Expired due date' OR comments = 'Supplier did not provide a quote' OR comments = 'Others' ORDER BY id DESC";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_no_bid($quote) {
    if (!isset($quote)) {
      return;
    }
    ?>
    <tr>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
            <?php echo $quote->get_email_code(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_type_of_bid(); ?></td>
      <td><?php echo $quote->get_issue_date(); ?></td>
      <td><?php echo $quote->get_end_date(); ?></td>
      <td><?php echo $quote->get_id(); ?></td>
      <td><?php echo $quote->get_comments(); ?></td>
    </tr>
    <?php
  }

  public static function print_no_bids() {
    Database::open_connection();
    $quotes = self::get_no_bids(Database::get_connection());
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_no_bid($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_cancelled($database) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE comments = 'Cancelled' ORDER BY id DESC";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_all_cancelled() {
    Database::open_connection();
    $quotes = self::get_cancelled(Database::get_connection());
    Database::close_connection();
    if (count($quotes)) {
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_no_bid($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function get_no_submitted($database) {
    $quotes = [];
    if (isset($database)) {
      try {
        $sql = "SELECT * FROM quotes WHERE comments = 'Not submitted' ORDER BY id DESC";
        $query = $database->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_all_no_submitted(){
    Database::open_connection();
    $quotes = self::get_no_submitted(Database::get_connection());
    Database::close_connection();
    if(count($quotes)){
      ?>
      <table id="tabla" class="table table-bordered table-responsive-md">
        <thead>
          <tr>
            <th>CODE</th>
            <th>DEDIGNATED USER</th>
            <th>TYPE OF BID</th>
            <th>ISSUE DATE</th>
            <th>END DATE</th>
            <th>PROPOSAL</th>
            <th>COMMENTS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_no_bid($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_search_result($quote) {
    if (!isset($quote)) {
      return;
    }
    ?>
    <tr <?php if($quote->get_comments() == 'QuickBooks'){echo 'class="quickbooks"';} ?>>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote->get_id(); ?>" class="btn-block">
          <?php echo $quote->get_email_code(); ?>
        </a>
      </td>
      <td>
        <?php
        Database::open_connection();
        $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $quote->get_assigned_user());
        Database::close_connection();
        echo $usuario->obtener_nombre_usuario();
        ?>
      </td>
      <td><?php echo $quote->get_type_of_bid(); ?></td>
      <td><?php echo $quote->get_id(); ?></td>
      <td><?php echo $quote->get_comments(); ?></td>
      <td>$ <?php echo $quote-> get_total_price(); ?></td>
      <?php
      if($quote-> get_channel() != 'FedBid'){
        if ($quote->get_channel() != 'GSA-Buy') {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        } else {
          ?>
          <td class="text-center"><a class="btn btn-sm calculate" href="<?php echo PROPOSAL . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a>&nbsp;&nbsp;<a class="btn btn-primary btn-sm" href="<?php echo PROPOSAL_GSA . '/' . $quote->get_id(); ?>" target="_blank"><i class="fa fa-copy"></i></a></td>
          <?php
        }
      }else{
        ?><td></td><?php
      }
      ?>
    </tr>
    <?php
  }

  public static function get_search_result($database, $keyword, $role, $assigned_user) {
    $quotes = [];
    $keyword = '%' . trim($keyword) . '%';
    if (isset($database)) {
      try {
        if($role <= 3){
          $sql = 'SELECT * FROM quotes WHERE (contract_number LIKE :keyword OR id LIKE :keyword OR email_code LIKE :keyword OR total_price LIKE :keyword OR address LIKE :keyword OR ship_to LIKE :keyword)';
          $query = $database-> prepare($sql);
          $query-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query->execute();
          $result = $query-> fetchAll(PDO::FETCH_ASSOC);
          $sql1 = 'SELECT * FROM quotes INNER JOIN item ON rfq.id = item.id_quote WHERE (item.brand LIKE :keyword OR item.brand_project LIKE :keyword OR item.part_number LIKE :keyword OR item.part_number_project LIKE :keyword OR item.description LIKE :keyword OR item.description_project LIKE :keyword)';
          $query1 = $database-> prepare($sql1);
          $query1-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query1-> execute();
          $result1 = $query1-> fetchAll(PDO::FETCH_ASSOC);
          $sql2 = 'SELECT * FROM quotes INNER JOIN item ON rfq.id = item.id_quote INNER JOIN subitems ON item.id = subitems.id_item WHERE (subitems.brand LIKE :keyword OR subitems.brand_project LIKE :keyword OR subitems.part_number LIKE :keyword OR subitems.part_number_project LIKE :keyword OR subitems.description LIKE :keyword OR subitems.description_project LIKE :keyword)';
          $query2 = $database-> prepare($sql2);
          $query2-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query2-> execute();
          $result2 = $query2-> fetchAll(PDO::FETCH_ASSOC);

          if (count($result)) {
            foreach ($result as $row) {
              $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
            }
          }

          if(count($result1)){
            foreach ($result1 as $row1) {
              Database::open_connection();
              $quote = QuoteRepository::get_by_id(Database::get_connection(), $row1['id_quote']);
              Database::close_connection();
              $quotes[] = new Quote($quote-> get_id(), $quote-> get_id_user(), $quote-> get_assigned_user(), $quote-> get_channel(), $quote-> get_email_code(), $quote-> get_type_of_bid(), $quote-> get_issue_date(), $quote-> get_end_date(), $quote-> get_submitted(), $quote-> get_complete(), $quote-> get_total_cost(), $quote-> get_total_price(), $quote-> get_comments(), $quote-> get_award(), $quote-> get_completed_date(), $quote-> get_submitted_date(), $quote-> get_award_date(), $quote-> get_payment_terms(), $quote-> get_address(), $quote-> get_ship_to(), $quote-> get_expiration_date(), $quote-> get_ship_via(), $quote-> get_taxes(), $quote-> get_profit(), $quote-> get_additional(), $quote-> get_shipping(), $quote-> get_shipping_cost(), $quote-> get_fulfillment(), $quote-> get_contract_number(), $quote-> get_fulfillment_profit(), $quote-> get_services_fulfillment_profit(), $quote-> get_total_fulfillment(), $quote-> get_total_services_fulfillment());
            }
          }

          if(count($result2)){
            foreach ($result2 as $row2) {
              Database::open_connection();
              $quote = QuoteRepository::get_by_id(Database::get_connection(), $row2['id_quote']);
              Database::close_connection();
              $quotes[] = new Quote($quote-> get_id(), $quote-> get_id_user(), $quote-> get_assigned_user(), $quote-> get_channel(), $quote-> get_email_code(), $quote-> get_type_of_bid(), $quote-> get_issue_date(), $quote-> get_end_date(), $quote-> get_submitted(), $quote-> get_complete(), $quote-> get_total_cost(), $quote-> get_total_price(), $quote-> get_comments(), $quote-> get_award(), $quote-> get_completed_date(), $quote-> get_submitted_date(), $quote-> get_award_date(), $quote-> get_payment_terms(), $quote-> get_address(), $quote-> get_ship_to(), $quote-> get_expiration_date(), $quote-> get_ship_via(), $quote-> get_taxes(), $quote-> get_profit(), $quote-> get_additional(), $quote-> get_shipping(), $quote-> get_shipping_cost(), $quote-> get_fulfillment(), $quote-> get_contract_number(), $quote-> get_fulfillment_profit(), $quote-> get_services_fulfillment_profit(), $quote-> get_total_fulfillment(), $quote-> get_total_services_fulfillment());
            }
          }
        }else if($role >= 4){
          $sql = 'SELECT * FROM quotes WHERE (complete = 1 OR submitted = 1 OR assigned_user = :assigned_user) AND (id LIKE :keyword OR email_code LIKE :keyword OR total_price LIKE :keyword)';
          $query = $database-> prepare($sql);
          $query-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query-> bindParam(':assigned_user', $assigned_user, PDO::PARAM_STR);
          $query->execute();
          $result = $query-> fetchAll(PDO::FETCH_ASSOC);
          $sql1 = 'SELECT * FROM quotes INNER JOIN item ON rfq.id = item.id_quote WHERE (rfq.complete = 1 OR rfq.submitted = 1 OR rfq.assigned_user = :assigned_user) AND (item.part_number LIKE :keyword OR item.part_number_project LIKE :keyword OR item.description LIKE :keyword OR item.description_project LIKE :keyword)';
          $query1 = $database-> prepare($sql1);
          $query1-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query1-> bindParam(':assigned_user', $assigned_user, PDO::PARAM_STR);
          $query1-> execute();
          $result1 = $query1-> fetchAll(PDO::FETCH_ASSOC);
          $sql2 = 'SELECT * FROM quotes INNER JOIN item ON rfq.id = item.id_quote INNER JOIN subitems ON item.id = subitems.id_item WHERE (rfq.complete = 1 OR rfq.submitted = 1 OR rfq.assigned_user = :assigned_user) AND (subitems.part_number LIKE :keyword OR subitems.part_number_project LIKE :keyword OR subitems.description LIKE :keyword OR subitems.description_project LIKE :keyword)';
          $query2 = $database-> prepare($sql2);
          $query2-> bindParam(':keyword', $keyword, PDO::PARAM_STR);
          $query2-> bindParam(':assigned_user', $assigned_user, PDO::PARAM_STR);
          $query2-> execute();
          $result2 = $query2-> fetchAll(PDO::FETCH_ASSOC);

          if (count($result)) {
            foreach ($result as $row) {
              $quotes [] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
            }
          }

          if(count($result1)){
            foreach ($result1 as $row1) {
              Database::open_connection();
              $quote = QuoteRepository::get_by_id(Database::get_connection(), $row1['id_quote']);
              Database::close_connection();
              $quotes[] = new Quote($quote-> get_id(), $quote-> get_id_user(), $quote-> get_assigned_user(), $quote-> get_channel(), $quote-> get_email_code(), $quote-> get_type_of_bid(), $quote-> get_issue_date(), $quote-> get_end_date(), $quote-> get_submitted(), $quote-> get_complete(), $quote-> get_total_cost(), $quote-> get_total_price(), $quote-> get_comments(), $quote-> get_award(), $quote-> get_completed_date(), $quote-> get_submitted_date(), $quote-> get_award_date(), $quote-> get_payment_terms(), $quote-> get_address(), $quote-> get_ship_to(), $quote-> get_expiration_date(), $quote-> get_ship_via(), $quote-> get_taxes(), $quote-> get_profit(), $quote-> get_additional(), $quote-> get_shipping(), $quote-> get_shipping_cost(), $quote-> get_fulfillment(), $quote-> get_contract_number(), $quote-> get_fulfillment_profit(), $quote-> get_services_fulfillment_profit(), $quote-> get_total_fulfillment(), $quote-> get_total_services_fulfillment());
            }
          }

          if(count($result2)){
            foreach ($result2 as $row2) {
              Database::open_connection();
              $quote = QuoteRepository::get_by_id(Database::get_connection(), $row2['id_quote']);
              Database::close_connection();
              $quotes[] = new Quote($quote-> get_id(), $quote-> get_id_user(), $quote-> get_assigned_user(), $quote-> get_channel(), $quote-> get_email_code(), $quote-> get_type_of_bid(), $quote-> get_issue_date(), $quote-> get_end_date(), $quote-> get_submitted(), $quote-> get_complete(), $quote-> get_total_cost(), $quote-> get_total_price(), $quote-> get_comments(), $quote-> get_award(), $quote-> get_completed_date(), $quote-> get_submitted_date(), $quote-> get_award_date(), $quote-> get_payment_terms(), $quote-> get_address(), $quote-> get_ship_to(), $quote-> get_expiration_date(), $quote-> get_ship_via(), $quote-> get_taxes(), $quote-> get_profit(), $quote-> get_additional(), $quote-> get_shipping(), $quote-> get_shipping_cost(), $quote-> get_fulfillment(), $quote-> get_contract_number(), $quote-> get_fulfillment_profit(), $quote-> get_services_fulfillment_profit(), $quote-> get_total_fulfillment(), $quote-> get_total_services_fulfillment());
            }
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function print_search_results($quotes) {
    ?>
    <table id="search_table" class="table table-bordered table-responsive-md">
      <thead>
        <tr>
          <th>CODE</th>
          <th>DEDIGNATED USER</th>
          <th>TYPE OF BID</th>
          <th>PROPOSAL</th>
          <th>COMMENTS</th>
          <th>AMOUNT</th>
          <th>GENERATE PROPOSAL</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($quotes as $quote) {
          self::print_search_result($quote);
        }
        ?>
      </tbody>
    </table>
    <?php
  }

  public static function get_award_by_month($database) {
    $monthly_award_quotes = array();
    if (isset($database)) {
      try {
        for ($i = 1; $i <= 12; $i++) {
          $sql = 'SELECT COUNT(*) as monthly_award_quotes FROM quotes WHERE award = 1 AND MONTH(award_date) =' . $i . ' AND YEAR(award_date) = YEAR(CURDATE())';
          $query = $database->prepare($sql);
          $query->execute();
          $result = $query->fetch();
          if (!empty($result)) {
            $monthly_award_quotes[$i - 1] = $result['monthly_award_quotes'];
          } else {
            $monthly_award_quotes[$i - 1] = 0;
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $monthly_award_quotes;
  }

  public static function get_total_award_by_month($database) {
    $total_monthly_award_quotes = array();
    if (isset($database)) {
      try {
        for ($i = 1; $i <= 12; $i++) {
          $sql = 'SELECT SUM(total_price) as total FROM quotes WHERE award = 1 AND MONTH(award_date) =' . $i . ' AND YEAR(award_date) = YEAR(CURDATE())';
          $query = $database->prepare($sql);
          $query->execute();
          $result = $query->fetch();

          if (is_null($result['total'])) {
            $total_monthly_award_quotes[$i - 1] = 0;
          } else {
            $total_monthly_award_quotes[$i - 1] = $result['total'];
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total_monthly_award_quotes;
  }

  public static function get_counter_comment_status($database){
    $no_bid = 0;
    $manufacturer_in_the_bid = 0;
    $expired_due_date = 0;
    $supplier_did_not_provide_a_quote = 0;
    $others = 0;
    if(isset($database)){
      try{
        $sql = 'SELECT COUNT(*) as no_bid FROM quotes WHERE comments = "No Bid" AND YEAR(completed_date) = YEAR(CURDATE())';
        $sql1 = 'SELECT COUNT(*) as manufacturer_in_bid FROM quotes WHERE comments = "Manufacturer in the Bid" AND YEAR(completed_date) = YEAR(CURDATE())';// AND YEAR(completed_date) = YEAR(CURDATE())
        $sql2 = 'SELECT COUNT(*) as expired_due_date FROM quotes WHERE comments = "Expired due date" AND YEAR(completed_date) = YEAR(CURDATE())';
        $sql3 = 'SELECT COUNT(*) as supplier_did_not_provide_a_quote FROM quotes WHERE comments = "Supplier did not provide a quote" AND YEAR(completed_date) = YEAR(CURDATE())';
        $sql4 = 'SELECT COUNT(*) as others FROM quotes WHERE comments = "Others" AND YEAR(completed_date) = YEAR(CURDATE())';
        $query = $database-> prepare($sql);
        $query1 = $database-> prepare($sql1);
        $query2 = $database-> prepare($sql2);
        $query3 = $database-> prepare($sql3);
        $query4 = $database-> prepare($sql4);
        $query-> execute();
        $query1-> execute();
        $query2-> execute();
        $query3-> execute();
        $query4-> execute();
        $result = $query-> fetch();
        $result1 = $query1-> fetch();
        $result2 = $query2-> fetch();
        $result3 = $query3-> fetch();
        $result4 = $query4-> fetch();
        if(!empty($result)){
          $no_bid = $result['no_bid'];
        }
        if(!empty($result1)){
          $manufacturer_in_the_bid = $result1['manufacturer_in_bid'];
        }
        if(!empty($result2)){
          $expired_due_date = $result2['expired_due_date'];
        }
        if(!empty($result3)){
          $supplier_did_not_provide_a_quote = $result3['supplier_did_not_provide_a_quote'];
        }
        if(!empty($result4)){
          $others = $result4['others'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return array($no_bid, $manufacturer_in_the_bid, $expired_due_date, $supplier_did_not_provide_a_quote, $others);
  }

  public static function update_submitted_status_date($database, $id_quote) {
    $updated_quote = false;
    if (isset($database)) {
      try {
        $sql = 'UPDATE quotes SET submitted = 1, submitted_date = NOW() WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        if ($query) {
          $updated_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_quote;
  }

  public static function update_complete_status($database, $id_quote){
    $updated_quote = false;
    if (isset($database)) {
      try {
        $sql = 'UPDATE quotes SET complete = 1 WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        if ($query) {
          $updated_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_quote;
  }

  public static function update_award_status_date($database, $id_quote) {
    $updated_quote = false;
    if (isset($database)) {
      try {
        $sql = 'UPDATE quotes SET award = 1, award_date = NOW() WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        if ($query) {
          $updated_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_quote;
  }

  public static function update_fulfillment_status_date($database, $id_quote) {
    $updated_quote = false;
    if (isset($database)) {
      try {
        $sql = 'UPDATE quotes SET fulfillment = 1, fulfillment_date = NOW() WHERE id = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        if ($query) {
          $updated_quote = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $updated_quote;
  }

  public static function update_total_fedbid($database, $total_cost_fedbid, $total_price_fedbid, $id_quote){
    if(isset($database)){
      try{
        $sql = 'UPDATE quotes SET total_cost = :total_cost_fedbid, total_price = :total_price_fedbid WHERE id = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':total_cost_fedbid', $total_cost_fedbid,PDO::PARAM_STR);
        $query-> bindParam(':total_price_fedbid', $total_price_fedbid, PDO::PARAM_STR);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function update_total_chemonics($database, $total_price_chemonics, $id_quote){
    if(isset($database)){
      try{
        $sql = 'UPDATE quotes SET total_price = :total_price_chemonics WHERE id = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':total_price_chemonics', $total_price_chemonics, PDO::PARAM_STR);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM quotes WHERE id = :id_quote';
        $query= $database->prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }

  public static function print_final_quote($id_quote){
    Database::open_connection();
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
    $items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $id_quote);
    Database::close_connection();
    if($quote-> get_channel() == 'FedBid'){
      ?>
      <div class="row">
        <div class="col-md-6">
          <label>Total cost:</label>
        </div>
        <div class="col-md-6">
          $ <?php echo $quote-> get_total_cost(); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label>Total price:</label>
        </div>
        <div class="col-md-6">
          $ <?php echo $quote-> get_total_price(); ?>
        </div>
      </div>
      <?php
    }else{
      ?>
      <div class="row">
        <div class="col-md-3">
          <b>Taxes:</b> <?php echo $quote-> get_taxes(); ?>%<br>
        </div>
        <div class="col-md-3">
          <b>Profit:</b> <?php echo $quote-> get_profit(); ?>%<br>
        </div>
        <div class="col-md-3">
          <b>Additional general:</b> $ <?php echo $quote-> get_additional(); ?>
        </div>
        <div class="col-md-3">
          <b>Payment terms:</b> <?php echo $quote-> get_payment_terms(); ?>
        </div>
      </div>
      <br>
      <?php
      if (count($items)) {
        ?>
        <table class="table table-bordered" style="width:100%;">
          <tr>
            <th class="quantity">#</th>
            <th style="width:200px;">PROJECT ESPC.</th>
            <th style="width:200px;">E-LOGIC PROP.</th>
            <th>WEBSITE</th>
            <th class="quantity">QTY</th>
            <th>PROVIDER</th>
            <th>ADDITIONAL</th>
            <th>BEST UNIT COST</th>
            <th>TOTAL COST</th>
            <th>PRICE FOR CLIENT</th>
            <th>TOTAL PRICE</th>
          </tr>
        <?php
        $a = 1;
        if($quote-> get_payment_terms() == 'Net 30/CC'){
          $payment_terms = 1.0299;
        }else{
          $payment_terms = 1;
        }
        for ($i = 0; $i < count($items); $i++) {
          $item = $items[$i];
          ?>
          <tr>
            <td><?php echo $a; ?></td>
            <td><b>Brand name:</b><?php echo $item-> get_brand_project(); ?><br><b>Part number:</b><?php echo $item-> get_part_number_project(); ?><br><b>Item description:</b><?php echo nl2br(mb_substr($item-> get_description_project(), 0, 150)); ?></td>
            <td><b>Brand name:</b><?php echo $item->get_brand(); ?><br><b>Part number:</b><?php echo $item->get_part_number(); ?><br><b> Item description:</b><br><?php echo nl2br(mb_substr($item->get_description(), 0, 150)); ?></td>
            <td><a target="_blank" href="<?php echo $item-> get_website(); ?>">Provider Website</a></td>
            <td style="text-align:right;"><?php echo $item->get_quantity(); ?></td>
            <td style="width: 200px;">
          <?php
          Database::open_connection();
          $providers = ProviderRepository::get_all_by_id_item(Database::get_connection(), $item-> get_id());
          Database::close_connection();
          if(count($providers)){
            Database::open_connection();
            $least_provider = ProviderRepository::get_by_id(Database::get_connection(), $item-> get_least_provider());
            Database::close_connection();
            if(count($providers)){
              foreach ($providers as $provider) {
                ?>
                <div class="row">
                  <div class="col-md-6">
                    <b><?php echo $provider-> get_provider(); ?>:</b>
                  </div>
                  <div class="col-md-6">
                    $ <?php echo number_format($provider-> get_price(), 2); ?>
                  </div>
                </div>
                <?php
              }
            }
            ?>
            </td>
            <td>$ <?php echo number_format($item-> get_additional(), 2); ?></td>
            <td>$
            <?php
            $best_unit_price = $least_provider-> get_price()*$payment_terms*(1+($quote-> get_taxes()/100)) + $item-> get_additional() + $quote-> get_additional();
            echo number_format($best_unit_price, 2);
            ?>
            </td>
            <td>$ <?php echo number_format(round($best_unit_price, 2) * $item-> get_quantity(), 2); ?></td>
            <td style="text-align:right;">$ <?php echo number_format($item->get_unit_price(), 2); ?></td>
            <td style="text-align:right;">$ <?php echo number_format($item->get_total_price(), 2); ?></td>
            <?php
          }else{
            ?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php
          }
          ?>
        </tr>
          <?php
          Database::open_connection();
          $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
          Database::close_connection();
          for($j = 0; $j < count($subitems); $j++){
            $subitem = $subitems[$j];
            ?>
        <tr>
          <td></td>
          <td><b>Brand name:</b><?php echo $subitem-> get_brand_project(); ?><br><b>Part number:</b><?php echo $subitem-> get_part_number_project(); ?><br><b>Item description:</b><br><?php echo nl2br(mb_substr($subitem->get_description_project(), 0, 150)); ?></td>
          <td><b>Brand name:</b><?php echo $subitem->get_brand(); ?><br><b>Part number:</b> <?php echo $item->get_part_number(); ?><br><b> Item description:</b><br> <?php echo nl2br(mb_substr($item->get_description(), 0, 150)); ?></td>
          <td><a target="_blank" href="<?php echo $subitem-> get_website(); ?>">Provider Website</a></td>
          <td style="text-align:right;"><?php echo $subitem-> get_quantity(); ?></td>
            <?php
              Database::open_connection();
              $providers_subitem = ProviderSubitemRepository::get_all_by_id_subitem(Database::get_connection(), $subitem-> get_id());
              Database::close_connection();
              if(count($providers_subitem)){
                ?>
          <td>
                <?php
                  Database::open_connection();
                  $provider_subitem_menor = ProviderSubitemRepository::get_by_id(Database::get_connection(), $subitem-> get_least_provider());
                  Database::close_connection();
                  if(count($providers_subitem)){
                    foreach ($providers_subitem as $provider_subitem) {
                      ?>
                      <div class="row">
                        <div class="col-md-6">
                          <b><?php echo $provider_subitem-> get_provider(); ?>:</b>
                        </div>
                        <div class="col-md-6">
                          $ <?php echo $provider_subitem-> get_price(); ?>
                        </div>
                      </div>
                      <?php
                    }
                  }
                  ?>
          </td>
          <td>$ <?php echo number_format($subitem-> get_additional(), 2); ?></td>
          <td>$
                  <?php
                  $best_unit_price = $provider_subitem_menor-> get_price()*$payment_terms*(1+($quote-> get_taxes()/100)) + $subitem-> get_additional() + $quote-> get_additional();
                  echo number_format($best_unit_price, 2);
                  ?>
          </td>
          <td>$ <?php echo number_format(round($best_unit_price, 2) * $subitem-> get_quantity(), 2); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($subitem-> get_unit_price(), 2); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($subitem-> get_total_price(), 2); ?></td>
                  <?php
                }else{
                  ?>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
                  <?php
                }
                ?>
        </tr>
                <?php
              }
              $a++;
            }
      ?>
        <tr>
          <td style="border:none;"></td>
          <td colspan="9" style="font-size:10pt;"><?php echo nl2br($quote->get_shipping()); ?></td>
          <td style="text-align:right;">$ <?php echo number_format($quote->get_shipping_cost(), 2); ?></td>
        </tr>
        <tr>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="border:none;"></td>
          <td style="font-size:12pt;">TOTAL:</td>
          <td>$ <?php echo number_format($quote-> get_total_cost(), 2); ?></td>
          <td></td>
          <td style="font-size:12pt;text-align:right;">$ <?php echo number_format($quote->get_total_price(), 2); ?></td>
        </tr>
      </table>
      <?php
      }
    }
  }

  public static function print_fulfillment_quotes(){
    Database::open_connection();
    $quotes = self::get_all_fulfillment_quotes(Database::get_connection());
    Database::close_connection();
    if(count($quotes)){
      ?>
      <table class="fulfillment_table table table-bordered">
        <thead>
          <tr>
            <th>PROPOSAL</th>
            <th>CODE</th>
            <th>CHANNEL</th>
            <th>AMOUNT(RE-QUOTE)</th>
            <th>FULFILLMENT DATE</th>
            <th>AWARD DATE</th>
            <th>RFP</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($quotes as $quote) {
            self::print_fulfillment_quote($quote);
          }
          ?>
        </tbody>
      </table>
      <?php
    }
  }

  public static function print_fulfillment_quote($quote){
    if(!isset($quote)){
      return;
    }
    Database::open_connection();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Database::get_connection(), $quote-> get_id());
    Database::close_connection();
    $fulfillment_date = CommentRepository::mysql_datetime_to_english_format($quote-> get_fulfillment_date());
    ?>
    <tr>
      <td>
        <a href="<?php echo EDIT_QUOTE . '/' . $quote-> get_id(); ?>" class="btn-block">
          <?php echo $quote-> get_id(); ?>
        </a>
      </td>
      <td><?php echo $quote-> get_email_code(); ?></td>
      <td><?php echo $quote-> get_channel(); ?></td>
      <td><?php echo isset($re_quote) ? '$' . $re_quote-> get_total_price() : 'No Re-Quote'; ?></td>
      <td><?php echo $fulfillment_date; ?></td>
      <td><?php echo $quote-> get_award_date(); ?></td>
      <td class="text-center"><?php echo $quote-> get_type_of_bid() == 'Services' ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>'; ?></td>
    </tr>
    <?php
  }

  public static function get_all_fulfillment_quotes($database){
    $quotes = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM quotes WHERE fulfillment = 1';
        $query = $database-> prepare($sql);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $quotes[] = new Quote($row['id'], $row['id_user'], $row['assigned_user'], $row['channel'], $row['email_code'], $row['type_of_bid'], $row['issue_date'], $row['end_date'], $row['submitted'], $row['complete'], $row['total_cost'], $row['total_price'], $row['comments'], $row['award'], $row['completed_date'], $row['submitted_date'], $row['award_date'], $row['payment_terms'], $row['address'], $row['ship_to'], $row['expiration_date'], $row['ship_via'], $row['taxes'], $row['profit'], $row['additional'], $row['shipping'], $row['shipping_cost'], $row['fulfillment'], $row['fulfillment_date'], $row['contract_number'], $row['fulfillment_profit'], $row['services_fulfillment_profit'], $row['total_fulfillment'], $row['total_services_fulfillment']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quotes;
  }

  public static function remove_award($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'UPDATE quotes SET award = 0 WHERE id = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function remove_fulfillment($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'UPDATE quotes SET fulfillment = 0 WHERE id = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_providers_name($database, $id_quote){
    $providers_name = [];
    $items = ItemRepository::get_all_by_id_quote($database, $id_quote);
    foreach ($items as $i => $item) {
      $providers = ProviderRepository::get_all_by_id_item($database, $item-> get_id());
      foreach ($providers as $j => $provider) {
        array_push($providers_name, $provider-> get_provider());
      }
      $subitems = RepositorioSubitem::obtener_subitems_por_id_item($database, $item-> get_id());
      foreach ($subitems as $k => $subitem) {
        $providers_subitem = ProviderSubitemRepository::get_all_by_id_subitem($database, $subitem-> get_id());
        foreach ($providers_subitem as $l => $provider_subitem) {
          array_push($providers_name, $provider_subitem-> get_provider());
        }
      }
    }
    $providers_name = array_unique($providers_name);
    return $providers_name;
  }
}
?>
