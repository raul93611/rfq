<?php
/**
 * Partner Opportunity Sync status badges (Quote Edit header). Expects $cotizacion_recuperada.
 * Hidden entirely while sharing is off and no assignment is pending. js/partner_share.js repaints
 * this container from the save_information response, so keep the markup in step with PartnerShare.badgesHtml().
 */
$pb_state  = $cotizacion_recuperada->getPartnerLinkState();
$pb_needs  = (int)$cotizacion_recuperada->getPartnerNeedsAssignment() === 1;
$pb_synced = $cotizacion_recuperada->getPartnerSharedUpdatedAt();
$pb_iso    = $pb_synced ? gmdate('c', strtotime($pb_synced . ' UTC')) : '';
?>
<div id="pp-badges" class="pp-badges" data-testid="partner-badges"<?= ($pb_state === 'off' && !$pb_needs) ? ' hidden' : ''; ?>>
  <?php if ($pb_state === 'linked'): ?>
    <span class="pp-badge is-linked" data-testid="badge-linked">
      <i class="fas fa-link"></i>Linked to partner<?php if ($pb_iso): ?><span class="pp-badge-meta">last synced <span data-pp-ago="<?= htmlspecialchars($pb_iso, ENT_QUOTES, 'UTF-8'); ?>"></span></span><?php endif; ?>
    </span>
  <?php elseif ($pb_state === 'waiting'): ?>
    <span class="pp-badge is-waiting" data-testid="badge-waiting"><i class="far fa-clock"></i>Waiting for partner</span>
  <?php endif; ?>
  <?php if ($pb_needs): ?>
    <button type="button" class="pp-badge is-assign" id="pp-assign-badge" data-testid="badge-needs-assignment" title="Open Information to assign an owner">
      <i class="fas fa-exclamation-triangle"></i>Needs assignment<span class="pp-badge-chev"><i class="fas fa-chevron-right"></i></span>
    </button>
  <?php endif; ?>
</div>
