<?php
class TrackingSubitem{
  private $id;
  private $id_subitem;
  private $quantity;
  private $carrier;
  private $tracking_number;
  private $delivery_date;
  private $due_date;
  private $signed_by;
  private $comments;

  public function __construct($id, $id_subitem, $quantity, $carrier, $tracking_number, $delivery_date, $due_date, $signed_by, $comments){
    $this-> id = $id;
    $this-> id_subitem = $id_subitem;
    $this-> quantity = $quantity;
    $this-> carrier = $carrier;
    $this-> tracking_number = $tracking_number;
    $this-> delivery_date = $delivery_date;
    $this-> due_date = $due_date;
    $this-> signed_by = $signed_by;
    $this-> comments = $comments;
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

  public function get_carrier(){
    return $this-> carrier;
  }

  public function get_tracking_number(){
    return $this-> tracking_number;
  }

  public function get_delivery_date(){
    return $this-> delivery_date;
  }

  public function get_due_date(){
    return $this-> due_date;
  }

  public function get_signed_by(){
    return $this-> signed_by;
  }

  public function get_comments(){
    return $this-> comments;
  }
}
?>
