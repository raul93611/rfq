<input type="hidden" name="is-partial-invoices" value="<?= $quote->obtener_fulfillment_pending(); ?>">

<a href="<?= $quote->obtener_fulfillment_pending() ? UNMARK_AS_PENDING . $quote->obtener_id() : MARK_AS_PENDING . $quote->obtener_id(); ?>"
  class="btn btn-primary">
  <i class="fas <?= $quote->obtener_fulfillment_pending() ? 'fa-pause-circle' : 'fa-play-circle'; ?>"></i> Partial Invoice
</a>