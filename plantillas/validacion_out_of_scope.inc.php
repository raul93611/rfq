<?php
if (isset($_POST['guardar_out_of_scope'])) {
    Conexion::abrir_conexion();
    $cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $_POST['id_cuestionario']);
    $out_of_scope = new OutOfScope('', $_POST['id_cuestionario'], $_POST['requirement']);
    $out_of_scope_insertado = RepositorioOutOfScope::insertar_out_of_scope(Conexion::obtener_conexion(), $out_of_scope);
    Conexion::cerrar_conexion();
    if($out_of_scope_insertado){
        Redireccion::redirigir1(CUESTIONARIO . '/' . $cuestionario-> obtener_id_rfq());
    }
}
?>
