<?php
class Subitem {
  private $id;
  private $id_item;
  private $provider_menor;
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

  public function __construct($id, $id_item, $provider_menor, $brand, $brand_project, $part_number, $part_number_project, $description, $description_project, $quantity, $unit_price, $total_price, $comments, $website, $additional, $fulfillment_profit) {
    $this->id = $id;
    $this->id_item = $id_item;
    $this->provider_menor = $provider_menor;
    $this->brand = $brand;
    $this->brand_project = $brand_project;
    $this->part_number = $part_number;
    $this->part_number_project = $part_number_project;
    $this->description_project = $description_project;
    $this->description = $description;
    $this->quantity = $quantity;
    $this->unit_price = $unit_price;
    $this->total_price = $total_price;
    $this->comments = $comments;
    $this->website = $website;
    $this->additional = $additional;
    $this->fulfillment_profit = $fulfillment_profit;
  }

  public function obtener_id() {
    return $this->id;
  }

  public function obtener_id_item() {
    return $this->id_item;
  }

  public function obtener_provider_menor() {
    return $this->provider_menor;
  }

  public function obtener_brand() {
    return $this->brand;
  }

  public function obtener_brand_project() {
    return $this->brand_project;
  }

  public function obtener_part_number() {
    return $this->part_number;
  }

  public function obtener_part_number_project() {
    return $this->part_number_project;
  }

  public function obtener_description() {
    return $this->description;
  }

  public function obtener_description_project() {
    return $this->description_project;
  }

  public function obtener_quantity() {
    return $this->quantity;
  }

  public function obtener_unit_price() {
    return $this->unit_price;
  }

  public function obtener_total_price() {
    return $this->total_price;
  }

  public function obtener_comments() {
    return $this->comments;
  }

  public function obtener_website() {
    return $this->website;
  }

  public function obtener_additional() {
    return (float) $this->additional;
  }

  public function obtener_fulfillment_profit() {
    return $this->fulfillment_profit;
  }
}
