<?php
class PriorityLevel {
  private $id;
  private $name;
  private $weight;

  public function __construct($id, $name, $weight) {
    $this->id = $id;
    $this->name = $name;
    $this->weight = $weight;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getWeight() {
    return $this->weight;
  }
}
