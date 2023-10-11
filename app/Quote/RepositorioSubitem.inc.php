<?php
class RepositorioSubitem {
  public static function insertar_subitem($conexion, $subitem) {
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO subitems(id_item, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_item, :provider_menor, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_item', $subitem->obtener_id_item(), PDO::PARAM_STR);
        $sentencia->bindValue(':provider_menor', $subitem->obtener_provider_menor(), PDO::PARAM_STR);
        $sentencia->bindValue(':brand', $subitem->obtener_brand(), PDO::PARAM_STR);
        $sentencia->bindValue(':brand_project', $subitem->obtener_brand_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':part_number', $subitem->obtener_part_number(), PDO::PARAM_STR);
        $sentencia->bindValue(':part_number_project', $subitem->obtener_part_number_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':description', $subitem->obtener_description(), PDO::PARAM_STR);
        $sentencia->bindValue(':description_project', $subitem->obtener_description_project(), PDO::PARAM_STR);
        $sentencia->bindValue(':quantity', $subitem->obtener_quantity(), PDO::PARAM_STR);
        $sentencia->bindValue(':unit_price', $subitem->obtener_unit_price(), PDO::PARAM_STR);
        $sentencia->bindValue(':total_price', $subitem->obtener_total_price(), PDO::PARAM_STR);
        $sentencia->bindValue(':comments', $subitem->obtener_comments(), PDO::PARAM_STR);
        $sentencia->bindValue(':website', $subitem->obtener_website(), PDO::PARAM_STR);
        $sentencia->bindValue(':additional', $subitem->obtener_additional(), PDO::PARAM_STR);
        $sentencia->execute();
        $id = $conexion->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function delete_subitem($conexion, $id_subitem) {
    if (isset($conexion)) {
      try {
        $conexion->beginTransaction();
        $sql1 = "DELETE FROM provider_subitems WHERE id_subitem = :id_subitem";
        $sentencia1 = $conexion->prepare($sql1);
        $sentencia1->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia1->execute();
        $sql2 = "DELETE FROM subitems WHERE id = :id_subitem";
        $sentencia2 = $conexion->prepare($sql2);
        $sentencia2->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia2->execute();
        $conexion->commit();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
        $conexion->rollBack();
      }
    }
  }

  public static function updateMinorProvider($conexion, $minor_provider, $id_subitem) {
    $item_editado = false;
    if (isset($conexion)) {
      try {
        $sql = "UPDATE subitems SET provider_menor = :provider_menor, unit_price = :unit_price, total_price = (:unit_price * quantity) WHERE id = :id_subitem";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':provider_menor', $minor_provider['id'], PDO::PARAM_STR);
        $sentencia->bindValue(':unit_price', $minor_provider['value'], PDO::PARAM_STR);
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
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

  public static function actualizar_subitem($conexion, $id_subitem, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE subitems SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_subitem';
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
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_subitem_por_id($conexion, $id_subitem) {
    $subitem = null;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM subitems WHERE id = :id_subitem';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch();
        if (!empty($resultado)) {
          $subitem = new Subitem($resultado['id'], $resultado['id_item'], $resultado['provider_menor'], $resultado['brand'], $resultado['brand_project'], $resultado['part_number'], $resultado['part_number_project'], $resultado['description'], $resultado['description_project'], $resultado['quantity'], $resultado['unit_price'], $resultado['total_price'], $resultado['comments'], $resultado['website'], $resultado['additional'], $resultado['fulfillment_profit']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $subitem;
  }

  public static function obtener_subitems_por_id_item($conexion, $id_item) {
    $subitems = [];
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM subitems WHERE id_item = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll();
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $subitems[] = new Subitem($fila['id'], $fila['id_item'], $fila['provider_menor'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['unit_price'], $fila['total_price'], $fila['comments'], $fila['website'], $fila['additional'], $fila['fulfillment_profit']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $subitems;
  }

  public static function escribir_subitem($subitem, $i) {
    if (!isset($subitem)) {
      return;
    }
    $j = $i;
    Conexion::abrir_conexion();
    $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitem->obtener_id());
    $minor_provider = RepositorioProviderSubitem::obtener_provider_subitem_por_id(Conexion::obtener_conexion(), $subitem->obtener_provider_menor());
    Conexion::cerrar_conexion();
?>
    <tr id="subitem<?= $subitem->obtener_id() ?>" class="fila_subitem">
      <td>
        <button data-id="<?= $subitem->obtener_id() ?>" class="subitem add-subitem-provider-button btn btn-warning mb-2">
          <i class="fas fa-user-tie fa-fw"></i>
        </button>
        <button data-id="<?= $subitem->obtener_id() ?>" class="subitem edit-subitem-button btn btn-warning mb-2">
          <i class="fas fa-pen fa-fw"></i>
        </button>
        <button data-id="<?= $subitem->obtener_id() ?>" class="delete-subitem-button btn btn-danger mb-2">
          <i class="fa fa-trash fa-fw"></i>
        </button>
      </td>
      <td></td>
      <td>
        <b>Brand: </b><?= $subitem->obtener_brand_project() ?>
        <br>
        <b>Part #: </b><?= $subitem->obtener_part_number_project() ?>
        <br>
        <b>Description: </b><?= strlen($subitem->obtener_description_project()) > 100 ? nl2br(mb_substr($subitem->obtener_description_project(), 0, 100)) . '...' : $subitem->obtener_description_project() ?>
      </td>
      <td>
        <b>Brand: </b><?= $subitem->obtener_brand() ?>
        <br>
        <b>Part #: </b><?= $subitem->obtener_part_number() ?>
        <br>
        <b>Description: </b><?= strlen($subitem->obtener_description()) > 100 ? nl2br(mb_substr($subitem->obtener_description(), 0, 100)) . '...' : $subitem->obtener_description() ?>
      </td>
      <td class="estrechar">
        <a target="_blank" href="<?= $subitem->obtener_website() ?>"><?= $subitem->obtener_website() ?></a>
      </td>
      <td><?= $subitem->obtener_quantity() ?></td>
      <td>
        <?php foreach ($providers_subitem as $key => $provider_subitem) : ?>
          <div class="row">
            <div class="col-6">
              <a href="#" class="edit-subitem-provider-button" data-id="<?= $provider_subitem->obtener_id() ?>">
                <b><?= strlen($provider_subitem->obtener_provider()) > 10 ? mb_substr($provider_subitem->obtener_provider(), 0, 10) . '...' : $provider_subitem->obtener_provider() ?></b>
              </a>
            </div>
            <div class="col-6">
              $ <?= $provider_subitem->obtener_price() ?>
            </div>
          </div>
        <?php endforeach; ?>
      </td>
      <td>
        <input type="number" step=".01" class="form-control form-control-sm" id="add_cost<?= $j ?>" size="10" value="<?= $subitem->obtener_additional() ?>">
      </td>
      <td>$ <?= $minor_provider?->obtener_price() ?></td>
      <td>$ <?= number_format($minor_provider?->obtener_price() * $subitem->obtener_quantity(), 2) ?></td>
      <td>$ <?= number_format($subitem->obtener_unit_price()) ?></td>
      <td>$ <?= number_format($subitem->obtener_total_price()) ?></td>
      <td class="estrechar"><?= nl2br($subitem->obtener_comments()) ?></td>
    </tr>
<?php
  }

  public static function escribir_subitems($id_item, $j) {
    $j++;
    Conexion::abrir_conexion();
    $subitems = self::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $id_item);
    Conexion::cerrar_conexion();
    if (count($subitems)) {
      for ($i = 0; $i < count($subitems); $i++) {
        $subitem = $subitems[$i];
        self::escribir_subitem($subitem, $j);
        $j++;
      }
    }
    return $j;
  }

  public static function insertar_calculos($conexion, $unit_price_subitem, $total_price_subitem, $additional_subitem, $id_subitem) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE subitems SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_subitem';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':unit_price', $unit_price_subitem, PDO::PARAM_STR);
        $sentencia->bindValue(':total_price', $total_price_subitem, PDO::PARAM_STR);
        $sentencia->bindValue(':additional', $additional_subitem, PDO::PARAM_STR);
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_fulfillment_profit($conexion, $fulfillment_profit, $id_subitem) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE subitems SET fulfillment_profit = :fulfillment_profit WHERE id = :id_subitem';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>