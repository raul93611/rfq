<?php
class RepositorioSubitem{
  public static function insertar_subitem($database, $subitem) {
    if (isset($database)) {
      try {
        $sql = 'INSERT INTO subitems(id_item, least_provider, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_item, :least_provider, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $query = $database->prepare($sql);
        $query->bindParam(':id_item', $subitem->get_id_item(), PDO::PARAM_STR);
        $query->bindParam(':least_provider', $subitem->get_least_provider(), PDO::PARAM_STR);
        $query->bindParam(':brand', $subitem->get_brand(), PDO::PARAM_STR);
        $query->bindParam(':brand_project', $subitem->get_brand_project(), PDO::PARAM_STR);
        $query->bindParam(':part_number', $subitem->get_part_number(), PDO::PARAM_STR);
        $query->bindParam(':part_number_project', $subitem->get_part_number_project(), PDO::PARAM_STR);
        $query->bindParam(':description', $subitem->get_description(), PDO::PARAM_STR);
        $query->bindParam(':description_project', $subitem->get_description_project(), PDO::PARAM_STR);
        $query->bindParam(':quantity', $subitem->get_quantity(), PDO::PARAM_STR);
        $query->bindParam(':unit_price', $subitem->get_unit_price(), PDO::PARAM_STR);
        $query->bindParam(':total_price', $subitem->get_total_price(), PDO::PARAM_STR);
        $query->bindParam(':comments', $subitem->get_comments(), PDO::PARAM_STR);
        $query->bindParam(':website', $subitem->get_website(), PDO::PARAM_STR);
        $query->bindParam(':additional', $subitem->get_additional(), PDO::PARAM_STR);
        $query->execute();
        $id = $database->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function delete_subitem($database, $id_subitem){
    if(isset($database)){
      try{
        $database -> beginTransaction();
        $sql1 = "DELETE FROM provider_subitems WHERE id_subitem = :id_subitem";
        $query1 = $database-> prepare($sql1);
        $query1-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query1-> execute();
        $sql2 = "DELETE FROM subitems WHERE id = :id_subitem";
        $query2 = $database-> prepare($sql2);
        $query2-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query2-> execute();
        $database-> commit();
      } catch (PDOException $ex) {
        print "ERROR:" . $ex->getMessage() . "<br>";
        $database-> rollBack();
      }
    }
  }

  public static function actualizar_provider_menor_subitem($database, $least_provider, $id_subitem){
    if(isset($database)){
      try{
        $sql = 'UPDATE subitems SET least_provider = :least_provider WHERE id = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':least_provider', $least_provider, PDO::PARAM_STR);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function actualizar_subitem($database, $id_subitem, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website) {
    if (isset($database)) {
      try {
        $sql = 'UPDATE subitems SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_subitem';
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
        $query->bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function obtener_subitem_por_id($database, $id_subitem) {
    $subitem = null;
    if (isset($database)) {
      try {
        $sql = 'SELECT * FROM subitems WHERE id = :id_subitem';
        $query = $database->prepare($sql);
        $query->bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch();
        if (!empty($result)) {
          $subitem = new Subitem($result['id'], $result['id_item'], $result['least_provider'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional'], $result['fulfillment_profit']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $subitem;
  }

  public static function obtener_subitems_por_id_item($database, $id_item) {
    $subitems = [];
    if (isset($database)) {
      try {
        $sql = 'SELECT * FROM subitems WHERE id_item = :id_item';
        $query = $database->prepare($sql);
        $query->bindParam(':id_item', $id_item, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetchAll();
        if (count($result)) {
          foreach ($result as $row) {
            $subitems[] = new Subitem($row['id'], $row['id_item'], $row['least_provider'], $row['brand'], $row['brand_project'], $row['part_number'], $row['part_number_project'], $row['description'], $row['description_project'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['comments'], $row['website'], $row['additional'], $row['fulfillment_profit']);
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
    Database::open_connection();
    $providers_subitem = ProviderSubitemRepository::get_all_by_id_subitem(Database::get_connection(), $subitem->get_id());
    Database::close_connection();
    echo '<tr id="subitem' . $subitem->get_id() .  '" class="fila_subitem">';
    echo '<td><a href="' . ADD_PROVIDER_SUBITEM . '/' . $subitem->get_id() . '" class="btn btn-warning btn-block subitem"><i class="fa fa-plus-circle"></i> Add Provider</a><br><a href="' . EDIT_SUBITEM . '/' . $subitem->get_id() . '" class="btn btn-warning btn-block subitem"><i class="fa fa-edit"></i> Edit subitem</a><br><a href="' . DELETE_SUBITEM . '/' . $subitem-> get_id() . '" class="delete_subitem_button btn btn-warning btn-block subitem"><i class="fa fa-trash"></i> Delete</a></td>';
    echo '<td></td>';
    if(strlen($subitem-> get_description_project()) >= 100){
      echo '<td><b>Brand:</b> ' . $subitem->get_brand_project() . '<br><b>Part #:</b> ' . $subitem->get_part_number_project() . '<br><b>Description:</b> ' . nl2br(mb_substr($subitem->get_description_project(), 0, 100)) . ' ...</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $subitem->get_brand_project() . '<br><b>Part #:</b> ' . $subitem->get_part_number_project() . '<br><b>Description:</b> ' . nl2br($subitem->get_description_project()) . '</td>';
    }
    if(strlen($subitem-> get_description()) >= 100){
      echo '<td><b>Brand:</b> ' . $subitem->get_brand() . '<br><b>Part #:</b> ' . $subitem->get_part_number() . '<br><b>Description:</b> ' . nl2br(mb_substr($subitem->get_description(), 0, 100)) . ' ...</td>';
    }else{
      echo '<td><b>Brand:</b> ' . $subitem->get_brand() . '<br><b>Part #:</b> ' . $subitem->get_part_number() . '<br><b>Description:</b> ' . nl2br($subitem->get_description()) . '</td>';
    }
    echo '<td class="estrechar"><a target="_blank" href="'. $subitem-> get_website() .'">'. $subitem-> get_website() .'</a></td>';
    echo '<td>' . $subitem->get_quantity() . '</td>';
    echo '<td><div class="row"><div class="col-6">';
    for ($i = 0; $i < count($providers_subitem); $i++) {
      $provider_subitem = $providers_subitem[$i];
      if(strlen($provider_subitem-> get_provider()) >= 10){
        echo '<a href="' . EDIT_PROVIDER_SUBITEM . '/' . $provider_subitem->get_id() . '"><b>' . mb_substr($provider_subitem->get_provider(), 0, 10) . '... :</b></a><br>';
      }else{
        echo '<a href="' . EDIT_PROVIDER_SUBITEM . '/' . $provider_subitem->get_id() . '"><b>' . $provider_subitem->get_provider() . ':</b></a><br>';
      }
    }
    echo '</div><div class="col-6">';
    for ($i = 0; $i < count($providers_subitem); $i++) {
      $provider_subitem = $providers_subitem[$i];
      echo '$ ' . $provider_subitem->get_price() . '<br>';
    }
    echo '</div></div></td>';
    if($subitem-> get_additional() != 0){
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="'.$subitem-> get_additional().'"></td>';
    }else{
      echo '<td><input type="text" class="form-control form-control-sm" id="add_cost'.$j.'" size="10" value="0"></td>';
    }
    echo '<td>';
    for ($i = 0; $i < count($providers_subitem); $i++) {
      $provider_subitem = $providers_subitem[$i];
      $prices_subitem[$i] = $provider_subitem->get_price();
    }
    if (!empty($prices_subitem)) {
      $best_unit_price = min($prices_subitem);
      for($i = 0;$i < count($prices_subitem); $i++){
        if($best_unit_price == $prices_subitem[$i]){
          Database::open_connection();
          self::actualizar_provider_menor_subitem(Database::get_connection(), $providers_subitem[$i]->get_id(), $subitem-> get_id());
          Database::close_connection();
        }
      }
      echo '$ ' . $best_unit_price;
    }
    echo '</td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td></td>';
    echo '<td>' . nl2br($subitem->get_comments()) . '</td>';
    echo '</tr>';
  }

  public static function escribir_subitems($id_item, $j) {
    $j++;
    Database::open_connection();
    $subitems = self::obtener_subitems_por_id_item(Database::get_connection(), $id_item);
    Database::close_connection();
    if (count($subitems)) {
      for ($i = 0; $i < count($subitems); $i++) {
        $subitem = $subitems[$i];
        self::escribir_subitem($subitem, $j);
        $j++;
      }
    }
    return $j;
  }

  public static function update_amounts($database, $unit_price_subitem, $total_price_subitem, $additional_subitem, $id_subitem){
    if(isset($database)){
      try{
        $sql = 'UPDATE subitems SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':unit_price', $unit_price_subitem, PDO::PARAM_STR);
        $query-> bindParam(':total_price', $total_price_subitem, PDO::PARAM_STR);
        $query-> bindParam(':additional', $additional_subitem, PDO::PARAM_STR);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function set_fulfillment_profit($database, $fulfillment_profit, $id_subitem){
    if(isset($database)){
      try{
        $sql = 'UPDATE subitems SET fulfillment_profit = :fulfillment_profit WHERE id = :id_subitem';
        $query = $database-> prepare($sql);
        $query-> bindParam(':fulfillment_profit', $fulfillment_profit, PDO::PARAM_STR);
        $query-> bindParam(':id_subitem', $id_subitem, PDO::PARAM_STR);
        $query-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
