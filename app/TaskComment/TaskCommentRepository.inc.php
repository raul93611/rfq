<?php
class TaskCommentRepository{
  public static function array_to_object($sentence){
    $objects = [];
    while ($row = $sentence-> fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new TaskComment($row['id'], $row['id_task'], $row['id_user'], $row['comment'], $row['created_at']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence){
    $row = $sentence-> fetch(PDO::FETCH_ASSOC);
    $object = new TaskComment($row['id'], $row['id_task'], $row['id_user'], $row['comment'], $row['created_at']);

    return $object;
  }

  public static function validate($request){
    $comment = Input::test_input($request['comment']);
    $task_comment = !empty($comment) ?  new TaskComment('', $request['id_task'], $_SESSION['user']-> obtener_id(), $comment, '') : false;

    return $task_comment;
  }

  public static function insert($connection, $comment){
    if(isset($connection)){
      try{
        $sql = 'INSERT INTO task_comments(id_task, id_user, comment, created_at) VALUES(:id_task, :id_user, :comment, NOW())';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_task', $comment-> get_id_task(), PDO::PARAM_STR);
        $sentence-> bindParam(':id_user', $comment-> get_id_user(), PDO::PARAM_STR);
        $sentence-> bindParam(':comment', $comment-> get_comment(), PDO::PARAM_STR);
        $sentence-> execute();
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_all($connection, $id_task){
    $comments = [];
    if(isset($connection)){
      try{
        $sql = 'SELECT * FROM task_comments WHERE id_task = :id_task ORDER BY created_at DESC';
        $sentence = $connection-> prepare($sql);
        $sentence-> bindParam(':id_task', $id_task, PDO::PARAM_STR);
        $sentence-> execute();
        $comments = self::array_to_object($sentence);
      }catch(PDOException $ex){
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $comments;
  }
}
?>
