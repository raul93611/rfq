<?php
session_start();
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
$end_date = RepositorioComment::english_format_to_mysql_datetime($cotizacion-> obtener_end_date());
Connection::open_connection();
$users = UserRepository::get_users_3_4(Connection::get_connection());
$array_id_users = [];
foreach ($users as $user) {
  $array_id_users[] = $user-> get_id();
}
$designated_user_index = array_rand($array_id_users);
$designated_user = $array_id_users[$designated_user_index];
$user = UserRepository::get_user_by_id(Connection::get_connection(), $designated_user);
$project = new Project('', $designated_user, '', $cotizacion-> obtener_email_code(), 'RFQ project', '', $end_date, '', '', '', 'services_and_equipment', 0, $designated_user, 0, '', '', '', 0, '', 0, 0, 0, '', '', 1,'', '', '', '', '', '', '', '', '', 0);
$id_project = ProjectRepository::insert_project(Connection::get_connection(), $project);
$service = New Service('', $id_project, 0, 0);
ServiceRepository::insert_service(Connection::get_connection(), $service);
Connection::close_connection();
Conexion::abrir_conexion();
RepositorioRfq::establecer_id_rfp(Conexion::obtener_conexion(), $id_project, $id_rfq);
Conexion::cerrar_conexion();
$rfq_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
$rfp_directory = $_SERVER['DOCUMENT_ROOT'] . '/rfp/documents/' . $id_project;
mkdir($rfp_directory, 0777);
if(is_dir($rfq_directory)){
  $manager = opendir($rfq_directory);
  $folder = @scandir($rfq_directory);
  while(($file = readdir($manager)) !== false){
    if($file != '.' && $file != '..'){
      copy($rfq_directory . '/' . $file, $rfp_directory . '/' . $file);
    }
  }
  closedir($manager);
}
$to = $user-> get_email();
$subject = "RFP system";
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: " . $_SESSION['nombre_usuario'] . " E-logic <elogic@e-logic.us>\r\n";
$message = '
<html>
<body>
<h3>New project:</h3>
<p>The project was created from RFQ Team</p>
<h3>Link:</h3>
<p><a href="http://www.elogicportal.com/rfp/profile/calendar_new_projects">E-logic portal</a></p>
</body>
</html>
';
mail($to, $subject, $message, $headers);
Redireccion::redirigir(EDITAR_COTIZACION . '/' . $id_rfq);
?>
