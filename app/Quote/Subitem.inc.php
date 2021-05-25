<?php
class Subitem{
  private $id;
  private $id_item;
  private $least_provider;
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
  private $fulfillment_profit;

  public function __construct($id, $id_item, $least_provider, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $unit_price, $total_price, $comments, $website, $additional, $fulfillment_profit){
    $this-> id = $id;
    $this-> id_item = $id_item;
    $this-> least_provider = $least_provider;
    $this-> brand = $brand;
    $this-> brand_project = $brand_project;
    $this-> part_number = $part_number;
    $this-> part_number_project = $part_number_project;
    $this-> description_project = $description_project;
    $this-> description = $description;
    $this-> quantity = $quantity;
    $this-> unit_price = $unit_price;
    $this-> total_price = $total_price;
    $this-> comments = $comments;
    $this-> website = $website;
    $this-> additional = $additional;
    $this-> fulfillment_profit = $fulfillment_profit;
  }

  public function get_id(){
    return $this-> id;
  }

  public function get_id_item(){
    return $this-> id_item;
  }

  public function get_least_provider(){
    return $this-> least_provider;
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

  public function get_fulfillment_profit(){
    return $this-> fulfillment_profit;
  }
}
?>
