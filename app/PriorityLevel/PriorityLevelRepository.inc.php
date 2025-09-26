<?php
class PriorityLevelRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new PriorityLevel($row['id'], $row['name'], $row['weight']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new PriorityLevel($row['id'], $row['name'], $row['weight']);

    return $object;
  }

  public static function getById($connection, $id) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM priority_levels WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
        $item = self::single_result_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function getAll($conexion) {
    $data = [];
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT *
        FROM priority_levels
        ORDER BY weight DESC
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
        $data = self::array_to_object($sentencia);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }
}
