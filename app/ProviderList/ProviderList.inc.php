<?php
class ProviderList{
  private $id;
  private $company_name;

  public function __construct($id, $company_name){
    $this-> id = $id;
    $this-> company_name = $company_name;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_company_name(){
    return $this-> company_name;
  }
}
?>
