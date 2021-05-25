<?php
class ReQuoteItemRepository{
  public static function insert_re_quote_item($database, $re_quote_item){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO re_quote_items(id_re_quote, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_re_quote, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote', $re_quote_item-> get_id_re_quote(), PDO::PARAM_STR);
        $query-> bindParam(':brand', $re_quote_item-> get_brand(), PDO::PARAM_STR);
        $query-> bindParam(':brand_project', $re_quote_item-> get_brand_project(), PDO::PARAM_STR);
        $query-> bindParam(':part_number', $re_quote_item-> get_part_number(), PDO::PARAM_STR);
        $query-> bindParam(':part_number_project', $re_quote_item-> get_part_number_project(), PDO::PARAM_STR);
        $query-> bindParam(':description', $re_quote_item-> get_description(), PDO::PARAM_STR);
        $query-> bindParam(':description_project', $re_quote_item-> get_description_project(), PDO::PARAM_STR);
        $query-> bindParam(':quantity', $re_quote_item-> get_quantity(), PDO::PARAM_STR);
        $query-> bindParam(':unit_price', $re_quote_item-> get_unit_price(), PDO::PARAM_STR);
        $query-> bindParam(':total_price', $re_quote_item-> get_total_price(), PDO::PARAM_STR);
        $query-> bindParam(':comments', $re_quote_item-> get_comments(), PDO::PARAM_STR);
        $query-> bindParam(':website', $re_quote_item-> get_website(), PDO::PARAM_STR);
        $query-> bindParam(':additional', $re_quote_item-> get_additional(), PDO::PARAM_STR);
        $query-> execute();
        $id = $database-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function print_re_quote_items($id_re_quote) {
    Database::open_connection();
    $re_quote = ReQuoteRepository::get_re_quote_by_id(Database::get_connection(), $id_re_quote);
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $re_quote-> get_id_quote());
    $re_quote_items = self::get_re_quote_items_by_id_re_quote(Database::get_connection(), $id_re_quote);
    $items = ItemRepository::get_all_by_id_quote(Database::get_connection(), $re_quote-> get_id_quote());
    Database::close_connection();
    if (count($re_quote_items)) {
      ?>
      <br>
      <a target="_blank" href="<?php echo PDF_RE_QUOTE . $re_quote-> get_id_quote(); ?>" class="btn btn-primary float-right"><i class="fa fa-file"></i> PDF</a>
      <h2>Items:</h2>
      <div class="p-3">
        <div class="custom-control custom-radio">
          <input type="radio" id="net_30" name="payment_terms" class="custom-control-input" value="Net 30" <?php if($re_quote-> get_payment_terms() == 'Net 30'){echo 'checked';} ?>>
          <label class="custom-control-label" for="net_30">Net 30</label>
        </div>
        <div class="custom-control custom-radio">
          <input type="radio" id="net_30_cc" name="payment_terms" class="custom-control-input" value="Net 30/CC" <?php if($re_quote-> get_payment_terms() == 'Net 30/CC'){echo 'checked';} ?>>
          <label class="custom-control-label" for="net_30_cc">Net 30/CC</label>
        </div>
        <input type="hidden" name="payment_terms_original" value="<?php echo $re_quote-> get_payment_terms(); ?>">
      </div>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="options">Options</th>
              <th id="numeration">#</th>
              <th class="description">PROJECT SPECIFICATIONS</th>
              <th class="description">E-LOGIC PROPOSAL</th>
              <th class="options">WEBSITE</th>
              <th class="qty">QTY</th>
              <th style="width: 200px;">PROVIDERS</th>
              <th style="width: 100px;">BEST UNIT COST</th>
              <th style="width: 100px;">TOTAL COST</th>
              <th style="width: 100px;">PRICE FOR CLIENT</th>
              <th style="width: 100px;">TOTAL PRICE</th>
              <th style="width: 100px;">COMMENTS</th>
            </tr>
          </thead>
          <tbody id="re_quote_data">
            <?php
            $k = 1;
            foreach ($re_quote_items as $key => $re_quote_item) {
              $item = $items[$key];
              $k = self::print_re_quote_item($re_quote_item, $item, $k, $key + 1);
            }
            ?>
            <td colspan="5" class="display-4"><b><h4>TOTAL:</h4></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="total_re_quote"></td>
            <td></td>
            <td id="total_ganado"><?php echo '$ ' . $quote-> get_total_price(); ?></td>
            <td id="profit_rq"></td>
          </tbody>
        </table>
      </div>
      <?php
      $id_items = [];
      $id_subitems = [];
      $subitems_counter = 0;
      foreach ($re_quote_items as $key => $re_quote_item) {
        $id_items[] = $re_quote_item-> get_id();
        Database::open_connection();
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Database::get_connection(), $re_quote_item-> get_id());
        Database::close_connection();
        foreach ($re_quote_subitems as $key => $re_quote_subitem) {
          $id_subitems[] = $re_quote_subitem-> get_id();
        }
      }
      $id_items = implode(',', $id_items);
      $id_subitems = implode(',', $id_subitems);
      ?>
      <input type="hidden" id="total_cost" name="total_cost" value="">
      <div class="row">
        <div class="col-md-6">
          <h5>Shipping Re-Quote</h5>
        </div>
        <div class="col-md-6">
          <h5>Shipping Quote</h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="1" name="shipping" id="shipping_rq" placeholder="Enter shipping ..."><?php echo $re_quote-> get_shipping(); ?></textarea>
            <input type="hidden" name="shipping_original" value="<?php echo $re_quote-> get_shipping(); ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost_rq" name="shipping_cost" value="<?php echo $re_quote-> get_shipping_cost(); ?>">
            <input type="hidden" name="shipping_cost_original" value="<?php echo $re_quote-> get_shipping_cost(); ?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="1" name="shipping" id="shipping" disabled placeholder="Enter shipping ..."><?php echo $quote-> get_shipping(); ?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" disabled name="shipping_cost" value="<?php echo $quote-> get_shipping_cost(); ?>">
          </div>
        </div>
      </div>
      <?php
    }
  }

  public static function print_re_quote_item($re_quote_item,$item, $i, $number) {
    if (!isset($re_quote_item)) {
      return;
    }
    $j = $i;
    Database::open_connection();
    $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Database::get_connection(), $re_quote_item-> get_id());
    Database::close_connection();
    ?>
    <tr id="<?php echo 'item' . $re_quote_item-> get_id(); ?>">
      <td>
        <a href="<?php echo ADD_RE_QUOTE_PROVIDER . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add Provider
        </a>
        <br>
        <a href="<?php echo EDIT_RE_QUOTE_ITEM . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-edit"></i> Edit item
        </a>
        <br>
        <!-- <a href="<?php echo DELETE_RE_QUOTE_ITEM . $re_quote_item-> get_id(); ?>" class="delete_item_button btn btn-warning btn-block">
          <i class="fa fa-trash"></i> Delete
        </a> -->
        <br>
        <!-- <a href="<?php echo ADD_RE_QUOTE_SUBITEM . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add subitem
        </a> -->
        <br>
      </td>
      <td><?php echo $number; ?></td>
    <?php
    if(strlen($re_quote_item-> get_description_project()) >= 100){
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_item-> get_brand_project(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_item-> get_part_number_project(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br(mb_substr($re_quote_item-> get_description_project(), 0, 100)); ?> ...
      </td>
      <?php
    }else{
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_item-> get_brand_project(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_item-> get_part_number_project(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br($re_quote_item-> get_description_project()); ?>
      </td>
      <?php
    }
    if(strlen($re_quote_item-> get_description()) >= 100){
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_item-> get_brand(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_item-> get_part_number(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br(mb_substr($re_quote_item-> get_description(), 0, 100)); ?> ...
      </td>
      <?php
    }else{
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_item-> get_brand(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_item-> get_part_number(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br($re_quote_item-> get_description()); ?>
      </td>
      <?php
    }
    ?>
      <td class="estrechar">
        <a target="_blank" href="<?php echo $re_quote_item-> get_website(); ?>"><?php echo $re_quote_item-> get_website(); ?></a>
      </td>
      <td><?php echo $re_quote_item-> get_quantity(); ?></td>
      <td>
        <div class="row">
          <div class="col-6">
            <?php
            if(count($re_quote_providers)){
              foreach ($re_quote_providers as $key => $re_quote_provider) {
                if(strlen($re_quote_provider-> get_provider()) >= 10){
                  ?>
                  <a href="<?php echo EDIT_RE_QUOTE_PROVIDER . $re_quote_provider-> get_id(); ?>">
                    <b><?php echo mb_substr($re_quote_provider-> get_provider(), 0, 10); ?>... :</b>
                  </a>
                  <br>
                  <?php
                }else{
                  ?>
                  <a href="<?php echo EDIT_RE_QUOTE_PROVIDER . $re_quote_provider-> get_id(); ?>">
                    <b><?php echo $re_quote_provider-> get_provider(); ?>:</b>
                  </a>
                  <br>
                  <?php
                }
              }
            }
            ?>
          </div>
          <div class="col-6">
            <?php
            if(count($re_quote_providers)){
              foreach ($re_quote_providers as $key => $re_quote_provider) {
                echo '$ ' . $re_quote_provider-> get_price() . '<br>';
              }
            }
            ?>
          </div>
        </div>
      </td>
    <?php
    echo '<td>';
    $prices = [];
    if(count($re_quote_providers)){
      foreach ($re_quote_providers as $key => $re_quote_provider) {
        $prices[$key] = $re_quote_provider-> get_price();
      }
    }
    if (!empty($prices)) {
      $best_unit_price = min($prices);
      echo '$ ' . $best_unit_price;
    }
    echo '</td>';
    ?>
      <td><?php echo '$ ' . $best_unit_price * $re_quote_item-> get_quantity(); ?></td>
      <td><?php if(!is_null($item)){echo '$ ' . $item-> get_unit_price();} ?></td>
      <td><?php if(!is_null($item)){echo '$ ' . $item-> get_total_price();} ?></td>
      <td><?php echo nl2br($re_quote_item-> get_comments()); ?></td>
    </tr>
    <?php
    if(!is_null($item)){
      $j = ReQuoteSubitemRepository::print_re_quote_subitems($re_quote_item-> get_id(), $item-> get_id(), $j);
    }else{
      $j = ReQuoteSubitemRepository::print_re_quote_subitems($re_quote_item-> get_id(), '', $j);
    }
    return $j;
  }

  public static function get_re_quote_items_by_id_re_quote($database, $id_re_quote){
    $re_quote_items = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM re_quote_items WHERE id_re_quote = :id_re_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $re_quote_items[] = new ReQuoteItem($row['id'], $row['id_re_quote'], $row['brand'], $row['brand_project'], $row['part_number'], $row['part_number_project'], $row['description'], $row['description_project'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['comments'], $row['website'], $row['additional']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_items;
  }

  public static function get_re_quote_item_by_id($database, $id_re_quote_item){
    $re_quote_item = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM re_quote_items WHERE id = :id_re_quote_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote_item = new ReQuoteItem($result['id'], $result['id_re_quote'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_item;
  }

  public static function update_re_quote_item($database, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website, $id_re_quote_item){
    if(isset($database)){
      try{
        $sql = 'UPDATE re_quote_items SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_re_quote_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':brand', $brand, PDO::PARAM_STR);
        $query-> bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
        $query-> bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $query-> bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $query-> bindParam(':description', $description, PDO::PARAM_STR);
        $query-> bindParam(':description_project', $description_project, PDO::PARAM_STR);
        $query-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $query-> bindParam(':comments', $comments, PDO::PARAM_STR);
        $query-> bindParam(':website', $website, PDO::PARAM_STR);
        $query-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_item($database, $id_re_quote_item){
    if(isset($database)){
      try{
        $database-> beginTransaction();
        $sql1 = 'DELETE FROM re_quote_providers WHERE id_re_quote_item = :id_re_quote_item';
        $query1 = $database-> prepare($sql1);
        $query1-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $query1-> execute();
        $sql2 = 'DELETE FROM re_quote_items WHERE id = :id_re_quote_item';
        $query2 = $database-> prepare($sql2);
        $query2-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $query2-> execute();
        $database-> commit();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
        $database-> rollBack();
      }
    }
  }

  public static function insert_calc($database, $unit_price, $total_price, $additional, $id_re_quote_item){
    if(isset($database)){
      try{
        $sql = 'UPDATE re_quote_items SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_re_quote_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
        $query-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $query-> bindParam(':additional', $additional, PDO::PARAM_STR);
        $query-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
