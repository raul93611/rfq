<?php
class AuditTrailRepository{
  public static function insert($database, $audit_trail){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO audit_trails(id_quote, username, audit_trail, created_date) VALUES(:id_quote, :username, :audit_trail, NOW())';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $audit_trail-> get_id_quote(), PDO::PARAM_STR);
        $query-> bindParam(':username', $audit_trail-> get_username(), PDO::PARAM_STR);
        $query-> bindParam(':audit_trail', $audit_trail-> get_audit_trail(), PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_by_id_quote($database, $id_quote){
    $audit_trails = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM audit_trails WHERE id_quote = :id_quote ORDER BY created_date DESC';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $audit_trails[] = new AuditTrail($row['id'], $row['id_quote'], $row['username'], $row['audit_trail'], $row['created_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $audit_trails;
  }

  public static function delete_all_by_id_quote($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM audit_trails WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function print_audit_trails($database, $id_quote){
    $audit_trails = self::get_all_by_id_quote($database, $id_quote);
    ?>
    <ul class="timeline">
      <li class="clickable_title">
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Proposal: <?php echo $id_quote; ?></h3>
        </div>
      </li>
      <?php
      if(count($audit_trails)){
        foreach ($audit_trails as $audit_trail) {
          $created_date = CommentRepository::mysql_datetime_to_english_format($audit_trail-> get_created_date());
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

  public static function quote_info_events($database, $contract_number, $contract_number_original, $code, $code_original, $type_of_bid, $type_of_bid_original, $issue_date, $issue_date_original, $end_date, $end_date_original, $channel, $channel_original, $assigned_user, $assigned_user_original, $completed_date, $completed_date_original, $expiration_date, $expiration_date_original, $comments, $comments_original, $ship_via, $ship_via_original, $address, $address_original, $ship_to, $ship_to_original, $id_quote){
    if($contract_number != $contract_number_original){
      self::create_audit_trail_modified($database, 'Contract Number', $contract_number, $contract_number_original, $id_quote);
    }
    if($code != $code_original){
      self::create_audit_trail_modified($database, 'Code', $code, $code_original, $id_quote);
    }
    if($type_of_bid != $type_of_bid_original){
      self::create_audit_trail_modified($database, 'Type of Bid', $type_of_bid, $type_of_bid_original, $id_quote);
    }
    if($issue_date != $issue_date_original){
      self::create_audit_trail_modified($database, 'Issue Date', $issue_date, $issue_date_original, $id_quote);
    }
    if($end_date != $end_date_original){
      self::create_audit_trail_modified($database, 'End Date', $end_date, $end_date_original, $id_quote);
    }
    if($channel != $channel_original){
      self::create_audit_trail_modified($database, 'Channel', $channel, $channel_original, $id_quote);
    }
    if($assigned_user != $assigned_user_original){
      self::create_audit_trail_modified($database, 'Designated User', $assigned_user, $assigned_user_original, $id_quote);
    }
    if($completed_date != $completed_date_original){
      self::create_audit_trail_modified($database, 'Completed Date', $completed_date, $completed_date_original, $id_quote);
    }
    if($expiration_date != $expiration_date_original){
      self::create_audit_trail_modified($database, 'Expiration Date', $expiration_date, $expiration_date_original, $id_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_modified($database, 'Comments', $comments, $comments_original, $id_quote);
    }
    if($ship_via != $ship_via_original){
      self::create_audit_trail_modified($database, 'Ship Via', $ship_via, $ship_via_original, $id_quote);
    }
    if($address != $address_original){
      self::create_audit_trail_modified($database, 'Address', $address, $address_original, $id_quote);
    }
    if($ship_to != $ship_to_original){
      self::create_audit_trail_modified($database, 'Ship To', $ship_to, $ship_to_original, $id_quote);
    }
  }

  public static function items_table_events($database, $taxes, $taxes_original, $profit, $profit_original, $additional_general, $additional_general_original, $payment_terms, $payment_terms_original, $shipping, $shipping_original, $shipping_cost, $shipping_cost_original, $id_quote){
    if($taxes != $taxes_original){
      self::create_audit_trail_modified($database, 'Taxes', $taxes, $taxes_original, $id_quote);
    }
    if($profit != $profit_original){
      self::create_audit_trail_modified($database, 'Profit', $profit, $profit_original, $id_quote);
    }
    if($additional_general != $additional_general_original){
      self::create_audit_trail_modified($database, 'Additional General', $additional_general, $additional_general_original, $id_quote);
    }
    if($payment_terms != $payment_terms_original){
      self::create_audit_trail_modified($database, 'Payment Terms', $payment_terms, $payment_terms_original, $id_quote);
    }
    if($shipping != $shipping_original){
      self::create_audit_trail_modified($database, 'Shipping', $shipping, $shipping_original, $id_quote);
    }
    if($shipping_cost != $shipping_cost_original){
      self::create_audit_trail_modified($database, 'Shipping Cost', $shipping_cost, $shipping_cost_original, $id_quote);
    }
  }

  public static function edit_item_events($database, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_item, $id_quote){
    if($brand != $brand_original){
      self::create_audit_trail_item_modified($database, 'Brand', $brand, $brand_original, $id_item, $id_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_item_modified($database, 'Brand Project', $brand_project, $brand_project_original, $id_item, $id_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_item_modified($database, 'Part Number', $part_number, $part_number_original, $id_item, $id_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_item_modified($database, 'Part Number Project', $part_number_project, $part_number_project_original, $id_item, $id_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_item_modified($database, 'Description', $description, $description_original, $id_item, $id_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_item_modified($database, 'Description Project', $description_project, $description_project_original, $id_item, $id_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_item_modified($database, 'Quantity', $quantity, $quantity_original, $id_item, $id_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_item_modified($database, 'Comments', $comments, $comments_original, $id_item, $id_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_item_modified($database, 'Website', $website, $website_original, $id_item, $id_quote);
    }
  }

  public static function edit_subitem_events($database, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_subitem, $id_quote){
    if($brand != $brand_original){
      self::create_audit_trail_subitem_modified($database, 'Brand', $brand, $brand_original, $id_subitem, $id_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_subitem_modified($database, 'Brand Project', $brand_project, $brand_project_original, $id_subitem, $id_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_subitem_modified($database, 'Part Number', $part_number, $part_number_original, $id_subitem, $id_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_subitem_modified($database, 'Part Number Project', $part_number_project, $part_number_project_original, $id_subitem, $id_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_subitem_modified($database, 'Description', $description, $description_original, $id_subitem, $id_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_subitem_modified($database, 'Description Project', $description_project, $description_project_original, $id_subitem, $id_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_subitem_modified($database, 'Quantity', $quantity, $quantity_original, $id_subitem, $id_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_subitem_modified($database, 'Comments', $comments, $comments_original, $id_subitem, $id_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_subitem_modified($database, 'Website', $website, $website_original, $id_subitem, $id_quote);
    }
  }

  public static function edit_provider_item_events($database, $provider, $provider_original, $price, $price_original, $id_item, $id_quote){
    if($provider != $provider_original){
      self::create_audit_trail_item_modified($database, 'Provider', $provider, $provider_original, $id_item, $id_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_item_modified($database, 'Provider Price', $price, $price_original, $id_item, $id_quote);
    }
  }

  public static function edit_provider_subitem_events($database, $provider, $provider_original, $price, $price_original, $id_subitem, $id_quote){
    if($provider != $provider_original){
      self::create_audit_trail_subitem_modified($database, 'Provider', $provider, $provider_original, $id_subitem, $id_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_subitem_modified($database, 'Provider Price', $price, $price_original, $id_subitem, $id_quote);
    }
  }

  public static function create_audit_trail_modified($database, $field_name, $field, $original_field, $id_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_modified($database, $field_name, $field, $original_field, $id_item, $id_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Item</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_modified($database, $field_name, $field, $original_field, $id_subitem, $id_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Subitem</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_created($database, $id_item, $object, $field, $field_name, $id_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_created($database, $id_subitem, $object, $field, $field_name, $id_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_deleted($database, $object, $field, $field_name, $id_quote){
    $message = '<b>' . $object . '</b> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_provider_deleted($database, $field, $field_name, $id_item, $id_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_provider_deleted($database, $field, $field_name, $id_subitem, $id_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function quote_status_audit_trail($database, $status, $id_quote){
    $message = 'The quote was <b>' . $status . '</b>';
    $audit_trail = new AuditTrail('', $id_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }
}
?>
