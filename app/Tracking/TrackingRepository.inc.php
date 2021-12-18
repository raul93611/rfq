<?php
class TrackingRepository{
  public static function tracking_list_items($id_rfq){
    Conexion::abrir_conexion();
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote-> get_id());
    Conexion::cerrar_conexion();
    if(count($items)){
      ?>
      <div class="table-responsive">
        <table id="tracking_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="thin">OPTIONS</th>
              <th class="thin">#</th>
              <th class="description">E-LOGIC PROPOSAL</th>
              <th class="thin">QTY(ordered)</th>
              <th class="thin">OPTIONS</th>
              <th class="thin">QTY(shipped)</th>
              <th>TRACKING</th>
              <th>DELIVERY DATE</th>
              <th>SIGNED BY</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($items as $i => $item) {
              $re_quote_item = $re_quote_items[$i];
              self::tracking_list_item($item, $re_quote_item, $i);
            }
            ?>
          </tbody>
        </table>
      </div>
      <?php
    }
  }

  public static function tracking_list_item($item, $re_quote_item, $i){
    if(!isset($item)){
      return;
    }
    Conexion::abrir_conexion();
    $trackings = TrackingRepository::get_all_trackings_by_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    if(!count($trackings)){
      $trackings_quantity = 1;
    }else{
      $trackings_quantity = count($trackings);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $trackings_quantity; ?>">
        <button type="button" class="add_tracking_button btn btn-warning" name="<?php echo $item-> obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $trackings_quantity; ?>"><?php echo $i + 1; ?></td>
      <td rowspan="<?php echo $trackings_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $re_quote_item-> get_brand() . '<br>';
        echo '<b>Part #:</b> ' . $re_quote_item-> get_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($re_quote_item-> get_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $trackings_quantity; ?>"><?php echo $re_quote_item-> get_quantity(); ?></td>
      <?php
  if(count($trackings)){
        ?>
        <td class="align-middle text-center">
          <a href="<?php echo DELETE_TRACKING . $trackings[0]-> get_id(); ?>" class="mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $trackings[0]-> get_id(); ?>" class="edit_tracking btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td><?php echo $trackings[0]-> get_quantity(); ?></td>
        <td><?php echo nl2br($trackings[0]-> get_tracking_number()); ?></td>
        <td><?php echo RepositorioComment::mysql_date_to_english_format($trackings[0]-> get_delivery_date()); ?></td>
        <td><?php echo $trackings[0]-> get_signed_by(); ?></td>
        <?php
      ?>
    </tr>
    <?php
    for ($j = 1; $j < count($trackings); $j++) {
      $tracking = $trackings[$j];
      ?>
      <tr>
        <td class="align-middle text-center">
          <a href="<?php echo DELETE_TRACKING . $tracking-> get_id(); ?>" class="mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
          <a href="#" data="<?php echo $tracking-> get_id(); ?>" class="edit_tracking btn btn-warning"><i class="fas fa-pen"></i></a>
        </td>
        <td><?php echo $tracking-> get_quantity(); ?></td>
        <td><?php echo nl2br($tracking-> get_tracking_number()); ?></td>
        <td><?php echo RepositorioComment::mysql_date_to_english_format($tracking-> get_delivery_date()); ?></td>
        <td><?php echo $tracking-> get_signed_by(); ?></td>
      </tr>
      <?php
    }
  }
    Conexion::abrir_conexion();
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
    Conexion::cerrar_conexion();
    Conexion::abrir_conexion();
    $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
    Conexion::cerrar_conexion();
    foreach ($subitems as $key => $subitem) {
      $re_quote_subitem = $re_quote_subitems[$key];
      TrackingRepository::tracking_list_subitem($subitem, $re_quote_subitem);
    }
  }

  public static function get_all_trackings_by_id_item($connection, $id_item){
    $trackings = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM trackings WHERE id_item = :id_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $trackings[] = new Tracking($row['id'], $row['id_item'], $row['quantity'], $row['tracking_number'], $row['delivery_date'], $row['signed_by']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $trackings;
  }

  public static function tracking_list_subitem($subitem, $re_quote_subitem){
    if(!isset($subitem)){
      return;
    }
    Conexion::abrir_conexion();
    $trackings_subitems = TrackingRepository::get_all_trackings_by_id_subitem(Conexion::obtener_conexion(), $subitem-> obtener_id());
    Conexion::cerrar_conexion();
    if(!count($trackings_subitems)){
      $trackings_quantity = 1;
    }else{
      $trackings_quantity = count($trackings_subitems);
    }
    ?>
    <tr>
      <td class="align-middle text-center" rowspan="<?php echo $trackings_quantity; ?>">
        <button type="button" class="add_tracking_subitem_button btn btn-warning" name="<?php echo $subitem-> obtener_id(); ?>"><i class="fas fa-plus"></i></button>
      </td>
      <td rowspan="<?php echo $trackings_quantity; ?>"></td>
      <td rowspan="<?php echo $trackings_quantity; ?>">
        <?php
        echo '<b>Brand:</b> ' . $re_quote_subitem-> get_brand() . '<br>';
        echo '<b>Part #:</b> ' . $re_quote_subitem-> get_part_number() . '<br>';
        echo '<b>Description:</b> ' . nl2br(mb_substr($re_quote_subitem-> get_description(), 0, 100));
        ?>
      </td>
      <td rowspan="<?php echo $trackings_quantity; ?>"><?php echo $re_quote_subitem-> get_quantity(); ?></td>
      <?php
    if(count($trackings_subitems)){
          ?>
          <td class="align-middle text-center">
            <a href="<?php echo DELETE_TRACKING_SUBITEM . $trackings_subitems[0]-> get_id(); ?>" class="mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $trackings_subitems[0]-> get_id(); ?>" class="edit_tracking_subitem btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $trackings_subitems[0]-> get_quantity(); ?></td>
          <td><?php echo nl2br($trackings_subitems[0]-> get_tracking_number()); ?></td>
          <td><?php echo RepositorioComment::mysql_date_to_english_format($trackings_subitems[0]-> get_delivery_date()); ?></td>
          <td><?php echo $trackings_subitems[0]-> get_signed_by(); ?></td>
          <?php
        ?>
      </tr>
      <?php
      for ($j = 1; $j < count($trackings_subitems); $j++) {
        $tracking = $trackings_subitems[$j];
        ?>
        <tr>
          <td class="align-middle text-center">
            <a href="<?php echo DELETE_TRACKING_SUBITEM . $tracking-> get_id(); ?>" class="mb-2 btn btn-warning"><i class="fas fa-trash"></i></a><br>
            <a href="#" data="<?php echo $tracking-> get_id(); ?>" class="edit_tracking_subitem btn btn-warning"><i class="fas fa-pen"></i></a>
          </td>
          <td><?php echo $tracking-> get_quantity(); ?></td>
          <td><?php echo nl2br($tracking-> get_tracking_number()); ?></td>
          <td><?php echo RepositorioComment::mysql_date_to_english_format($tracking-> get_delivery_date()); ?></td>
          <td><?php echo $tracking-> get_signed_by(); ?></td>
        </tr>
        <?php
      }
    }
  }

  public static function get_all_trackings_by_id_subitem($connection, $id_subitem){
    $trackings_subitems = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM trackings_subitems WHERE id_subitem = :id_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $trackings_subitems[] = new TrackingSubitem($row['id'], $row['id_subitem'], $row['quantity'], $row['tracking_number'], $row['delivery_date'], $row['signed_by']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $trackings_subitems;
  }

  public static function insert_tracking($connection, $tracking){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO trackings(id_item, quantity, tracking_number, delivery_date, signed_by) VALUES(:id_item, :quantity, :tracking_number, :delivery_date, :signed_by)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_item', $tracking-> get_id_item(), PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $tracking-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindParam(':tracking_number', $tracking-> get_tracking_number(), PDO::PARAM_STR);
        $sentence-> bindParam(':delivery_date', $tracking-> get_delivery_date(), PDO::PARAM_STR);
        $sentence-> bindParam(':signed_by', $tracking-> get_signed_by(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_tracking_by_id($connection, $id_tracking){
    $tracking = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM trackings WHERE id = :id_tracking';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_tracking', $id_tracking, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $tracking = new Tracking($result['id'], $result['id_item'], $result['quantity'], $result['tracking_number'], $result['delivery_date'], $result['signed_by']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tracking;
  }

  public static function update_tracking($connection, $quantity, $tracking_number, $delivery_date, $signed_by, $id_tracking){
    if(isset($connection)){
      try{
        $sql = 'UPDATE trackings SET quantity = :quantity, tracking_number = :tracking_number, delivery_date = :delivery_date, signed_by = :signed_by WHERE id = :id_tracking';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $sentence-> bindParam(':tracking_number', $tracking_number, PDO::PARAM_STR);
        $sentence-> bindParam(':delivery_date', $delivery_date, PDO::PARAM_STR);
        $sentence-> bindParam(':signed_by', $signed_by, PDO::PARAM_STR);
        $sentence-> bindParam(':id_tracking', $id_tracking, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_tracking($connection, $id_tracking){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM trackings WHERE id = :id_tracking';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_tracking', $id_tracking, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
