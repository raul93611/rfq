<?php

include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$usuario_designado = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion->obtener_usuario_designado());
$items = RepositorioItem::obtener_items_por_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();

$partes_fecha_completado = explode('-', $cotizacion->obtener_fecha_completado());
$fecha_completado = $partes_fecha_completado[1] . '/' . $partes_fecha_completado[2] . '/' . $partes_fecha_completado[0];
$partes_expiration_date = explode('-', $cotizacion->obtener_expiration_date());
$expiration_date = $partes_expiration_date[1] . '/' . $partes_expiration_date[2] . '/' . $partes_expiration_date[0];
$mpdf = new \Mpdf\Mpdf(['format' => 'Letter']);
$html = '<!DOCTYPE html>
<html>
<head>
<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    
}
th{
    color: #004A97;
    background-color: #DEE8F2;
}
th, td {
    border: 1px solid black;
    border-collapse: collapse;
    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    border-color: #004A97;
}
td{
    font-size: 12px;
    color: #022B49;
}

.color{
    color: #004A97;
}
</style>
</head>';
$html .= '<body><div class="color">
    <img style="float:right;width:270px;height:170px;margin-left:15px;" src="' . RUTA_IMG . '/elogic_logo.png">
    <b>E-logic, Inc.</b><br>
    1025 Connecticut Ave NW<br>
    Suite 1000<br>
    Washington, DC 20036<br>
    (202) 499-7840<br>
    elogic@e-logic.us<br>
    http://www.e-logic.us
</div>
<div style="text-align:center;">
    <h1 class="color">Proposal</h1>
</div>';

$html .= '<table style="width:100%">
  <tr>
    <th style="width:50%">ADDRESS</th>
    <th style="width:50%">SHIP TO</th> 
  </tr>
  <tr>
    <td>' . nl2br($cotizacion->obtener_address()) . '</td>
    <td>' . nl2br($cotizacion->obtener_ship_to()) . '</td>
  </tr>
</table>
<br>
<table style="width:100%">
  <tr>
    <th>PROPOSAL #</th>
    <th>DATE</th> 
    <th>EXPIRATION DATE</th>
  </tr>
  <tr>
    <td style="text-align:center;">' . $cotizacion->obtener_id() . '</td>
    <td style="text-align:center;">' . $fecha_completado . '</td>
    <td style="text-align:center;">' . $expiration_date . '</td>
  </tr>
</table>
<br>
<table style="width:100%">
  <tr>
    <th>SHIP VIA</th>
    <th>CONTRACT NUMBER</th> 
    <th>SALES REP</th>
    <th>E-MAIL</th>
    <th>PAYMENT TERMS</th>
  </tr>
  <tr>
    <td style="text-align:center;">' . $cotizacion->obtener_ship_via() . '</td>
    <td style="text-align:center;">' . $cotizacion->obtener_email_code() . '</td>
    <td style="text-align:center;">' . $usuario_designado->obtener_nombres() . ' ' . $usuario_designado->obtener_apellidos() . '</td>
        <td style="text-align:center;">' . $usuario_designado->obtener_email() . '</td>
    <td style="text-align:center;">' . $cotizacion->obtener_payment_terms() . '</td>
  </tr>
</table><br>';

if (count($items)) {
    if($encabezado){
        $html .= '<table style="width:100%">
            <tr>
    <td colspan="5">OPEN MARKET PRICING PROPOSAL<br><br>
    
E-Logic is an SBA 8(a) and HUBZONE Certified SB<br>
SBA 8(a) Case Number: 307867<br>
SBA 8(a) Entrance Date: 09/30/2016<br>
SBA 8(a) Exit Date: 09/30/2025<br><br>

As authorized by FAR 19.8, Federal agencies may issue sole source contracts to 8(a) firms up to $4 million. <br><br>

The agency CO should send “Offer Letter” and PWS to dcofferletters@sba.gov. The SBA processes the Offer Letter and returns it to the agency CO within 5 business days for contract processing.
</td> 
  </tr>';
    }
    $html .= '
  <tr>
    <th>#</th>
    <th>DESCRIPTION</th>
    <th>QTY</th> 
    <th>UNIT PRICE</th>
    <th>TOTAL</th>
  </tr>';
    $a = 1;
    for ($i = 0; $i < count($items); $i++) {
        $item = $items[$i];
        $html .= '<tr>
    <td>' . $a . '</td>
    <td><b>Brand:</b>' . $item->obtener_brand() . '<br><b>Part #:</b>' . $item->obtener_part_number() . '<br><b>Description:</b>' . nl2br($item->obtener_description()) . '</td>
    <td style="text-align:right;">' . $item->obtener_quantity() . '</td>
    <td style="text-align:right;">$ ' . $item->obtener_unit_price() . '</td>
    <td style="text-align:right;">$ ' . $item->obtener_total_price() . '</td>
  </tr>';
        $a++;
    }
    $html .= '<tr>
        <td style="border:none;"></td>
    <td style="font-size:20px;border:none;"><b>TOTAL:</b></td>
    <td style="border:none;"></td> 
    <td style="border:none;"></td>
    <td style="font-size:20px;text-align:right;"><b>$ ' . $cotizacion-> obtener_total_price() . '</b></td>
  </tr>';
    $html .= '</table>';
}
if ($cotizacion->obtener_payment_terms() == 'Net 30/CC') {
    $html .= '<br><div class="color"><b>PAYMENT TERMS</b><br><b>NET TERMS: </b>30 Days<br><b>CREDIT CARD PAYMENT: </b>Please add an additional 2.1% to process credit card payments.</div>';
}
$html .= '</body></html>';
$mpdf->SetHTMLFooter('
<div class="color" style="text-align:center;">
EIN: 51-0629765, DUNS: 786-965876, CAGE:4QTF4<br>SBA 8(a) and HUBZone certified
</div>
');
$mpdf->WriteHTML($html);
$mpdf->Output($_SERVER['DOCUMENT_ROOT'] . 'rfq/documentos/' . $cotizacion->obtener_id() . '/' . $cotizacion->obtener_email_code() . '.pdf', 'F');
$mpdf->Output($cotizacion->obtener_email_code() . '.pdf', 'I');
?>
