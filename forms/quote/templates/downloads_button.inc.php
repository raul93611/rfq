<?php
Conexion::abrir_conexion();
$re_quote_exists = ReQuoteRepository::re_quote_exists(Conexion::obtener_conexion(), $id_rfq);
$rooms = RoomRepository::getAll(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>

<div class="btn-group dropup">
  <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-download mr-1"></i> Downloads
  </button>
  <div class="dropdown-menu dropdown-menu-right">
    <a target="_blank" href="<?= PDF_TABLA_ITEMS . $cotizacion_recuperada->obtener_id(); ?>" class="dropdown-item">PDF - Items table</a>
    <?php if ($re_quote_exists): ?>
      <a target="_blank" href="<?= EXCEL_ITEMS_TABLE . $cotizacion_recuperada->obtener_id(); ?>" class="dropdown-item">EXCEL - Quote &amp; Re-quote</a>
    <?php endif; ?>
    <a class="dropdown-item" href="<?= PROPOSAL . '/' . $cotizacion_recuperada->obtener_id(); ?>" target="_blank">Proposal</a>
    <?php if ($cotizacion_recuperada->obtener_canal() == 'GSA-Buy'): ?>
      <a class="dropdown-item" href="<?= PROPOSAL_GSA . '/' . $cotizacion_recuperada->obtener_id(); ?>" target="_blank">GSA Proposal</a>
    <?php endif; ?>
    <?php if (count($rooms)): ?>
      <a class="dropdown-item" href="<?= PROPOSAL_ROOM . '/' . $cotizacion_recuperada->obtener_id(); ?>" target="_blank">Proposal by Room</a>
    <?php endif; ?>
  </div>
</div>
