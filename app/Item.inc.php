<?php
class Item{
    private $id;
    private $id_rfq;
    private $id_usuario;
    private $provider_menor;
    private $brand;
    private $brand_project;
    private $part_number;
    private $part_number_project;
    private $description;
    private $description_project;
    private $quantity;
    private $unit_price;
    private $total_price;
    private $comments;
    private $website;
    
    public function __construct($id, $id_rfq, $id_usuario, $provider_menor, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $unit_price, $total_price, $comments, $website){
        $this-> id = $id;
        $this-> id_rfq = $id_rfq;
        $this-> id_usuario = $id_usuario;
        $this-> provider_menor = $provider_menor;
        $this-> brand = $brand;
        $this-> brand_project = $brand_project;
        $this-> part_number = $part_number;
        $this-> part_number_project = $part_number_project;
        $this-> description = $description;
        $this-> description_project = $description_project;
        $this-> quantity = $quantity;
        $this-> unit_price = $unit_price;
        $this-> total_price = $total_price;
        $this-> comments = $comments;
        $this-> website = $website;
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
    
    public function obtener_provider_menor(){
        return $this-> provider_menor;
    }

    public function obtener_brand(){
        return $this-> brand;
    }
    
    public function obtener_brand_project(){
        return $this-> brand_project;
    }
    
    public function obtener_part_number(){
        return $this-> part_number;
    }
    
    public function obtener_part_number_project(){
        return $this-> part_number_project;
    }
    
    public function obtener_description(){
        return $this-> description;
    }
    
    public function obtener_description_project(){
        return $this-> description_project;
    }
    
    public function obtener_quantity(){
        return $this-> quantity;
    }
    
    public function obtener_unit_price(){
        return $this-> unit_price;
    }
    
    public function obtener_total_price(){
        return $this-> total_price;
    }
    
    public function obtener_comments(){
        return $this-> comments;
    }
    
    public function obtener_website(){
        return $this-> website;
    }
}
?>
