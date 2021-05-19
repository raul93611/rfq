<?php
class TrackingSubitem{
  private $id;
  private $id_subitem;
  private $quantity;
  private $tracking_number;
  private $delivery_date;
  private $signed_by;

  public function __construct($id, $id_subitem, $quantity, $tracking_number, $delivery_date, $signed_by){
    $this-> id = $id;
    $this-> id_subitem = $id_subitem;
    $this-> quantity = $quantity;
    $this-> tracking_number = $tracking_number;
    $this-> delivery_date = $delivery_date;
    $this-> signed_by = $signed_by;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_subitem(){
    return $this-> id_subitem;
  }

  public function get_quantity(){
    return $this-> quantity;
  }

  public function get_tracking_number(){
    return $this-> tracking_number;
  }

  public function get_delivery_date(){
    return $this-> delivery_date;
  }

  public function get_signed_by(){
    return $this-> signed_by;
  }
}
?>
