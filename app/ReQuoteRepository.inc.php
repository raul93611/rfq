<?php
class ReQuoteRepository{
  public static function re_quote_exists($connection, $id_rfq){
    $re_quote_exists = true;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quotes WHERE id_rfq = :id_rfq';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          $re_quote_exists = true;
        }else{
          $re_quote_exists = false;
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_exists;
  }

  public static function insert_re_quote($connection, $re_quote){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quotes(id_rfq, total_cost, total_price, payment_terms, taxes, profit, additional, shipping_cost, shipping) VALUES(:id_rfq, :total_cost, :total_price, :payment_terms, :taxes, :profit, :additional, :shipping_cost, :shipping)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $re_quote-> get_id_rfq(), PDO::PARAM_STR);
        $sentence-> bindParam(':total_cost', $re_quote-> get_total_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':total_price', $re_quote-> get_total_price(), PDO::PARAM_STR);
        $sentence-> bindParam(':payment_terms', $re_quote-> get_payment_terms(), PDO::PARAM_STR);
        $sentence-> bindParam(':taxes', $re_quote-> get_taxes(), PDO::PARAM_STR);
        $sentence-> bindParam(':profit', $re_quote-> get_profit(), PDO::PARAM_STR);
        $sentence-> bindParam(':additional', $re_quote-> get_additional(), PDO::PARAM_STR);
        $sentence-> bindParam(':shipping_cost', $re_quote-> get_shipping_cost(), PDO::PARAM_STR);
        $sentence-> bindParam(':shipping', $re_quote-> get_shipping(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_re_quote_by_id_rfq($connection, $id_rfq){
    $re_quote = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quotes WHERE id_rfq = :id_rfq';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote = new ReQuote($result['id'], $result['id_rfq'], $result['total_cost'], $result['total_price'], $result['payment_terms'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping_cost'], $result['shipping']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote;
  }

  public static function get_re_quote_by_id($connection, $id_re_quote){
    $re_quote = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quotes WHERE id = :id_re_quote';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote = new ReQuote($result['id'], $result['id_rfq'], $result['total_cost'], $result['total_price'], $result['payment_terms'], $result['taxes'], $result['profit'], $result['additional'], $result['shipping_cost'], $result['shipping']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote;
  }

  public static function update_re_quote($connection, $payment_terms, $total_cost, $shipping, $shipping_cost, $id_re_quote){
    if(isset($connection)){
      echo $total_cost;
      try{
        $sql = 'UPDATE re_quotes SET payment_terms = :payment_terms, total_cost = :total_cost, shipping = :shipping, shipping_cost = :shipping_cost WHERE id = :id_re_quote';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':payment_terms', $payment_terms, PDO::PARAM_STR);
        $sentence-> bindParam(':total_cost', $total_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':shipping', $shipping, PDO::PARAM_STR);
        $sentence-> bindParam(':shipping_cost', $shipping_cost, PDO::PARAM_STR);
        $sentence-> bindParam(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function print_final_re_quote($id_rfq){
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $re_quote = self::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $re_quote_items = ReQuoteItemRepository::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $re_quote-> get_id());
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    if (count($re_quote_items)) {
      ?>
      <table class="table table-bordered" style="width:100%;">
        <tr>
          <th class="quantity">#</th>
          <th>PROJECT ESPC.</th>
          <th>E-LOGIC PROP.</th>
          <th class="quantity">QTY</th>
          <th>PROVIDER</th>
          <th>BEST UNIT COST</th>
          <th>TOTAL COST</th>
          <th>PRICE FOR CLIENT</th>
          <th>TOTAL PRICE</th>
        </tr>
      <?php
      $a = 1;
      for ($i = 0; $i < count($re_quote_items); $i++) {
        $re_quote_item = $re_quote_items[$i];
        $item = $items[$i];
        ?>
        <tr>
          <td><?php echo $a; ?></td>
          <td><b>Brand name:</b><?php echo $re_quote_item-> get_brand_project(); ?><br><b>Part number:</b><?php echo $re_quote_item-> get_part_number_project(); ?><br><b>Item description:</b><?php echo nl2br(mb_substr($re_quote_item-> get_description_project(), 0, 150)); ?></td>
          <td><b>Brand name:</b><?php echo $re_quote_item-> get_brand(); ?><br><b>Part number:</b><?php echo $re_quote_item-> get_part_number(); ?><br><b> Item description:</b><br><?php echo nl2br(mb_substr($re_quote_item-> get_description(), 0, 150)); ?></td>
          <td style="text-align:right;"><?php echo $re_quote_item-> get_quantity(); ?></td>
          <td style="width: 200px;">
        <?php
        Conexion::abrir_conexion();
        $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
        Conexion::cerrar_conexion();
        $prices = [];
        if(count($re_quote_providers)){
          foreach ($re_quote_providers as $re_quote_provider) {
            $prices[] = $re_quote_provider-> get_price();
            ?>
            <div class="row">
              <div class="col-md-6">
                <b><?php echo $re_quote_provider-> get_provider(); ?>:</b>
              </div>
              <div class="col-md-6">
                $ <?php echo number_format($re_quote_provider-> get_price(), 2); ?>
              </div>
            </div>
            <?php
          }
          ?>
          </td>
          <td>$
          <?php
          $best_unit_price = min($prices);
          echo number_format($best_unit_price, 2);
          ?>
          </td>
          <td>$ <?php echo number_format(round($best_unit_price, 2) * $re_quote_item-> get_quantity(), 2); ?></td>
          <td style="text-align:right;">$ <?php if(!is_null($item)){echo number_format($item-> obtener_unit_price(), 2);} ?></td>
          <td style="text-align:right;">$ <?php if(!is_null($item)){echo number_format($item-> obtener_total_price(), 2);} ?></td>
          <?php
        }else{
          ?>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <?php
        }
        ?>
      </tr>
        <?php
        Conexion::abrir_conexion();
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item-> get_id());
        if(!is_null($item)){
          $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item-> obtener_id());
        }else{
          $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), '');
        }
        Conexion::cerrar_conexion();
        for($j = 0; $j < count($re_quote_subitems); $j++){
          $re_quote_subitem = $re_quote_subitems[$j];
          $subitem = $subitems[$j];
          ?>
      <tr>
        <td></td>
        <td><b>Brand name:</b><?php echo $re_quote_subitem-> get_brand_project(); ?><br><b>Part number:</b><?php echo $re_quote_subitem-> get_part_number_project(); ?><br><b>Item description:</b><br><?php echo nl2br(mb_substr($re_quote_subitem-> get_description_project(), 0, 150)); ?></td>
        <td><b>Brand name:</b><?php echo $re_quote_subitem-> get_brand(); ?><br><b>Part number:</b> <?php echo $re_quote_item-> get_part_number(); ?><br><b> Item description:</b><br> <?php echo nl2br(mb_substr($re_quote_item-> get_description(), 0, 150)); ?></td>
        <td style="text-align:right;"><?php echo $re_quote_subitem-> get_quantity(); ?></td>
          <?php
            Conexion::abrir_conexion();
            $re_quote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem-> get_id());
            Conexion::cerrar_conexion();
            if(count($re_quote_subitem_providers)){
              ?>
        <td>
              <?php
              $prices = [];
              if(count($re_quote_subitem_providers)){
                foreach ($re_quote_subitem_providers as $re_quote_subitem_provider) {
                  $prices[] = $re_quote_subitem_provider-> get_price();
                  ?>
                  <div class="row">
                    <div class="col-md-6">
                      <b><?php echo $re_quote_subitem_provider-> get_provider(); ?>:</b>
                    </div>
                    <div class="col-md-6">
                      $ <?php echo $re_quote_subitem_provider-> get_price(); ?>
                    </div>
                  </div>
                  <?php
                }
              }
              ?>
        </td>
        <td>$
                <?php
                $best_unit_price = min($prices);
                echo number_format($best_unit_price, 2);
                ?>
        </td>
        <td>$ <?php echo number_format(round($best_unit_price, 2) * $re_quote_subitem-> get_quantity(), 2); ?></td>
        <td style="text-align:right;">$ <?php if(!is_null($subitem)){echo number_format($subitem-> obtener_unit_price(), 2);} ?></td>
        <td style="text-align:right;">$ <?php if(!is_null($subitem)){echo number_format($subitem-> obtener_total_price(), 2);} ?></td>
                <?php
              }else{
                ?>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
                <?php
              }
              ?>
      </tr>
              <?php
            }
            $a++;
          }
    ?>
      <tr>
        <td style="border:none;"></td>
        <td colspan="5" style="font-size:10pt;"><?php echo nl2br($re_quote-> get_shipping()); ?></td>
        <td style="text-align:right;">$ <?php echo number_format($re_quote-> get_shipping_cost(), 2); ?></td>
        <td></td>
        <td style="text-align:right;">$ <?php echo number_format($cotizacion-> obtener_shipping_cost(), 2); ?></td>
      </tr>
      <tr>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="border:none;"></td>
        <td style="font-size:12pt;">TOTAL:</td>
        <td>$ <?php echo number_format($re_quote-> get_total_cost(), 2); ?></td>
        <td></td>
        <td style="font-size:12pt;text-align:right;">$ <?php echo number_format($cotizacion-> obtener_total_price(), 2); ?></td>
      </tr>
    </table>
    <?php
    }
  }
}
?>
