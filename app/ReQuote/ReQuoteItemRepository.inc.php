<?php
class ReQuoteItemRepository {
  public static function insert_re_quote_item($connection, $re_quote_item) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO re_quote_items(id_re_quote, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_re_quote, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $re_quote_item->get_id_re_quote(), PDO::PARAM_STR);
        $sentence->bindValue(':brand', $re_quote_item->get_brand(), PDO::PARAM_STR);
        $sentence->bindValue(':brand_project', $re_quote_item->get_brand_project(), PDO::PARAM_STR);
        $sentence->bindValue(':part_number', $re_quote_item->get_part_number(), PDO::PARAM_STR);
        $sentence->bindValue(':part_number_project', $re_quote_item->get_part_number_project(), PDO::PARAM_STR);
        $sentence->bindValue(':description', $re_quote_item->get_description(), PDO::PARAM_STR);
        $sentence->bindValue(':description_project', $re_quote_item->get_description_project(), PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $re_quote_item->get_quantity(), PDO::PARAM_STR);
        $sentence->bindValue(':unit_price', $re_quote_item->get_unit_price(), PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $re_quote_item->get_total_price(), PDO::PARAM_STR);
        $sentence->bindValue(':comments', $re_quote_item->get_comments(), PDO::PARAM_STR);
        $sentence->bindValue(':website', $re_quote_item->get_website(), PDO::PARAM_STR);
        $sentence->bindValue(':additional', $re_quote_item->get_additional(), PDO::PARAM_STR);
        $sentence->execute();
        $id = $connection->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function print_re_quote_items($id_re_quote) {
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id(Conexion::obtener_conexion(), $id_re_quote);
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $re_quote->get_id_rfq());
    $re_quote_items = self::get_re_quote_items_by_id_re_quote(Conexion::obtener_conexion(), $id_re_quote);
    $items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $re_quote->get_id_rfq());
    Conexion::cerrar_conexion();
    if (count($re_quote_items)) {
      if (count($items) != count($re_quote_items)) :
?>
        <div class="alert alert-danger" role="alert">
          <i class="fas fa-exclamation-triangle"></i> Attention: The quantity of items in the initial quote has been modified, leading to an outdated RFQ Re Quote. Please review and <b>RELOAD</b> to ensure accuracy.
        </div>
      <?php endif; ?>
      <br>
      <a target="_blank" href="<?= PDF_RE_QUOTE . $re_quote->get_id_rfq(); ?>" class="btn btn-primary float-right"><i class="fa fa-file"></i> PDF</a>
      <h2>Items:</h2>
      <div class="p-3">
        <div class="custom-control custom-radio">
          <input type="radio" id="net_30" name="payment_terms" class="custom-control-input" value="Net 30" <?= $re_quote->get_payment_terms() == 'Net 30' ? 'checked' : ''; ?>>
          <label class="custom-control-label" for="net_30">Net 30</label>
        </div>
        <div class="custom-control custom-radio">
          <input type="radio" id="net_30_cc" name="payment_terms" class="custom-control-input" value="Net 30/CC" <?= $re_quote->get_payment_terms() == 'Net 30/CC' ? 'checked' : ''; ?>>
          <label class="custom-control-label" for="net_30_cc">Net 30/CC</label>
        </div>
        <input type="hidden" name="payment_terms_original" value="<?= $re_quote->get_payment_terms(); ?>">
      </div>
      <div class="table-responsive">
        <table id="requote_table" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th class="options">Options</th>
              <th id="numeracion">#</th>
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
            foreach ($items as $key => $item) {
              $re_quote_item = $re_quote_items[$key];
              $k = self::print_re_quote_item($re_quote_item, $item, $k, $key + 1);
            }
            ?>
            <td colspan="5" class="display-4"><b>
                <h4>TOTAL:</h4>
              </b></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="total_re_quote"></td>
            <td></td>
            <td id="total_ganado"><?= '$ ' . $quote->obtener_total_price(); ?></td>
            <td id="profit_rq"></td>
          </tbody>
        </table>
      </div>
      <?php
      $id_items = [];
      $id_subitems = [];
      $contador_subitems = 0;
      foreach ($re_quote_items as $key => $re_quote_item) {
        $id_items[] = $re_quote_item->get_id();
        Conexion::abrir_conexion();
        $re_quote_subitems = ReQuoteSubitemRepository::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item->get_id());
        Conexion::cerrar_conexion();
        foreach ($re_quote_subitems as $key => $re_quote_subitem) {
          $id_subitems[] = $re_quote_subitem->get_id();
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
            <textarea class="form-control form-control-sm" rows="1" name="shipping" id="shipping_rq" placeholder="Enter shipping ..."><?= $re_quote->get_shipping(); ?></textarea>
            <input type="hidden" name="shipping_original" value="<?= $re_quote->get_shipping(); ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost_rq" name="shipping_cost" value="<?= $re_quote->get_shipping_cost(); ?>">
            <input type="hidden" name="shipping_cost_original" value="<?= $re_quote->get_shipping_cost(); ?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <textarea class="form-control form-control-sm" rows="1" name="shipping" id="shipping" disabled placeholder="Enter shipping ..."><?= $quote->obtener_shipping(); ?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" disabled name="shipping_cost" value="<?= $quote->obtener_shipping_cost(); ?>">
          </div>
        </div>
      </div>
    <?php
    }
  }

  public static function print_re_quote_item($re_quote_item, $item, $i, $number) {
    if (!isset($re_quote_item)) {
      return;
    }
    $j = $i;
    Conexion::abrir_conexion();
    $re_quote_providers = ReQuoteProviderRepository::get_re_quote_providers_by_id_re_quote_item(Conexion::obtener_conexion(), $re_quote_item->get_id());
    Conexion::cerrar_conexion();
    ?>
    <tr id="<?= 'item' . $re_quote_item->get_id(); ?>">
      <td>
        <a href="<?= ADD_RE_QUOTE_PROVIDER . $re_quote_item->get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add Provider
        </a>
        <br>
        <a href="<?= EDIT_RE_QUOTE_ITEM . $re_quote_item->get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-edit"></i> Edit item
        </a>
        <br>
        <!-- <a href="<?= DELETE_RE_QUOTE_ITEM . $re_quote_item->get_id(); ?>" class="delete_item_button btn btn-warning btn-block">
          <i class="fa fa-trash"></i> Delete
        </a> -->
        <br>
        <!-- <a href="<?= ADD_RE_QUOTE_SUBITEM . $re_quote_item->get_id(); ?>" class="btn btn-warning btn-block">
          <i class="fa fa-plus-circle"></i> Add subitem
        </a> -->
        <br>
      </td>
      <td><?= $number; ?></td>
      <?php
      if (strlen($re_quote_item->get_description_project()) >= 100) {
      ?>
        <td>
          <b>Brand:</b> <?= $re_quote_item->get_brand_project(); ?>
          <br>
          <b>Part #:</b> <?= $re_quote_item->get_part_number_project(); ?>
          <br>
          <b>Description:</b> <?= nl2br(mb_substr($re_quote_item->get_description_project(), 0, 100)); ?> ...
        </td>
      <?php
      } else {
      ?>
        <td>
          <b>Brand:</b> <?= $re_quote_item->get_brand_project(); ?>
          <br>
          <b>Part #:</b> <?= $re_quote_item->get_part_number_project(); ?>
          <br>
          <b>Description:</b> <?= nl2br($re_quote_item->get_description_project()); ?>
        </td>
      <?php
      }
      if (strlen($re_quote_item->get_description()) >= 100) {
      ?>
        <td>
          <b>Brand:</b> <?= $re_quote_item->get_brand(); ?>
          <br>
          <b>Part #:</b> <?= $re_quote_item->get_part_number(); ?>
          <br>
          <b>Description:</b> <?= nl2br(mb_substr($re_quote_item->get_description(), 0, 100)); ?> ...
        </td>
      <?php
      } else {
      ?>
        <td>
          <b>Brand:</b> <?= $re_quote_item->get_brand(); ?>
          <br>
          <b>Part #:</b> <?= $re_quote_item->get_part_number(); ?>
          <br>
          <b>Description:</b> <?= nl2br($re_quote_item->get_description()); ?>
        </td>
      <?php
      }
      ?>
      <td class="estrechar">
        <a target="_blank" href="<?= $re_quote_item->get_website(); ?>"><?= $re_quote_item->get_website(); ?></a>
      </td>
      <td><?= $re_quote_item->get_quantity(); ?></td>
      <td>
        <div class="row">
          <div class="col-6">
            <?php
            if (count($re_quote_providers)) {
              foreach ($re_quote_providers as $key => $re_quote_provider) {
                if (strlen($re_quote_provider->get_provider()) >= 10) {
            ?>
                  <a href="<?= EDIT_RE_QUOTE_PROVIDER . $re_quote_provider->get_id(); ?>">
                    <b><?= mb_substr($re_quote_provider->get_provider(), 0, 10); ?>... :</b>
                  </a>
                  <br>
                <?php
                } else {
                ?>
                  <a href="<?= EDIT_RE_QUOTE_PROVIDER . $re_quote_provider->get_id(); ?>">
                    <b><?= $re_quote_provider->get_provider(); ?>:</b>
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
            if (count($re_quote_providers)) {
              foreach ($re_quote_providers as $key => $re_quote_provider) {
                echo '$ ' . $re_quote_provider->get_price() . '<br>';
              }
            }
            ?>
          </div>
        </div>
      </td>
      <?php
      echo '<td>';
      $prices = [];
      if (count($re_quote_providers)) {
        foreach ($re_quote_providers as $key => $re_quote_provider) {
          $prices[$key] = $re_quote_provider->get_price();
        }
      }
      if (!empty($prices)) {
        $best_unit_price = min($prices);
        echo '$ ' . $best_unit_price;
      }
      echo '</td>';
      ?>
      <td><?= '$ ' . $best_unit_price * $re_quote_item->get_quantity(); ?></td>
      <td><?php if (!is_null($item)) {
            echo '$ ' . $item->obtener_unit_price();
          } ?></td>
      <td><?php if (!is_null($item)) {
            echo '$ ' . $item->obtener_total_price();
          } ?></td>
      <td><?= nl2br($re_quote_item->get_comments()); ?></td>
    </tr>
<?php
    if (!is_null($item)) {
      $j = ReQuoteSubitemRepository::print_re_quote_subitems($re_quote_item->get_id(), $item->obtener_id(), $j);
    } else {
      $j = ReQuoteSubitemRepository::print_re_quote_subitems($re_quote_item->get_id(), '', $j);
    }
    return $j;
  }

  public static function get_re_quote_items_by_id_re_quote($connection, $id_re_quote) {
    $re_quote_items = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM re_quote_items WHERE id_re_quote = :id_re_quote';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote', $id_re_quote, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetchAll(PDO::FETCH_ASSOC);
        if (count($result)) {
          foreach ($result as $key => $row) {
            $re_quote_items[] = new ReQuoteItem($row['id'], $row['id_re_quote'], $row['brand'], $row['brand_project'], $row['part_number'], $row['part_number_project'], $row['description'], $row['description_project'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['comments'], $row['website'], $row['additional']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_items;
  }

  public static function get_re_quote_item_by_id($connection, $id_re_quote_item) {
    $re_quote_item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM re_quote_items WHERE id = :id_re_quote_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $re_quote_item = new ReQuoteItem($result['id'], $result['id_re_quote'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_item;
  }

  public static function update_re_quote_item($connection, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website, $id_re_quote_item) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE re_quote_items SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_re_quote_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':brand', $brand, PDO::PARAM_STR);
        $sentence->bindValue(':brand_project', $brand_project, PDO::PARAM_STR);
        $sentence->bindValue(':part_number', $part_number, PDO::PARAM_STR);
        $sentence->bindValue(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $sentence->bindValue(':description', $description, PDO::PARAM_STR);
        $sentence->bindValue(':description_project', $description_project, PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentence->bindValue(':comments', $comments, PDO::PARAM_STR);
        $sentence->bindValue(':website', $website, PDO::PARAM_STR);
        $sentence->bindValue(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_item($connection, $id_re_quote_item) {
    if (isset($connection)) {
      try {
        $connection->beginTransaction();
        $sql1 = 'DELETE FROM re_quote_providers WHERE id_re_quote_item = :id_re_quote_item';
        $sentence1 = $connection->prepare($sql1);
        $sentence1->bindValue(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence1->execute();
        $sql2 = 'DELETE FROM re_quote_items WHERE id = :id_re_quote_item';
        $sentence2 = $connection->prepare($sql2);
        $sentence2->bindValue(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence2->execute();
        $connection->commit();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
        $connection->rollBack();
      }
    }
  }

  public static function insert_calc($connection, $unit_price, $total_price, $additional, $id_re_quote_item) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE re_quote_items SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_re_quote_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentence->bindValue(':total_price', $total_price, PDO::PARAM_STR);
        $sentence->bindValue(':additional', $additional, PDO::PARAM_STR);
        $sentence->bindValue(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>