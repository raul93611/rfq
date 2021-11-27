<?php
$audit_trails = FulfillmentAuditTrailRepository::get_audit_trails($_POST['id_rfq']);
?>
<div class="timeline">
  <div>
    <i class="fa fa-bookmark"></i>
    <div class="timeline-item">
      <h3 class="timeline-header">Proposal: <?php echo $_POST['id_rfq']; ?></h3>
    </div>
  </div>
  <?php
  if(count($audit_trails)){
    foreach ($audit_trails as $audit_trail) {
      $created_date = RepositorioComment::mysql_datetime_to_english_format($audit_trail-> get_created_date());
      ?>
      <div>
        <i class="fa fa-user"></i>
        <div class="timeline-item">
          <span class="time"><i class="far fa-clock"></i> <?php echo $created_date; ?></span>
          <h3 class="timeline-header">
            <span class="text-primary"><?php echo $audit_trail-> get_username(); ?></span></h3>
            <div class="timeline-body">
              <?php echo nl2br($audit_trail-> get_audit_trail()); ?>
            </div>
          </div>
      </div>
      <?php
    }
  }
  ?>
  <div>
    <i class="fa fa-infinity"></i>
  </div>
</div>
<br>
