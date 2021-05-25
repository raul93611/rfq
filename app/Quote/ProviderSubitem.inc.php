<?php
class ProviderSubitem{
  private $id;
  private $id_subitem;
  private $provider;
  private $price;

  public function __construct($id, $id_subitem, $provider, $price){
    $this-> id = $id;
    $this-> id_subitem = $id_subitem;
    $this-> provider = $provider;
    $this-> price = $price;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_subitem(){
    return $this-> id_subitem;
  }

  public function get_provider(){
    return $this-> provider;
  }

  public function get_price(){
    return $this-> price;
  }
}
?>
