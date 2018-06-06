<?php
class Equipo{
    private $id;
    private $id_rfq;
    private $brand;
    private $part_number;
    private $description;
    private $quantity;
    private $unit_price;
    private $total;
    
    public function __construct($id, $id_rfq, $brand, $part_number, $description, $quantity, $unit_price, $total){
        $this-> id = $id;
        $this-> id_rfq = $id_rfq;
        $this-> brand = $brand;
        $this-> part_number = $part_number;
        $this-> description = $description;
        $this-> quantity = $quantity;
        $this-> unit_price = $unit_price;
        $this-> total = $total;
    }
    
    public function obtener_id(){
        return $this-> id;
    }
    
    public function obtener_id_rfq(){
        return $this-> id_rfq;
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
    
    public function obtener_unit_price(){
        return $this-> unit_price;
    }
    
    public function obtener_total(){
        return $this-> total;
    }
}
?>
