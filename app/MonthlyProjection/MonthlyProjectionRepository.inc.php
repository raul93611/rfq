<?php
class MonthlyProjectionRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new MonthlyProjection($row['id'], $row['yearly_projection_id'], $row['month'], $row['projected_amount']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new MonthlyProjection($row['id'], $row['yearly_projection_id'], $row['month'], $row['projected_amount']);

    return $object;
  }

  public static function getById($connection, $id) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM monthly_projections WHERE id = :id';
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

  public static function update($connection, $projected_amount, $id) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE monthly_projections SET projected_amount = :projected_amount WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':projected_amount', $projected_amount, PDO::PARAM_STR);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }
}
