<?php 
class Personnel{
  private $id;
  private $name;
  private $criteria;
  private $id_type_of_project;

  public function __construct($id, $name, $criteria, $id_type_of_project) {
    $this->id = $id;
    $this->name = $name;
    $this->criteria = $criteria;
    $this->id_type_of_project = $id_type_of_project;
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

  public function getIdTypeOfProject() {
    return $this->id_type_of_project;
  }
}
