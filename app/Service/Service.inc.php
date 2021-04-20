<?php
class Service{
  private $id;
  private $id_rfq;
  private $description;
  private $quantity;
  private $unit_price;
  private $total_price;
  private $fulfillment_profit;

  public function __construct($id, $id_rfq, $description, $quantity, $unit_price, $total_price, $fulfillment_profit){
    $this-> id = $id;
    $this-> id_rfq = $id_rfq;
    $this-> description = $description;
    $this-> quantity = $quantity;
    $this-> unit_price = $unit_price;
    $this-> total_price = $total_price;
    $this-> fulfillment_profit = $fulfillment_profit;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_rfq(){
    return $this-> id_rfq;
  }

  public function get_description(){
    return $this-> description;
  }

  public function get_quantity(){
    return $this-> quantity;
  }

  public function get_unit_price(){
    return $this-> unit_price;
  }

  public function get_total_price(){
    return $this-> total_price;
  }

  public function get_fulfillment_profit(){
    return $this-> fulfillment_profit;
  }
}
?>
