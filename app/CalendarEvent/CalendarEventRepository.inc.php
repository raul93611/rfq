<?php 
class CalendarEventRepository {
  public static function getAll($conexion) {
    $data = [];
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        id_personnel as 'group',
        start,
        end,
        'range' as 'type',
        name as content,
        name as title
        FROM calendar_events 
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        while ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new CalendarEvent($row['id'], $row['id_personnel'], $row['name'], $row['start'], $row['end'], $row['end']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new CalendarEvent($row['id'], $row['id_personnel'], $row['name'], $row['start'], $row['end']);

    return $object;
  }

  public static function save($connection, $event){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO calendar_events(id_personnel, name, start, end) VALUES(:id_personnel, :name, STR_TO_DATE(:start, "%m/%d/%Y"), STR_TO_DATE(:end, "%m/%d/%Y"))';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id_personnel', $event-> getIdPersonnel(), PDO::PARAM_STR);
        $sentence-> bindValue(':name', $event-> getName(), PDO::PARAM_STR);
        $sentence-> bindValue(':start', $event-> getStart(), PDO::PARAM_STR);
        $sentence-> bindValue(':end', $event-> getEnd(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getById($connection, $id){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM calendar_events WHERE id = :id';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id', $id, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function update($connection, $name, $start, $end, $id){
    if(isset($connection)){
      try{
        $sql = 'UPDATE calendar_events SET name = :name, start = STR_TO_DATE(:start, "%m/%d/%Y"), end = STR_TO_DATE(:end, "%m/%d/%Y") WHERE id = :id';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':name', $name, PDO::PARAM_STR);
        $sentence-> bindValue(':start', $start, PDO::PARAM_STR);
        $sentence-> bindValue(':end', $end, PDO::PARAM_STR);
        $sentence-> bindValue(':id', $id, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id){
    if(isset($connection)){
      try{
        $sql = 'DELETE FROM calendar_events WHERE id = :id';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindValue(':id', $id, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
