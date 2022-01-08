<?php
class Tracking{
  private $id;
  private $id_item;
  private $quantity;
  private $carrier;
  private $tracking_number;
  private $delivery_date;
  private $due_date;
  private $signed_by;
  private $comments;

  public function __construct($id, $id_item, $quantity, $carrier, $tracking_number, $delivery_date, $due_date, $signed_by, $comments){
    $this-> id = $id;
    $this-> id_item = $id_item;
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

  public function get_id_item(){
    return $this-> id_item;
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
