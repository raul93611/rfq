<?php 
class Personnel{
  private $id;
  private $name;
  private $criteria;

  public function __construct($id, $name, $criteria) {
    $this->id = $id;
    $this->name = $name;
    $this->criteria = $criteria;
  }

  public function getId() {
    return $this->id;
  }

  public function getName() {
    return $this->name;
  }

  public function getCriteria() {
    return $this->criteria;
  }
}
