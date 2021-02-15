<?php
// echo __DIR__ . 'adasd';
require_once 'vendor/autoload.php';
//Create an instance of the class:
try {
  $mpdf = new Mpdf\Mpdf();
  $mpdf->WriteHTML('Hello World');
  // Other code
  $mpdf->Output();
} catch (\Mpdf\MpdfException $e) { // Note: safer fully qualified exception name used for catch
  // Process the exception, log, print etc.
  echo $e->getMessage();
}
