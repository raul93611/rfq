<?php
class ReQuoteAuditTrailRepository{
  public static function insert_audit_trail($connection, $audit_trail){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quote_audit_trails(id_re_quote, username, audit_trail, created_date) VALUES(:id_re_quote, :username, :audit_trail, NOW())';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $audit_trail-> get_id_re_quote(), PDO::PARAM_STR);
        $sentence-> bindParam(':username', $audit_trail-> get_username(), PDO::PARAM_STR);
        $sentence-> bindParam(':audit_trail', $audit_trail-> get_audit_trail(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_audit_trails($connection, $id_re_quote){
    $audit_trails = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_audit_trails WHERE id_re_quote = :id_re_quote ORDER BY created_date DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $fila) {
            $audit_trails[] = new ReQuoteAuditTrail($fila['id'], $fila['id_re_quote'], $fila['username'], $fila['audit_trail'], $fila['created_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $audit_trails;
  }

  public static function delete_audit_trails($connection, $id_re_quote){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM re_quote_audit_trails WHERE id_re_quote = :id_re_quote';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function display_audit_trails($connection, $id_re_quote){
    $audit_trails = self::get_audit_trails($connection, $id_re_quote);
    ?>
    <div class="timeline">
      <div>
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Re-Quote</h3>
        </div>
      </div>
      <?php
      if(count($audit_trails)){
        foreach ($audit_trails as $audit_trail) {
          $created_date = RepositorioComment::mysql_datetime_to_english_format($audit_trail-> get_created_date());
          ?>
          <div>
            <i class="fa fa-user"></i>
            <div class="timeline-item">
              <span class="time"><i class="far fa-clock"></i> <?php echo $created_date; ?></span>
              <h3 class="timeline-header">
                <span class="text-primary"><?php echo $audit_trail-> get_username(); ?></span></h3>
                <div class="timeline-body">
                  <?php echo nl2br($audit_trail-> get_audit_trail()); ?>
                </div>
              </div>
          </div>
          <?php
        }
      }
      ?>
      <div>
        <i class="fa fa-infinity"></i>
      </div>
    </div>
    <br>
    <?php
  }

  public static function create_audit_trail_item_modified($connection, $field_name, $field, $original_field, $id_item, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Item</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_modified($connection, $field_name, $field, $original_field, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_modified($connection, $field_name, $field, $original_field, $id_subitem, $id_re_quote){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Subitem</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function items_table_events($connection, $payment_terms, $payment_terms_original, $shipping, $shipping_original, $shipping_cost, $shipping_cost_original, $id_re_quote){
    if($payment_terms != $payment_terms_original){
      self::create_audit_trail_modified($connection, 'Payment Terms', $payment_terms, $payment_terms_original, $id_re_quote);
    }
    if($shipping != $shipping_original){
      self::create_audit_trail_modified($connection, 'Shipping', $shipping, $shipping_original, $id_re_quote);
    }
    if($shipping_cost != $shipping_cost_original){
      self::create_audit_trail_modified($connection, 'Shipping Cost', $shipping_cost, $shipping_cost_original, $id_re_quote);
    }
  }

  public static function edit_item_events($connection, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_item, $id_re_quote){
    if($brand != $brand_original){
      self::create_audit_trail_item_modified($connection, 'Brand', $brand, $brand_original, $id_item, $id_re_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_item_modified($connection, 'Brand Project', $brand_project, $brand_project_original, $id_item, $id_re_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_item_modified($connection, 'Part Number', $part_number, $part_number_original, $id_item, $id_re_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_item_modified($connection, 'Part Number Project', $part_number_project, $part_number_project_original, $id_item, $id_re_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_item_modified($connection, 'Description', $description, $description_original, $id_item, $id_re_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_item_modified($connection, 'Description Project', $description_project, $description_project_original, $id_item, $id_re_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_item_modified($connection, 'Quantity', $quantity, $quantity_original, $id_item, $id_re_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_item_modified($connection, 'Comments', $comments, $comments_original, $id_item, $id_re_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_item_modified($connection, 'Website', $website, $website_original, $id_item, $id_re_quote);
    }
  }

  public static function edit_subitem_events($connection, $brand, $brand_original, $brand_project, $brand_project_original, $part_number, $part_number_original, $part_number_project, $part_number_project_original, $description, $description_original, $description_project, $description_project_original, $quantity, $quantity_original, $comments, $comments_original, $website, $website_original, $id_subitem, $id_re_quote){
    if($brand != $brand_original){
      self::create_audit_trail_subitem_modified($connection, 'Brand', $brand, $brand_original, $id_subitem, $id_re_quote);
    }
    if($brand_project != $brand_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Brand Project', $brand_project, $brand_project_original, $id_subitem, $id_re_quote);
    }
    if($part_number != $part_number_original){
      self::create_audit_trail_subitem_modified($connection, 'Part Number', $part_number, $part_number_original, $id_subitem, $id_re_quote);
    }
    if($part_number_project != $part_number_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Part Number Project', $part_number_project, $part_number_project_original, $id_subitem, $id_re_quote);
    }
    if($description != $description_original){
      self::create_audit_trail_subitem_modified($connection, 'Description', $description, $description_original, $id_subitem, $id_re_quote);
    }
    if($description_project != $description_project_original){
      self::create_audit_trail_subitem_modified($connection, 'Description Project', $description_project, $description_project_original, $id_subitem, $id_re_quote);
    }
    if($quantity != $quantity_original){
      self::create_audit_trail_subitem_modified($connection, 'Quantity', $quantity, $quantity_original, $id_subitem, $id_re_quote);
    }
    if($comments != $comments_original){
      self::create_audit_trail_subitem_modified($connection, 'Comments', $comments, $comments_original, $id_subitem, $id_re_quote);
    }
    if($website != $website_original){
      self::create_audit_trail_subitem_modified($connection, 'Website', $website, $website_original, $id_subitem, $id_re_quote);
    }
  }

  public static function edit_provider_item_events($connection, $provider, $provider_original, $price, $price_original, $id_item, $id_re_quote){
    if($provider != $provider_original){
      self::create_audit_trail_item_modified($connection, 'Provider', $provider, $provider_original, $id_item, $id_re_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_item_modified($connection, 'Provider Price', $price, $price_original, $id_item, $id_re_quote);
    }
  }

  public static function edit_provider_subitem_events($connection, $provider, $provider_original, $price, $price_original, $id_subitem, $id_re_quote){
    if($provider != $provider_original){
      self::create_audit_trail_subitem_modified($connection, 'Provider', $provider, $provider_original, $id_subitem, $id_re_quote);
    }
    if($price != $price_original){
      self::create_audit_trail_subitem_modified($connection, 'Provider Price', $price, $price_original, $id_subitem, $id_re_quote);
    }
  }

  public static function create_audit_trail_item_created($connection, $id_item, $object, $field, $field_name, $id_re_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_created($connection, $id_subitem, $object, $field, $field_name, $id_re_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>' . $object . '</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_deleted($connection, $object, $field, $field_name, $id_re_quote){
    $message = '<b>' . $object . '</b> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_provider_deleted($connection, $field, $field_name, $id_item, $id_re_quote){
    $message = '<a class="audit_trail" href="#item' . $id_item . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_provider_deleted($connection, $field, $field_name, $id_subitem, $id_re_quote){
    $message = '<a class="audit_trail" href="#subitem' . $id_subitem . '"><b>Provider</b></a> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new ReQuoteAuditTrail('', $id_re_quote, $_SESSION['user']-> obtener_nombre_usuario(), $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }
}
?>
