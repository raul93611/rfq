<?php
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From:hola <elogic@e-logic.us>\r\n";
$result = mail('raul93611@gmail.com', 'subject', 'hola', $headers) ? 'enviado' : 'no enviado';
print $result;
?>