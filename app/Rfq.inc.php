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
    private $amount;
    private $proposal;
    private $comments;
    private $award;
    private $fecha_completado;

    public function __construct($id, $id_usuario, $usuario_designado, $canal, $email_code, $type_of_bid, $issue_date, $end_date, $status, $completado, $amount, $proposal, $comments, $award, $fecha_completado) {
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
        $this->amount = $amount;
        $this->proposal = $proposal;
        $this->comments = $comments;
        $this->award = $award;
        $this->fecha_completado = $fecha_completado;
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

    public function obtener_amount() {
        return $this->amount;
    }
    
    public function obtener_proposal(){
        return $this-> proposal;
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

}

?>
