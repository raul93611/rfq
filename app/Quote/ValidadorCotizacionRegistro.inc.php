<?php
class ValidadorCotizacionRegistro extends Validadorcotizacion{
  public function __construct($database, $email_code, $issue_date, $end_date, $type_of_bid, $assigned_user, $channel){
    $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
    $this->aviso_cierre = "</div>";

    $this-> email_code = '';
    $this-> issue_date = '';
    $this-> end_date = '';
    $this-> type_of_bid = $type_of_bid;
    $this-> assigned_user = $assigned_user;
    $this-> channel = $channel;

    $this-> error_email_code = $this-> validar_email_code($database, $email_code);
    $this-> error_issue_date = $this-> validar_issue_date($issue_date);
    $this-> error_end_date = $this-> validar_end_date($end_date);
  }
}
?>
