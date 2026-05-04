<?php
class RoomRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Room($row['id'], $row['id_rfq'], $row['name'], $row['color']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new Room($row['id'], $row['id_rfq'], $row['name'], $row['color']);

    return $object;
  }

  public static function getById($connection, $id) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM rooms WHERE id = :id';
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

  public static function save($connection, $room) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO rooms(id_rfq, name, color) VALUES(:id_rfq, :name, :color)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $room->getIdRfq(), PDO::PARAM_STR);
        $sentence->bindValue(':name', $room->getName(), PDO::PARAM_STR);
        $sentence->bindValue(':color', $room->getColor(), PDO::PARAM_STR);
        $sentence->execute();
        $id = $connection->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function getAll($conexion, $id_rfq) {
    $data = [];
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT *
        FROM rooms
        WHERE id_rfq = :id_rfq
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentencia->execute();
        $data = self::array_to_object($sentencia);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function update($connection, $name, $color, $id) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE rooms SET name = :name, color = :color WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->bindValue(':color', $color, PDO::PARAM_STR);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id) {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM rooms WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function copyRooms($connection, $id_rfq, $id_rfq_copy) {
    // Fetch all rooms associated with the original RFQ
    $rooms = self::getAll($connection, $id_rfq);

    // Check if there are any rooms to copy
    if (!empty($rooms)) {
      foreach ($rooms as $room) {
        // Create a new Room object with the details from the original room
        $duplicated_room = new Room('', $id_rfq_copy, $room->getName(), $room->getColor());

        // Save the new duplicated room
        self::save($connection, $duplicated_room);
      }
    }
  }
}
