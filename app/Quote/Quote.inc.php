<?php
class Quote {
  private $id;
  private $id_user;
  private $assigned_user;
  private $channel;
  private $email_code;
  private $type_of_bid;
  private $issue_date;
  private $end_date;
  private $submitted;
  private $complete;
  private $total_cost;
  private $total_price;
  private $comments;
  private $award;
  private $completed_date;
  private $submitted_date;
  private $award_date;
  private $payment_terms;
  private $address;
  private $ship_to;
  private $expiration_date;
  private $ship_via;
  private $taxes;
  private $profit;
  private $additional;
  private $shipping;
  private $shipping_cost;
  private $fulfillment;
  private $fulfillment_date;
  private $contract_number;
  private $fulfillment_profit;
  private $services_fulfillment_profit;
  private $total_fulfillment;
  private $total_services_fulfillment;

  public function __construct($id, $id_user, $assigned_user, $channel, $email_code, $type_of_bid, $issue_date, $end_date, $submitted, $complete, $total_cost, $total_price, $comments, $award, $completed_date, $submitted_date, $award_date, $payment_terms, $address, $ship_to, $expiration_date, $ship_via, $taxes, $profit, $additional, $shipping, $shipping_cost, $fulfillment, $fulfillment_date, $contract_number, $fulfillment_profit, $services_fulfillment_profit, $total_fulfillment, $total_services_fulfillment) {
    $this->id = $id;
    $this->id_user = $id_user;
    $this->assigned_user = $assigned_user;
    $this->channel = $channel;
    $this->email_code = $email_code;
    $this->type_of_bid = $type_of_bid;
    $this->issue_date = $issue_date;
    $this->end_date = $end_date;
    $this->submitted = $submitted;
    $this->complete = $complete;
    $this->total_cost = $total_cost;
    $this->total_price = $total_price;
    $this->comments = $comments;
    $this->award = $award;
    $this->completed_date = $completed_date;
    $this->submitted_date = $submitted_date;
    $this->award_date = $award_date;
    $this->payment_terms = $payment_terms;
    $this->address = $address;
    $this->ship_to = $ship_to;
    $this->expiration_date = $expiration_date;
    $this->ship_via = $ship_via;
    $this->taxes = $taxes;
    $this->profit = $profit;
    $this->additional = $additional;
    $this->shipping = $shipping;
    $this->shipping_cost = $shipping_cost;
    $this-> fulfillment = $fulfillment;
    $this-> fulfillment_date = $fulfillment_date;
    $this-> contract_number = $contract_number;
    $this-> fulfillment_profit = $fulfillment_profit;
    $this-> services_fulfillment_profit = $services_fulfillment_profit;
    $this-> total_fulfillment = $total_fulfillment;
    $this-> total_services_fulfillment = $total_services_fulfillment;
  }

  public function get_id() {
    return $this->id;
  }

  public function get_id_user() {
    return $this->id_user;
  }

  public function get_assigned_user(){
    return $this->assigned_user;
  }

  public function get_channel() {
    return $this->channel;
  }

  public function get_email_code() {
    return $this->email_code;
  }

  public function get_type_of_bid() {
    return $this->type_of_bid;
  }

  public function get_issue_date() {
    return $this->issue_date;
  }

  public function get_end_date() {
    return $this->end_date;
  }

  public function get_submitted() {
    return $this->submitted;
  }

  public function get_complete(){
    return $this->complete;
  }

  public function get_total_cost() {
    return $this->total_cost;
  }

  public function get_total_price(){
    return $this->total_price;
  }

  public function get_comments() {
    return $this->comments;
  }

  public function get_award() {
    return $this->award;
  }

  public function get_completed_date(){
    return $this->completed_date;
  }

  public function get_submitted_date(){
    return $this->submitted_date;
  }

  public function get_award_date(){
    return $this->award_date;
  }

  public function get_payment_terms(){
    return $this->payment_terms;
  }

  public function get_address(){
    return $this->address;
  }

  public function get_ship_to(){
    return $this->ship_to;
  }

  public function get_expiration_date(){
    return $this->expiration_date;
  }

  public function get_ship_via(){
    return $this->ship_via;
  }

  public function get_taxes(){
    return $this->taxes;
  }

  public function get_profit(){
    return $this->profit;
  }

  public function get_additional(){
    return $this-> additional;
  }

  public function get_shipping(){
    return $this-> shipping;
  }

  public function get_shipping_cost(){
    return $this-> shipping_cost;
  }

  public function get_fulfillment(){
    return $this-> fulfillment;
  }

  public function get_fulfillment_date(){
    return $this-> fulfillment_date;
  }

  public function get_contract_number(){
    return $this-> contract_number;
  }

  public function get_fulfillment_profit(){
    return $this-> fulfillment_profit;
  }

  public function get_services_fulfillment_profit(){
    return $this-> services_fulfillment_profit;
  }

  public function get_total_fulfillment(){
    return $this-> total_fulfillment;
  }

  public function get_total_services_fulfillment(){
    return $this-> total_services_fulfillment;
  }
}
?>
