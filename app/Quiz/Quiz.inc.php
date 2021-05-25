<?php
class Quiz{
  private $id;
  private $id_quote;
  private $reach_objectives;
  private $cost_objectives;
  private $time_objectives;
  private $quality_objectives;
  private $reach_goals;
  private $cost_goals;
  private $time_goals;
  private $quality_goals;
  private $locations;

  public function __construct($id, $id_quote, $reach_objectives, $cost_objectives, $time_objectives, $quality_objectives, $reach_goals, $cost_goals, $time_goals, $quality_goals, $locations){
    $this-> id = $id;
    $this-> id_quote = $id_quote;
    $this-> reach_objectives = $reach_objectives;
    $this-> cost_objectives = $cost_objectives;
    $this-> time_objectives = $time_objectives;
    $this-> quality_objectives = $quality_objectives;
    $this-> reach_goals = $reach_goals;
    $this-> cost_goals = $cost_goals;
    $this-> time_goals = $time_goals;
    $this-> quality_goals = $quality_goals;
    $this-> locations = $locations;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_quote(){
    return $this-> id_quote;
  }

  public function get_reach_objectives(){
    return $this-> reach_objectives;
  }

  public function get_cost_objectives(){
    return $this-> cost_objectives;
  }

  public function get_time_objectives(){
    return $this-> time_objectives;
  }

  public function get_quality_objectives(){
    return $this-> quality_objectives;
  }

  public function get_reach_goals(){
    return $this-> reach_goals;
  }

  public function get_cost_goals(){
    return $this-> cost_goals;
  }

  public function get_time_goals(){
    return $this-> time_goals;
  }

  public function get_quality_goals(){
    return $this-> quality_goals;
  }

    public function get_locations(){
    return $this-> locations;
  }
}
?>
