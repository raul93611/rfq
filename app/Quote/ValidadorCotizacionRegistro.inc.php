<?php
class ValidadorCotizacionRegistro extends ValidadorCotizacion{
  public function __construct($conexion, $email_code, $issue_date, $end_date, $type_of_bid, $usuario_designado, $canal){
    $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
    $this->aviso_cierre = "</div>";

    $this-> email_code = '';
    $this-> issue_date = '';
    $this-> end_date = '';
    $this-> type_of_bid = $type_of_bid;
    $this-> usuario_designado = $usuario_designado;
    $this-> canal = $canal;

    $this-> error_email_code = $this-> validar_email_code($conexion, $email_code);
    $this-> error_issue_date = $this-> validar_issue_date($issue_date);
    $this-> error_end_date = $this-> validar_end_date($end_date);
  }
}
?>
