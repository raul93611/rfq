<?php
class AuditTrailRepository{
  public static function insert_audit_trail($connection, $audit_trail){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO audit_trails(id_rfq, username, audit_trail, created_date) VALUES(:id_rfq, :username, :audit_trail, NOW())';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $audit_trail-> get_id_rfq(), PDO::PARAM_STR);
        $sentence-> bindParam(':username', $audit_trail-> get_username(), PDO::PARAM_STR);
        $sentence-> bindParam(':audit_trail', $audit_trail-> get_audit_trail(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_audit_trails($connection, $id_rfq){
    $audit_trails = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM audit_trails WHERE id_rfq = :id_rfq ORDER BY created_date DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $audit_trails[] = new AuditTrail($fila['id'], $fila['id_rfq'], $fila['username'], $fila['audit_trail'], $fila['created_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $audit_trails;
  }

  public static function delete_audit_trails($connection, $id_rfq){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM audit_trails WHERE id_rfq = :id_rfq';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function display_audit_trails($connection, $id_rfq){
    $audit_trails = self::get_audit_trails($connection, $id_rfq);
    ?>
    <ul class="timeline">
      <li class="clickable_title">
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Proposal: <?php echo $id_rfq; ?></h3>
        </div>
      </li>
      <?php
      if(count($audit_trails)){
        foreach ($audit_trails as $audit_trail) {
          $created_date = RepositorioComment::mysql_datetime_to_english_format($audit_trail-> get_created_date());
          ?>
          <li class="body_comments">
            <i class="fa fa-user"></i>
            <div class="timeline-item">
              <span class="time"><i class="far fa-clock"></i> <?php echo $created_date; ?></span>
              <h3 class="timeline-header">
                <span class="text-primary"><?php echo $audit_trail-> get_username(); ?></span></h3>
              <div class="timeline-body">
                <?php echo nl2br($audit_trail-> get_audit_trail()); ?>
              </div>
            </div>
          </li>
          <?php
        }
      }
      ?>
      <li>
        <i class="fa fa-infinity"></i>
      </li>
      </ul>
      <br>
      <?php
  }

  public static function quote_info_events($connection, $contract_number, $contract_number_original, $code, $code_original, $type_of_bid, $type_of_bid_original, $issue_date, $issue_date_original, $end_date, $end_date_original, $canal, $canal_original, $designated_user, $designated_user_original, $completed_date, $completed_date_original, $expiration_date, $expiration_date_original, $comments, $comments_original, $ship_via, $ship_via_original, $address, $address_original, $ship_to, $ship_to_original, $id_rfq){
    if($contract_number != $contract_number_original){
      self::create_audit_trail_modified($connection, 'Contract Number', $contract_number, $contract_number_original, $id_rfq);
    }
    if($code != $code_original){
      self::create_audit_trail_modified($connection, 'Code', $code, $code_original, $id_rfq);
    }
    if($type_of_bid != $type_of_bid_original){
      self::create_audit_trail_modified($connection, 'Type of Bid', $type_of_bid, $type_of_bid_original, $id_rfq);
    }
    if($issue_date != $issue_date_original){
      self::create_audit_trail_modified($connection, 'Issue Date', $issue_date, $issue_date_original, $id_rfq);
    }
    if($end_date != $end_date_original){
      self::create_audit_trail_modified($connection, 'End Date', $end_date, $end_date_original, $id_rfq);
    }
    if($canal != $canal_original){
      self::create_audit_trail_modified($connection, 'Channel', $canal, $canal_original, $id_rfq);
    }
    if($designated_user != $designated_user_original){
      self::create_audit_trail_modified($connection, 'Designated User', $designated_user, $designated_user_original, $id_rfq);
    }
    if($completed_date != $completed_date_original){
      self::create_audit_trail_modified($connection, 'Completed Date', $completed_date, $completed_date_original, $id_rfq);
    }
    if($expiration_date != $expiration_date_original){
      self::create_audit_trail_modified($connection, 'Expiration Date', $expiration_date, $expiration_date_original, $id_rfq);
    }
    if($comments != $comments_original){
      self::create_audit_trail_modified($connection, 'Comments', $comments, $comments_original, $id_rfq);
    }
    if($ship_via != $ship_via_original){
      self::create_audit_trail_modified($connection, 'Ship Via', $ship_via, $ship_via_original, $id_rfq);
    }
    if($address != $address_original){
      self::create_audit_trail_modified($connection, 'Address', $address, $address_original, $id_rfq);
    }
    if($ship_to != $ship_to_original){
      self::create_audit_trail_modified($connection, 'Ship To', $ship_to, $ship_to_original, $id_rfq);
    }
  }

  public static function items_table_events($connection, $taxes, $taxes_original, $profit, $profit_original, $additional_general, $additional_general_original, $payment_terms, $payment_terms_original, $shipping, $shipping_original, $shipping_cost, $shipping_cost_original, $id_rfq){
    if($taxes != $taxes_original){
      self::create_audit_trail_modified($connection, 'Taxes', $taxes, $taxes_original, $id_rfq);
    }
    if($profit != $profit_original){
      self::create_audit_trail_modified($connection, 'Profit', $profit, $profit_original, $id_rfq);
    }
    if($additional_general != $additional_general_original){
      self::create_audit_trail_modified($connection, 'Additional General', $additional_general, $additional_general_original, $id_rfq);
    }
    if($payment_terms != $payment_terms_original){
      self::create_audit_trail_modified($connection, 'Payment Terms', $payment_terms, $payment_terms_original, $id_rfq);
    }
    if($shipping != $shipping_original){
      self::create_audit_trail_modified($connection, 'Shipping', $shipping, $shipping_original, $id_rfq);
    }
    if($shipping_cost != $shipping_cost_original){
      self::create_audit_trail_modified($connection, 'Shipping Cost', $shipping_cost, $shipping_cost_original, $id_rfq);
    }
  }

  public static function edit_item_events($connection, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_item, $id_rfq){
    if($brand != $brand_original){
      self::create_audit_trail_item_modified($connection, 'Brand', $brand, $brand_original, $id_item, $id_rfq);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_item_modified($connection, 'Brand Project', $brand_project, $brand_project_original, $id_item, $id_rfq);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_item_modified($connection, 'Part Number', $part_number, $part_number_original, $id_item, $id_rfq);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_item_modified($connection, 'Part Number Project', $part_number_project, $part_number_project_original, $id_item, $id_rfq);
    }
    if($description != $description_original){
      self::create_audit_trail_item_modified($connection, 'Description', $description, $description_original, $id_item, $id_rfq);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_item_modified($connection, 'Description Project', $description_project, $description_project_original, $id_item, $id_rfq);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_item_modified($connection, 'Quantity', $quantity, $quantity_original, $id_item, $id_rfq);
    }
    if($comments != $comments_original){
      self::create_audit_trail_item_modified($connection, 'Comments', $comments, $comments_original, $id_item, $id_rfq);
    }
    if($website != $website_original){
      self::create_audit_trail_item_modified($connection, 'Website', $website, $website_original, $id_item, $id_rfq);
    }
  }

  public static function edit_subitem_events($connection, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_subitem, $id_rfq){
    if($brand != $brand_original){
      self::create_audit_trail_subitem_modified($connection, 'Brand', $brand, $brand_original, $id_subitem, $id_rfq);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Brand Project', $brand_project, $brand_project_original, $id_subitem, $id_rfq);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_subitem_modified($connection, 'Part Number', $part_number, $part_number_original, $id_subitem, $id_rfq);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Part Number Project', $part_number_project, $part_number_project_original, $id_subitem, $id_rfq);
    }
    if($description != $description_original){
      self::create_audit_trail_subitem_modified($connection, 'Description', $description, $description_original, $id_subitem, $id_rfq);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Description Project', $description_project, $description_project_original, $id_subitem, $id_rfq);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_subitem_modified($connection, 'Quantity', $quantity, $quantity_original, $id_subitem, $id_rfq);
    }
    if($comments != $comments_original){
      self::create_audit_trail_subitem_modified($connection, 'Comments', $comments, $comments_original, $id_subitem, $id_rfq);
    }
    if($website != $website_original){
      self::create_audit_trail_subitem_modified($connection, 'Website', $website, $website_original, $id_subitem, $id_rfq);
    }
  }

  public static function edit_provider_item_events($connection, $provider, $provider_original, $price, $price_original, $id_item, $id_rfq){
    if($provider != $provider_original){
      self::create_audit_trail_item_modified($connection, 'Provider', $provider, $provider_original, $id_item, $id_rfq);
    }
    if($price != $price_original){
      self::create_audit_trail_item_modified($connection, 'Provider Price', $price, $price_original, $id_item, $id_rfq);
    }
  }

  public static function edit_provider_subitem_events($connection, $provider, $provider_original, $price, $price_original, $id_subitem, $id_rfq){
    if($provider != $provider_original){
      self::create_audit_trail_subitem_modified($connection, 'Provider', $provider, $provider_original, $id_subitem, $id_rfq);
    }
    if($price != $price_original){
      self::create_audit_trail_subitem_modified($connection, 'Provider Price', $price, $price_original, $id_subitem, $id_rfq);
    }
  }

  public static function create_audit_trail_modified($connection, $field_name, $field, $original_field, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_modified($connection, $field_name, $field, $original_field, $id_item, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Item</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_modified($connection, $field_name, $field, $original_field, $id_subitem, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Subitem</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_created($connection, $id_item, $object, $field, $field_name, $id_rfq){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_created($connection, $id_subitem, $object, $field, $field_name, $id_rfq){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_deleted($connection, $object, $field, $field_name, $id_rfq){
    $message = '<b>' . $object . '</b> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_provider_deleted($connection, $field, $field_name, $id_item, $id_rfq){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_provider_deleted($connection, $field, $field_name, $id_subitem, $id_rfq){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }
}
?>
