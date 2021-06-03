<?php
class RepositorioItem {
  public static function insertar_item($conexion, $item) {
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO item(id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_rfq, :id_usuario, :provider_menor, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_rfq', $item->obtener_id_rfq(), PDO::PARAM_STR);
        $sentencia->bindParam(':id_usuario', $item->obtener_id_usuario(), PDO::PARAM_STR);
        $sentencia->bindParam(':provider_menor', $item->obtener_provider_menor(), PDO::PARAM_STR);
        $sentencia->bindParam(':brand', $item->obtener_brand(), PDO::PARAM_STR);
        $sentencia->bindParam(':brand_project', $item->obtener_brand_project(), PDO::PARAM_STR);
        $sentencia->bindParam(':part_number', $item->obtener_part_number(), PDO::PARAM_STR);
        $sentencia->bindParam(':part_number_project', $item->obtener_part_number_project(), PDO::PARAM_STR);
        $sentencia->bindParam(':description', $item->obtener_description(), PDO::PARAM_STR);
        $sentencia->bindParam(':description_project', $item->obtener_description_project(), PDO::PARAM_STR);
        $sentencia->bindParam(':quantity', $item->obtener_quantity(), PDO::PARAM_STR);
        $sentencia->bindParam(':unit_price', $item->obtener_unit_price(), PDO::PARAM_STR);
        $sentencia->bindParam(':total_price', $item->obtener_total_price(), PDO::PARAM_STR);
        $sentencia->bindParam(':comments', $item->obtener_comments(), PDO::PARAM_STR);
        $sentencia->bindParam(':website', $item->obtener_website(), PDO::PARAM_STR);
        $sentencia->bindParam(':additional', $item->obtener_additional(), PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        $id = $conexion->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function actualizar_provider_menor_item($conexion, $provider_menor, $id_item){
    $item_editado = false;
    if(isset($conexion)){
      try{
        $sql = 'UPDATE item SET provider_menor = :provider_menor WHERE id = :id_item';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':provider_menor', $provider_menor, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia-> execute();
        if($sentencia){
          $item_editado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item_editado;
  }

  public static function obtener_items_por_id_rfq($conexion, $id_rfq) {
    $items = [];
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM item WHERE id_rfq = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchall(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $items[] = new Item($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['provider_menor'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['unit_price'], $fila['total_price'], $fila['comments'], $fila['website'], $fila['additional'], $fila['fulfillment_profit']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function items_exists($connection, $id_rfq) {
    $items = 0;
    if (isset($connection)) {
      try {
        $sql = "SELECT COUNT(*) as items FROM item WHERE id_rfq = :id_rfq";
        $sentence = $connection->prepare($sql);
        $sentence->bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $items = $result['items'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function escribir_item($item, $i, $numeracion) {
    if (!isset($item)) {
      return;
    }
    $j = $i;
    Conexion::abrir_conexion();
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
    Conexion::cerrar_conexion();
    echo '<tr id="item' . $item->obtener_id() . '">';
    echo '<td><a href="' . ADD_PROVIDER . '/' . $item->obtener_id() . '" class="btn btn-warning btn-block"><i class="fa fa-plus-circle"></i> Add Provider</a><br><a href="' . EDIT_ITEM . '/' . $item->obtener_id() . '" class="btn btn-warning btn-block"><i class="fa fa-edit"></i> Edit item</a><br><a href="' . DELETE_ITEM . '/' . $item-> obtener_id() . '" class="delete_item_button btn btn-warning btn-block"><i class="fa fa-trash"></i> Delete</a><br><a href="' . ADD_SUBITEM . '/' . $item-> obtener_id() . '" class="btn btn-warning btn-block"><i class="fa fa-plus-circle"></i> Add subitem</a></td>';
    echo '<td>' . $numeracion . '</td>';
    if(strlen($item-> obtener_description_project()) >= 100){
      echo '<td><b>Brand:</b> ' . $item->obtener_brand_project() . '<br><b>Part #:</b> ' . $item->obtener_part_number_project() . '<br><b>Description:</b> ' . nl2br(mb_substr($item->obtener_description_project(), 0, 100)) . ' ...</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $item->obtener_brand_project() . '<br><b>Part #:</b> ' . $item->obtener_part_number_project() . '<br><b>Description:</b> ' . nl2br($item->obtener_description_project()) . '</td>';
    }
    if(strlen($item-> obtener_description()) >= 100){
      echo '<td><b>Brand:</b> ' . $item->obtener_brand() . '<br><b>Part #:</b> ' . $item->obtener_part_number() . '<br><b>Description:</b> ' . nl2br(mb_substr($item->obtener_description(), 0, 100)) . ' ...</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $item->obtener_brand() . '<br><b>Part #:</b> ' . $item->obtener_part_number() . '<br><b>Description:</b> ' . nl2br($item->obtener_description()) . '</td>';
    }
    echo '<td class="estrechar"><a target="_blank" href="'. $item-> obtener_website() .'">'. $item-> obtener_website() .'</a></td>';
    echo '<td>' . $item->obtener_quantity() . '</td>';
    echo '<td><div class="row"><div class="col-6">';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      if(strlen($provider-> obtener_provider()) >= 10){
        echo '<a href="' . EDIT_PROVIDER . '/' . $provider->obtener_id() . '"><b>' . mb_substr($provider->obtener_provider(), 0, 10) . '... :</b></a><br>';
      }else{
        echo '<a href="' . EDIT_PROVIDER . '/' . $provider->obtener_id() . '"><b>' . $provider->obtener_provider() . ':</b></a><br>';
      }
    }
    echo '</div><div class="col-6">';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      echo '$ ' . $provider->obtener_price() . '<br>';
    }
    echo '</div></div></td>';
    if($item-> obtener_additional() != 0){
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="'.$item-> obtener_additional().'"></td>';
    }else{
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="0"></td>';
    }
    echo '<td>';
    for ($i = 0; $i < count($providers); $i++) {
      $provider = $providers[$i];
      $precios[$i] = $provider->obtener_price();
    }
    if (!empty($precios)) {
      $best_unit_price = min($precios);
      for($i = 0;$i < count($precios); $i++){
        if($best_unit_price == $precios[$i]){
          Conexion::abrir_conexion();
          self::actualizar_provider_menor_item(Conexion::obtener_conexion(), $providers[$i]->obtener_id(), $item-> obtener_id());
          Conexion::cerrar_conexion();
        }
      }
      echo '$ ' . $best_unit_price;
    }
    echo '</td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td>' . nl2br($item->obtener_comments()) . '</td>';
    echo '</tr>';
    $j = RepositorioSubitem::escribir_subitems($item-> obtener_id(), $j);
    return $j;
  }

  public static function escribir_items($id_rfq) {
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = self::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    ?>
    <br><h2 id="caja_items">Items:</h2>
    <?php
    if (count($items)) {
      ?>
      <br>
      <a target="_blank" href="<?php echo PDF_TABLA_ITEMS . $id_rfq; ?>" class="ml-2 float-right btn btn-primary"><i class="fa fa-file"></i> PDF</a>
      <?php
      if($re_quote_exists){
        ?>
        <a target="_blank" href="<?php echo EXCEL_ITEMS_TABLE . $id_rfq; ?>" class="float-right btn btn-primary"><i class="fa fa-file"></i> EXCEL</a>
        <?php
      }
      ?>
      <div class="row">
        <div class="col-md-3">
          <label>Taxes (%):</label>
          <input type="hidden" name="taxes_original" value="<?php echo $cotizacion->obtener_taxes(); ?>">
          <input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="<?php echo $cotizacion->obtener_taxes(); ?>">
        </div>
        <div class="col-md-3">
          <label>Profit (%):</label>
          <input type="hidden" name="profit_original" value="<?php echo $cotizacion->obtener_profit(); ?>">
          <input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="<?php echo $cotizacion->obtener_profit(); ?>">
        </div>
        <div class="col-md-3">
          <label>Additional general ($):</label>
          <input type="hidden" name="additional_general_original" value="<?php echo $cotizacion->obtener_additional(); ?>">
          <input type="text" name="additional_general" id="additional_general" class="form-control form-control-sm" value="<?php echo $cotizacion->obtener_additional(); ?>">
        </div>
        <div class="col-md-3">
          <label>Payment terms:</label>
          <div class="custom-control custom-radio">
            <input type="radio" id="net_30" name="payment_terms" class="custom-control-input" value="Net 30" <?php echo $cotizacion->obtener_payment_terms() == 'Net 30' ? 'checked': ''; ?>>
            <label class="custom-control-label" for="net_30">Net 30</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="net_30cc" name="payment_terms" class="custom-control-input" value="Net 30/CC" <?php echo $cotizacion->obtener_payment_terms() == 'Net 30/CC' ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="net_30cc">Net 30/CC</label>
          </div>
          <input type="hidden" name="payment_terms_original" value="<?php echo $cotizacion-> obtener_payment_terms(); ?>">
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
          for ($i = 0; $i < count($items); $i++) {
            $item = $items[$i];
            $k = self::escribir_item($item, $k, $i+1);
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
      $id_items = '';
      $id_subitems = '';
      $contador_subitems = 0;
      for($i = 0; $i < count($items); $i++){
        $item = $items[$i];
        if($i == 0){
          $id_items = $id_items . $item-> obtener_id();
        }else{
          $id_items = $id_items . ',' . $item-> obtener_id();
        }
        Conexion::abrir_conexion();
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
        Conexion::cerrar_conexion();
        for($j = 0; $j < count($subitems); $j++){
          $subitem = $subitems[$j];
          if($contador_subitems == 0){
            $id_subitems = $id_subitems . $subitem-> obtener_id();
          }else{
            $id_subitems = $id_subitems . ',' . $subitem-> obtener_id();
          }
          $contador_subitems++;
        }
      }
      ?>
      <input type="hidden" id="id_items" name="id_items" value="'.$id_items.'">
      <input type="hidden" id="id_subitems" name="id_subitems" value="'.$id_subitems.'">
      <input type="hidden" id="partes_total_price" name="partes_total_price" value="">
      <input type="hidden" id="partes_total_price_subitems" name="partes_total_price_subitems" value="">
      <input type="hidden" id="additional" name="additional" value="">
      <input type="hidden" id="additional_subitems" name="additional_subitems" value="">
      <input type="hidden" id="unit_prices" name="unit_prices" value="">
      <input type="hidden" id="unit_prices_subitems" name="unit_prices_subitems" value="">
      <input type="hidden" id="total_cost" name="total_cost" value="">
      <input type="hidden" id="total_price" name="total_price" value="">
      <br>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="1" id="shipping" name="shipping" placeholder="Enter shipping ..."><?php echo $cotizacion->obtener_shipping(); ?></textarea>
            <input type="hidden" name="shipping_original" value="<?php echo $cotizacion->obtener_shipping(); ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="<?php echo $cotizacion->obtener_shipping_cost(); ?>">
            <input type="hidden" name="shipping_cost_original" value="<?php echo $cotizacion->obtener_shipping_cost(); ?>">
          </div>
        </div>
      </div>
      <?php
    }
  }

  public static function obtener_item_por_id($conexion, $id_item) {
    $item = null;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM item WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch();
        if (!empty($resultado)) {
          $item = new Item($resultado['id'], $resultado['id_rfq'], $resultado['id_usuario'], $resultado['provider_menor'], $resultado['brand'], $resultado['brand_project'], $resultado['part_number'], $resultado['part_number_project'], $resultado['description'], $resultado['description_project'], $resultado['quantity'], $resultado['unit_price'], $resultado['total_price'], $resultado['comments'], $resultado['website'], $resultado['additional'], $resultado['fulfillment_profit']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function actualizar_item($conexion, $id_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website) {
    $item_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE item SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindParam(':brand', $brand, PDO::PARAM_STR);
        $sentencia->bindParam(':brand_project', $brand_project, PDO::PARAM_STR);
        $sentencia->bindParam(':part_number', $part_number, PDO::PARAM_STR);
        $sentencia->bindParam(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $sentencia->bindParam(':description', $description, PDO::PARAM_STR);
        $sentencia->bindParam(':description_project', $description_project, PDO::PARAM_STR);
        $sentencia->bindParam(':quantity', $quantity, PDO::PARAM_STR);
        $sentencia->bindParam(':comments', $comments, PDO::PARAM_STR);
        $sentencia->bindParam(':website', $website, PDO::PARAM_STR);
        $sentencia->bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia->execute();
        if ($sentencia) {
          $item_editado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item_editado;
  }

  public static function insertar_calculos($conexion, $unit_price, $total_price, $additional, $id_item){
    $item_editado = false;
    if(isset($conexion)){
      try{
        $sql = 'UPDATE item SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_item';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentencia-> bindParam(':total_price', $total_price, PDO::PARAM_STR);
        $sentencia-> bindParam(':additional', $additional, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia-> execute();
        if($sentencia){
          $item_editado = true;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item_editado;
  }

  public static function set_fulfillment_profit($conexion, $fulfillment_profit, $id_item){
    if(isset($conexion)){
      try{
        $sql = 'UPDATE item SET fulfillment_profit = :fulfillment_profit WHERE id = :id_item';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindParam(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $sentencia-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_item($conexion, $id_item){
    if(isset($conexion)){
      try{
        $conexion -> beginTransaction();
        $sql1 = "DELETE FROM provider WHERE id_item = :id_item";
        $sentencia1 = $conexion-> prepare($sql1);
        $sentencia1-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia1-> execute();
        $sql2 = "DELETE FROM item WHERE id = :id_item";
        $sentencia2 = $conexion-> prepare($sql2);
        $sentencia2-> bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia2-> execute();
        $conexion-> commit();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
        $conexion-> rollBack();
      }
    }
  }
}
?>
