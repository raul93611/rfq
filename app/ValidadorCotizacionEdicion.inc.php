<?php
class ValidadorCotizacionEdicion extends ValidadorCotizacion{
    public function __construct($amount){
        $this->aviso_inicio = "<br><div class='alert alert-danger' role='alert'>";
        $this->aviso_cierre = "</div>";
        
        $this-> amount = '';
        
        $this-> error_amount = $this-> validar_amount($amount);
    }
}
?>
