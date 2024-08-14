<?php
class YearlyProjection {
  private $id;
  private $year;

  public function __construct($id, $year) {
    $this->id = $id;
    $this->year = $year;
  }

  public function getId() {
    return $this->id;
  }

  public function getYear() {
    return $this->year;
  }
}
