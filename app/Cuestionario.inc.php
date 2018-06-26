<?php
class Cuestionario{
    private $id;
    private $id_rfq;
    
    public function __construct($id, $id_rfq){
        $this-> id = $id;
        $this-> id_rfq = $id_rfq;
    }
    
    public function obtener_id(){
        return $this-> id;
    }
    
    public function obtener_id_rfq(){
        return $this-> id_rfq;
    }
}
?>
