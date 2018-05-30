<?php
class ValidadorCotizacionRegistro extends Validadorcotizacion{
    public function __construct($email_code, $issue_date, $end_date){
        $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
        $this->aviso_cierre = "</div>";
        
        $this-> email_code = '';
        $this-> issue_date = '';
        $this-> end_date = '';
        
        $this-> error_email_code = $this-> validar_email_code($email_code);
        $this-> error_issue_date = $this-> validar_issue_date($issue_date);
        $this-> error_end_date = $this-> validar_end_date($end_date);
    }
}
?>
