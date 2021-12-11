<?php
class TypeOfContract{
  private $id;
  private $type_of_contract;

  public function __construct($id, $type_of_contract){
    $this-> id = $id;
    $this-> type_of_contract = $type_of_contract;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_type_of_contract(){
    return $this-> type_of_contract;
  }
}
?>
