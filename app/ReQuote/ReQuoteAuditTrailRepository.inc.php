<?php
class ReQuoteAuditTrailRepository{
  public static function insert($database, $audit_trail){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO re_quote_audit_trails(id_re_quote, username, audit_trail, created_date) VALUES(:id_re_quote, :username, :audit_trail, NOW())';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote', $audit_trail-> get_id_re_quote(), PDO::PARAM_STR);
        $query-> bindParam(':username', $audit_trail-> get_username(), PDO::PARAM_STR);
        $query-> bindParam(':audit_trail', $audit_trail-> get_audit_trail(), PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all_by_id_quote($database, $id_re_quote){
    $audit_trails = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM re_quote_audit_trails WHERE id_re_quote = :id_re_quote ORDER BY created_date DESC';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $audit_trails[] = new ReQuoteAuditTrail($row['id'], $row['id_re_quote'], $row['username'], $row['audit_trail'], $row['created_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $audit_trails;
  }

  public static function delete_all_by_id_quote($database, $id_re_quote){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM re_quote_audit_trails WHERE id_re_quote = :id_re_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function print_audit_trails($database, $id_re_quote){
    $audit_trails = self::get_all_by_id_quote($database, $id_re_quote);
    ?>
    <ul class="timeline">
      <li class="clickable_title">
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Re-Quote</h3>
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

  public static function create_audit_trail_item_modified($database, $field_name, $field, $original_field, $id_item, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Item</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_modified($database, $field_name, $field, $original_field, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_modified($database, $field_name, $field, $original_field, $id_subitem, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Subitem</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function items_table_events($database, $payment_terms, $payment_terms_original, $shipping, $shipping_original, $shipping_cost, $shipping_cost_original, $id_re_quote){
    if($payment_terms != $payment_terms_original){
      self::create_audit_trail_modified($database, 'Payment Terms', $payment_terms, $payment_terms_original, $id_re_quote);
    }
    if($shipping != $shipping_original){
      self::create_audit_trail_modified($database, 'Shipping', $shipping, $shipping_original, $id_re_quote);
    }
    if($shipping_cost != $shipping_cost_original){
      self::create_audit_trail_modified($database, 'Shipping Cost', $shipping_cost, $shipping_cost_original, $id_re_quote);
    }
  }

  public static function edit_item_events($database, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_item, $id_re_quote){
    if($brand != $brand_original){
      self::create_audit_trail_item_modified($database, 'Brand', $brand, $brand_original, $id_item, $id_re_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_item_modified($database, 'Brand Project', $brand_project, $brand_project_original, $id_item, $id_re_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_item_modified($database, 'Part Number', $part_number, $part_number_original, $id_item, $id_re_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_item_modified($database, 'Part Number Project', $part_number_project, $part_number_project_original, $id_item, $id_re_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_item_modified($database, 'Description', $description, $description_original, $id_item, $id_re_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_item_modified($database, 'Description Project', $description_project, $description_project_original, $id_item, $id_re_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_item_modified($database, 'Quantity', $quantity, $quantity_original, $id_item, $id_re_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_item_modified($database, 'Comments', $comments, $comments_original, $id_item, $id_re_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_item_modified($database, 'Website', $website, $website_original, $id_item, $id_re_quote);
    }
  }

  public static function edit_subitem_events($database, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_subitem, $id_re_quote){
    if($brand != $brand_original){
      self::create_audit_trail_subitem_modified($database, 'Brand', $brand, $brand_original, $id_subitem, $id_re_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_subitem_modified($database, 'Brand Project', $brand_project, $brand_project_original, $id_subitem, $id_re_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_subitem_modified($database, 'Part Number', $part_number, $part_number_original, $id_subitem, $id_re_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_subitem_modified($database, 'Part Number Project', $part_number_project, $part_number_project_original, $id_subitem, $id_re_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_subitem_modified($database, 'Description', $description, $description_original, $id_subitem, $id_re_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_subitem_modified($database, 'Description Project', $description_project, $description_project_original, $id_subitem, $id_re_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_subitem_modified($database, 'Quantity', $quantity, $quantity_original, $id_subitem, $id_re_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_subitem_modified($database, 'Comments', $comments, $comments_original, $id_subitem, $id_re_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_subitem_modified($database, 'Website', $website, $website_original, $id_subitem, $id_re_quote);
    }
  }

  public static function edit_provider_item_events($database, $provider, $provider_original, $price, $price_original, $id_item, $id_re_quote){
    if($provider != $provider_original){
      self::create_audit_trail_item_modified($database, 'Provider', $provider, $provider_original, $id_item, $id_re_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_item_modified($database, 'Provider Price', $price, $price_original, $id_item, $id_re_quote);
    }
  }

  public static function edit_provider_subitem_events($database, $provider, $provider_original, $price, $price_original, $id_subitem, $id_re_quote){
    if($provider != $provider_original){
      self::create_audit_trail_subitem_modified($database, 'Provider', $provider, $provider_original, $id_subitem, $id_re_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_subitem_modified($database, 'Provider Price', $price, $price_original, $id_subitem, $id_re_quote);
    }
  }

  public static function create_audit_trail_item_created($database, $id_item, $object, $field, $field_name, $id_re_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_created($database, $id_subitem, $object, $field, $field_name, $id_re_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_deleted($database, $object, $field, $field_name, $id_re_quote){
    $message = '<b>' . $object . '</b> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_item_provider_deleted($database, $field, $field_name, $id_item, $id_re_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }

  public static function create_audit_trail_subitem_provider_deleted($database, $field, $field_name, $id_subitem, $id_re_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['username'], $message, '');
    self::insert($database, $audit_trail);
  }
}
?>
