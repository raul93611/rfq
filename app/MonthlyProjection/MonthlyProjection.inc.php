<?php
class MonthlyProjection {
  private $id;
  private $yearly_projection_id;
  private $month;
  private $projected_amount;

  public function __construct($id, $yearly_projection_id, $month, $projected_amount) {
    $this->id = $id;
    $this->yearly_projection_id = $yearly_projection_id;
    $this->month = $month;
    $this->projected_amount = $projected_amount;
  }

  public function getId() {
    return $this->id;
  }

  public function getYearlyProjectionId() {
    return $this->yearly_projection_id;
  }

  public function getMonth(){
    return $this->month;
  }

  public function getProjectedAmount() {
    return $this->projected_amount;
  }
}
