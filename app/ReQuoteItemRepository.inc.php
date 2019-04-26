<?php
class ReQuoteItemRepository{
  public static function insert_re_quote_item($connection, $re_quote_item){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quote_items(id_re_quote, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_re_quote, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $re_quote_item-> get_id_re_quote(), PDO::PARAM_STR);
        $sentence-> bindParam(':brand', $re_quote_item-> get_brand(), PDO::PARAM_STR);
        $sentence-> bindParam(':brand_project', $re_quote_item-> get_brand_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':part_number', $re_quote_item-> get_part_number(), PDO::PARAM_STR);
        $sentence-> bindParam(':part_number_project', $re_quote_item-> get_part_number_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':description', $re_quote_item-> get_description(), PDO::PARAM_STR);
        $sentence-> bindParam(':description_project', $re_quote_item-> get_description_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $re_quote_item-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindParam(':unit_price', $re_quote_item-> get_unit_price(), PDO::PARAM_STR);
        $sentence-> bindParam(':total_price', $re_quote_item-> get_total_price(), PDO::PARAM_STR);
        $sentence-> bindParam(':comments', $re_quote_item-> get_comments(), PDO::PARAM_STR);
        $sentence-> bindParam(':website', $re_quote_item-> get_website(), PDO::PARAM_STR);
        $sentence-> bindParam(':additional', $re_quote_item-> get_additional(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function print_re_quote_items($id_re_quote) {
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $id_re_quote);
    $re_quote_items = self::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $id_re_quote);
    Conexion::cerrar_conexion();
    if (count($re_quote_items)) {
      ?>
      <br>
      <h2 id="caja_items">Items:</h2>
      <div class="row">
        <div class="col-md-3">
        <?php
        if ($re_quote-> get_taxes() != 0) {
          ?>
          <label>Taxes (%):</label>
          <input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="<?php echo $re_quote-> get_taxes(); ?>">
          <?php
        } else {
          ?>
          <label>Taxes (%):</label>
          <input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="0">
          <?php
        }
        ?>
        </div>
        <div class="col-md-3">
        <?php
        if ($re_quote-> get_profit() != 0) {
          ?>
          <label>Profit (%):</label>
          <input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="<?php echo $re_quote-> get_profit(); ?>">
          <?php
        } else {
          ?>
          <label>Profit (%):</label>
          <input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="0">
          <?php
        }
        ?>
        </div>
        <div class="col-md-3">
        <?php
        if($re_quote-> get_additional() != 0){
          ?>
          <label>Additional general ($):</label>
          <input type="number" step=".01" name="additional_general" id="additional_general" class="form-control form-control-sm" value="<?php echo $re_quote-> get_additional(); ?>">
          <?php
        }else{
          ?>
          <label>Additional general ($):</label>
          <input type="number" step=".01" name="additional_general" id="additional_general" class="form-control form-control-sm" value="0">
          <?php
        }
        ?>
        </div>
        <div class="col-md-3">
          <label>Payment terms:</label>
          <div class="form-group">
            <div class="form-check-inline">
              <input class="form-check-input" type="radio" id="net_30" value="Net 30" name="payment_terms"
              <?php
              if ($re_quote-> get_payment_terms() == 'Net 30') {
                echo 'checked';
              }
              ?>
              ><label class="form-check-label" for="net_30">Net 30</label>
            </div>
            <div class="form-check-inline">
              <input class="form-check-input" type="radio" id="net_30cc" value="Net 30/CC" name="payment_terms"
              <?php
              if ($re_quote-> get_payment_terms() == 'Net 30/CC') {
                echo 'checked';
              }
              ?>
              ><label class="form-check-label" for="net_30cc">Net 30/CC</label>
            </div>
          </div>
        </div>
      </div>
      <br>
      <div class="table-responsive">
        <table id="tabla_items" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="options">Options</th>
              <th id="numeracion">#</th>
              <th class="description">PROJECT SPECIFICATIONS</th>
              <th class="description">E-LOGIC PROPOSAL</th>
              <th class="options">WEBSITE</th>
              <th class="qty">QTY</th>
              <th id="provider">PROVIDERS</th>
              <th class="qty">ADDITIONAL</th>
              <th class="options">BEST UNIT COST</th>
              <th class="options">TOTAL COST</th>
              <th class="options">PRICE FOR CLIENT</th>
              <th class="options">TOTAL PRICE</th>
              <th class="description">COMMENTS</th>
            </tr>
          </thead>
          <tbody id="items">
            <?php
            $k = 1;
            foreach ($re_quote_items as $key => $re_quote_item) {
              $k = self::print_re_quote_item($re_quote_item, $k, $key + 1);
            }
            ?>
            <td colspan="5" class="display-4"><b><h4>TOTAL:</h4></b></td>
            <td id="total_quantity"></td>
            <td></td>
            <td id="total_additional"></td>
            <td></td>
            <td id="total1"></td>
            <td></td>
            <td id="total2"></td>
            <td id="dif_total"></td>
          </tbody>
        </table>
      </div>
      <?php
      $id_items = [];
      $id_subitems = [];
      $contador_subitems = 0;
      foreach ($re_quote_items as $key => $re_quote_item) {
        $id_items[] = $re_quote_item-> get_id();
        Conexion::abrir_conexion();
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
        Conexion::cerrar_conexion();
        foreach ($re_quote_subitems as $key => $re_quote_subitem) {
          $id_subitems[] = $re_quote_subitem-> get_id();
        }
      }
      $id_items = implode(',', $id_items);
      $id_subitems = implode(',', $id_subitems);
      ?>
      <input type="hidden" id="id_re_quote_items" name="id_re_quote_items" value="<?php echo $id_items; ?>">
      <input type="hidden" id="id_re_quote_subitems" name="id_re_quote_subitems" value="<?php echo $id_subitems; ?>">
      <input type="hidden" id="partes_total_price" name="partes_total_price" value="">
      <input type="hidden" id="partes_total_price_subitems" name="partes_total_price_subitems" value="">
      <input type="hidden" id="additional" name="additional" value="">
      <input type="hidden" id="additional_subitems" name="additional_subitems" value="">
      <input type="hidden" id="unit_prices" name="unit_prices" value="">
      <input type="hidden" id="unit_prices_subitems" name="unit_prices_subitems" value="">
      <input type="hidden" id="total_cost" name="total_cost" value="">
      <input type="hidden" id="total_price" name="total_price" value="">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="3" name="shipping" id="shipping" placeholder="Enter shipping ..."><?php echo $re_quote-> get_shipping(); ?></textarea>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="<?php echo $re_quote-> get_shipping_cost(); ?>">
          </div>
        </div>
      </div>
      <?php
    }
  }

  public static function print_re_quote_item($re_quote_item, $i, $number) {
    if (!isset($re_quote_item)) {
      return;
    }
    $j = $i;
    Conexion::abrir_conexion();
    $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
    Conexion::cerrar_conexion();
    ?>
    <tr>
      <td>
        <a href="<?php echo ADD_RE_QUOTE_PROVIDER . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add Provider
        </a>
        <br>
        <a href="<?php echo EDIT_RE_QUOTE_ITEM . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-edit"></i> Edit item
        </a>
        <br>
        <a href="<?php echo DELETE_RE_QUOTE_ITEM . $re_quote_item-> get_id(); ?>" class="delete_item_button btn btn-warning btn-block">
          <i class="fa fa-trash"></i> Delete
        </a>
        <br>
        <a href="<?php echo ADD_RE_QUOTE_SUBITEM . $re_quote_item-> get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add subitem
        </a>
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
    if($re_quote_item-> get_additional() != 0){
      ?>
      <td>
        <input type="text" class="form-control form-control-sm" id="add_cost<?php echo $j; ?>" size="10" value="<?php echo $re_quote_item-> get_additional(); ?>">
      </td>
      <?php
    }else{
      ?>
      <td>
        <input type="text" class="form-control form-control-sm" id="add_cost<?php echo $j; ?>" size="10" value="0">
      </td>
      <?php
    }
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
      <td></td>
      <td></td>
      <td></td>
      <td><?php echo nl2br($re_quote_item-> get_comments()); ?></td>
    </tr>
    <?php
    $j = ReQuoteSubitemRepository::print_re_quote_subitems($re_quote_item-> get_id(), $j);
    return $j;
  }

  public static function get_re_quote_items_by_id_re_quote($connection, $id_re_quote){
    $re_quote_items = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_items WHERE id_re_quote = :id_re_quote';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
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

  public static function get_re_quote_item_by_id($connection, $id_re_quote_item){
    $re_quote_item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_items WHERE id = :id_re_quote_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote_item = new ReQuoteItem($result['id'], $result['id_re_quote'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_item;
  }

  public static function update_re_quote_item($connection, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website, $id_re_quote_item){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_items SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_re_quote_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':brand', $brand, PDO::PARAM_STR);
        $sentence-> bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
        $sentence-> bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $sentence-> bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $sentence-> bindParam(':description', $description, PDO::PARAM_STR);
        $sentence-> bindParam(':description_project', $description_project, PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $sentence-> bindParam(':comments', $comments, PDO::PARAM_STR);
        $sentence-> bindParam(':website', $website, PDO::PARAM_STR);
        $sentence-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_item($connection, $id_re_quote_item){
    if(isset($connection)){
      try{
        $connection-> beginTransaction();
        $sql1 = 'DELETE FROM re_quote_providers WHERE id_re_quote_item = :id_re_quote_item';
        $sentence1 = $connection-> prepare($sql1);
        $sentence1-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence1-> execute();
        $sql2 = 'DELETE FROM re_quote_items WHERE id = :id_re_quote_item';
        $sentence2 = $connection-> prepare($sql2);
        $sentence2-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence2-> execute();
        $connection-> commit();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
        $connection-> rollBack();
      }
    }
  }

  public static function insert_calc($connection, $unit_price, $total_price, $additional, $id_re_quote_item){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_items SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_re_quote_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentence-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $sentence-> bindParam(':additional', $additional, PDO::PARAM_STR);
        $sentence-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
