<?php
header('Content-Type: application/json');
$delivery_date = RepositorioComment::english_format_to_mysql_date($_POST['delivery_date']);
Database::open_connection();
TrackingSubitemRepository::update_tracking_subitem(Database::get_connection(), $_POST['quantity'], htmlspecialchars($_POST['tracking_number']), $delivery_date, $_POST['signed_by'], $_POST['id_tracking_subitem']);
Database::close_connection();
echo json_encode(array(
  'id_rfq'=> $_POST['id_rfq']
));
?>
