<?php
class RepositorioItem {
  public static function insertar_item($conexion, $item) {
    if (isset($conexion)) {
      try {
        $sql = 'INSERT INTO item(id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional, id_room) VALUES(:id_rfq, :id_usuario, :provider_menor, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional, :id_room)';
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
        $sentencia->bindValue(':id_room', $item->getIdRoom(), PDO::PARAM_STR);
        $resultado = $sentencia->execute();
        $id = $conexion->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function actualizar_provider_menor_item($conexion, $provider_menor, $id_item) {
    $item_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE item SET provider_menor = :provider_menor WHERE id = :id_item';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':provider_menor', $provider_menor, PDO::PARAM_STR);
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
            $items[] = new Item($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['provider_menor'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['unit_price'], $fila['total_price'], $fila['comments'], $fila['website'], $fila['additional'], $fila['fulfillment_profit'], $fila['id_room']);
          }
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function getItemsByRoomId($conexion, $id_rfq, $id_room) {
    $items = [];
    if (isset($conexion)) {
      try {
        $sql = 'SELECT * FROM item WHERE id_rfq = :id_rfq AND id_room = :id_room';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->bindValue(':id_room', $id_room, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchall(PDO::FETCH_ASSOC);
        if (count($resultado)) {
          foreach ($resultado as $fila) {
            $items[] = new Item($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['provider_menor'], $fila['brand'], $fila['brand_project'], $fila['part_number'], $fila['part_number_project'], $fila['description'], $fila['description_project'], $fila['quantity'], $fila['unit_price'], $fila['total_price'], $fila['comments'], $fila['website'], $fila['additional'], $fila['fulfillment_profit'], $fila['id_room']);
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

  public static function count_subitems_por_id_rfq($conexion, $id_rfq) {
    $count = 0;
    if (isset($conexion)) {
      try {
        $sql = 'SELECT COUNT(*) AS c FROM subitems s INNER JOIN item i ON s.id_item = i.id WHERE i.id_rfq = :id_rfq';
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        if (!empty($resultado)) {
          $count = (int) $resultado['c'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $count;
  }

  /* ============ Items & Services table redesign — shared render helpers ============
     Used by RepositorioItem, RepositorioSubitem and ServiceRepository so the three
     row types (item / subitem / service) share one visual language. */

  public static function itIcon($name, $size = 14) {
    $paths = [
      'kebab'    => '<circle cx="12" cy="5" r="1.6" fill="currentColor" stroke="none"/><circle cx="12" cy="12" r="1.6" fill="currentColor" stroke="none"/><circle cx="12" cy="19" r="1.6" fill="currentColor" stroke="none"/>',
      'edit'     => '<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
      'trash'    => '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/>',
      'userPlus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>',
      'layers'   => '<polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/>',
      'copy'     => '<rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>',
      'chevD'    => '<polyline points="6 9 12 15 18 9"/>',
      'chevR'    => '<polyline points="9 6 15 12 9 18"/>',
      'check'    => '<polyline points="20 6 9 17 4 12"/>',
      'plus'     => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
      'note'     => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
      'box'      => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
      'tool'     => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
      'table'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="9" x2="9" y2="20"/>',
      'pct'      => '<line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
      'truck'    => '<rect x="1" y="6" width="14" height="10" rx="1"/><path d="M15 9h4l3 3v4h-7z"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="18" r="2"/>',
    ];
    $body = $paths[$name] ?? '';
    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
  }

  public static function itUSD($amount) {
    return '$' . number_format((float) $amount, 2);
  }

  /* Kebab action menu — wraps caller-built menu-item buttons. */
  public static function renderKebab($id, $menuItemsHtml) {
    return '<div class="it-kebab-wrap">'
      . '<button type="button" class="it-kebab" data-toggle-kebab="' . $id . '" aria-haspopup="true" title="Row actions"><span class="it-kebab-ico">' . self::itIcon('kebab', 15) . '</span></button>'
      . '<div class="it-menu" hidden>' . $menuItemsHtml . '</div>'
      . '</div>';
  }

  /* Comment icon + popover, or a dash when there is no comment. */
  public static function renderNoteCell($comments) {
    $comments = trim((string) $comments);
    if ($comments === '') {
      return '<span class="it-dash">&mdash;</span>';
    }
    return '<div class="it-note">'
      . '<button type="button" class="it-note-btn has-note" data-toggle-note title="Comments">' . self::itIcon('note', 13) . '</button>'
      . '<div class="it-note-pop" hidden><div class="it-note-pop-l">Comments</div><div class="it-note-pop-t">' . nl2br(htmlspecialchars($comments, ENT_QUOTES, 'UTF-8')) . '</div></div>'
      . '</div>';
  }

  /* Brand/Part#/Description block — condensed by default, expands in place on click.
     Sends the FULL description (no server-side truncation); the collapsed look comes
     from CSS (grid-rows 0fr/1fr), so expanding never needs a follow-up request. */
  public static function renderDescBlock($tone, $brand, $part, $description, $site = null) {
    $brandEsc = htmlspecialchars((string) $brand, ENT_QUOTES, 'UTF-8');
    $partEsc  = htmlspecialchars((string) $part, ENT_QUOTES, 'UTF-8');
    $desc     = trim((string) $description);

    if ($brandEsc === '' && $partEsc === '' && $desc === '') {
      return '<span class="it-desc-empty">&mdash;</span>';
    }

    $siteHtml = '';
    $site = trim((string) $site);
    if ($site !== '') {
      if (filter_var($site, FILTER_VALIDATE_URL)) {
        $host = preg_replace('/^www\./i', '', parse_url($site, PHP_URL_HOST) ?: $site);
        $siteHtml = '<a class="it-link" target="_blank" href="' . htmlspecialchars($site, ENT_QUOTES, 'UTF-8') . '" onclick="event.stopPropagation()" title="' . htmlspecialchars($site, ENT_QUOTES, 'UTF-8') . '"><span>' . htmlspecialchars($host, ENT_QUOTES, 'UTF-8') . '</span></a>';
      } else {
        $siteHtml = '<span class="it-link" title="' . htmlspecialchars($site, ENT_QUOTES, 'UTF-8') . '"><span>' . htmlspecialchars($site, ENT_QUOTES, 'UTF-8') . '</span></span>';
      }
    }

    $caretHtml = $desc !== '' ? '<span class="it-desc-caret">' . self::itIcon('chevD', 11) . '</span>' : '';
    $bodyHtml  = $desc !== '' ? '<div class="it-collapse"><div><div class="it-desc-body">' . nl2br(htmlspecialchars($desc, ENT_QUOTES, 'UTF-8')) . '</div></div></div>' : '';

    return '<div class="it-desc tone-' . $tone . '" data-toggle-desc tabindex="0" role="button">'
      . '<div class="it-desc-top"><span class="it-desc-brand" title="' . $brandEsc . '">' . $brandEsc . '</span>' . $caretHtml . '</div>'
      . '<div class="it-desc-meta"><span class="it-desc-part" title="' . $partEsc . '">' . $partEsc . '</span>' . $siteHtml . '</div>'
      . $bodyHtml
      . '</div>';
  }

  /* Provider price comparison list — cheapest first, lowest price highlighted.
     $isSubitem selects the subitem-flavored action classes/attributes. */
  public static function renderProvidersList($providers, $ownerId, $isSubitem) {
    if (empty($providers)) {
      $addClass = $isSubitem ? 'iem-add-provider-subitem' : 'iem-add-provider';
      $addAttr  = $isSubitem ? 'data-id-subitem' : 'data-id-item';
      return '<div class="it-prov-empty"><span class="it-prov-none">No providers</span>'
        . '<button type="button" class="it-prov-add ' . $addClass . '" ' . $addAttr . '="' . $ownerId . '">' . self::itIcon('plus', 9) . 'Add provider</button></div>';
    }

    $sorted = $providers;
    usort($sorted, function ($a, $b) {
      return $a->obtener_price() <=> $b->obtener_price();
    });
    $low = $sorted[0]->obtener_price();
    $scrollClass = count($sorted) > 3 ? ' is-scroll' : '';
    $editClass   = $isSubitem ? 'iem-edit-provider-subitem' : 'iem-edit-provider';
    $loadUrl     = $isSubitem ? LOAD_EDIT_PROVIDER_SUBITEM_FORM : LOAD_EDIT_PROVIDER_FORM;

    $rows = '';
    foreach ($sorted as $provider) {
      $isBest = $provider->obtener_price() == $low;
      $name   = htmlspecialchars($provider->obtener_provider(), ENT_QUOTES, 'UTF-8');
      $rows .= '<div class="it-prov-row' . ($isBest ? ' is-best' : '') . '">'
        . ($isBest ? '<span class="it-prov-mark">' . self::itIcon('check', 9) . '</span>' : '')
        . '<button type="button" class="it-prov-name ' . $editClass . '" data-id="' . $provider->obtener_id() . '" data-load-url="' . $loadUrl . $provider->obtener_id() . '" title="' . $name . '">' . $name . '</button>'
        . '<span class="it-prov-price">$ ' . number_format((float) $provider->obtener_price(), 2) . '</span>'
        . '</div>';
    }
    return '<div class="it-prov' . $scrollClass . '">' . $rows . '</div>';
  }

  public static function escribir_item($item, $i, $numeracion) {
    if (!isset($item)) {
      return;
    }

    $j = $i;
    $itemId = $item->obtener_id();

    // Load related data
    Conexion::abrir_conexion();
    $room      = $item->getIdRoom() ? RoomRepository::getById(Conexion::obtener_conexion(), $item->getIdRoom()) : null;
    $providers = RepositorioProvider::obtener_providers_por_id_item(Conexion::obtener_conexion(), $itemId);
    $subitems  = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $itemId);
    Conexion::cerrar_conexion();
    $subCount  = count($subitems);

    // --- Kebab (row actions) column ---
    $menu = '<button type="button" class="it-menu-item iem-edit-item" data-id="' . $itemId . '" data-load-url="' . LOAD_EDIT_ITEM_FORM . $itemId . '">' . self::itIcon('edit', 13) . 'Edit item</button>'
      . '<button type="button" class="it-menu-item iem-add-provider" data-id-item="' . $itemId . '">' . self::itIcon('userPlus', 13) . 'Add provider</button>'
      . '<button type="button" class="it-menu-item iem-add-subitem" data-id-item="' . $itemId . '">' . self::itIcon('layers', 13) . 'Add subitem</button>'
      . '<hr class="it-menu-sep">'
      . '<button type="button" class="it-menu-item is-danger iem-delete-item" data-id="' . $itemId . '" data-url="' . DELETE_ITEM . '/' . $itemId . '">' . self::itIcon('trash', 13) . 'Delete item</button>';
    $kebabCell = self::renderKebab($itemId, $menu);

    // --- # column (room badge + row number, disclosure caret when it has subitems) ---
    $roomName = $room ? htmlspecialchars($room->getName(), ENT_QUOTES, 'UTF-8') : '';
    $discHtml = $subCount > 0
      ? '<button type="button" class="it-disc is-open" data-toggle-subitems="' . $itemId . '" title="Hide subitems">' . self::itIcon('chevR', 12) . '</button>'
      : '';
    $subChip  = $subCount > 0 ? '<span class="it-subn">+' . $subCount . '</span>' : '';
    $numberCell = '<div class="it-idx">'
      . ($roomName !== '' ? '<span class="it-room" title="' . $roomName . '">' . $roomName . '</span>' : '')
      . '<span class="it-no"><span class="it-disc-slot">' . $discHtml . '</span>' . htmlspecialchars((string) $numeracion, ENT_QUOTES, 'UTF-8') . $subChip . '</span>'
      . '</div>';

    // --- Description columns (full text always sent — see renderDescBlock) ---
    $projectDesc = self::renderDescBlock('spec', $item->obtener_brand_project(), $item->obtener_part_number_project(), $item->obtener_description_project());
    $elogicDesc  = self::renderDescBlock('prop', $item->obtener_brand(), $item->obtener_part_number(), $item->obtener_description(), $item->obtener_website());

    // --- Providers column ---
    $providersCell = self::renderProvidersList($providers, $itemId, false);

    // --- Additional cost column ---
    $additionalValue = $item->obtener_additional() != 0 ? $item->obtener_additional() : 0;
    $additionalCell  = '<label class="it-addl"><span class="it-addl-sym">$</span><input type="number" step=".01" class="it-addl-input" id="add_cost' . $j . '" value="' . htmlspecialchars($additionalValue, ENT_QUOTES, 'UTF-8') . '"></label>';

    // --- Best unit cost column (also updates provider_menor) ---
    $bestUnitCostCell = '';
    $precios = [];
    foreach ($providers as $idx => $provider) {
      $precios[$idx] = $provider->obtener_price();
    }
    if (!empty($precios)) {
      $best_unit_price = min($precios);
      foreach ($precios as $idx => $price) {
        if ($best_unit_price == $price) {
          Conexion::abrir_conexion();
          self::actualizar_provider_menor_item(Conexion::obtener_conexion(), $providers[$idx]->obtener_id(), $itemId);
          Conexion::cerrar_conexion();
        }
      }
      $bestUnitCostCell = '$ ' . $best_unit_price;
    }

    // --- Comments column ---
    $commentsCell = self::renderNoteCell($item->obtener_comments());

    // --- Render row ---
    echo '<tr id="item' . $itemId . '" class="it-row">';
    echo '<td class="it-td is-c">' . $kebabCell . '</td>';
    echo '<td class="it-td">' . $numberCell . '</td>';
    echo '<td class="it-td is-div it-td-spec">' . $projectDesc . '</td>';
    echo '<td class="it-td is-div it-td-prop">' . $elogicDesc . '</td>';
    echo '<td class="it-td is-div is-num">' . $item->obtener_quantity() . '</td>';
    echo '<td class="it-td">' . $providersCell . '</td>';
    echo '<td class="it-td is-div">' . $additionalCell . '</td>';
    echo '<td class="it-td is-num it-cost">' . $bestUnitCostCell . '</td>';
    echo '<td class="it-td is-num it-cost"></td>'; // Total cost  (calculated by JS)
    echo '<td class="it-td is-div is-num it-price-client it-td-price"></td>'; // Price for client (calculated by JS)
    echo '<td class="it-td is-num it-price-total it-td-price"></td>'; // Total price (calculated by JS)
    echo '<td class="it-td is-c">' . $commentsCell . '</td>';
    echo '</tr>';

    $j = RepositorioSubitem::escribir_subitems($itemId, $j, (string) $numeracion);
    return $j;
  }

  public static function escribir_items($id_rfq) {
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $items = self::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    $subCount = self::count_subitems_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();

    $itemCount = count($items);
    $items_payment_terms = $cotizacion->obtener_payment_terms();
    ?>
    <div class="it-card">
      <div class="it-card-head">
        <div class="it-card-titlewrap">
          <div class="it-card-title"><span class="it-card-title-ico"><?= self::itIcon('box', 15); ?></span>Items</div>
          <div class="it-card-sub"><?= $itemCount; ?> item<?= $itemCount === 1 ? '' : 's'; ?> &middot; <?= $subCount; ?> subitem<?= $subCount === 1 ? '' : 's'; ?></div>
        </div>
        <div class="it-ctl">
          <div class="it-fld" style="width:78px;">
            <span class="it-fld-l"><?= self::itIcon('pct', 10); ?>Taxes</span>
            <input type="hidden" name="taxes_original" value="<?php echo $cotizacion->obtener_taxes(); ?>">
            <span class="it-affix is-pct"><span class="it-affix-sym">%</span><input type="number" step=".01" name="taxes" id="taxes" class="it-input it-input-num" value="<?php echo $cotizacion->obtener_taxes(); ?>"></span>
          </div>
          <div class="it-fld" style="width:78px;">
            <span class="it-fld-l"><?= self::itIcon('pct', 10); ?>Profit</span>
            <input type="hidden" name="profit_original" value="<?php echo $cotizacion->obtener_profit(); ?>">
            <span class="it-affix is-pct"><span class="it-affix-sym">%</span><input type="number" step=".01" name="profit" id="profit" class="it-input it-input-num" value="<?php echo $cotizacion->obtener_profit(); ?>"></span>
          </div>
          <div class="it-fld" style="width:148px;">
            <span class="it-fld-l">Additional General</span>
            <input type="hidden" name="additional_general_original" value="<?php echo $cotizacion->obtener_additional(); ?>">
            <span class="it-affix"><span class="it-affix-sym">$</span><input type="number" step=".01" name="additional_general" id="additional_general" class="it-input it-input-num" value="<?php echo $cotizacion->obtener_additional(); ?>"></span>
          </div>
          <div class="it-fld" style="width:190px;">
            <span class="it-fld-l">Payment Terms</span>
            <select name="payment_terms" id="items_payment_terms" class="it-input it-select js-payment-terms">
              <option value="Net 30" <?= $items_payment_terms == 'Net 30' ? 'selected' : ''; ?>>Net 30</option>
              <option value="Net 30/CC" <?= $items_payment_terms == 'Net 30/CC' ? 'selected' : ''; ?>>Net 30/CC</option>
              <option value="50% Upfront / 50% on Completion" <?= $items_payment_terms == '50% Upfront / 50% on Completion' ? 'selected' : ''; ?>>50% Upfront / 50% on Completion</option>
            </select>
            <input type="hidden" name="payment_terms_original" value="<?= htmlspecialchars($items_payment_terms); ?>">
          </div>
        </div>
      </div>

      <?php if ($itemCount > 0): ?>
        <div id="table_items_container" class="it-table-scroll">
          <table id="tabla_items" class="it-table">
            <colgroup>
              <col style="width:36px;"><col style="width:104px;"><col><col><col style="width:48px;">
              <col style="width:172px;"><col style="width:84px;"><col style="width:88px;"><col style="width:90px;">
              <col style="width:92px;"><col style="width:108px;"><col style="width:36px;">
            </colgroup>
            <thead>
              <tr class="it-group">
                <th colspan="2"></th>
                <th class="is-spec"><span class="it-gsw" style="background:#1d2a3d;"></span>Project Specifications</th>
                <th class="is-prop is-div"><span class="it-gsw" style="background:#2db4e8;"></span>E-Logic Proposal</th>
                <th colspan="2" class="is-div">Sourcing</th>
                <th colspan="3" class="is-div">Cost</th>
                <th colspan="2" class="is-price is-div">Price</th>
                <th></th>
              </tr>
              <tr class="it-thead">
                <th></th>
                <th>Room / #</th>
                <th class="is-div">Brand / Part #</th>
                <th class="is-div">Brand / Part # &middot; Link</th>
                <th class="is-div is-num">Qty</th>
                <th>Providers</th>
                <th class="is-div is-num">Additional</th>
                <th class="is-num">Best Unit</th>
                <th class="is-num">Total Cost</th>
                <th class="is-div is-num">For Client</th>
                <th class="is-num">Total Price</th>
                <th></th>
              </tr>
            </thead>
            <tbody id="items">
              <?php
              $k = 1;
              for ($i = 0; $i < $itemCount; $i++) {
                $item = $items[$i];
                $k = self::escribir_item($item, $k, $i + 1);
              }
              ?>
            </tbody>
            <tfoot>
              <tr class="it-totals">
                <td colspan="4" class="it-totals-label">
                  <span class="it-totals-k">Totals</span>
                  <span class="it-totals-meta"><?= $itemCount; ?> item<?= $itemCount === 1 ? '' : 's'; ?> &middot; <?= $subCount; ?> subitem<?= $subCount === 1 ? '' : 's'; ?> &middot; incl. <?= self::itUSD($cotizacion->obtener_shipping_cost()); ?> shipping and <?= self::itUSD($cotizacion->obtener_additional()); ?> additional general</span>
                </td>
                <td class="is-num" id="total_quantity"></td>
                <td></td>
                <td class="is-num" id="total_additional"></td>
                <td></td>
                <td class="is-num" id="total1"></td>
                <td class="is-div is-num"></td>
                <td class="is-num is-grand" id="total2"></td>
                <td id="dif_total"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      <?php else: ?>
        <div class="it-empty section-empty-state">
          <div class="it-empty-ico"><?= self::itIcon('table', 24); ?></div>
          <div class="it-empty-t">No items added yet</div>
          <div class="it-empty-s">Add an item to start pricing — providers and subitems can be added afterwards.</div>
        </div>
      <?php endif; ?>

      <?php
      $id_items = '';
      $id_subitems = '';
      $contador_subitems = 0;
      for ($i = 0; $i < $itemCount; $i++) {
        $item = $items[$i];
        $id_items = $id_items . ($i == 0 ? '' : ',') . $item->obtener_id();
        Conexion::abrir_conexion();
        $subitemsForIds = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $item->obtener_id());
        Conexion::cerrar_conexion();
        for ($j = 0; $j < count($subitemsForIds); $j++) {
          $id_subitems = $id_subitems . ($contador_subitems == 0 ? '' : ',') . $subitemsForIds[$j]->obtener_id();
          $contador_subitems++;
        }
      }
      ?>
      <input type="hidden" id="id_items" name="id_items" value="<?php echo $id_items; ?>">
      <input type="hidden" id="id_subitems" name="id_subitems" value="<?php echo $id_subitems; ?>">
      <input type="hidden" id="partes_total_price" name="partes_total_price" value="">
      <input type="hidden" id="partes_total_price_subitems" name="partes_total_price_subitems" value="">
      <input type="hidden" id="additional" name="additional" value="">
      <input type="hidden" id="additional_subitems" name="additional_subitems" value="">
      <input type="hidden" id="unit_prices" name="unit_prices" value="">
      <input type="hidden" id="unit_prices_subitems" name="unit_prices_subitems" value="">
      <input type="hidden" id="total_cost" name="total_cost" value="">
      <input type="hidden" id="total_price" name="total_price" value="">

      <div class="it-ship">
        <div class="it-fld">
          <span class="it-fld-l"><?= self::itIcon('truck', 10); ?>Shipping Notes</span>
          <textarea class="it-input" rows="1" id="shipping" name="shipping" placeholder="Enter shipping ..."><?php echo $cotizacion->obtener_shipping(); ?></textarea>
          <input type="hidden" name="shipping_original" value="<?php echo $cotizacion->obtener_shipping(); ?>">
        </div>
        <div class="it-fld">
          <span class="it-fld-l">Shipping Cost</span>
          <span class="it-affix"><span class="it-affix-sym">$</span><input type="number" step=".01" class="it-input it-input-num" id="shipping_cost" name="shipping_cost" value="<?php echo $cotizacion->obtener_shipping_cost(); ?>"></span>
          <input type="hidden" name="shipping_cost_original" value="<?php echo $cotizacion->obtener_shipping_cost(); ?>">
        </div>
      </div>
    </div>
<?php
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
          $item = new Item($resultado['id'], $resultado['id_rfq'], $resultado['id_usuario'], $resultado['provider_menor'], $resultado['brand'], $resultado['brand_project'], $resultado['part_number'], $resultado['part_number_project'], $resultado['description'], $resultado['description_project'], $resultado['quantity'], $resultado['unit_price'], $resultado['total_price'], $resultado['comments'], $resultado['website'], $resultado['additional'], $resultado['fulfillment_profit'], $resultado['id_room']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function actualizar_item($conexion, $id_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website, $id_room) {
    $item_editado = false;
    if (isset($conexion)) {
      try {
        $sql = 'UPDATE item SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website, id_room = :id_room WHERE id = :id_item';
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
        $sentencia->bindValue(':id_room', $id_room, PDO::PARAM_STR);
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
        // Delete related providers
        $sql1 = "DELETE FROM provider WHERE id_item = :id_item";
        $sentencia1 = $conexion->prepare($sql1);
        $sentencia1->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia1->execute();

        // Delete the item itself
        $sql2 = "DELETE FROM item WHERE id = :id_item";
        $sentencia2 = $conexion->prepare($sql2);
        $sentencia2->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentencia2->execute();
      } catch (PDOException $ex) {
        throw new Exception("Error deleting item: " . $ex->getMessage());
      }
    }
  }
}
?>
