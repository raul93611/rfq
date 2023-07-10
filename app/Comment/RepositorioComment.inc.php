<?php
class RepositorioComment{
  public static function insertar_comment($conexion, $comment){
    if(isset($conexion)){
      try{
        $sql = 'INSERT INTO comments (id_rfq, id_usuario, comment, fecha_comment) VALUES(:id_rfq, :id_usuario, :comment, NOW())';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindValue(':id_rfq', $comment-> obtener_id_rfq(), PDO::PARAM_STR);
        $sentencia-> bindValue(':id_usuario', $comment-> obtener_id_usuario(), PDO::PARAM_STR);
        $sentencia-> bindValue(':comment', $comment-> obtener_comment(), PDO::PARAM_STR);
        $sentencia-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function contar_todos_comentarios_quote($conexion, $id_rfq){
    $todos_comentarios_quote = 0;
    if(isset($conexion)){
      try{
        $sql = 'SELECT COUNT(*) as todos_comentarios_quote FROM comments WHERE id_rfq = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetch(PDO::FETCH_ASSOC);
        if(!empty($resultado)){
          $todos_comentarios_quote = $resultado['todos_comentarios_quote'];
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $todos_comentarios_quote;
  }

  public static function obtener_comments_de_un_rfq($conexion, $id_rfq){
    $comments = [];
    if(isset($conexion)){
      try{
        $sql = 'SELECT * FROM comments WHERE id_rfq = :id_rfq ORDER BY fecha_comment DESC';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
        $resultado = $sentencia-> fetchAll(PDO::FETCH_ASSOC);
        if(count($resultado)){
          foreach ($resultado as $fila) {
            $comments[] = new Comment($fila['id'], $fila['id_rfq'], $fila['id_usuario'], $fila['comment'], $fila['fecha_comment']);
          }
        }
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $comments;
  }

  public static function escribir_comments($id_rfq){
    Conexion::abrir_conexion();
    $cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
    $comments = self::obtener_comments_de_un_rfq(Conexion::obtener_conexion(), $id_rfq);
    Conexion::cerrar_conexion();
    ?>
    <div class="timeline">
      <div>
        <i class="fa fa-bookmark"></i>
        <div class="timeline-item">
          <h3 class="timeline-header">Proposal: <?php echo $cotizacion-> obtener_id(); ?></h3>
        </div>
      </div>
      <?php
      if(count($comments)){
        foreach ($comments as $comment) {
          $fecha_comment = self::mysql_datetime_to_english_format($comment-> obtener_fecha_comment());
          ?>
          <div>
            <i class="fa fa-user"></i>
            <div class="timeline-item">
              <span class="time"><i class="far fa-clock"></i> <?php echo $fecha_comment; ?></span>
              <h3 class="timeline-header">
                <span class="text-primary">
                  <?php
                  Conexion::abrir_conexion();
                  $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $comment-> obtener_id_usuario());
                  Conexion::cerrar_conexion();
                  echo $usuario-> obtener_nombre_usuario();
                  ?>
                </span>
                said</h3>
                <div class="timeline-body">
                  <?php echo nl2br($comment-> obtener_comment()); ?>
                </div>
              </div>
          </div>
          <?php
        }
      }
      ?>
      <div>
        <i class="fa fa-infinity"></i>
      </div>
    </div>
    <br>
    <?php
  }

  public static function delete_all_comments($conexion, $id_rfq){
    if(isset($conexion)){
      try{
        $sql = 'DELETE FROM comments WHERE id_rfq = :id_rfq';
        $sentencia = $conexion-> prepare($sql);
        $sentencia-> bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia-> execute();
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
