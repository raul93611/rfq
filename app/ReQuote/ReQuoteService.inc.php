<?php
class ReQuoteService{
  private $id;
  private $id_re_quote;
  private $description;
  private $quantity;
  private $unit_price;
  private $total_price;

  public function __construct($id, $id_re_quote, $description, $quantity, $unit_price, $total_price){
    $this-> id = $id;
    $this-> id_re_quote = $id_re_quote;
    $this-> description = $description;
    $this-> quantity = $quantity;
    $this-> unit_price = $unit_price;
    $this-> total_price = $total_price;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_re_quote(){
    return $this-> id_re_quote;
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
}
?>
