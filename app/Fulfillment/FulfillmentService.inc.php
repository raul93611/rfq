<?php
class FulfillmentService{
  private $id;
  private $id_service;
  private $provider;
  private $quantity;
  private $unit_cost;
  private $other_cost;
  private $real_cost;
  private $payment_term;
  private $reviewed;
  private $created_at;

  public function __construct($id, $id_service, $provider, $quantity, $unit_cost, $other_cost, $real_cost, $payment_term, $reviewed, $created_at){
    $this-> id = $id;
    $this-> id_service = $id_service;
    $this-> provider = $provider;
    $this-> quantity = $quantity;
    $this-> unit_cost = $unit_cost;
    $this-> other_cost = $other_cost;
    $this-> real_cost = $real_cost;
    $this-> payment_term = $payment_term;
    $this-> reviewed = $reviewed;
    $this-> created_at = $created_at;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_service(){
    return $this-> id_service;
  }

  public function get_provider(){
    return $this-> provider;
  }

  public function get_quantity(){
    return $this-> quantity;
  }

  public function get_unit_cost(){
    return $this-> unit_cost;
  }

  public function get_other_cost(){
    return $this-> other_cost;
  }

  public function get_real_cost(){
    return $this-> real_cost;
  }

  public function get_payment_term(){
    return $this-> payment_term;
  }

  public function get_reviewed(){
    return $this-> reviewed;
  }

  public function get_created_at(){
    return $this-> created_at;
  }
}
?>
