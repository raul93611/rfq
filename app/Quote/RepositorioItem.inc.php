<?php
class RepositorioItem {
  public static function insertar_item($conexion, $item) {
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO item(id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_rfq, :id_usuario, :provider_menor, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $item->obtener_id_rfq(), PDO::PARAM_STR);
        $sentencia->bindValue(':id_usuario', $item->obtener_id_usuario(), PDO::PARAM_STR);
        $sentencia->bindValue(':provider_menor', $item->obtener_provider_menor(), PDO::PARAM_STR);
        $sentencia->bindValue(':brand', $item->obtener_brand(), PDO::PARAM_STR);
        $sentencia->bindValue(':brand_project', $item->obtener_brand_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':part_number', $item->obtener_part_number(), PDO::PARAM_STR);
        $sentencia->bindValue(':part_number_project', $item->obtener_part_number_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':description', $item->obtener_description(), PDO::PARAM_STR);
        $sentencia->bindValue(':description_project', $item->obtener_description_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':quantity', $item->obtener_quantity(), PDO::PARAM_STR);
        $sentencia->bindValue(':unit_price', $item->obtener_unit_price(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_price', $item->obtener_total_price(), PDO::PARAM_STR);
        $sentencia->bindValue(':comments', $item->obtener_comments(), PDO::PARAM_STR);
        $sentencia->bindValue(':website', $item->obtener_website(), PDO::PARAM_STR);
        $sentencia->bindValue(':additional', $item->obtener_additional(), PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        $id = $conexion->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function updateMinorProvider($conexion, $id_item) {
    if (isset($conexion)) {
      try {
        $sql = "
        UPDATE item AS i
        JOIN (
          SELECT id, id_item, price
		      FROM provider
		      WHERE price = (SELECT MIN(price) FROM provider WHERE id_item = {$id_item})
		      AND id_item = {$id_item}
        ) AS min_providers ON i.id = min_providers.id_item
        SET i.provider_menor = min_providers.id
        WHERE i.id = {$id_item};
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function updateItemsPrices($conexion, $id_rfq) {
    if (isset($conexion)) {
      try {
        $sql = "
        UPDATE item AS i
        JOIN rfq ON i.id_rfq = rfq.id
        JOIN (
          SELECT id_item, MIN(price) AS min_price
          FROM provider
          GROUP BY id_item
        ) AS min_providers ON i.id = min_providers.id_item
        SET i.unit_price = 
          CASE 
              WHEN rfq.payment_terms = 'Net 30' THEN min_providers.min_price * 1
              WHEN rfq.payment_terms = 'Net 30/CC' THEN min_providers.min_price * 1.0298661174047374
          END,
          i.total_price = 
          CASE 
              WHEN rfq.payment_terms = 'Net 30' THEN min_providers.min_price * 1 * i.quantity
              WHEN rfq.payment_terms = 'Net 30/CC' THEN min_providers.min_price * 1.0298661174047374 * i.quantity
          END
        WHERE rfq.id = :id_rfq;
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function updateItemPrice($conexion, $id_item) {
    if (isset($conexion)) {
      try {
        $sql = "
        UPDATE item AS i
        JOIN rfq ON i.id_rfq = rfq.id
        JOIN (
          SELECT id_item, MIN(price) AS min_price
          FROM provider
            WHERE id_item = {$id_item}
          GROUP BY id_item
        ) AS min_providers ON i.id = min_providers.id_item
        SET i.unit_price = 
          CASE 
              WHEN rfq.payment_terms = 'Net 30' THEN min_providers.min_price * 1
              WHEN rfq.payment_terms = 'Net 30/CC' THEN min_providers.min_price * 1.0298661174047374
          END,
          i.total_price = 
          CASE 
              WHEN rfq.payment_terms = 'Net 30' THEN min_providers.min_price * 1 * i.quantity
              WHEN rfq.payment_terms = 'Net 30/CC' THEN min_providers.min_price * 1.0298661174047374 * i.quantity
          END
        WHERE i.id = {$id_item};
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_items_por_id_rfq($conexion, $id_rfq) {
    $items = [];
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM item WHERE id_rfq = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
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
    $quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $item->obtener_id_rfq());
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
    $minor_provider = RepositorioProvider::obtener_provider_por_id(Conexion::obtener_conexion(), $item->obtener_provider_menor());
    Conexion::cerrar_conexion();
    $payment_terms = $quote->obtener_payment_terms() == 'Net 30/CC' ? 1.0298661174047374 : 1;
?>
    <tr id="item<?= $item->obtener_id() ?>">
      <td>
        <button data-id="<?= $item->obtener_id() ?>" class="add-provider-button btn btn-warning mb-2">
          <i class="fas fa-user-tie fa-fw"></i>
        </button>
        <button data-id="<?= $item->obtener_id() ?>" class="edit-item-button btn btn-warning mb-2">
          <i class="fas fa-pen fa-fw"></i>
        </button>
        <button data-id="<?= $item->obtener_id() ?>" class="delete-item-button btn btn-danger mb-2">
          <i class="fa fa-trash fa-fw"></i>
        </button>
        <button data-id="<?= $item->obtener_id() ?>" class="add-subitem-button btn btn-warning">
          <i class="fa fa-plus-circle fa-fw"></i>
        </button>
      </td>
      <td><?= $numeracion ?></td>
      <td>
        <b>Brand: </b><?= $item->obtener_brand_project() ?>
        <br>
        <b>Part #: </b><?= $item->obtener_part_number_project() ?>
        <br>
        <b>Description: </b><?= strlen($item->obtener_description_project()) > 100 ? nl2br(mb_substr($item->obtener_description_project(), 0, 100)) . '...' : $item->obtener_description_project() ?>
      </td>
      <td>
        <b>Brand: </b><?= $item->obtener_brand() ?>
        <br>
        <b>Part #: </b><?= $item->obtener_part_number() ?>
        <br>
        <b>Description: </b><?= strlen($item->obtener_description()) > 100 ? nl2br(mb_substr($item->obtener_description(), 0, 100)) . '...' : $item->obtener_description() ?>
      </td>
      <td class="estrechar">
        <a target="_blank" href="<?= $item->obtener_website() ?>"><?= $item->obtener_website() ?></a>
      </td>
      <td><?= $item->obtener_quantity() ?></td>
      <td>
        <?php foreach ($providers as $key => $provider) : ?>
          <div class="row">
            <div class="col-6">
              <a href="#" class="edit-provider-button" data-id="<?= $provider->obtener_id() ?>">
                <b><?= strlen($provider->obtener_provider()) > 10 ? mb_substr($provider->obtener_provider(), 0, 10) . '...' : $provider->obtener_provider() ?></b>
              </a>
            </div>
            <div class="col-6">
              $ <?= $provider->obtener_price() ?>
            </div>
          </div>
        <?php endforeach; ?>
      </td>
      <td>
        <input type="number" step=".01" class="form-control form-control-sm" id="add_cost<?= $j ?>" size="10" value="<?= $item->obtener_additional() ?>">
      </td>
      <td>$ <?= number_format($minor_provider?->obtener_price() * $payment_terms, 2) ?></td>
      <td>$ <?= number_format($minor_provider?->obtener_price() * $item->obtener_quantity() * $payment_terms, 2) ?></td>
      <td>$ <?= number_format($item->obtener_unit_price(), 2) ?></td>
      <td>$ <?= number_format($item->obtener_total_price(), 2) ?></td>
      <td class="estrechar"><?= nl2br($item->obtener_comments()) ?></td>
    </tr>
  <?php
    $j = RepositorioSubitem::escribir_subitems($item->obtener_id(), $j);
    return $j;
  }

  public static function escribir_items($id_rfq) {
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = self::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
  ?>
    <div class="row">
      <div class="col-md-6">
        <h2 id="caja_items">Items:</h2>
      </div>
      <div class="col-md-6">
        <div class="dropdown">
          <button class="float-right btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Downloads
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php if (count($items)) : ?>
              <a target="_blank" href="<?= PDF_TABLA_ITEMS . $id_rfq; ?>" class="dropdown-item">PDF - Items table</a>
              <?php if ($re_quote_exists) : ?>
                <a target="_blank" href="<?= EXCEL_ITEMS_TABLE . $id_rfq; ?>" class="dropdown-item">EXCEL - Quote&Re-quote</a>
              <?php endif; ?>
            <?php endif; ?>
            <a class="dropdown-item" href="<?= PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank">Proposal</a>
            <?php if ($cotizacion->obtener_canal() == 'GSA-Buy') : ?>
              <a class="dropdown-item" href="<?= PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank">GSA Proposal</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php if (count($items)) : ?>
      <div class="row">
        <div class="col-md-3">
          <label>Taxes (%):</label>
          <input type="hidden" name="taxes_original" value="<?= $cotizacion->obtener_taxes(); ?>">
          <input type="number" step=".01" name="taxes" id="taxes" class="form-control form-control-sm" value="<?= $cotizacion->obtener_taxes(); ?>">
        </div>
        <div class="col-md-3">
          <label>Profit (%):</label>
          <input type="hidden" name="profit_original" value="<?= $cotizacion->obtener_profit(); ?>">
          <input type="number" step=".01" name="profit" id="profit" class="form-control form-control-sm" value="<?= $cotizacion->obtener_profit(); ?>">
        </div>
        <div class="col-md-3">
          <label>Additional general ($):</label>
          <input type="hidden" name="additional_general_original" value="<?= $cotizacion->obtener_additional(); ?>">
          <input type="number" step=".01" name="additional_general" id="additional_general" class="form-control form-control-sm" value="<?= $cotizacion->obtener_additional(); ?>">
        </div>
        <div class="col-md-3">
          <label>Payment terms:</label>
          <div class="custom-control custom-radio">
            <input type="radio" id="net_30" name="payment_terms" class="custom-control-input" value="Net 30" <?= $cotizacion->obtener_payment_terms() == 'Net 30' ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="net_30">Net 30</label>
          </div>
          <div class="custom-control custom-radio">
            <input type="radio" id="net_30cc" name="payment_terms" class="custom-control-input" value="Net 30/CC" <?= $cotizacion->obtener_payment_terms() == 'Net 30/CC' ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="net_30cc">Net 30/CC</label>
          </div>
          <input type="hidden" name="payment_terms_original" value="<?= $cotizacion->obtener_payment_terms(); ?>">
        </div>
      </div>
      <br>
      <div class="table-responsive">
        <table id="tabla_items" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>OPT</th>
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
              $k = self::escribir_item($item, $k, $i + 1);
            }
            ?>
          </tbody>
          <thead>
            <tr>
              <th colspan="5" class="display-4"><b>
                  <h4>TOTAL:</h4>
                </b></th>
              <th id="total_quantity"></th>
              <th></th>
              <th id="total_additional"></th>
              <th></th>
              <th id="total1"></th>
              <th></th>
              <th id="total2"></th>
              <th id="dif_total"></th>
            </tr>
          </thead>
        </table>
      </div>
      <?php
      $arrayIdItems = array_map(function ($item) {
        return $item->obtener_id();
      }, $items);
      $arrayIdSubitems = array_map(function ($idItem) {
        Conexion::abrir_conexion();
        $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $idItem);
        Conexion::cerrar_conexion();
        return implode(',', array_map(function ($subitem) {
          return $subitem->obtener_id();
        }, $subitems));
      }, $arrayIdItems);
      ?>
      <input type="hidden" id="id_items" name="id_items" value="<?= implode(',', $arrayIdItems); ?>">
      <input type="hidden" id="id_subitems" name="id_subitems" value="<?= implode(',', array_filter($arrayIdSubitems)); ?>">
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
            <textarea class="form-control form-control-sm" rows="1" id="shipping" name="shipping" placeholder="Enter shipping ..."><?= $cotizacion->obtener_shipping(); ?></textarea>
            <input type="hidden" name="shipping_original" value="<?= $cotizacion->obtener_shipping(); ?>">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input type="number" step=".01" class="form-control form-control-sm" id="shipping_cost" name="shipping_cost" value="<?= $cotizacion->obtener_shipping_cost(); ?>">
            <input type="hidden" name="shipping_cost_original" value="<?= $cotizacion->obtener_shipping_cost(); ?>">
          </div>
        </div>
      </div>
    <?php else : ?>
      <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items!</h3>
<?php
    endif;
  }

  public static function obtener_item_por_id($conexion, $id_item) {
    $item = null;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM item WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_item', $id_item, PDO::PARAM_STR);
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
        $sentencia->bindValue(':brand', $brand, PDO::PARAM_STR);
        $sentencia->bindValue(':brand_project', $brand_project, PDO::PARAM_STR);
        $sentencia->bindValue(':part_number', $part_number, PDO::PARAM_STR);
        $sentencia->bindValue(':part_number_project', $part_number_project, PDO::PARAM_STR);
        $sentencia->bindValue(':description', $description, PDO::PARAM_STR);
        $sentencia->bindValue(':description_project', $description_project, PDO::PARAM_STR);
        $sentencia->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentencia->bindValue(':comments', $comments, PDO::PARAM_STR);
        $sentencia->bindValue(':website', $website, PDO::PARAM_STR);
        $sentencia->bindValue(':id_item', $id_item, PDO::PARAM_STR);
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

  public static function insertar_calculos($conexion, $unit_price, $total_price, $additional, $id_item) {
    $item_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE item SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
        $sentencia->bindValue(':total_price', $total_price, PDO::PARAM_STR);
        $sentencia->bindValue(':additional', $additional, PDO::PARAM_STR);
        $sentencia->bindValue(':id_item', $id_item, PDO::PARAM_STR);
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

  public static function set_fulfillment_profit($conexion, $fulfillment_profit, $id_item) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE item SET fulfillment_profit = :fulfillment_profit WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $sentencia->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_item($conexion, $id_item) {
    if (isset($conexion)) {
      try {
        $conexion->beginTransaction();
        $sql1 = "DELETE FROM provider WHERE id_item = :id_item";
        $sentencia1 = $conexion->prepare($sql1);
        $sentencia1->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia1->execute();
        $sql2 = "DELETE FROM item WHERE id = :id_item";
        $sentencia2 = $conexion->prepare($sql2);
        $sentencia2->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia2->execute();
        $conexion->commit();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
        $conexion->rollBack();
      }
    }
  }
}
?>