<?php
echo $id_rfq;
$directorio = $_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $id_rfq;
$documentos = array_filter($_FILES['archivos_ejemplo']['name']);
$total = count($documentos);
for ($i = 0; $i < $total; $i++) {
  $tmp_path = $_FILES['archivos_ejemplo']['tmp_name'][$i];
  $file = $_FILES['archivos_ejemplo']['name'][$i];
  if ($tmp_path != '') {
    $file = preg_replace('/[^a-z0-9-_\-\.]/i','_',$file);
    $new_path = $directorio . '/' . $file;
    move_uploaded_file($tmp_path, $new_path);
  }
}
//Redireccion::redirigir(EDITAR_COTIZACION . $id_rfq);
?>
