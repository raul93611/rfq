<?php
header('Content-Type: application/json');
list($nombres_usuario, $cotizaciones_completadas, $cotizaciones_completadas_pasadas, $cotizaciones_ganadas, $cotizaciones_ganadas_pasadas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
Conexion::abrir_conexion();
list($monthly_price_awards, $past_monthly_price_awards, $monthly_awards, $past_monthly_awards) = RepositorioRfq::obtener_monto_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
$cotizaciones_completadas_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_completadas_por_usuario_y_mes(Conexion::obtener_conexion());
list($cotizaciones_ganadas_anual_usuarios, $cotizaciones_ganadas_anual_usuarios_monto) = RepositorioUsuario::obtener_cotizaciones_ganadas_por_usuario_y_mes(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
echo json_encode(array(
  'usernames' => $nombres_usuario,
  'completed_quotes' => $cotizaciones_completadas,
  'past_completed_quotes' => $cotizaciones_completadas_pasadas,
  'monthly_price_awards' => $monthly_price_awards,
  'past_monthly_price_awards' => $past_monthly_price_awards,
  'award_quotes' => $cotizaciones_ganadas,
  'past_award_quotes' => $cotizaciones_ganadas_pasadas,
  'monthly_awards' => $monthly_awards,
  'past_monthly_awards' => $past_monthly_awards,
  'monthly_completed_quotes_by_user' => $cotizaciones_completadas_anual_usuarios,
  'monthly_awards_quotes_by_user' => $cotizaciones_ganadas_anual_usuarios,
  'monthly_price_awards_quotes_by_user' => $cotizaciones_ganadas_anual_usuarios_monto,
  'total_annual_awards_amounts' => number_format(array_sum($monthly_price_awards), 2),
  'past_total_annual_awards_amounts' => number_format(array_sum($past_monthly_price_awards), 2),
  'total_annual_awards' => array_sum($monthly_awards),
  'past_total_annual_awards' => array_sum($past_monthly_awards)
));
?>
