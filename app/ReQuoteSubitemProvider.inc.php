<?php
class ReQuoteSubitemProvider{
  private $id;
  private $id_re_quote_subitem;
  private $provider;
  private $price;

  public function __construct($id, $id_re_quote_subitem, $provider, $price){
    $this-> id = $id;
    $this-> id_re_quote_subitem = $id_re_quote_subitem;
    $this-> provider = $provider;
    $this-> price = $price;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_re_quote_subitem(){
    return $this-> id_re_quote_subitem;
  }

  public function get_provider(){
    return $this-> provider;
  }

  public function get_price(){
    return $this-> price;
  }
}
?>
