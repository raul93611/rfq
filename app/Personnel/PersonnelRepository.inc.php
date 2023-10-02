<?php
class PersonnelRepository {
  public static function getPersonnel($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 1:
        $sort_column = 'p.name';
        break;
      case 2:
        $sort_column = 'p.criteria';
        break;
      case 3:
        $sort_column = 'type';
        break;
      default:
        $sort_column = 'p.name';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT p.id, 
        p.name, 
        p.criteria,
        t.name as type,
        NULL AS options  
        FROM personnel p
        LEFT JOIN types_of_projects t ON t.id = p.id_type_of_project
        WHERE 
        (p.name LIKE :search OR t.name LIKE :search)
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
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

  public static function getTotalPersonnelCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM personnel 
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredPersonnelCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(p.id) 
        FROM personnel p
        LEFT JOIN types_of_projects t ON t.id = p.id_type_of_project
        WHERE 
        (p.name LIKE :search OR t.name LIKE :search)
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->bindValue(':search', $search, PDO::PARAM_STR);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Personnel($row['id'], $row['name'], $row['criteria'], $row['id_type_of_project']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new Personnel($row['id'], $row['name'], $row['criteria'], $row['id_type_of_project']);

    return $object;
  }

  public static function save($connection, $personnel) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO personnel(name, criteria, id_type_of_project) VALUES(:name, :criteria, :id_type_of_project)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $personnel->getName(), PDO::PARAM_STR);
        $sentence->bindValue(':criteria', $personnel->getCriteria(), PDO::PARAM_STR);
        $sentence->bindValue(':id_type_of_project', $personnel->getIdTypeOfProject(), PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getById($connection, $id) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM personnel WHERE id = :id';
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

  public static function update($connection, $name, $criteria, $id_type_of_project, $id) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE personnel SET name = :name, criteria = :criteria, id_type_of_project = :id_type_of_project WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->bindValue(':criteria', $criteria, PDO::PARAM_STR);
        $sentence->bindValue(':id_type_of_project', $id_type_of_project, PDO::PARAM_STR);
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
        $sql = 'DELETE FROM personnel WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getAll($conexion) {
    $data = [];
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        CONCAT(name, ' ', IFNULL(CONCAT('(', criteria, ')'), '')) as content
        FROM personnel
        ORDER BY id_type_of_project, name ASC
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
}
