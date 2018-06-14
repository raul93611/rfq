<?php
class Item{
    private $id;
    private $id_rfq;
    private $id_usuario;
    private $brand;
    private $part_number;
    private $description;
    private $quantity;
    
    public function __construct($id, $id_rfq, $id_usuario, $brand, $part_number, $description, $quantity){
        $this-> id = $id;
        $this-> id_rfq = $id_rfq;
        $this-> id_usuario = $id_usuario;
        $this-> brand = $brand;
        $this-> part_number = $part_number;
        $this-> description = $description;
        $this-> quantity = $quantity;
    }
    
    public function obtener_id(){
        return $this-> id;
    }
    
    public function obtener_id_rfq(){
        return $this-> id_rfq;
    }
    
    public function obtener_id_usuario(){
        return $this-> id_usuario;
    }
    
    public function obtener_brand(){
        return $this-> brand;
    }
    
    public function obtener_part_number(){
        return $this-> part_number;
    }
    
    public function obtener_description(){
        return $this-> description;
    }
    
    public function obtener_quantity(){
        return $this-> quantity;
    }
}
?>
