<?php
class CommentRepository{
  public static function insert($database, $comment){
    if(isset($database)){
      try{
        $sql = 'INSERT INTO comments (id_quote, id_user, comment, comment_date) VALUES(:id_quote, :id_user, :comment, NOW())';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $comment-> get_id_quote(), PDO::PARAM_STR);
        $query-> bindParam(':id_user', $comment-> get_id_user(), PDO::PARAM_STR);
        $query-> bindParam(':comment', $comment-> get_comment(), PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function count_all_by_quote($database, $id_quote){
    $total = 0;
    if(isset($database)){
      try{
        $sql = 'SELECT COUNT(*) as total FROM comments WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch(PDO::FETCH_ASSOC);
        if(!empty($result)){
          $total = $result['total'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total;
  }

  public static function get_all_by_quote($database, $id_quote){
    $comments = [];
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM comments WHERE id_quote = :id_quote ORDER BY comment_date DESC';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetchAll(PDO::FETCH_ASSOC);
        if(count($result)){
          foreach ($result as $row) {
            $comments[] = new Comment($row['id'], $row['id_quote'], $row['id_user'], $row['comment'], $row['comment_date']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $comments;
  }

  public static function print_comments($id_quote){
    Database::open_connection();
    $quote = QuoteRepository::get_by_id(Database::get_connection(), $id_quote);
    $comments = self::get_all_by_quote(Database::get_connection(), $id_quote);
    Database::close_connection();
    ?>
    <ul class="timeline">
      <li class="clickable_title">
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Proposal: <?php echo $quote-> get_id(); ?></a></h3>
        </div>
      </li>
      <?php
      if(count($comments)){
        foreach ($comments as $comment) {
          $comment_date = self::mysql_datetime_to_english_format($comment-> get_comment_date());
          ?>
          <li class="body_comments">
            <i class="fa fa-user"></i>
            <div class="timeline-item">
              <span class="time"><i class="far fa-clock"></i> <?php echo $comment_date; ?></span>
              <h3 class="timeline-header">
                <span class="text-primary">
                <?php
                Database::open_connection();
                $usuario = RepositorioUsuario::obtener_usuario_por_id(Database::get_connection(), $comment-> get_id_user());
                Database::close_connection();
                echo $usuario-> obtener_nombre_usuario();
                ?>
                </span>
                 said</h3>
              <div class="timeline-body">
                <?php echo nl2br($comment-> get_comment()); ?>
              </div>
            </div>
          </li>
          <?php
        }
      }
      ?>
      <li>
        <i class="fa fa-infinity"></i>
      </li>
      </ul>
      <br>
      <?php
  }

  public static function delete_all_by_quote($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM comments WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function mysql_date_to_english_format($mysql_date){
    $parts_mysql_date = explode('-', $mysql_date);
    $english_format = $parts_mysql_date[1] . '/' . $parts_mysql_date[2] . '/' . $parts_mysql_date[0];
    return $english_format;
  }

  public static function mysql_datetime_to_english_format($mysql_datetime){
    $parts_mysql_datetime = explode(' ', $mysql_datetime);
    $date = $parts_mysql_datetime[0];
    $time = $parts_mysql_datetime[1];
    $date = self::mysql_date_to_english_format($date);
    $english_format = $date . ' ' . $time;
    return $english_format;
  }

  public static function english_format_to_mysql_date($english_format){
    $parts_english_format = explode('/', $english_format);
    $mysql_date = $parts_english_format[2] . '-' . $parts_english_format[0] . '-' . $parts_english_format[1];
    $mysql_date = strtotime($mysql_date);
    $mysql_date = date('Y-m-d', $mysql_date);
    return $mysql_date;
  }

  public static function english_format_to_mysql_datetime($english_format){
    $parts_english_format = explode(' ', $english_format);
    $date = $parts_english_format[0];
    $time = $parts_english_format[1];
    $parts_date = explode('/', $date);
    $date = $parts_date[2] . '-' . $parts_date[0] . '-' . $parts_date[1];
    $mysql_datetime = $date . ' ' . $time;
    $mysql_datetime = strtotime($mysql_datetime);
    $mysql_datetime = date('Y-m-d H:i:s', $mysql_datetime);
    return $mysql_datetime;
  }
}
?>
