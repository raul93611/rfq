<?php
class TypeOfProjectRepository {
  public static function getTypesOfProjects($conexion, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 1:
        $sort_column = 'name';
        break;
      default:
        $sort_column = 'name';
        break;
    }
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT id, 
        name, 
        NULL AS options  
        FROM types_of_projects 
        WHERE 
        name LIKE :search 
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

  public static function getTotalTypesOfProjectsCount($conexion) {
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM types_of_projects 
        ";
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentencia->fetchColumn();
  }

  public static function getTotalFilteredTypesOfProjectsCount($conexion, $search) {
    $search = '%' . $search . '%';
    if (isset($conexion)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM types_of_projects 
        WHERE 
        name LIKE :search
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
      $objects[] = new TypeOfProject($row['id'], $row['name']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new TypeOfProject($row['id'], $row['name']);

    return $object;
  }

  public static function save($connection, $typeOfProject) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO types_of_projects(name) VALUES(:name)';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $typeOfProject->getName(), PDO::PARAM_STR);
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
        $sql = 'SELECT * FROM types_of_projects WHERE id = :id';
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

  public static function update($connection, $name, $id) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE types_of_projects SET name = :name WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
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
        $sql = 'DELETE FROM types_of_projects WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getAll($connection){
    $items = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM types_of_projects ORDER BY id';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $items = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }
}
