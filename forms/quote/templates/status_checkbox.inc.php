<?php
$canal = $cotizacion_recuperada->obtener_canal();
$awardChecked = $cotizacion_recuperada->obtener_award() ? 'checked' : '';
$submittedInvoiceChecked = $cotizacion_recuperada->obtener_submitted_invoice() ? 'checked' : '';
$invoiceChecked = $cotizacion_recuperada->obtener_invoice() ? 'checked' : '';
$fulfillmentChecked = $cotizacion_recuperada->obtener_fullfillment() ? 'checked' : '';
$statusChecked = $cotizacion_recuperada->obtener_status() ? 'checked' : '';
$completedChecked = $cotizacion_recuperada->obtener_completado() ? 'checked' : '';

if ($canal == 'Chemonics' && !$cotizacion_recuperada->obtener_award()) {
  echo '<div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="award" value="si" ' . $awardChecked . ' id="award">
            <label class="custom-control-label" for="award">Award</label>
          </div>';
} else {
  if ($cotizacion_recuperada->obtener_invoice()) {
    echo '<div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="submitted_invoice" value="si" ' . $submittedInvoiceChecked . ' id="submitted_invoice">
                <label class="custom-control-label" for="submitted_invoice">Submitted Invoice</label>
              </div>';
  } elseif ($cotizacion_recuperada->isEnabledToInvoice() === true) {
    echo '<div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="invoice" value="si" ' . $invoiceChecked . ' id="invoice">
                <label class="custom-control-label" for="invoice">Invoice</label>
              </div>';
  } elseif ($cotizacion_recuperada->obtener_fullfillment()) {
    $invoiceCheck = $cotizacion_recuperada->isEnabledToInvoice();
    // If there are errors, display them
    echo '<div class="mb-0 alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5><ul>';
    foreach ($invoiceCheck as $error) {
      echo '<li>' . $error . '</li>';
    }
    echo '</ul></div>';
  } elseif ($cotizacion_recuperada->isEnabledToFulfillment() === true) {
    echo '<div id="id1" class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="fulfillment" value="si" ' . $fulfillmentChecked . ' id="fulfillment">
                <label class="custom-control-label" for="fulfillment">Fulfillment</label>
              </div>';
  } elseif ($cotizacion_recuperada->obtener_award()) {
    $fulfillmentCheck = $cotizacion_recuperada->isEnabledToFulfillment();
    // If there are errors, display them
    echo '<div class="mb-0 alert alert-warning alert-dismissible">
    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5><ul>';
    foreach ($fulfillmentCheck as $error) {
      echo '<li>' . $error . '</li>';
    }
    echo '</ul></div>';
  } elseif ($cotizacion_recuperada->obtener_status() && !$cotizacion_recuperada->obtener_award()) {
    echo '<div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="award" value="si" ' . $awardChecked . ' id="award">
                <label class="custom-control-label" for="award">Award</label>
              </div>';
  } elseif ($cotizacion_recuperada->obtener_completado() && !$cotizacion_recuperada->obtener_status()) {
    echo '<div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="status" value="si" ' . $statusChecked . ' id="status">
                <label class="custom-control-label" for="status">Submitted</label>
              </div>';
  } elseif (!$cotizacion_recuperada->obtener_completado()) {
    echo '<div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="completado" value="si" ' . $completedChecked . ' id="completado">
                <label class="custom-control-label" for="completado">Completed</label>
              </div>';
  }
}
