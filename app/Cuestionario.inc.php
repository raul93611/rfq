<?php
class Cuestionario{
    private $id;
    private $id_rfq;
    private $reach_objectives;
    private $cost_objectives;
    private $time_objectives;
    private $quality_objectives;
    private $reach_goals;
    private $cost_goals;
    private $time_goals;
    private $quality_goals;
    
    public function __construct($id, $id_rfq, $reach_objectives, $cost_objectives, $time_objectives, $quality_objectives, $reach_goals, $cost_goals, $time_goals, $quality_goals){
        $this-> id = $id;
        $this-> id_rfq = $id_rfq;
        $this-> reach_objectives = $reach_objectives;
        $this-> cost_objectives = $cost_objectives;
        $this-> time_objectives = $time_objectives;
        $this-> quality_objectives = $quality_objectives;
        $this-> reach_goals = $reach_goals;
        $this-> cost_goals = $cost_goals;
        $this-> time_goals = $time_goals;
        $this-> quality_goals = $quality_goals;
    }
    
    public function obtener_id(){
        return $this-> id;
    }
    
    public function obtener_id_rfq(){
        return $this-> id_rfq;
    }
    
    public function obtener_reach_objectives(){
        return $this-> reach_objectives;
    }
    
    public function obtener_cost_objectives(){
        return $this-> cost_objectives;
    }
    
    public function obtener_time_objectives(){
        return $this-> time_objectives;
    }
    
    public function obtener_quality_objectives(){
        return $this-> quality_objectives;
    }
    
    public function obtener_reach_goals(){
        return $this-> reach_goals;
    }
    
    public function obtener_cost_goals(){
        return $this-> cost_goals;
    }
    
    public function obtener_time_goals(){
        return $this-> time_goals;
    }
    
    public function obtener_quality_goals(){
        return $this-> quality_goals;
    }
    
}
?>
