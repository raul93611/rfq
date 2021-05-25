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

  public function get_id(){
    return $this-> id;
  }

  public function get_id_item(){
    return $this-> id_item;
  }

  public function get_provider(){
    return $this-> provider;
  }

  public function get_price(){
    return $this-> price;
  }
}
?>
