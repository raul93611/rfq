<?php
class OutOfScope{
    private $id;
    private $id_cuestionario;
    private $requirement;
    
    public function __construct($id, $id_cuestionario, $requirement){
        $this-> id = $id;
        $this-> id_cuestionario = $id_cuestionario;
        $this-> requirement = $requirement;
    }
    
    public function obtener_id(){
        return $this-> id; 
    }
    
    public function obtener_id_cuestionario(){
        return $this-> id_cuestionario; 
    }
    
    public function obtener_requirement(){
        return $this-> requirement; 
    }
}
?>
