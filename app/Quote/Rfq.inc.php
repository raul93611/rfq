<?php
class Rfq {
  private $id;
  private $id_usuario;
  private $usuario_designado;
  private $canal;
  private $email_code;
  private $type_of_bid;
  private $issue_date;
  private $end_date;
  private $status;
  private $completado;
  private $total_cost;
  private $total_price;
  private $comments;
  private $award;
  private $fecha_completado;
  private $fecha_submitted;
  private $fecha_award;
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
  private $fullfillment;
  private $fulfillment_date;
  private $contract_number;
  private $fulfillment_profit;
  private $services_fulfillment_profit;
  private $total_fulfillment;
  private $total_services_fulfillment;
  private $invoice;
  private $invoice_date;
  private $multi_year_project;
  private $submitted_invoice;
  private $submitted_invoice_date;
  private $fulfillment_pending;
  private $fulfillment_shipping_cost;
  private $fulfillment_shipping;
  private $type_of_contract;

  public function __construct($id,
    $id_usuario,
    $usuario_designado,
    $canal,
    $email_code,
    $type_of_bid,
    $issue_date,
    $end_date,
    $status,
    $completado,
    $total_cost,
    $total_price,
    $comments,
    $award,
    $fecha_completado,
    $fecha_submitted,
    $fecha_award,
    $payment_terms,
    $address,
    $ship_to,
    $expiration_date,
    $ship_via,
    $taxes,
    $profit,
    $additional,
    $shipping,
    $shipping_cost,
    $fullfillment,
    $fulfillment_date,
    $contract_number,
    $fulfillment_profit,
    $services_fulfillment_profit,
    $total_fulfillment,
    $total_services_fulfillment,
    $invoice,
    $invoice_date,
    $multi_year_project,
    $submitted_invoice,
    $submitted_invoice_date,
    $fulfillment_pending,
    $fulfillment_shipping_cost,
    $fulfillment_shipping,
    $type_of_contract
  ) {
    $this->id = $id;
    $this->id_usuario = $id_usuario;
    $this->usuario_designado = $usuario_designado;
    $this->canal = $canal;
    $this->email_code = $email_code;
    $this->type_of_bid = $type_of_bid;
    $this->issue_date = $issue_date;
    $this->end_date = $end_date;
    $this->status = $status;
    $this->completado = $completado;
    $this->total_cost = $total_cost;
    $this->total_price = $total_price;
    $this->comments = $comments;
    $this->award = $award;
    $this->fecha_completado = $fecha_completado;
    $this->fecha_submitted = $fecha_submitted;
    $this->fecha_award = $fecha_award;
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
    $this-> fullfillment = $fullfillment;
    $this-> fulfillment_date = $fulfillment_date;
    $this-> contract_number = $contract_number;
    $this-> fulfillment_profit = $fulfillment_profit;
    $this-> services_fulfillment_profit = $services_fulfillment_profit;
    $this-> total_fulfillment = $total_fulfillment;
    $this-> total_services_fulfillment = $total_services_fulfillment;
    $this-> invoice = $invoice;
    $this-> invoice_date = $invoice_date;
    $this-> multi_year_project = $multi_year_project;
    $this-> submitted_invoice = $submitted_invoice;
    $this-> submitted_invoice_date = $submitted_invoice_date;
    $this-> fulfillment_pending = $fulfillment_pending;
    $this-> fulfillment_shipping_cost = $fulfillment_shipping_cost;
    $this-> fulfillment_shipping = $fulfillment_shipping;
    $this-> type_of_contract = $type_of_contract;
  }

  public function obtener_id() {
    return $this->id;
  }

  public function obtener_id_usuario() {
    return $this->id_usuario;
  }

  public function obtener_designated_username(){
    Conexion::abrir_conexion();
    $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this-> usuario_designado);
    Conexion::cerrar_conexion();
    return $usuario-> obtener_nombre_usuario();
  }

  public function obtener_usuario_designado(){
    return $this->usuario_designado;
  }

  public function obtener_canal() {
    return $this->canal;
  }

  public function print_channel(){
    $channel = $this-> canal;
    switch ($this-> canal) {
      case 'FedBid':
        $channel = 'Unison';
        break;
      case 'FBO':
        $channel = 'SAM';
        break;
    }
    return $channel;
  }

  public function obtener_email_code() {
    return $this->email_code;
  }

  public function obtener_type_of_bid() {
    return $this->type_of_bid;
  }

  public function obtener_issue_date() {
    return $this->issue_date;
  }

  public function obtener_end_date() {
    return $this->end_date;
  }

  public function obtener_status() {
    return $this->status;
  }

  public function obtener_completado(){
    return $this->completado;
  }

  public function obtener_total_cost() {
    return $this->total_cost;
  }

  public function obtener_total_price(){
    return $this->total_price;
  }

  public function obtener_comments() {
    return $this->comments;
  }

  public function obtener_award() {
    return $this->award;
  }

  public function obtener_fecha_completado(){
    return $this->fecha_completado;
  }

  public function obtener_fecha_submitted(){
    return $this->fecha_submitted;
  }

  public function obtener_fecha_award(){
    return $this->fecha_award;
  }

  public function obtener_payment_terms(){
    return $this->payment_terms;
  }

  public function obtener_address(){
    return $this->address;
  }

  public function obtener_ship_to(){
    return $this->ship_to;
  }

  public function obtener_expiration_date(){
    return $this->expiration_date;
  }

  public function obtener_ship_via(){
    return $this->ship_via;
  }

  public function obtener_taxes(){
    return $this->taxes;
  }

  public function obtener_profit(){
    return $this->profit;
  }

  public function obtener_additional(){
    return $this-> additional;
  }

  public function obtener_shipping(){
    return $this-> shipping;
  }

  public function obtener_shipping_cost(){
    return $this-> shipping_cost;
  }

  public function obtener_fullfillment(){
    return $this-> fullfillment;
  }

  public function obtener_fulfillment_date(){
    return $this-> fulfillment_date;
  }

  public function obtener_contract_number(){
    return $this-> contract_number;
  }

  public function obtener_fulfillment_profit(){
    return $this-> fulfillment_profit;
  }

  public function obtener_services_fulfillment_profit(){
    return $this-> services_fulfillment_profit;
  }

  public function obtener_total_fulfillment(){
    return $this-> total_fulfillment;
  }

  public function obtener_total_services_fulfillment(){
    return $this-> total_services_fulfillment;
  }

  public function obtener_invoice(){
    return $this-> invoice;
  }

  public function obtener_invoice_date(){
    return $this-> invoice_date;
  }

  public function obtener_multi_year_project(){
    return $this-> multi_year_project;
  }

  public function obtener_real_fulfillment_profit(){
    return $this-> obtener_quote_total_price() - ($this-> total_fulfillment + $this-> total_services_fulfillment);
  }

  public function obtener_real_fulfillment_profit_percentage(){
    return 100*($this-> obtener_real_fulfillment_profit()/$this-> obtener_quote_total_price());
  }

  public function obtener_quote_total_price(){
    Conexion::abrir_conexion();
    $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $this-> id);
    Conexion::cerrar_conexion();
    return $this-> total_price + $total_services;
  }

  public function obtener_quote_profit(){
    return $this-> obtener_quote_total_price() - $this-> total_cost;
  }

  public function obtener_quote_profit_percentage(){
    if($this-> obtener_quote_total_price()){
      return 100*($this-> obtener_quote_profit()/$this-> obtener_quote_total_price());
    }else{
      return 0;
    }
  }

  public function obtener_submitted_invoice(){
    return $this-> submitted_invoice;
  }

  public function obtener_submitted_invoice_date(){
    return $this-> submitted_invoice_date;
  }

  public function obtener_child_quotes(){
    Conexion::abrir_conexion();
    $child_quotes = RepositorioRfq::get_child_quotes(Conexion::obtener_conexion(), $this-> id);
    Conexion::cerrar_conexion();

    return $child_quotes;
  }

  public function obtener_fulfillment_pending(){
    return $this-> fulfillment_pending;
  }

  public function obtener_fulfillment_shipping_cost(){
    return $this-> fulfillment_shipping_cost;
  }

  public function obtener_fulfillment_shipping(){
    return $this-> fulfillment_shipping;
  }

  public function obtener_type_of_contract(){
    return $this-> type_of_contract;
  }
}
?>
