<?php

if (isset($_POST['guardar_equipo'])) {
    Conexion::abrir_conexion();
    $total = $_POST['quantity'] * $_POST['unit_price'];

    $equipo = new Equipo('', $_POST['id_rfq'], htmlspecialchars($_POST['description']), $_POST['quantity'], $_POST['unit_price'], $total);
    $equipo_insertado = RepositorioEquipo::insertar_equipo(Conexion::obtener_conexion(), $equipo);
    
    Conexion::cerrar_conexion();
}
?>
