<?php
class TaskRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Task($row['id'], $row['id_user'], $row['assigned_user'], $row['id_rfq'], $row['title'], $row['message'], $row['status']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new Task($row['id'], $row['id_user'], $row['assigned_user'], $row['id_rfq'], $row['title'], $row['message'], $row['status']);

    return $object;
  }

  public static function insert($connection, $task){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO tasks(id_user, assigned_user, id_rfq, title, message, status) VALUES(:id_user, :assigned_user, :id_rfq, :title, :message, :status)';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_user', $task-> get_id_user(), PDO::PARAM_STR);
        $sentence-> bindParam(':assigned_user', $task-> get_assigned_user(), PDO::PARAM_STR);
        $sentence-> bindParam(':id_rfq', $task-> get_id_rfq(), PDO::PARAM_STR);
        $sentence-> bindParam(':title', $task-> get_title(), PDO::PARAM_STR);
        $sentence-> bindParam(':message', $task-> get_message(), PDO::PARAM_STR);
        $sentence-> bindParam(':status', $task-> get_status(), PDO::PARAM_STR);
        $sentence-> execute();
        $id = $connection->lastInsertId();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function update($connection, $task, $id_task){
    if(isset($connection)){
      try{
        $sql = 'UPDATE tasks SET assigned_user = :assigned_user, status = :status WHERE id = :id_task';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':assigned_user', $task-> get_assigned_user(), PDO::PARAM_STR);
        $sentence-> bindParam(':status', $task-> get_status(), PDO::PARAM_STR);
        $sentence-> bindParam(':id_task', $id_task, PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_todo($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "todo" ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_in_progress($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "in_progress" ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_done($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "done" ORDER BY id DESC LIMIT 10';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_all_done($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "done" ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_my_todo($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "todo" AND assigned_user = :assigned_user ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':assigned_user', $_SESSION['id_usuario'], PDO::PARAM_STR);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_my_in_progress($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "in_progress" AND assigned_user = :assigned_user ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':assigned_user', $_SESSION['id_usuario'], PDO::PARAM_STR);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_my_done($connection){
    $tasks = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE status = "done" AND assigned_user = :assigned_user ORDER BY id DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':assigned_user', $_SESSION['id_usuario'], PDO::PARAM_STR);
        $sentence-> execute();
        $tasks = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $tasks;
  }

  public static function get_one($connection, $id_task){
    $item = null;
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM tasks WHERE id = :id_task';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_task', $id_task, PDO::PARAM_STR);
        $sentence-> execute();
        $item = self::single_result_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
?>
