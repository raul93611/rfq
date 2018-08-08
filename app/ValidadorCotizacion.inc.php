<?php
abstract class ValidadorCotizacion {
    protected $aviso_inicio;
    protected $aviso_cierre;
    protected $email_code;
    protected $issue_date;
    protected $end_date;
    protected $usuario_designado;
    protected $type_of_bid;
    protected $canal;
    protected $error_email_code;
    protected $error_issue_date;
    protected $error_end_date;

    public function __construct() {

    }

    protected function variable_iniciada($variable) {
        if (isset($variable) && !empty($variable)) {
            return true;
        } else {
            return false;
        }
    }

    protected function validar_email_code($conexion, $email_code) {
        if (!$this->variable_iniciada($email_code)) {
            return 'Must be fill out.';
        } else {
            $this->email_code = $email_code;
        }
        if(RepositorioRfq::email_code_existe($conexion, $email_code)){
            return 'E-mail Code already exists.';
        }
        return '';
    }

    protected function validar_issue_date($issue_date) {
        if (!$this->variable_iniciada($issue_date)) {
            return 'Must be fill out.';
        } else {
            $this->issue_date = $issue_date;
        }
        return '';
    }

    protected function validar_end_date($end_date) {
        if (!$this->variable_iniciada($end_date)) {
            return 'Must be fill out.';
        } else {
            $this->end_date = $end_date;
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

    public function obtener_usuario_designado() {
        return $this->usuario_designado;
    }

    public function obtener_type_of_bid() {
        return $this->type_of_bid;
    }

    public function obtener_canal() {
        return $this->canal;
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

    public function mostrar_email_code() {
        if ($this->email_code != '') {
            echo 'value="' . $this->email_code . '"';
        }
    }

    public function mostrar_issue_date() {
        if ($this->issue_date != '') {
            echo 'value="' . $this->issue_date . '"';
        }
    }

    public function mostrar_end_date() {
        if ($this->end_date != '') {
            echo 'value="' . $this->end_date . '"';
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

    public function registro_cotizacion_valida() {
        if ($this->error_email_code == '' && $this->error_issue_date == '' && $this->error_end_date == '') {
            return true;
        } else {
            return false;
        }
    }
}
?>
