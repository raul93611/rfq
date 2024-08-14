<?php
class Room {
  private $id;
  private $id_rfq;
  private $name;
  private $color;

  public function __construct($id, $id_rfq, $name, $color) {
    $this->id = $id;
    $this->id_rfq = $id_rfq;
    $this->name = $name;
    $this->color = $color;
  }

  public function getId() {
    return $this->id;
  }

  public function getIdRfq() {
    return $this->id_rfq;
  }

  public function getName() {
    return $this->name;
  }

  public function getColor() {
    return $this->color;
  }
}
