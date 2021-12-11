<?php
class FulfillmentAuditTrailRepository{
  public static function insert_audit_trail($connection, $audit_trail){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO fulfillment_audit_trails(id_rfq, username, audit_trail, created_date) VALUES(:id_rfq, :username, :audit_trail, NOW())';
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

  public static function get_audit_trails($id_rfq){
    $audit_trails = [];
    try{
      Conexion::abrir_conexion();
      $connection = Conexion::obtener_conexion();
      $sql = 'SELECT * FROM fulfillment_audit_trails WHERE id_rfq = :id_rfq ORDER BY created_date DESC';
      $sentence = $connection-> prepare($sql);
      $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
      $sentence-> execute();
      $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
      Conexion::cerrar_conexion();
      if(count($result)){
        foreach ($result as $fila) {
          $audit_trails[] = new AuditTrail($fila['id'], $fila['id_rfq'], $fila['username'], $fila['audit_trail'], $fila['created_date']);
        }
      }
    }catch(PDOException $ex){
      print 'ERROR:' . $ex->getMessage() . '<br>';
    }
    return $audit_trails;
  }

  public static function delete_audit_trails($connection, $id_rfq){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM fulfillment_audit_trails WHERE id_rfq = :id_rfq';
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
    <div class="timeline">
      <div>
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Proposal: <?php echo $id_rfq; ?></h3>
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

  public static function edit_item_events($connection, $provider, $provider_original, $quantity, $quantity_original, $unit_cost, $unit_cost_original, $other_cost, $other_cost_original, $payment_term, $payment_term_original, $id_item, $id_rfq){
    self::create_audit_trail_item_modified($connection, 'Provider', $provider, $provider_original, $id_item, $id_rfq);
    self::create_audit_trail_item_modified($connection, 'Quantity', $quantity, $quantity_original, $id_item, $id_rfq);
    self::create_audit_trail_item_modified($connection, 'Unit Cost', $unit_cost, $unit_cost_original, $id_item, $id_rfq);
    self::create_audit_trail_item_modified($connection, 'Other Cost', $other_cost, $other_cost_original, $id_item, $id_rfq);
    self::create_audit_trail_item_modified($connection, 'Payment Term', $payment_term, $payment_term_original, $id_item, $id_rfq);
  }

  public static function edit_subitem_events($connection, $provider, $provider_original, $quantity, $quantity_original, $unit_cost, $unit_cost_original, $other_cost, $other_cost_original, $payment_term, $payment_term_original, $id_subitem, $id_rfq){
    self::create_audit_trail_subitem_modified($connection, 'Provider', $provider, $provider_original, $id_subitem, $id_rfq);
    self::create_audit_trail_subitem_modified($connection, 'Quantity', $quantity, $quantity_original, $id_subitem, $id_rfq);
    self::create_audit_trail_subitem_modified($connection, 'Unit Cost', $unit_cost, $unit_cost_original, $id_subitem, $id_rfq);
    self::create_audit_trail_subitem_modified($connection, 'Other Cost', $other_cost, $other_cost_original, $id_subitem, $id_rfq);
    self::create_audit_trail_subitem_modified($connection, 'Payment Term', $payment_term, $payment_term_original, $id_subitem, $id_rfq);
  }

  public static function edit_service_events($connection, $provider, $provider_original, $quantity, $quantity_original, $unit_cost, $unit_cost_original, $other_cost, $other_cost_original, $payment_term, $payment_term_original, $id_service, $id_rfq){
    self::create_audit_trail_service_modified($connection, 'Provider', $provider, $provider_original, $id_service, $id_rfq);
    self::create_audit_trail_service_modified($connection, 'Quantity', $quantity, $quantity_original, $id_service, $id_rfq);
    self::create_audit_trail_service_modified($connection, 'Unit Cost', $unit_cost, $unit_cost_original, $id_service, $id_rfq);
    self::create_audit_trail_service_modified($connection, 'Other Cost', $other_cost, $other_cost_original, $id_service, $id_rfq);
    self::create_audit_trail_service_modified($connection, 'Payment Term', $payment_term, $payment_term_original, $id_service, $id_rfq);
  }

  public static function shipping_event($connection, $shipping, $shipping_original, $shipping_cost, $shipping_cost_original, $id_rfq){
    self::create_audit_trail_shipping_modified($connection, 'Shipping', $shipping, $shipping_original, $id_rfq);
    self::create_audit_trail_shipping_modified($connection, 'Shipping Cost', $shipping_cost, $shipping_cost_original, $id_rfq);
  }

  public static function create_audit_trail_shipping_modified($connection, $field_name, $field, $original_field, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    if($field != $original_field){
      $message = '<b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
      $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
      self::insert_audit_trail($connection, $audit_trail);
    }
  }

  public static function create_audit_trail_item_modified($connection, $field_name, $field, $original_field, $id_item, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    if($field != $original_field){
      $message = '<a class="audit_trail_link" href="#" data=".item' . $id_item . '"><b>Charge</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
      $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
      self::insert_audit_trail($connection, $audit_trail);
    }
  }

  public static function create_audit_trail_subitem_modified($connection, $field_name, $field, $original_field, $id_subitem, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    if($field != $original_field){
      $message = '<a class="audit_trail_link" href="#" data=".subitem' . $id_subitem . '"><b>Charge</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
      $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
      self::insert_audit_trail($connection, $audit_trail);
    }
  }

  public static function create_audit_trail_service_modified($connection, $field_name, $field, $original_field, $id_service, $id_rfq){
    if(empty($field)){
      $field = 'Empty';
    }
    if(empty($original_field)){
      $original_field = 'Empty';
    }
    if($field != $original_field){
      $message = '<a class="audit_trail_link" href="#" data=".service' . $id_service . '"><b>Charge</b></a> modified:<br><b>' . $field_name . '</b> modified:<br><b>' . $original_field . ' > ' . $field . '</b>';
      $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
      self::insert_audit_trail($connection, $audit_trail);
    }
  }

  public static function create_audit_trail_item_created($connection, $id_item, $field, $field_name, $id_rfq){
    $message = '<a class="audit_trail_link" href="#" data=".item' . $id_item . '"><b>New charge</b></a> entered<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_subitem_created($connection, $id_subitem, $field, $field_name, $id_rfq){
    $message = '<a class="audit_trail_link" href="#" data=".subitem' . $id_subitem . '"><b>New charge</b></a> created<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_service_created($connection, $id_service, $field, $field_name, $id_rfq){
    $message = '<a class="audit_trail_link" href="#" data=".service' . $id_service . '"><b>New charge</b></a> entered<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }

  public static function create_audit_trail_item_deleted($connection, $field, $field_name, $id_rfq){
    $message = '<b>Charge</b> deleted<br><b>' . $field_name . ' = ' . $field . '</b>';
    $audit_trail = new AuditTrail('', $id_rfq, $_SESSION['nombre_usuario'], $message, '');
    self::insert_audit_trail($connection, $audit_trail);
  }
}
?>
