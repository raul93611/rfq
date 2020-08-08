<?php
class TypeOfBid{
  private $id;
  private $type_of_bid;

  public function __construct($id, $type_of_bid){
    $this-> id = $id;
    $this-> type_of_bid = $type_of_bid;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_type_of_bid(){
    return $this-> type_of_bid;
  }
}
?>
