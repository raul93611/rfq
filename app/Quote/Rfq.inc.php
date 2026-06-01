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
  private $net30_fulfillment;
  private $sales_commission;
  private $sales_commission_comment;
  private $services_payment_term;
  private $city;
  private $zip_code;
  private $state;
  private $client;
  private $deleted;
  private $set_side;
  private $poc;
  private $co;
  private $estimated_delivery_date;
  private $shipping_address;
  private $special_requirements;
  private $file_document;
  private $accounting;
  private $gsa;
  private $client_payment_terms;
  private $net30_fulfillment_services;
  private $bpa;
  private $reference_url;
  private $priority;
  private $name;
  private $sheet_sync_status;
  private $sheet_sync_at;
  private $sheet_row;
  private $site_visit;
  private $resumes;
  private $qa_deadline;

  public function __construct(array $row) {
    $this->id = $row['id'] ?? null;
    $this->id_usuario = $row['id_usuario'] ?? null;
    $this->usuario_designado = $row['usuario_designado'] ?? null;
    $this->canal = $row['canal'] ?? null;
    $this->email_code = $row['email_code'] ?? null;
    $this->type_of_bid = $row['type_of_bid'] ?? null;
    $this->issue_date = $row['issue_date'] ?? null;
    $this->end_date = $row['end_date'] ?? null;
    $this->status = $row['status'] ?? null;
    $this->completado = $row['completado'] ?? null;
    $this->total_cost = $row['total_cost'] ?? null;
    $this->total_price = $row['total_price'] ?? null;
    $this->comments = $row['comments'] ?? null;
    $this->award = $row['award'] ?? null;
    $this->fecha_completado = $row['fecha_completado'] ?? null;
    $this->fecha_submitted = $row['fecha_submitted'] ?? null;
    $this->fecha_award = $row['fecha_award'] ?? null;
    $this->payment_terms = $row['payment_terms'] ?? null;
    $this->address = $row['address'] ?? null;
    $this->ship_to = $row['ship_to'] ?? null;
    $this->expiration_date = $row['expiration_date'] ?? null;
    $this->ship_via = $row['ship_via'] ?? null;
    $this->taxes = $row['taxes'] ?? null;
    $this->profit = $row['profit'] ?? null;
    $this->additional = $row['additional'] ?? null;
    $this->shipping = $row['shipping'] ?? null;
    $this->shipping_cost = $row['shipping_cost'] ?? null;
    $this->fullfillment = $row['fullfillment'] ?? null;
    $this->fulfillment_date = $row['fulfillment_date'] ?? null;
    $this->contract_number = $row['contract_number'] ?? null;
    $this->fulfillment_profit = $row['fulfillment_profit'] ?? null;
    $this->services_fulfillment_profit = $row['services_fulfillment_profit'] ?? null;
    $this->total_fulfillment = $row['total_fulfillment'] ?? null;
    $this->total_services_fulfillment = $row['total_services_fulfillment'] ?? null;
    $this->invoice = $row['invoice'] ?? null;
    $this->invoice_date = $row['invoice_date'] ?? null;
    $this->multi_year_project = $row['multi_year_project'] ?? null;
    $this->submitted_invoice = $row['submitted_invoice'] ?? null;
    $this->submitted_invoice_date = $row['submitted_invoice_date'] ?? null;
    $this->fulfillment_pending = $row['fulfillment_pending'] ?? null;
    $this->fulfillment_shipping_cost = $row['fulfillment_shipping_cost'] ?? null;
    $this->fulfillment_shipping = $row['fulfillment_shipping'] ?? null;
    $this->type_of_contract = $row['type_of_contract'] ?? null;
    $this->net30_fulfillment = $row['net30_fulfillment'] ?? null;
    $this->sales_commission = $row['sales_commission'] ?? null;
    $this->sales_commission_comment = $row['sales_commission_comment'] ?? null;
    $this->services_payment_term = $row['services_payment_term'] ?? null;
    $this->city = $row['city'] ?? null;
    $this->zip_code = $row['zip_code'] ?? null;
    $this->state = $row['state'] ?? null;
    $this->client = $row['client'] ?? null;
    $this->deleted = $row['deleted'] ?? null;
    $this->set_side = $row['set_side'] ?? null;
    $this->poc = $row['poc'] ?? null;
    $this->co = $row['co'] ?? null;
    $this->estimated_delivery_date = $row['estimated_delivery_date'] ?? null;
    $this->shipping_address = $row['shipping_address'] ?? null;
    $this->special_requirements = $row['special_requirements'] ?? null;
    $this->file_document = $row['file_document'] ?? null;
    $this->accounting = $row['accounting'] ?? null;
    $this->gsa = $row['gsa'] ?? null;
    $this->client_payment_terms = $row['client_payment_terms'] ?? null;
    $this->net30_fulfillment_services = $row['net30_fulfillment_services'] ?? null;
    $this->bpa = $row['bpa'] ?? null;
    $this->reference_url = $row['reference_url'] ?? null;
    $this->priority = $row['priority'] ?? null;
    $this->name = $row['name'] ?? null;
    $this->sheet_sync_status = $row['sheet_sync_status'] ?? null;
    $this->sheet_sync_at = $row['sheet_sync_at'] ?? null;
    $this->sheet_row = $row['sheet_row'] ?? null;
    $this->site_visit = isset($row['site_visit']) ? (int)$row['site_visit'] : null;
    $this->resumes = isset($row['resumes']) ? (int)$row['resumes'] : null;
    $this->qa_deadline = $row['qa_deadline'] ?? null;
  }

  public function obtener_id() {
    return $this->id;
  }

  public function obtener_id_usuario() {
    return $this->id_usuario;
  }

  public function obtener_designated_username() {
    Conexion::abrir_conexion();
    $usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $this->usuario_designado);
    Conexion::cerrar_conexion();
    return $usuario->obtener_nombre_usuario();
  }

  public function obtener_usuario_designado() {
    return $this->usuario_designado;
  }

  public function obtener_canal() {
    return $this->canal;
  }

  public function print_channel() {
    $channel = $this->canal;
    switch ($this->canal) {
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

  public function obtener_completado() {
    return $this->completado;
  }

  public function obtener_total_cost() {
    return $this->total_cost;
  }

  public function obtener_total_price() {
    return (float)$this->total_price;
  }

  public function obtener_comments() {
    return $this->comments;
  }

  public function obtener_award() {
    return $this->award;
  }

  public function obtener_fecha_completado() {
    return $this->fecha_completado;
  }

  public function obtener_fecha_submitted() {
    return $this->fecha_submitted;
  }

  public function obtener_fecha_award() {
    return $this->fecha_award;
  }

  public function obtener_payment_terms() {
    return $this->payment_terms;
  }

  public function obtener_address() {
    return $this->address;
  }

  public function obtener_ship_to() {
    return $this->ship_to;
  }

  public function obtener_expiration_date() {
    return $this->expiration_date;
  }

  public function obtener_ship_via() {
    return $this->ship_via;
  }

  public function obtener_taxes() {
    return $this->taxes;
  }

  public function obtener_profit() {
    return $this->profit;
  }

  public function obtener_additional() {
    return $this->additional;
  }

  public function obtener_shipping() {
    return $this->shipping;
  }

  public function obtener_shipping_cost() {
    return $this->shipping_cost;
  }

  public function obtener_fullfillment() {
    return $this->fullfillment;
  }

  public function obtener_fulfillment_date() {
    return $this->fulfillment_date;
  }

  public function obtener_contract_number() {
    return $this->contract_number;
  }

  public function obtener_fulfillment_profit() {
    return $this->fulfillment_profit;
  }

  public function obtener_services_fulfillment_profit() {
    return $this->services_fulfillment_profit;
  }
  //fulfillment rfq
  public function obtener_total_fulfillment() {
    return (float)$this->total_fulfillment;
  }

  public function getRfqFulfillmentProfit() {
    return $this->obtener_total_price() - $this->total_fulfillment;
  }

  public function getRfqFulfillmentProfitPercentage() {
    return $this->obtener_total_price() ? 100 * ($this->getRfqFulfillmentProfit() / $this->obtener_total_price()) : 0;
  }
  //fulfillment rfp
  public function obtener_total_services_fulfillment() {
    return $this->total_services_fulfillment;
  }

  public function getRfpFulfillmentProfit() {
    return $this->getTotalQuoteServices() - $this->total_services_fulfillment;
  }

  public function getRfpFulfillmentProfitPercentage() {
    return $this->getTotalQuoteServices() ? 100 * ($this->getRfpFulfillmentProfit() / $this->getTotalQuoteServices()) : 0;
  }

  public function obtener_invoice() {
    return $this->invoice;
  }

  public function obtener_invoice_date() {
    return $this->invoice_date;
  }

  public function obtener_multi_year_project() {
    return $this->multi_year_project;
  }

  //fulfillment amounts
  public function obtener_fulfillment_total_cost() {
    return $this->total_fulfillment + $this->total_services_fulfillment;
  }

  public function obtener_real_fulfillment_profit() {
    return $this->obtener_quote_total_price() - $this->obtener_fulfillment_total_cost();
  }

  public function obtener_real_fulfillment_profit_percentage() {
    return $this->obtener_quote_total_price() ? 100 * ($this->obtener_real_fulfillment_profit() / $this->obtener_quote_total_price()) : 0;
  }

  //quote amounts
  public function getTotalQuoteServices() {
    Conexion::abrir_conexion();
    $total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $this->id);
    Conexion::cerrar_conexion();
    return $total_services;
  }

  public function obtener_quote_total_cost() {
    return $this->total_cost + $this->getTotalQuoteServices();
  }

  public function obtener_quote_total_price() {
    return $this->total_price + $this->getTotalQuoteServices();
  }

  public function obtener_quote_profit() {
    return $this->obtener_quote_total_price() - $this->obtener_quote_total_cost();
  }

  public function obtener_quote_profit_percentage() {
    if ($this->obtener_quote_total_price()) {
      return 100 * ($this->obtener_quote_profit() / $this->obtener_quote_total_price());
    } else {
      return 0;
    }
  }
  //requote amounts
  public function obtener_re_quote_total_cost() {
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $this->id);
    $total_services = ReQuoteServiceRepository::get_total(Conexion::obtener_conexion(), $re_quote->get_id());
    Conexion::cerrar_conexion();
    return $re_quote->get_total_cost() + $total_services;
  }

  public function obtener_re_quote_profit() {
    return $this->obtener_quote_total_price() - $this->obtener_re_quote_total_cost();
  }

  public function obtener_re_quote_profit_percentage() {
    if ($this->obtener_quote_total_price()) {
      return 100 * ($this->obtener_re_quote_profit() / $this->obtener_quote_total_price());
    } else {
      return 0;
    }
  }

  //reQuote amounts (only RFQ)
  public function obtener_re_quote_total_cost_rfq() {
    Conexion::abrir_conexion();
    $re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $this->id);
    Conexion::cerrar_conexion();
    return $re_quote?->get_total_cost() ?? 0;
  }

  public function obtener_re_quote_rfq_profit() {
    return $this->obtener_total_price() - $this->obtener_re_quote_total_cost_rfq();
  }

  public function obtener_re_quote_rfq_profit_percentage() {
    if ($this->obtener_total_price()) {
      return 100 * ($this->obtener_re_quote_rfq_profit() / $this->obtener_total_price());
    } else {
      return 0;
    }
  }

  public function obtener_submitted_invoice() {
    return $this->submitted_invoice;
  }

  public function obtener_submitted_invoice_date() {
    return $this->submitted_invoice_date;
  }

  public function getSlavesQuotes() {
    Conexion::abrir_conexion();
    $child_quotes = RepositorioRfq::getSlavesQuotes(Conexion::obtener_conexion(), $this->id);
    Conexion::cerrar_conexion();

    return $child_quotes;
  }

  public function obtener_fulfillment_pending() {
    return $this->fulfillment_pending;
  }

  public function obtener_fulfillment_shipping_cost() {
    return $this->fulfillment_shipping_cost;
  }

  public function obtener_fulfillment_shipping() {
    return $this->fulfillment_shipping;
  }

  public function obtener_type_of_contract() {
    return $this->type_of_contract;
  }

  public function obtener_net30_fulfillment() {
    return $this->net30_fulfillment;
  }

  public function obtener_sales_commission() {
    return $this->sales_commission;
  }

  public function obtener_sales_commission_comment() {
    return $this->sales_commission_comment;
  }

  public function isServices() {
    $services = ['Services', 'Audio Visual', 'Computers', 'Back Up Batteries'];
    if (in_array($this->type_of_bid, $services)) {
      return true;
    }
    return false;
  }

  public function isNobid() {
    $no_bid = ['No Bid', 'Manufacturer in the Bid', 'Expired due date', 'Supplier did not provide a quote', 'Others'];
    if (in_array($this->comments, $no_bid)) {
      return true;
    }
    return false;
  }

  public function isNotSubmitted() {
    $not_submitted = ['Not submitted'];
    if (in_array($this->comments, $not_submitted)) {
      return true;
    }
    return false;
  }

  public function isCancelled() {
    $cancelled = ['Cancelled'];
    if (in_array($this->comments, $cancelled)) {
      return true;
    }
    return false;
  }

  public function isEnabledToInvoice() {
    $errors = [];

    Conexion::abrir_conexion();
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $this->id);
    Conexion::cerrar_conexion();

    if (!$this->obtener_fullfillment()) {
      $errors[] = "Fulfillment is not set.";
    }

    if (is_null($this->obtener_fulfillment_profit()) && is_null($this->obtener_services_fulfillment_profit())) {
      $errors[] = "Fulfillment profit or services fulfillment profit is not set.";
    }

    if (!$re_quote_exists) {
      $errors[] = "Re-quote does not exist.";
    }

    if (strlen($this->city ?? '') == 0) {
      $errors[] = "City is not set.";
    }

    if (strlen($this->zip_code ?? '') == 0) {
      $errors[] = "Zip code is not set.";
    }

    if (strlen($this->client ?? '') == 0) {
      $errors[] = "Client is not set.";
    }

    if (strlen($this->set_side ?? '') == 0) {
      $errors[] = "Set side is not set.";
    }

    if (strlen($this->poc ?? '') == 0) {
      $errors[] = "POC is not set.";
    }

    if (strlen($this->co ?? '') == 0) {
      $errors[] = "CO is not set.";
    }

    if (strlen($this->estimated_delivery_date ?? '') == 0) {
      $errors[] = "Estimated delivery date is not set.";
    }

    if (strlen($this->file_document ?? '') == 0) {
      $errors[] = "File document is not set.";
    }

    // If there are no errors, return true. Otherwise, return the list of errors.
    return empty($errors) ? true : $errors;
  }

  public function isEnabledToFulfillment() {
    $errors = [];

    // Check initial conditions
    if (!$this->obtener_completado()) {
      $errors[] = "Completed status is not set.";
    }
    if (!$this->obtener_status()) {
      $errors[] = "Status is not set.";
    }
    if (!$this->obtener_award()) {
      $errors[] = "Award is not set.";
    }

    // If any of the initial conditions fail, skip the rest and return errors
    if (!empty($errors)) {
      return $errors;
    }

    // Check re-quote existence
    Conexion::abrir_conexion();
    $re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $this->id);
    Conexion::cerrar_conexion();

    if (!$re_quote_exists) {
      $errors[] = "Re-quote does not exist.";
    }

    // Check other conditions
    if ($this->fullfillment) {
      $errors[] = "Fulfillment is already set.";
    }
    if (!$this->award) {
      $errors[] = "Award is not set.";
    }
    if (strlen($this->city ?? '') == 0) {
      $errors[] = "City is not set.";
    }
    if (strlen($this->zip_code ?? '') == 0) {
      $errors[] = "Zip code is not set.";
    }
    if (strlen($this->client ?? '') == 0) {
      $errors[] = "Client is not set.";
    }
    if (strlen($this->set_side ?? '') == 0) {
      $errors[] = "Set side is not set.";
    }
    if (strlen($this->poc ?? '') == 0) {
      $errors[] = "POC is not set.";
    }
    if (strlen($this->co ?? '') == 0) {
      $errors[] = "CO is not set.";
    }
    if (strlen($this->estimated_delivery_date ?? '') == 0) {
      $errors[] = "Estimated delivery date is not set.";
    }
    if (strlen($this->file_document ?? '') == 0) {
      $errors[] = "File document is not set.";
    }

    // Return true if no errors, otherwise return the list of errors
    return empty($errors) ? true : $errors;
  }

  public function obtener_services_payment_term() {
    return $this->services_payment_term;
  }

  public function obtener_city() {
    return $this->city;
  }

  public function obtener_zip_code() {
    return $this->zip_code;
  }

  public function obtener_state() {
    return $this->state;
  }

  public function obtener_client() {
    return $this->client;
  }

  public function getDeleted() {
    return $this->deleted;
  }

  public function getSetSide() {
    return $this->set_side;
  }

  public function getPoc() {
    return $this->poc;
  }

  public function getCo() {
    return $this->co;
  }

  public function getEstimatedDeliveryDate() {
    return $this->estimated_delivery_date;
  }

  public function getShippingAddress() {
    return $this->shipping_address;
  }

  public function getSpecialRequirements() {
    return $this->special_requirements;
  }

  public function getFileDocument() {
    return explode('|', $this->file_document ?? '');
  }

  public function getAccounting() {
    return explode('|', $this->accounting ?? '');
  }

  public function getGsa() {
    return $this->gsa;
  }

  public function getClientPaymentTerms() {
    return $this->client_payment_terms;
  }

  public function getNet30FulfillmentServices() {
    return $this->net30_fulfillment_services;
  }

  public function getBpa() {
    return $this->bpa;
  }

  public function getReferenceUrl() {
    return $this->reference_url;
  }

  public function getPriority() {
    return $this->priority;
  }

  public function getName() {
    return $this->name;
  }

  public function getSheetSyncStatus() {
    return $this->sheet_sync_status;
  }

  public function getSheetSyncAt() {
    return $this->sheet_sync_at;
  }

  public function getSheetRow() {
    return $this->sheet_row;
  }

  public function getSheetStatus() {
    $no_bid_comments = ['No Bid', 'Manufacturer in the Bid', 'Expired due date', 'Supplier did not provide a quote', 'Others'];
    if ($this->award || $this->fullfillment || $this->invoice || $this->submitted_invoice) {
      return 'AWARD';
    }
    if ($this->status == 1) {
      return 'SUBMITTED';
    }
    if ($this->comments === 'Not submitted') {
      return 'NOT SUBMITTED';
    }
    if ($this->comments === 'Cancelled') {
      return 'CANCELLED';
    }
    if (in_array($this->comments, $no_bid_comments)) {
      return 'NO BID';
    }
    if ($this->completado == 1) {
      return 'BID';
    }
    return 'TBD';
  }

  public function getSiteVisit() {
    return $this->site_visit;
  }

  public function getResumes() {
    return $this->resumes;
  }

  public function getQaDeadline() {
    return $this->qa_deadline;
  }

  public function getVehicleForSheet() {
    switch ($this->canal) {
      case 'FedBid': return 'Unison';
      case 'FBO':    return 'SAM';
      default:       return $this->canal;
    }
  }
}
