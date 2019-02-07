<?php
class ReQuoteSubitem{
  private $id;
  private $id_re_quote_item;
  private $brand;
  private $brand_project;
  private $part_number;
  private $part_number_project;
  private $description;
  private $description_project;
  private $quantity;
  private $unit_price;
  private $total_price;
  private $comments;
  private $website;
  private $additional;

  public function __construct($id, $id_re_quote_item, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $unit_price, $total_price, $comments, $website, $additional){
    $this-> id = $id;
    $this-> id_re_quote_item = $id_re_quote_item;
    $this-> brand = $brand;
    $this-> brand_project = $brand_project;
    $this-> part_number = $part_number;
    $this-> part_number_project = $part_number_project;
    $this-> description = $description;
    $this-> description_project = $description_project;
    $this-> quantity = $quantity;
    $this-> unit_price = $unit_price;
    $this-> total_price = $total_price;
    $this-> comments = $comments;
    $this-> website = $website;
    $this-> additional = $additional;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_re_quote_item(){
    return $this-> id_re_quote_item;
  }

  public function get_brand(){
    return $this-> brand;
  }

  public function get_brand_project(){
    return $this-> brand_project;
  }

  public function get_part_number(){
    return $this-> part_number;
  }

  public function get_part_number_project(){
    return $this-> part_number_project;
  }

  public function get_description(){
    return $this-> description;
  }

  public function get_description_project(){
    return $this-> description_project;
  }

  public function get_quantity(){
    return $this-> quantity;
  }

  public function get_unit_price(){
    return $this-> unit_price;
  }

  public function get_total_price(){
    return $this-> total_price;
  }

  public function get_comments(){
    return $this-> comments;
  }

  public function get_website(){
    return $this-> website;
  }

  public function get_additional(){
    return $this-> additional;
  }
}
?>
