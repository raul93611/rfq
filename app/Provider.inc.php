<?php
class Provider{
  private $id;
  private $id_item;
  private $provider;
  private $price;

  public function __construct($id, $id_item, $provider, $price){
    $this-> id = $id;
    $this-> id_item = $id_item;
    $this-> provider = $provider;
    $this-> price = $price;
  }

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_item(){
    return $this-> id_item;
  }

  public function obtener_provider(){
    return $this-> provider;
  }

  public function obtener_price(){
    return $this-> price;
  }
}
?>
