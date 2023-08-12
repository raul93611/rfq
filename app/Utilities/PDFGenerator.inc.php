<?php
include_once 'vendor/autoload.php';

class PDFGenerator {
  private $mpdf;

  public function __construct($landscape = false) {
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];
    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $this->mpdf = new Mpdf\Mpdf([
      'tempDir' => dirname(__DIR__, 2) . '/tmp',
      'format' => 'Letter' . ($landscape ? '-L' : ''), 'margin_footer' => '8',
      'fontDir' => array_merge($fontDirs, [dirname(__DIR__, 2) . '/herramientas/fonts']),
      'fontdata' => $fontData + [
        'roboto' => [
          'R' => 'Roboto-Regular.ttf',
          'I' => 'Roboto-Italic.ttf',
        ]
      ]
    ]);
  }

  public function generatePDF($content) {
    $this->mpdf->WriteHTML($content);
  }

  public function saveInServer($path) {
    $this->mpdf->Output($path, 'F');
  }

  public function display($name) {
    $this->mpdf->Output($name, 'I');
  }

  public function setFooter($footer) {
    $this->mpdf->SetHTMLFooter($footer);
  }
}
