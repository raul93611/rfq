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

    public function __construct($id, $id_usuario, $usuario_designado, $canal, $email_code, $type_of_bid, $issue_date, $end_date, $status, $completado, $total_cost, $total_price, $comments, $award, $fecha_completado, $fecha_submitted, $fecha_award, $payment_terms, $address, $ship_to, $expiration_date, $ship_via, $taxes, $profit, $additional, $shipping, $shipping_cost) {
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
    }

    public function obtener_id() {
        return $this->id;
    }

    public function obtener_id_usuario() {
        return $this->id_usuario;
    }

    public function obtener_usuario_designado(){
        return $this->usuario_designado;
    }

    public function obtener_canal() {
        return $this->canal;
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
}
?>
