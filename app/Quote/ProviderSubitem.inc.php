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

  public function obtener_id(){
    return $this-> id;
  }

  public function obtener_id_subitem(){
    return $this-> id_subitem;
  }

  public function obtener_provider(){
    return $this-> provider;
  }

  public function obtener_price(){
    return $this-> price;
  }
}
?>
