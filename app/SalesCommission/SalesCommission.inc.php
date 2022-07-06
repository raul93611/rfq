<?php
class SalesCommission{
  private $id;
  private $sales_commission;

  public function __construct($id, $sales_commission){
    $this-> id = $id;
    $this-> sales_commission = $sales_commission;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_sales_commission(){
    return $this-> sales_commission;
  }
}
?>
