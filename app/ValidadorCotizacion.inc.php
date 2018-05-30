<?php

abstract class ValidadorCotizacion {

    protected $aviso_inicio;
    protected $aviso_cierre;
    protected $email_code;
    protected $issue_date;
    protected $end_date;
    protected $amount;
    protected $error_email_code;
    protected $error_issue_date;
    protected $error_end_date;
    protected $error_amount;

    public function __construct() {
        
    }

    protected function variable_iniciada($variable) {
        if (isset($variable) && !empty($variable)) {
            return true;
        } else {
            return false;
        }
    }

    protected function validar_email_code($email_code) {
        if (!$this->variable_iniciada($email_code)) {
            return 'Completa este campo.';
        } else {
            $this->email_code = $email_code;
        }

        return '';
    }

    protected function validar_issue_date($issue_date) {
        if (!$this->variable_iniciada($issue_date)) {
            return 'Completa este campo.';
        } else {
            $this->issue_date = $issue_date;
        }

        return '';
    }

    protected function validar_end_date($end_date) {
        if (!$this->variable_iniciada($end_date)) {
            return 'Completa este campo.';
        } else {
            $this->end_date = $end_date;
        }

        return '';
    }

    protected function validar_amount($amount) {
        $this->amount = $amount;

        $partes_amount = explode('.', $amount);
        
        if(count($partes_amount)){
            for($i = 0; $i < count($partes_amount); $i++){
                if(!preg_match("/^-?[0-9]+([,\.][0-9]*)?$/", $partes_amount[$i])){
                    return 'Caracteres no permitidos';
                }
            }
        }

        return '';
    }

    public function obtener_email_code() {
        return $this->email_code;
    }

    public function obtener_issue_date() {
        return $this->issue_date;
    }

    public function obtener_end_date() {
        return $this->end_date;
    }

    public function obtener_amount() {
        return $this->amount;
    }

    public function obtener_error_email_code() {
        return $this->error_email_code;
    }

    public function obtener_error_issue_date() {
        return $this->error_issue_date;
    }

    public function obtener_error_end_date() {
        return $this->error_end_date;
    }

    public function obtener_error_amount() {
        return $this->error_amount;
    }

    public function mostrar_email_code() {
        if ($this->email_code != '') {
            echo 'value="' . $this->email_code . '"';
        }
    }

    public function mostrar_amount() {
        if ($this->amount != '') {
            echo 'value="' . $this->amount . '"';
        }
    }

    public function mostrar_error_email_code() {
        if ($this->error_email_code != '') {
            echo $this->aviso_inicio . $this->error_email_code . $this->aviso_cierre;
        }
    }

    public function mostrar_error_issue_date() {
        if ($this->error_issue_date != '') {
            echo $this->aviso_inicio . $this->error_issue_date . $this->aviso_cierre;
        }
    }

    public function mostrar_error_end_date() {
        if ($this->error_end_date != '') {
            echo $this->aviso_inicio . $this->error_end_date . $this->aviso_cierre;
        }
    }

    public function mostrar_error_amount() {
        if ($this->error_amount != '') {
            echo $this->aviso_inicio . $this->error_amount . $this->aviso_cierre;
        }
    }

    public function registro_cotizacion_valida() {
        if ($this->error_email_code == '' && $this->error_issue_date == '' && $this->error_end_date == '' && $this->error_amount == '') {
            return true;
        } else {
            return false;
        }
    }

}

?>
