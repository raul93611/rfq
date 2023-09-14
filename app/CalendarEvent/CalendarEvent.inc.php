<?php
class CalendarEvent{
  private $id;
  private $id_personnel;
  private $name;
  private $start;
  private $end;

  public function __construct($id, $id_personnel, $name, $start, $end) {
    $this->id = $id;
    $this->id_personnel = $id_personnel;
    $this->name = $name;
    $this->start = $start;
    $this->end = $end;
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
}
