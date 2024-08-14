<?php
$audit_trails = FulfillmentAuditTrailRepository::get_audit_trails($_POST['id_rfq']);
?>
<div class="timeline">
  <div>
    <i class="fa fa-bookmark"></i>
    <div class="timeline-item">
      <h3 class="timeline-header">Proposal: <?= htmlspecialchars($_POST['id_rfq'], ENT_QUOTES, 'UTF-8'); ?></h3>
    </div>
  </div>
  <?php if (count($audit_trails)) : ?>
    <?php foreach ($audit_trails as $audit_trail) :
      $created_date = RepositorioComment::mysql_datetime_to_english_format($audit_trail->get_created_date());
    ?>
      <div>
        <i class="fa fa-user"></i>
        <div class="timeline-item">
          <span class="time"><i class="far fa-clock"></i> <?= htmlspecialchars($created_date, ENT_QUOTES, 'UTF-8'); ?></span>
          <h3 class="timeline-header">
            <span class="text-primary"><?= htmlspecialchars($audit_trail->get_username(), ENT_QUOTES, 'UTF-8'); ?></span>
          </h3>
          <div class="timeline-body">
            <?= nl2br($audit_trail->get_audit_trail()); ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <div>
    <i class="fa fa-infinity"></i>
  </div>
</div>
<br>