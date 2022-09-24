<?php
class ReQuote{
  private $id;
  private $id_rfq;
  private $total_cost;
  private $total_price;
  private $payment_terms;
  private $taxes;
  private $profit;
  private $additional;
  private $shipping_cost;
  private $shipping;
  private $services_payment_term;

  public function __construct($id, $id_rfq, $total_cost, $total_price, $payment_terms, $taxes, $profit, $additional, $shipping_cost, $shipping, $services_payment_term){
    $this-> id = $id;
    $this-> id_rfq = $id_rfq;
    $this-> total_cost = $total_cost;
    $this-> total_price = $total_price;
    $this-> payment_terms = $payment_terms;
    $this-> taxes = $taxes;
    $this-> profit = $profit;
    $this-> additional = $additional;
    $this-> shipping_cost = $shipping_cost;
    $this-> shipping = $shipping;
    $this-> services_payment_term = $services_payment_term;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_rfq(){
    return $this-> id_rfq;
  }

  public function get_total_cost(){
    return $this-> total_cost;
  }

  public function get_total_price(){
    return $this-> total_price;
  }

  public function get_payment_terms(){
    return $this-> payment_terms;
  }

  public function get_taxes(){
    return $this-> taxes;
  }

  public function get_profit(){
    return $this-> profit;
  }

  public function get_additional(){
    return $this-> additional;
  }

  public function get_shipping_cost(){
    return $this-> shipping_cost;
  }

  public function get_shipping(){
    return $this-> shipping;
  }

  public function get_services_payment_term(){
    return $this-> services_payment_term;
  }
}
?>
