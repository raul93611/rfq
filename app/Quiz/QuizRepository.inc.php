<?php
class QuizRepository{
  public static function get_by_id_quote($database, $id_quote){
    $quiz = null;
    if(isset($database)){
      try{
        $sql = 'SELECT * FROM quizzes WHERE id_quote = :id_quote';
        $query = $database-> prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
        $result = $query-> fetch();
        if(!empty($result)){
          $quiz = new Quiz($result['id'], $result['id_quote'], $result['reach_objectives'], $result['cost_objectives'], $result['time_objectives'], $result['quality_objectives'], $result['reach_goals'], $result['cost_goals'], $result['time_goals'], $result['quality_goals'], $result['locations']);
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $quiz;
  }

  public static function delete_by_id_quote($database, $id_quote){
    if(isset($database)){
      try{
        $sql = 'DELETE FROM quizzes WHERE id_quote = :id_quote';
        $query= $database->prepare($sql);
        $query-> bindParam(':id_quote', $id_quote, PDO::PARAM_STR);
        $query-> execute();
      }catch(PDOException $ex){
        print "ERROR:" . $ex->getMessage() . "<br>";
      }
    }
  }
}
?>
