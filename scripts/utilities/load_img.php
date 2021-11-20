<?php
header('Content-Type: application/json');
$directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
Input::save_files($directorio, $_FILES['archivos_ejemplo'], $id_rfq);
echo json_encode(array(
  'result'=> '1'
));
?>
