<?php
class PaymentTerm{
  private $id;
  private $payment_term;

  public function __construct($id, $payment_term){
    $this-> id = $id;
    $this-> payment_term = $payment_term;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_payment_term(){
    return $this-> payment_term;
  }
}
?>
