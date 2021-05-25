<?php
class ItemRepository {
  public static function insert($database, $item) {
    if (isset($database)) {
      try {
        $sql = 'INSERT INTO items(id_quote, id_user, least_provider, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_quote, :id_user, :least_provider, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $item->get_id_quote(), PDO::PARAM_STR);
        $query->bindParam(':id_user', $item->get_id_user(), PDO::PARAM_STR);
        $query->bindParam(':least_provider', $item->get_least_provider(), PDO::PARAM_STR);
        $query->bindParam(':brand', $item->get_brand(), PDO::PARAM_STR);
        $query->bindParam(':brand_project', $item->get_brand_project(), PDO::PARAM_STR);
        $query->bindParam(':part_number', $item->get_part_number(), PDO::PARAM_STR);
        $query->bindParam(':part_number_project', $item->get_part_number_project(), PDO::PARAM_STR);
        $query->bindParam(':description', $item->get_description(), PDO::PARAM_STR);
        $query->bindParam(':description_project', $item->get_description_project(), PDO::PARAM_STR);
        $query->bindParam(':quantity', $item->get_quantity(), PDO::PARAM_STR);
        $query->bindParam(':unit_price', $item->get_unit_price(), PDO::PARAM_STR);
        $query->bindParam(':total_price', $item->get_total_price(), PDO::PARAM_STR);
        $query->bindParam(':comments', $item->get_comments(), PDO::PARAM_STR);
        $query->bindParam(':website', $item->get_website(), PDO::PARAM_STR);
        $query->bindParam(':additional', $item->get_additional(), PDO::PARAM_STR);
        $result = $query->execute();
        $id = $database->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function update_least_provider($database, $least_provider, $id_item){
    $item = false;
    if(isset($database)){
      try{
        $sql = 'UPDATE items SET least_provider = :least_provider WHERE id = :id_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':least_provider', $least_provider, PDO::PARAM_STR);
        $query-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query-> execute();
        if($query){
          $item = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function get_all_by_id_quote($database, $id_quote) {
    $items = [];
    if (isset($database)) {
      try {
        $sql = 'SELECT * FROM items WHERE id_quote = :id_quote';
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchall(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $row) {
            $items[] = new Item($row['id'], $row['id_quote'], $row['id_user'], $row['least_provider'], $row['brand'], $row['brand_project'], $row['part_number'], $row['part_number_project'], $row['description'], $row['description_project'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['comments'], $row['website'], $row['additional'], $row['fulfillment_profit']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function items_exists($database, $id_quote) {
    $items = 0;
    if (isset($database)) {
      try {
        $sql = "SELECT COUNT(*) as items FROM items WHERE id_quote = :id_quote";
        $query = $database->prepare($sql);
        $query->bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $items = $result['items'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function print_item($item, $i, $numeration) {
    if (!isset($item)) {
      return;
    }
    $j = $i;
    Database::open_connection();
    $providers = ProviderRepository::get_all_by_id_item(Database::get_connection(), $item->get_id());
    Database::close_connection();
    echo '<tr id="item' . $item->get_id() . '">';
    echo '<td><a href="' . ADD_PROVIDER . '/' . $item->get_id() . '" class="btn btn-warning btn-block"><i class="fa fa-plus-circle"></i> Add Provider</a><br><a href="' . EDIT_ITEM . '/' . $item->get_id() . '" class="btn btn-warning btn-block"><i class="fa fa-edit"></i> Edit item</a><br><a href="' . DELETE_ITEM . '/' . $item-> get_id() . '" class="delete_item_button btn btn-warning btn-block"><i class="fa fa-trash"></i> Delete</a><br><a href="' . ADD_SUBITEM . '/' . $item-> get_id() . '" class="btn btn-warning btn-block"><i class="fa fa-plus-circle"></i> Add subitem</a></td>';
    echo '<td>' . $numeration . '</td>';
    if(strlen($item-> get_description_project()) >= 100){
      echo '<td><b>Brand:</b> ' . $item->get_brand_project() . '<br><b>Part #:</b> ' . $item->get_part_number_project() . '<br><b>Description:</b> ' . $item->get_description_project() . '</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $item->get_brand_project() . '<br><b>Part #:</b> ' . $item->get_part_number_project() . '<br><b>Description:</b> ' . nl2br($item->get_description_project()) . '</td>';
    }
    if(strlen($item-> get_description()) >= 100){
      echo '<td><b>Brand:</b> ' . $item->get_brand() . '<br><b>Part #:</b> ' . $item->get_part_number() . '<br><b>Description:</b> ' . nl2br(mb_substr($item->get_description(), 0, 100)) . ' ...</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $item->get_brand() . '<br><b>Part #:</b> ' . $item->get_part_number() . '<br><b>Description:</b> ' . nl2br($item->get_description()) . '</td>';
    }
    echo '<td class="estrechar"><a target="_blank" href="'. $item-> get_website() .'">'. $item-> get_website() .'</a></td>';
    echo '<td>' . $item->get_quantity() . '</td>';
    echo '<td><div class="row"><div class="col-6">';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      if(strlen($provider-> get_provider()) >= 10){
        echo '<a href="' . EDIT_PROVIDER . '/' . $provider->get_id() . '"><b>' . mb_substr($provider->get_provider(), 0, 10) . '... :</b></a><br>';
      }else{
        echo '<a href="' . EDIT_PROVIDER . '/' . $provider->get_id() . '"><b>' . $provider->get_provider() . ':</b></a><br>';
      }
    }
    echo '</div><div class="col-6">';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      echo '$ ' . $provider->get_price() . '<br>';
    }
    echo '</div></div></td>';
    if($item-> get_additional() != 0){
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="'.$item-> get_additional().'"></td>';
    }else{
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="0"></td>';
    }
    echo '<td>';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      $prices[$i] = $provider->get_price();
    }
    if (!empty($prices)) {
      $best_unit_price = min($prices);
      for($i = 0;$i < count($prices); $i++){
        if($best_unit_price == $prices[$i]){
          Database::open_connection();
          self::update_least_provider(Database::get_connection(), $providers[$i]->get_id(), $item-> get_id());
          Database::close_connection();
        }
      }
      echo '$ ' . $best_unit_price;
    }
    echo '</td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td>' . nl2br($item->get_comments()) . '</td>';
    echo '</tr>';
    $j = RepositorioSubitem::escribir_subitems($item-> get_id(), $j);
    return $j;
  }

  public static function print_items($id_quote) {
    Database::open_connection();
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
    $items = self::get_all_by_id_quote(Database::get_connection(), $id_quote);
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Database::get_connection(), $id_quote);
    Database::close_connection();
    echo '<br><h2 id="caja_items">Items:</h2>';
    if (count($items)) {
      ?>
      <br>
      <a target="_blank" href="<?php echo PDF_TABLA_ITEMS . $id_quote; ?>" class="ml-2 float-right btn btn-primary"><i class="fa fa-file"></i> PDF</a>
      <?php
      if($re_quote_exists){
        ?>
        <a target="_blank" href="<?php echo EXCEL_ITEMS_TABLE . $id_quote; ?>" class="float-right btn btn-primary"><i class="fa fa-file"></i> EXCEL</a>
        <?php
      }
      echo '<div class="row">';
      echo '<div class="col-md-3">';
      if ($quote->get_taxes() != 0) {
        echo '<label>Taxes (%):</label><input type="hidden" name="taxes_original" value="' . $quote->get_taxes() . '"><input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="' . $quote->get_taxes() . '">';
      } else {
        echo '<label>Taxes (%):</label><input type="hidden" name="taxes_original" value="' . $quote->get_taxes() . '"><input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="0">';
      }
      echo '</div><div class="col-md-3">';
      if ($quote->get_profit() != 0) {
        echo '<label>Profit (%):</label><input type="hidden" name="profit_original" value="' . $quote->get_profit() . '"><input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="' . $quote->get_profit() . '">';
      } else {
        echo '<label>Profit (%):</label><input type="hidden" name="profit_original" value="' . $quote->get_profit() . '"><input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="0">';
      }
      echo '</div><div class="col-md-3">';
      if($quote-> get_additional() != 0){
        echo '<label>Additional general ($):</label><input type="hidden" name="additional_general_original" value="' . $quote->get_additional() . '"><input type="text" name="additional_general" id="additional_general" class="form-control form-control-sm" value="' . $quote->get_additional() . '">';
      }else{
        echo '<label>Additional general ($):</label><input type="hidden" name="additional_general_original" value="' . $quote->get_additional() . '"><input type="text" name="additional_general" id="additional_general" class="form-control form-control-sm" value="0">';
      }
      echo '</div><div class="col-md-3">';
      echo '<label>Payment terms:</label><div class="form-group">
          <div class="form-check-inline custom-control custom-radio">
              <input class="custom-control-input" type="radio" id="net_30" value="Net 30" name="payment_terms"';
              if ($quote->get_payment_terms() == 'Net 30') {
                echo 'checked';
              }
              echo '><label class="custom-control-label" for="net_30">Net 30</label>
          </div>
          <div class="form-check-inline custom-control custom-radio">
              <input class="custom-control-input" type="radio" id="net_30cc" value="Net 30/CC" name="payment_terms"';

              if ($quote->get_payment_terms() == 'Net 30/CC') {
                echo 'checked';
              }
      echo '><label class="custom-control-label" for="net_30cc">Net 30/CC</label>
          </div>
      </div>';
      echo '<input type="hidden" name="payment_terms_original" value="' . $quote-> get_payment_terms() . '">';
      echo '</div></div><br>';
      echo '<div class="table-responsive">';
      echo '<table id="items_table" class="table table-bordered table-hover">';
      echo '<thead>';
      echo '<tr>';
      echo '<th class="options">Options</th>';
      echo '<th id="numeration">#</th>';
      echo '<th class="description">PROJECT SPECIFICATIONS</th>';
      echo '<th class="description">E-LOGIC PROPOSAL</th>';
      echo '<th class="options">WEBSITE</th>';
      echo '<th class="qty">QTY</th>';
      echo '<th id="provider">PROVIDERS</th>';
      echo '<th class="qty">ADDITIONAL</th>';
      echo '<th class="options">BEST UNIT COST</th>';
      echo '<th class="options">TOTAL COST</th>';
      echo '<th class="options">PRICE FOR CLIENT</th>';
      echo '<th class="options">TOTAL PRICE</th>';
      echo '<th class="description">COMMENTS</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody id="items">';
      $k = 1;
      for ($i = 0; $i < count($items); $i++) {
        $item = $items[$i];
        $k = self::print_item($item, $k, $i+1);
      }
      echo '<td colspan="5" class="display-4"><b><h4>TOTAL:</h4></b></td>';
      echo '<td id="total_quantity"></td>';
      echo '<td></td>';
      echo '<td id="total_additional"></td>';
      echo '<td></td>';
      echo '<td id="total1"></td>';
      echo '<td></td>';
      echo '<td id="total2"></td>';
      echo '<td id="dif_total"></td>';
      echo '</tbody>';
      echo '</table>';
      echo '</div>';
      $id_items = '';
      $id_subitems = '';
      $subitems_counter = 0;
      for($i = 0; $i < count($items); $i++){
        $item = $items[$i];
        if($i == 0){
          $id_items = $id_items . $item-> get_id();
        }else{
          $id_items = $id_items . ',' . $item-> get_id();
        }
        Database::open_connection();
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Database::get_connection(), $item-> get_id());
        Database::close_connection();
        for($j = 0; $j < count($subitems); $j++){
          $subitem = $subitems[$j];
          if($subitems_counter == 0){
            $id_subitems = $id_subitems . $subitem-> get_id();
          }else{
            $id_subitems = $id_subitems . ',' . $subitem-> get_id();
          }
          $subitems_counter++;
        }
      }
      echo '<input type="hidden" id="id_items" name="id_items" value="'.$id_items.'">';
      echo '<input type="hidden" id="id_subitems" name="id_subitems" value="'.$id_subitems.'">';
      echo '<input type="hidden" id="total_price_parts" name="total_price_parts" value="">';
      echo '<input type="hidden" id="total_price_subitems_parts" name="total_price_subitems_parts" value="">';
      echo '<input type="hidden" id="additional" name="additional" value="">';
      echo '<input type="hidden" id="additional_subitems" name="additional_subitems" value="">';
      echo '<input type="hidden" id="unit_prices" name="unit_prices" value="">';
      echo '<input type="hidden" id="unit_prices_subitems" name="unit_prices_subitems" value="">';
      echo '<input type="hidden" id="total_cost" name="total_cost" value="">';
      echo '<input type="hidden" id="total_price" name="total_price" value="">';
      ?>
        <br>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <textarea class="form-control form-control-sm" rows="1" id="shipping" name="shipping" placeholder="Enter shipping ..."><?php echo $quote->get_shipping(); ?></textarea>
              <input type="hidden" name="shipping_original" value="<?php echo $quote->get_shipping(); ?>">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="<?php echo $quote->get_shipping_cost(); ?>">
              <input type="hidden" name="shipping_cost_original" value="<?php echo $quote->get_shipping_cost(); ?>">
            </div>
          </div>
        </div>
        <?php
    }else{
      ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items to display!</h3>
      <?php
    }
  }

  public static function get_by_id($database, $id_item) {
    $item = null;
    if (isset($database)) {
      try {
        $sql = 'SELECT * FROM items WHERE id = :id_item';
        $query = $database->prepare($sql);
        $query->bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        if (!empty($result)) {
          $item = new Item($result['id'], $result['id_quote'], $result['id_user'], $result['least_provider'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional'], $result['fulfillment_profit']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function update($database, $id_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website) {
    $item = false;
    if (isset($database)) {
      try {
        $sql = 'UPDATE items SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_item';
        $query = $database->prepare($sql);
        $query->bindParam(':brand', $brand, PDO::PARAM_STR);
        $query->bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
        $query->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $query->bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $query->bindParam(':description', $description, PDO::PARAM_STR);
        $query->bindParam(':description_project', $description_project, PDO::PARAM_STR);
        $query->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $query->bindParam(':comments', $comments, PDO::PARAM_STR);
        $query->bindParam(':website', $website, PDO::PARAM_STR);
        $query->bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query->execute();
        if ($query) {
          $item = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function update_amounts($database, $unit_price, $total_price, $additional, $id_item){
    $item = false;
    if(isset($database)){
      try{
        $sql = 'UPDATE items SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
        $query-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $query-> bindParam(':additional', $additional, PDO::PARAM_STR);
        $query-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query-> execute();
        if($query){
          $item = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function set_fulfillment_profit($database, $fulfillment_profit, $id_item){
    if(isset($database)){
      try{
        $sql = 'UPDATE items SET fulfillment_profit = :fulfillment_profit WHERE id = :id_item';
        $query = $database-> prepare($sql);
        $query-> bindParam(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $query-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_item($database, $id_item){
    if(isset($database)){
      try{
        $database -> beginTransaction();
        $sql1 = "DELETE FROM providers WHERE id_item = :id_item";
        $query1 = $database-> prepare($sql1);
        $query1-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query1-> execute();
        $sql2 = "DELETE FROM items WHERE id = :id_item";
        $query2 = $database-> prepare($sql2);
        $query2-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query2-> execute();
        $database-> commit();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
        $database-> rollBack();
      }
    }
  }
}
?>
