<?php
header('Content-Type: application/json');
list($nombres_usuario, $cotizaciones_completadas, $cotizaciones_completadas_pasadas, $cotizaciones_ganadas, $cotizaciones_ganadas_pasadas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
Conexion::abrir_conexion();
$cotizaciones_mes = RepositorioRfq::obtener_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
$monto_cotizaciones_mes = RepositorioRfq::obtener_monto_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
$cotizaciones_completadas_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_completadas_por_usuario_y_mes(Conexion::obtener_conexion());
list($cotizaciones_ganadas_anual_usuarios, $cotizaciones_ganadas_anual_usuarios_monto) = RepositorioUsuario::obtener_cotizaciones_ganadas_por_usuario_y_mes(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
echo json_encode(array(
  'usernames' => $nombres_usuario,
  'completed_quotes' => $cotizaciones_completadas,
  'past_completed_quotes' => $cotizaciones_completadas_pasadas,
  'monthly_price_awards' => $monto_cotizaciones_mes,
  'award_quotes' => $cotizaciones_ganadas,
  'past_award_quotes' => $cotizaciones_ganadas_pasadas,
  'monthly_awards' => $cotizaciones_mes,
  'monthly_completed_quotes_by_user' => $cotizaciones_completadas_anual_usuarios,
  'monthly_awards_quotes_by_user' => $cotizaciones_ganadas_anual_usuarios,
  'monthly_price_awards_quotes_by_user' => $cotizaciones_ganadas_anual_usuarios_monto,
));
?>
