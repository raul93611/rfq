<?php
if($quote-> obtener_fulfillment_pending()){
  ?>
  <a href="<?php echo UNMARK_AS_PENDING . $quote-> obtener_id(); ?>" class="btn btn-primary"><i class="fas fa-play-circle"></i> Unmark as Partial Invoice</a>
  <?php
}else{
  ?>
  <a href="<?php echo MARK_AS_PENDING . $quote-> obtener_id(); ?>" class="btn btn-primary"><i class="fas fa-pause-circle"></i> Mark as Partial Invoice</a>
  <?php
}
?>
