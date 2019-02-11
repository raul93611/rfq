<?php
include_once 'vendor/autoload.php';
Conexion::abrir_conexion();
$cuestionario = RepositorioCuestionario::obtener_cuestionario_por_id(Conexion::obtener_conexion(), $id_cuestionario);
$cotizacion = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $cuestionario-> obtener_id_rfq());
$usuario = RepositorioUsuario::obtener_usuario_por_id(Conexion::obtener_conexion(), $cotizacion-> obtener_usuario_designado());
$high_level_requirements = RepositorioHighLevelRequirement::obtener_high_level_requirements_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
$out_of_scopes = RepositorioOutOfScope::obtener_out_of_scopes_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
$project_risks = RepositorioProjectRisk::obtener_project_risks_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
$project_milestones = RepositorioProjectMilestone::obtener_project_milestones_de_un_cuestionario(Conexion::obtener_conexion(), $id_cuestionario);
Conexion::cerrar_conexion();
$hoy = getdate();
try{
  $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
  $fontDirs = $defaultConfig['fontDir'];
  $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
  $fontData = $defaultFontConfig['fontdata'];
  $mpdf = new \Mpdf\Mpdf(['format' => 'Letter', 'margin_footer' => '8',
  'fontDir' => array_merge($fontDirs, [
          SERVIDOR . '/vendor/mpdf/mpdf/ttfonts',
      ]),
      'fontdata' => $fontData + [
          'roboto' => [
              'R' => 'Roboto-Regular.ttf',
              'I' => 'Roboto-Italic.ttf',
          ]
      ],
      'default_font' => 'roboto'
  ]);
  $html = '<!DOCTYPE html>
  <html>
  <head>
  <style>
  body{
    font-family: roboto;
  }
  th{
    color: #004A97;
    background-color: #DEE8F2;
  }
  #tabla th,#tabla td {
    border: 1px solid #DEE8F2;

    padding-left: 10px;
    padding-right: 10px;
    padding-top: 5px;
    padding-bottom: 5px;
    font-size: 9pt;
  }
  table, th, td{
    border-collapse: collapse;
  }
  td{
    color: #3B3B3B;
  }

  .quantity{
    width: 20px;
  }

  .total_ancho{
    width: 130px;
  }

  .letra_chiquita{
    font-size: 8pt;
  }

  .color{
    color: #004A97;
  }
  .letra_grande{
    font-size: 25pt;
  }
  </style>
  </head>';
  $html .= '<body>
  <table border=0 width="100%">
    <tr>
      <td width="400">
      <img style="width:350px;height:130px;" src="' . RUTA_IMG . '/logo_proposal.jpg">
      </td>
      <td align="right" valign="top">
        <span class="color letra_grande">PROJECT CHARTER</span>
        <br><br>
        <table id="tabla">
          <tr>
            <th>PROPOSAL #</th>
            <th>SALES REP</th>
            <th>DATE</th>
          </tr>
          <tr>
            <td style="text-align:center;">' . $cotizacion-> obtener_id() . '</td>
            <td style="text-align:center;">' . $usuario-> obtener_nombres() . ' ' . $usuario-> obtener_apellidos() . '</td>
            <td style="text-align:center;">' . $hoy['mon'] . '/' . $hoy['mday'] . '/' . $hoy['year'] . '</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <div >
  </div>';

  $html .= '
  <h2 class="color">Project objectives</h2>
  <table id="tabla" width="100%">
  <tr>
    <td style="border:none;"></td>
    <th>GOALS</th>
    <th>OBJECTIVES</th>
  </tr>
  ';
  $html .= '
  <tr>
    <th>REACH</th>
    <td>' . $cuestionario-> obtener_reach_goals() . '</td>
    <td>' . $cuestionario-> obtener_reach_objectives() . '</td>
  </tr>
  <tr>
    <th>COST</th>
    <td>' . $cuestionario-> obtener_cost_goals() . '</td>
    <td>' . $cuestionario-> obtener_cost_objectives() . '</td>
  </tr>
  <tr>
    <th>TIME</th>
    <td>' . $cuestionario-> obtener_time_goals() . '</td>
    <td>' . $cuestionario-> obtener_time_objectives() . '</td>
  </tr>
  <tr>
    <th>QUALITY</th>
    <td>' . $cuestionario-> obtener_quality_goals() . '</td>
    <td>' . $cuestionario-> obtener_quality_objectives() . '</td>
  </tr>
  ';
  $html .= '</table>';

  if($cuestionario-> obtener_locations() == 'multiple'){
    $locations = 'multiple';
  }else if($cuestionario-> obtener_locations() == 'one_location'){
    $locations = 'one location';
  }
  $html .= '
    <h3 class="color">Location: <small>' . $locations . '</small></h3>
  ';
if(count($high_level_requirements)){
  $html .= '
  <h2 class="color">High level requirements</h2>
  <table id="tabla" width="100%">
    <tr>
      <th>#</th>
      <th>REQUIREMENT</th>
    </tr>
  ';
  $a = 1;
  for ($i=0; $i < count($high_level_requirements); $i++) {
    $high_level_requirement = $high_level_requirements[$i];
    $html .= '
      <tr>
        <td class="quantity">' . $a . '</td>
        <td>' . $high_level_requirement-> obtener_requirement() . '</td>
      </tr>
    ';
    $a++;
  }
  $html .= '</table>';
}

if(count($out_of_scopes)){
  $html .= '
  <h2 class="color">Out of scope</h2>
  <table id="tabla" width="100%">
    <tr>
      <th class="quantity">#</th>
      <th>REQUIREMENT</th>
    </tr>
  ';
  $a = 1;
  for ($i=0; $i < count($out_of_scopes); $i++) {
    $out_of_scope = $out_of_scopes[$i];
    $html .= '
      <tr>
        <td class="quantity">' . $a . '</td>
        <td>' . $out_of_scope-> obtener_requirement() . '</td>
      </tr>
    ';
    $a++;
  }
  $html .= '</table>';
}

if(count($project_risks)){
  $html .= '
  <h2 class="color">Project risks</h2>
  <table id="tabla" width="100%">
    <tr>
      <th class="quantity">#</th>
      <th>DESCRIPTION</th>
    </tr>
  ';
  $a = 1;
  for ($i=0; $i < count($project_risks); $i++) {
    $project_risk = $project_risks[$i];
    $html .= '
      <tr>
        <td class="quantity">' . $a . '</td>
        <td>' . $project_risk-> obtener_description() . '</td>
      </tr>
    ';
    $a++;
  }
  $html .= '</table>';
}

if(count($project_milestones)){
  $html .= '
  <h2 class="color">Project milestones</h2>
  <table id="tabla" width="100%">
    <tr>
      <th class="quantity">#</th>
      <th>DATE</th>
      <th>DESCRIPTION</th>
    </tr>
  ';
  $a = 1;
  for ($i=0; $i < count($project_milestones); $i++) {
    $project_milestone = $project_milestones[$i];
    $html .= '
      <tr>
        <td>' . $a . '</td>
        <td>' . $project_milestone-> obtener_date_milestone() . '</td>
        <td>' . $project_milestone-> obtener_description() . '</td>
      </tr>
    ';
    $a++;
  }
  $html .= '</table>';
}

  $html .= '</body></html>';
  $mpdf->WriteHTML($html);
  $mpdf->Output($_SERVER['DOCUMENT_ROOT'] . '/rfq/documentos/' . $cotizacion->obtener_id() . '/' . 'project_charter.pdf', 'F');
  $mpdf->Output('project_charter.pdf', 'I');
} catch (\Mpdf\MpdfException $e) {
  echo $e->getMessage();
}
?>
