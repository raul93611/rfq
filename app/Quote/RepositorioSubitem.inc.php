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
        // Delete related provider_subitems
        $sql1 = "DELETE FROM provider_subitems WHERE id_subitem = :id_subitem";
        $sentencia1 = $conexion->prepare($sql1);
        $sentencia1->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia1->execute();

        // Delete the subitem itself
        $sql2 = "DELETE FROM subitems WHERE id = :id_subitem";
        $sentencia2 = $conexion->prepare($sql2);
        $sentencia2->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia2->execute();
      } catch (PDOException $ex) {
        throw new Exception("Error deleting subitem: " . $ex->getMessage());
      }
    }
  }

  public static function actualizar_provider_menor_subitem($conexion, $provider_menor, $id_subitem) {
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE subitems SET provider_menor = :provider_menor WHERE id = :id_subitem';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':provider_menor', $provider_menor, PDO::PARAM_STR);
        $sentencia->bindValue(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
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

  public static function escribir_subitem($subitem, $i, $idItem, $displayNo) {
    if (!isset($subitem)) {
      return;
    }
    $j = $i;
    $subitemId = $subitem->obtener_id();
    Conexion::abrir_conexion();
    $providers_subitem = RepositorioProviderSubitem::obtener_providers_subitem_por_id_subitem(Conexion::obtener_conexion(), $subitemId);
    Conexion::cerrar_conexion();

    // --- Kebab (row actions) column ---
    $menu = '<button type="button" class="it-menu-item iem-edit-subitem" data-id="' . $subitemId . '" data-load-url="' . LOAD_EDIT_SUBITEM_FORM . $subitemId . '">' . RepositorioItem::itIcon('edit', 13) . 'Edit subitem</button>'
      . '<button type="button" class="it-menu-item iem-add-provider-subitem" data-id-subitem="' . $subitemId . '">' . RepositorioItem::itIcon('userPlus', 13) . 'Add provider</button>'
      . '<hr class="it-menu-sep">'
      . '<button type="button" class="it-menu-item is-danger iem-delete-subitem" data-id="' . $subitemId . '" data-url="' . DELETE_SUBITEM . '/' . $subitemId . '">' . RepositorioItem::itIcon('trash', 13) . 'Delete subitem</button>';
    $kebabCell = RepositorioItem::renderKebab($subitemId, $menu);

    // --- # column (elbow + hierarchical N.N number, no room badge of its own) ---
    $numberCell = '<div class="it-idx"><span class="it-no"><span class="it-disc-slot"><span class="it-elbow">&#8627;</span></span><span class="it-subn">' . htmlspecialchars((string) $displayNo, ENT_QUOTES, 'UTF-8') . '</span></span></div>';

    // --- Description columns (full text always sent — see RepositorioItem::renderDescBlock) ---
    $projectDesc = RepositorioItem::renderDescBlock('spec', $subitem->obtener_brand_project(), $subitem->obtener_part_number_project(), $subitem->obtener_description_project());
    $elogicDesc  = RepositorioItem::renderDescBlock('prop', $subitem->obtener_brand(), $subitem->obtener_part_number(), $subitem->obtener_description(), $subitem->obtener_website());

    // --- Providers column ---
    $providersCell = RepositorioItem::renderProvidersList($providers_subitem, $subitemId, true);

    // --- Additional cost column ---
    $additionalValue = $subitem->obtener_additional() != 0 ? $subitem->obtener_additional() : 0;
    $additionalCell  = '<label class="it-addl"><span class="it-addl-sym">$</span><input type="number" step=".01" class="it-addl-input" id="add_cost' . $j . '" value="' . htmlspecialchars($additionalValue, ENT_QUOTES, 'UTF-8') . '"></label>';

    // --- Best unit cost column (also updates provider_menor) ---
    $bestUnitCostCell = '';
    $precios_subitem = [];
    foreach ($providers_subitem as $idx => $provider_subitem) {
      $precios_subitem[$idx] = $provider_subitem->obtener_price();
    }
    if (!empty($precios_subitem)) {
      $best_unit_price = min($precios_subitem);
      foreach ($precios_subitem as $idx => $price) {
        if ($best_unit_price == $price) {
          Conexion::abrir_conexion();
          self::actualizar_provider_menor_subitem(Conexion::obtener_conexion(), $providers_subitem[$idx]->obtener_id(), $subitemId);
          Conexion::cerrar_conexion();
        }
      }
      $bestUnitCostCell = '$ ' . $best_unit_price;
    }

    // --- Comments column ---
    $commentsCell = RepositorioItem::renderNoteCell($subitem->obtener_comments());

    // --- Render row ---
    // "fila_subitem" is a legacy class kept for js/quote.js's `$row.hasClass('fila_subitem')` calc branch.
    echo '<tr id="subitem' . $subitemId . '" class="it-row is-sub fila_subitem" data-parent-item="' . $idItem . '">';
    echo '<td class="it-td is-c">' . $kebabCell . '</td>';
    echo '<td class="it-td">' . $numberCell . '</td>';
    echo '<td class="it-td is-div it-td-spec">' . $projectDesc . '</td>';
    echo '<td class="it-td is-div it-td-prop">' . $elogicDesc . '</td>';
    echo '<td class="it-td is-div is-num">' . $subitem->obtener_quantity() . '</td>';
    echo '<td class="it-td">' . $providersCell . '</td>';
    echo '<td class="it-td is-div">' . $additionalCell . '</td>';
    echo '<td class="it-td is-num it-cost">' . $bestUnitCostCell . '</td>';
    echo '<td class="it-td is-num it-cost"></td>'; // Total cost  (calculated by JS)
    echo '<td class="it-td is-div is-num it-price-client it-td-price"></td>'; // Price for client (calculated by JS)
    echo '<td class="it-td is-num it-price-total it-td-price"></td>'; // Total price (calculated by JS)
    echo '<td class="it-td is-c">' . $commentsCell . '</td>';
    echo '</tr>';
  }

  public static function escribir_subitems($id_item, $j, $parentNumeracion = '') {
    $j++;
    Conexion::abrir_conexion();
    $subitems = self::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $id_item);
    Conexion::cerrar_conexion();
    if (count($subitems)) {
      for ($i = 0; $i < count($subitems); $i++) {
        $subitem = $subitems[$i];
        $displayNo = $parentNumeracion !== '' ? ($parentNumeracion . '.' . ($i + 1)) : (string) ($i + 1);
        self::escribir_subitem($subitem, $j, $id_item, $displayNo);
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
