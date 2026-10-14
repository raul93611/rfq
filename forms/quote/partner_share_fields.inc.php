<?php
/**
 * Solicitation # + "Share with partner" — shared by New Quote (both form states) and the
 * Information drawer. Expects:
 *   $ps_solicitation  string   current value
 *   $ps_share         bool     toggle state
 *   $ps_error         ?string  server-side validation message (blank / duplicate), shown inline
 *   $ps_small         bool     use .form-control-sm (drawer)
 * Behaviour (required marker, inline error, submit guard) lives in js/partner_share.js.
 */
$ps_solicitation = (string)($ps_solicitation ?? '');
$ps_share = !empty($ps_share);
$ps_error = $ps_error ?? null;
$ps_small = !empty($ps_small);
$ps_blank_msg = PartnerShareRepository::ERR_REQUIRED;
?>
<div class="form-row pp-new-row" data-testid="partner-share-fields">
  <div class="col-md-6">
    <div class="form-group <?= $ps_error ? 'has-pp-error' : ''; ?>" id="pp-sol-group">
      <label for="solicitation_number">Solicitation #<span class="pp-req" id="pp-sol-req"<?= $ps_share ? '' : ' hidden'; ?>> *</span></label>
      <input type="text" class="form-control pp-mono<?= $ps_small ? ' form-control-sm' : ''; ?>" id="solicitation_number" name="solicitation_number"
             maxlength="255" autocomplete="off" placeholder="e.g. FA8307-26-Q-0021"
             value="<?= htmlspecialchars($ps_solicitation, ENT_QUOTES, 'UTF-8'); ?>"
             aria-describedby="pp-sol-error" aria-invalid="<?= $ps_error ? 'true' : 'false'; ?>"
             data-blank-message="<?= htmlspecialchars($ps_blank_msg, ENT_QUOTES, 'UTF-8'); ?>">
      <div class="pp-field-error" id="pp-sol-error" role="alert" data-testid="solicitation-error">
        <i class="fas fa-exclamation-circle"></i><span><?= htmlspecialchars($ps_error ?: $ps_blank_msg, ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group sync-field pp-share-field">
      <label class="sync-check" for="share_with_partner">
        <input type="checkbox" class="sync-check-input" id="share_with_partner" name="share_with_partner" value="1" <?= $ps_share ? 'checked' : ''; ?>>
        <span class="sync-check-box" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </span>
        <span class="sync-check-text">
          <span class="sync-check-label">Share with partner</span>
          <span class="sync-check-help">Sends this opportunity and the documents you select to the partner capture platform.</span>
        </span>
      </label>
    </div>
  </div>
</div>
