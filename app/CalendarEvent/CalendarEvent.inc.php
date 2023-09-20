<?php
class CalendarEvent{
  private $id;
  private $id_personnel;
  private $name;
  private $start;
  private $end;
  private $color;

  public function __construct($id, $id_personnel, $name, $start, $end, $color) {
    $this->id = $id;
    $this->id_personnel = $id_personnel;
    $this->name = $name;
    $this->start = $start;
    $this->end = $end;
    $this->color = $color;
  }

  public function getId(){
    return $this->id;
  }

  public function getIdPersonnel(){
    return $this->id_personnel;
  }

  public function getName(){
    return $this->name;
  }

  public function getStart(){
    return $this->start;
  }

  public function getEnd(){
    return $this->end;
  }

  public function getColor(){
    return $this->color;
  }
}
