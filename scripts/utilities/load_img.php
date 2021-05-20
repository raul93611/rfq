<?php
header('Content-Type: application/json');
$directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documents/' . $id_rfq;
Input::save_files($directorio, $_FILES['archivos_ejemplo']['name'], $_FILES['archivos_ejemplo']['tmp_name']);
echo json_encode(array(
  'result'=> '1'
));
?>
