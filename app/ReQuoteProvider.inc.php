<?php
class ReQuoteProvider{
  private $id;
  private $id_re_quote_item;
  private $provider;
  private $price;

  public function __construct($id, $id_re_quote_item, $provider, $price){
    $this-> id = $id;
    $this-> id_re_quote_item = $id_re_quote_item;
    $this-> provider = $provider;
    $this-> price = $price;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_re_quote_item(){
    return $this-> id_re_quote_item;
  }

  public function get_provider(){
    return $this-> provider;
  }

  public function get_price(){
    return $this-> price;
  }
}
?>
