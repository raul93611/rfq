<?php
class ReQuoteSubitemRepository{
  public static function insert_re_quote_subitem($connection, $re_quote_subitem){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO re_quote_subitems(id_re_quote_item, brand, brand_project, part_number, part_number_project, description, description_project, quantity, unit_price, total_price, comments, website, additional) VALUES(:id_re_quote_item, :brand, :brand_project, :part_number, :part_number_project, :description, :description_project, :quantity, :unit_price, :total_price, :comments, :website, :additional)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_item', $re_quote_subitem-> get_id_re_quote_item(), PDO::PARAM_STR);
        $sentence-> bindParam(':brand', $re_quote_subitem-> get_brand(), PDO::PARAM_STR);
        $sentence-> bindParam(':brand_project', $re_quote_subitem-> get_brand_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':part_number', $re_quote_subitem-> get_part_number(), PDO::PARAM_STR);
        $sentence-> bindParam(':part_number_project', $re_quote_subitem-> get_part_number_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':description', $re_quote_subitem-> get_description(), PDO::PARAM_STR);
        $sentence-> bindParam(':description_project', $re_quote_subitem-> get_description_project(), PDO::PARAM_STR);
        $sentence-> bindParam(':quantity', $re_quote_subitem-> get_quantity(), PDO::PARAM_STR);
        $sentence-> bindParam(':unit_price', $re_quote_subitem-> get_unit_price(), PDO::PARAM_STR);
        $sentence-> bindParam(':total_price', $re_quote_subitem-> get_total_price(), PDO::PARAM_STR);
        $sentence-> bindParam(':comments', $re_quote_subitem-> get_comments(), PDO::PARAM_STR);
        $sentence-> bindParam(':website', $re_quote_subitem-> get_website(), PDO::PARAM_STR);
        $sentence-> bindParam(':additional', $re_quote_subitem-> get_additional(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection-> lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function get_re_quote_subitems_by_id_re_quote_item($connection, $id_re_quote_item){
    $re_quote_subitems = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_subitems WHERE id_re_quote_item = :id_re_quote_item';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_item', $id_re_quote_item, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $key => $row) {
            $re_quote_subitems[] = new ReQuoteSubitem($row['id'], $row['id_re_quote_item'], $row['brand'], $row['brand_project'], $row['part_number'], $row['part_number_project'], $row['description'], $row['description_project'], $row['quantity'], $row['unit_price'], $row['total_price'], $row['comments'], $row['website'], $row['additional']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_subitems;
  }

  public static function print_re_quote_subitems($id_re_quote_item, $id_item, $j) {
    echo 'asdsadsadsad';
    $j++;
    Conexion::abrir_conexion();
    $re_quote_subitems = self::get_re_quote_subitems_by_id_re_quote_item(Conexion::obtener_conexion(), $id_re_quote_item);
    $subitems = RepositorioSubitem::obtener_subitems_por_id_item(Conexion::obtener_conexion(), $id_item);
    Conexion::cerrar_conexion();
    if (count($re_quote_subitems)) {
      foreach ($re_quote_subitems as $key => $re_quote_subitem) {
        $subitem = $subitems[$key];
        self::print_re_quote_subitem($re_quote_subitem, $subitem, $j);
        $j++;
      }
    }
    return $j;
  }

  public static function print_re_quote_subitem($re_quote_subitem, $subitem, $i) {
    echo 'sadsadsadasd';
    if (!isset($re_quote_subitem)) {
      return;
    }
    $j = $i;
    Conexion::abrir_conexion();
    $re_quote_subitem_providers = ReQuoteSubitemProviderRepository::get_re_quote_subitem_providers_by_id_re_quote_subitem(Conexion::obtener_conexion(), $re_quote_subitem-> get_id());
    Conexion::cerrar_conexion();
    ?>
    <tr class="fila_subitem">
      <td>
        <a href="<?php echo ADD_RE_QUOTE_SUBITEM_PROVIDER . $re_quote_subitem-> get_id(); ?>" class="btn btn-warning btn-block subitem">
          <i class="fa fa-plus-circle"></i> Add Provider
        </a>
        <br>
        <a href="<?php echo EDIT_RE_QUOTE_SUBITEM . $re_quote_subitem-> get_id(); ?>" class="btn btn-warning btn-block subitem">
          <i class="fa fa-edit"></i> Edit subitem
        </a>
        <br>
        <a href="<?php echo DELETE_RE_QUOTE_SUBITEM . $re_quote_subitem-> get_id(); ?>" class="delete_subitem_button btn btn-warning btn-block subitem">
          <i class="fa fa-trash"></i> Delete
        </a>
      </td>
      <td></td>
    <?php
    if(strlen($re_quote_subitem-> get_description_project()) >= 100){
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_subitem-> get_brand_project(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_subitem-> get_part_number_project(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br(mb_substr($re_quote_subitem-> get_description_project(), 0, 100)); ?> ...
      </td>
      <?php
    }else{
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_subitem-> get_brand_project(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_subitem-> get_part_number_project(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br($re_quote_subitem-> get_description_project()); ?>
      </td>
      <?php
    }
    if(strlen($re_quote_subitem-> get_description()) >= 100){
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_subitem-> get_brand(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_subitem-> get_part_number(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br(mb_substr($re_quote_subitem-> get_description(), 0, 100)); ?> ...
      </td>
      <?php
    }else{
      ?>
      <td>
        <b>Brand:</b> <?php echo $re_quote_subitem-> get_brand(); ?>
        <br>
        <b>Part #:</b> <?php echo $re_quote_subitem-> get_part_number(); ?>
        <br>
        <b>Description:</b> <?php echo nl2br($re_quote_subitem-> get_description()); ?>
      </td>
      <?php
    }
    ?>
      <td class="estrechar">
        <a target="_blank" href="<?php echo $re_quote_subitem-> get_website(); ?>"><?php echo $re_quote_subitem-> get_website(); ?></a>
      </td>
      <td><?php echo $re_quote_subitem-> get_quantity(); ?></td>
      <td>
        <div class="row">
          <div class="col-6">
            <?php
            if(count($re_quote_subitem_providers)){
              foreach ($re_quote_subitem_providers as $key => $re_quote_subitem_provider) {
                if(strlen($re_quote_subitem_provider-> get_provider()) >= 10){
                  ?>
                  <a href="<?php echo EDIT_RE_QUOTE_SUBITEM_PROVIDER . $re_quote_subitem_provider-> get_id(); ?>">
                    <b><?php echo mb_substr($re_quote_subitem_provider-> get_provider(), 0, 10); ?>... :</b>
                  </a>
                  <br>
                  <?php
                }else{
                  ?>
                  <a href="<?php echo EDIT_RE_QUOTE_SUBITEM_PROVIDER . $re_quote_subitem_provider-> get_id(); ?>">
                    <b><?php echo $re_quote_subitem_provider-> get_provider(); ?>:</b>
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
            if(count($re_quote_subitem_providers)){
              foreach ($re_quote_subitem_providers as $key => $re_quote_subitem_provider) {
                echo '$ ' . $re_quote_subitem_provider-> get_price() . '<br>';
              }
            }
            ?>
          </div>
        </div>
      </td>
    <?php
    echo '<td>';
    if(count($re_quote_subitem_providers)){
      foreach ($re_quote_subitem_providers as $key => $re_quote_subitem_provider) {
        $subitem_prices[$key] = $re_quote_subitem_provider-> get_price();
      }
    }
    if (!empty($subitem_prices)) {
      $best_unit_price = min($subitem_prices);
      echo '$ ' . $best_unit_price;
    }
    echo '</td>';
    echo '<td>$ ' . $best_unit_price * $re_quote_subitem-> get_quantity() . '</td>';
    if(!is_null($subitem)){echo '<td>$ ' . $subitem-> obtener_unit_price() . '</td>';}else{echo '<td></td>';}
    if(!is_null($subitem)){echo '<td>$ ' . $subitem-> obtener_total_price() . '</td>';}else{echo '<td></td>';}
    echo '<td>' . nl2br($re_quote_subitem-> get_comments()) . '</td>';
    echo '</tr>';
  }

  public static function get_re_quote_subitem_by_id($connection, $id_re_quote_subitem){
    $re_quote_subitem = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM re_quote_subitems WHERE id = :id_re_quote_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence-> execute();
        $result = $sentence-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $re_quote_subitem = new ReQuoteSubitem($result['id'], $result['id_re_quote_item'], $result['brand'], $result['brand_project'], $result['part_number'], $result['part_number_project'], $result['description'], $result['description_project'], $result['quantity'], $result['unit_price'], $result['total_price'], $result['comments'], $result['website'], $result['additional']);
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $re_quote_subitem;
  }

  public static function update_re_quote_subitem($connection, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $comments, $website, $id_re_quote_subitem){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_subitems SET brand = :brand, brand_project = :brand_project, part_number = :part_number, part_number_project = :part_number_project, description = :description, description_project = :description_project, quantity = :quantity, comments = :comments, website = :website WHERE id = :id_re_quote_subitem';
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
        $sentence-> bindParam(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete_re_quote_subitem($connection, $id_re_quote_subitem){
    if(isset($connection)){
      try{
        $connection-> beginTransaction();
        $sql1 = 'DELETE FROM re_quote_subitem_providers WHERE id_re_quote_subitem = :id_re_quote_subitem';
        $sentence1 = $connection-> prepare($sql1);
        $sentence1-> bindParam(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence1-> execute();
        $sql2 = 'DELETE FROM re_quote_subitems WHERE id = :id_re_quote_subitem';
        $sentence2 = $connection-> prepare($sql2);
        $sentence2-> bindParam(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence2-> execute();
        $connection-> commit();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
        $connection-> rollBack();
      }
    }
  }

  public static function insert_calc($connection, $unit_price_subitem, $total_price_subitem, $additional_subitem, $id_re_quote_subitem){
    if(isset($connection)){
      try{
        $sql = 'UPDATE re_quote_subitems SET unit_price = :unit_price, total_price = :total_price, additional = :additional WHERE id = :id_re_quote_subitem';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':unit_price', $unit_price_subitem, PDO::PARAM_STR);
        $sentence-> bindParam(':total_price', $total_price_subitem, PDO::PARAM_STR);
        $sentence-> bindParam(':additional', $additional_subitem, PDO::PARAM_STR);
        $sentence-> bindParam(':id_re_quote_subitem', $id_re_quote_subitem, PDO::PARAM_STR);
        $sentence-> execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
?>
