<?php
class FulfillmentSubitem {
  private $id;
  private $id_subitem;
  private $provider;
  private $quantity;
  private $unit_cost;
  private $other_cost;
  private $real_cost;
  private $payment_term;
  private $comments;
  private $reviewed;
  private $created_at;
  private $id_invoice;
  private $transaction_date;

  public function __construct(
    $id,
    $id_subitem,
    $provider,
    $quantity,
    $unit_cost,
    $other_cost,
    $real_cost,
    $payment_term,
    $comments,
    $reviewed,
    $created_at,
    $id_invoice,
    $transaction_date
  ) {
    $this->id = $id;
    $this->id_subitem = $id_subitem;
    $this->provider = $provider;
    $this->quantity = $quantity;
    $this->unit_cost = $unit_cost;
    $this->other_cost = $other_cost;
    $this->real_cost = $real_cost;
    $this->payment_term = $payment_term;
    $this->comments = $comments;
    $this->reviewed = $reviewed;
    $this->created_at = $created_at;
    $this->id_invoice = $id_invoice;
    $this->transaction_date = $transaction_date;
  }

  public function get_id() {
    return $this->id;
  }

  public function get_id_subitem() {
    return $this->id_subitem;
  }

  public function get_provider() {
    return $this->provider;
  }

  public function get_quantity() {
    return $this->quantity;
  }

  public function get_unit_cost() {
    return $this->unit_cost;
  }

  public function get_other_cost() {
    return $this->other_cost;
  }

  public function get_real_cost() {
    return $this->real_cost;
  }

  public function get_payment_term() {
    return $this->payment_term;
  }

  public function get_comments() {
    return $this->comments;
  }

  public function get_reviewed() {
    return $this->reviewed;
  }

  public function get_created_at() {
    return $this->created_at;
  }

  public function getIdInvoice() {
    return $this->id_invoice;
  }

  public function getInvoiceName() {
    Conexion::abrir_conexion();
    $invoice = InvoiceRepository::get_one(Conexion::obtener_conexion(), $this-> id_invoice);
    Conexion::cerrar_conexion();
    return $invoice?->get_name();
  }

  public function getTransactionDate() {
    return $this->transaction_date;
  }
}
