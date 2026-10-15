<?php
/**
 * Admin Settings → Partner Integration card + Failed deliveries + Needs review.
 * Included by admin_settings.inc.php (admin-only, already gated there). All interaction is in
 * js/partner_admin.js over the admin/partner/* JSON endpoints. Stored secrets are never echoed back
 * into the page: the inputs show a "saved" placeholder and a blank submit keeps the stored value.
 */
Conexion::abrir_conexion();
$pp_c = Conexion::obtener_conexion();
$pp = PartnerIntegrationRepository::get($pp_c);
$pp_failed = PartnerOutboxRepository::failed($pp_c);
$pp_reviews = PartnerSyncService::openReviews($pp_c);
$pp_issuer = null;
if (!empty($pp['token_created_by'])) {
  $pp_issuer_user = RepositorioUsuario::obtener_usuario_por_id($pp_c, $pp['token_created_by']);
  $pp_issuer = $pp_issuer_user ? $pp_issuer_user->obtener_nombre_usuario() : null;
}
Conexion::cerrar_conexion();

$pp_enabled = (int)$pp['enabled'] === 1;
$pp_has_token = !empty($pp['issued_token_hash']);
$pp_et = function ($utc) {
  if (!$utc) return null;
  return (new DateTime($utc . ' UTC'))->setTimezone(new DateTimeZone('America/New_York'))->format('M j, Y · g:i A');
};
$pp_iso = function ($utc) { return $utc ? gmdate('c', strtotime($utc . ' UTC')) : ''; };
$pp_e = function ($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$pp_reason = [
  'possible'  => ['Possible existing quote', 'fa-link'],
  'ambiguous' => ['Ambiguous match', 'fa-exclamation-triangle'],
  'rejected'  => ['Rejected', 'fa-ban'],
];
?>
<!-- Partner Integration card -->
<div class="ac-card" id="pp-card" data-testid="partner-card" data-enabled="<?= $pp_enabled ? '1' : '0'; ?>">
  <div class="ac-card-head" style="align-items:flex-start;">
    <div class="ac-card-head-icon"><i class="fas fa-sync-alt"></i></div>
    <div class="ac-card-head-text" style="max-width:640px;">
      <div class="ac-card-head-title">
        Partner Integration
        <span class="ac-admin-tag"><i class="fas fa-shield-alt"></i> Admin only</span>
      </div>
      <div class="ac-card-head-sub">Opportunities and their solicitation documents are shared automatically with the partner capture platform — no double data entry.</div>
    </div>
    <div class="pp-head-right">
      <button type="button" role="switch" class="pp-switch" id="pp-enabled" data-testid="partner-enabled" aria-checked="<?= $pp_enabled ? 'true' : 'false'; ?>">
        <span class="pp-switch-track"></span><span id="pp-enabled-label"><?= $pp_enabled ? 'Enabled' : 'Disabled'; ?></span>
      </button>
    </div>
  </div>

  <div class="pp-banner-wrap" id="pp-banner"<?= $pp_enabled ? ' hidden' : ''; ?>>
    <div class="pp-banner">
      <i class="fas fa-pause-circle"></i>
      <span><strong>Partner Integration is off — no sync is happening.</strong> Quotes set to “Share with partner” are not sent, and nothing is pulled from or received from the partner until you enable it.</span>
      <button type="button" class="pp-btn pp-btn-sm" id="pp-banner-enable">Enable</button>
    </div>
  </div>

  <form id="pp-settings-form" autocomplete="off" onsubmit="return false;">
    <div class="pp-sec<?= $pp_enabled ? '' : ' pp-muted'; ?>" data-pp-mutable>
      <div class="pp-sec-title">Partner connection <small>How Elogic reaches the partner</small></div>
      <div class="pp-fields">
        <div class="pp-field">
          <label class="pp-label" for="pp-base-url">Partner base URL</label>
          <input class="pp-input" id="pp-base-url" name="partner_base_url" type="url" placeholder="https://api.partner.example/v2" value="<?= $pp_e($pp['partner_base_url']); ?>">
          <span class="pp-help">Where Elogic pulls opportunities from.</span>
        </div>
        <div class="pp-field">
          <label class="pp-label" for="pp-webhook-url">Partner webhook URL</label>
          <input class="pp-input" id="pp-webhook-url" name="partner_webhook_url" type="url" placeholder="https://hooks.partner.example/elogic" value="<?= $pp_e($pp['partner_webhook_url']); ?>">
          <span class="pp-help">Where Elogic delivers events when quotes change.</span>
        </div>
        <div class="pp-field">
          <label class="pp-label" for="pp-partner-token">Partner token</label>
          <div class="pp-input-wrap">
            <input class="pp-input pp-input-mono" id="pp-partner-token" name="partner_token" type="password"
                   placeholder="<?= !empty($pp['partner_token']) ? '•••••••• saved — leave blank to keep' : 'Paste the token the partner issued'; ?>">
            <button type="button" class="pp-eye" data-pp-eye="pp-partner-token" aria-label="Show"><i class="fas fa-eye"></i></button>
          </div>
          <span class="pp-help">Credential the partner issued to Elogic.</span>
        </div>
        <div class="pp-field">
          <label class="pp-label" for="pp-signing-secret">Signing secret</label>
          <div class="pp-input-wrap">
            <input class="pp-input pp-input-mono" id="pp-signing-secret" name="signing_secret" type="password"
                   placeholder="<?= !empty($pp['signing_secret']) ? '•••••••• saved — leave blank to keep' : 'Shared secret for webhook signatures'; ?>">
            <button type="button" class="pp-eye" data-pp-eye="pp-signing-secret" aria-label="Show"><i class="fas fa-eye"></i></button>
          </div>
          <span class="pp-help">Signs webhooks in both directions so each side can verify the other.</span>
        </div>
      </div>
    </div>

    <div class="pp-sec<?= $pp_enabled ? '' : ' pp-muted'; ?>" data-pp-mutable>
      <div class="pp-sec-title">Token for the partner <small>The credential the partner uses to call Elogic</small></div>
      <div class="pp-token" id="pp-token-panel" data-testid="token-panel"
           data-has-token="<?= $pp_has_token ? '1' : '0'; ?>" data-revoked="<?= !empty($pp['token_revoked_at']) ? '1' : '0'; ?>"
           data-last4="<?= $pp_e($pp['issued_token_last4']); ?>"
           data-issued="<?= $pp_e($pp_et($pp['token_created_at'])); ?>" data-issuer="<?= $pp_e($pp_issuer); ?>"></div>
    </div>
  </form>

  <div class="pp-status<?= $pp_enabled ? '' : ' pp-muted'; ?>" data-pp-mutable>
    <div class="pp-status-item">
      <span class="ac-status-dot <?= $pp_enabled ? 'ac-status-dot-connected' : 'ac-status-dot-disconnected'; ?>"></span>
      Last pull: <strong data-testid="last-pull"><?= $pp_e($pp_et($pp['last_pull_at']) ?: 'Never'); ?></strong>
      <?php if ($pp['last_pull_at']): ?><span>(<span data-pp-ago="<?= $pp_e($pp_iso($pp['last_pull_at'])); ?>"></span>)</span><?php endif; ?>
    </div>
    <div class="pp-status-item">
      <span class="ac-status-dot <?= $pp_enabled ? 'ac-status-dot-connected' : 'ac-status-dot-disconnected'; ?>"></span>
      Last successful webhook: <strong data-testid="last-webhook"><?= $pp_e($pp_et($pp['last_webhook_success_at']) ?: 'Never'); ?></strong>
      <?php if ($pp['last_webhook_success_at']): ?><span>(<span data-pp-ago="<?= $pp_e($pp_iso($pp['last_webhook_success_at'])); ?>"></span>)</span><?php endif; ?>
    </div>
  </div>

  <div class="pp-foot">
    <span class="pp-form-error" id="pp-settings-error" role="alert" hidden></span>
    <span class="pp-saved" id="pp-saved"><i class="fas fa-check"></i> Saved</span>
    <button type="button" class="pp-btn pp-btn-primary" id="pp-save" data-testid="partner-save"<?= $pp_enabled ? '' : ' disabled'; ?>><i class="fas fa-check"></i> Save settings</button>
  </div>
</div>

<!-- Failed deliveries -->
<div class="ac-card" id="pp-failed-card" data-testid="failed-deliveries">
  <div class="ac-card-head">
    <div class="ac-card-head-icon pp-ic-red"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="ac-card-head-text">
      <div class="ac-card-head-title">Failed deliveries <span class="pp-count<?= $pp_failed ? '' : ' is-zero'; ?>" id="pp-failed-count"><?= count($pp_failed); ?></span></div>
      <div class="ac-card-head-sub">Events Elogic couldn't deliver to the partner's webhook.</div>
    </div>
  </div>
  <div class="pp-empty" id="pp-failed-empty"<?= $pp_failed ? ' hidden' : ''; ?>>
    <span class="pp-empty-ic"><i class="fas fa-check-circle"></i></span>
    <div class="pp-empty-title">No failed deliveries</div>
    <div class="pp-empty-sub">Everything Elogic has sent to the partner was delivered.</div>
  </div>
  <div class="pp-table" id="pp-failed-table"<?= $pp_failed ? '' : ' hidden'; ?>>
    <div class="pp-thead pp-g-failed"><span class="pp-th">Quote</span><span class="pp-th">Event</span><span class="pp-th">Attempts</span><span class="pp-th">Last error</span><span class="pp-th"></span></div>
    <?php foreach ($pp_failed as $f): ?>
      <div class="pp-trow pp-g-failed is-center" data-testid="failed-row-<?= (int)$f['id']; ?>" data-id="<?= (int)$f['id']; ?>">
        <div class="pp-cell-quote">
          <a href="<?= $pp_e(EDITAR_COTIZACION . '/' . (int)$f['id_rfq']); ?>">#<?= (int)$f['id_rfq']; ?></a>
          <span class="pp-sub"><?= $pp_e($f['quote_name']); ?></span>
        </div>
        <div><span class="pp-evt"><?= $pp_e($f['event']); ?></span></div>
        <div class="pp-num" data-pp-attempts><?= (int)$f['attempts']; ?></div>
        <div class="pp-err"><span data-pp-error><?= $pp_e($f['last_error']); ?></span><span class="pp-when">last attempt <span data-pp-ago="<?= $pp_e($pp_iso($f['failed_at'])); ?>" data-pp-when></span></span></div>
        <div class="pp-act"><button type="button" class="pp-btn pp-btn-sm" data-pp-retry="<?= (int)$f['id']; ?>"<?= $pp_enabled ? '' : ' disabled title="Enable Partner Integration to retry"'; ?>><i class="fas fa-redo"></i> Retry</button></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Needs review -->
<div class="ac-card" id="pp-review-card" data-testid="needs-review">
  <div class="ac-card-head">
    <div class="ac-card-head-icon pp-ic-amber"><i class="fas fa-eye"></i></div>
    <div class="ac-card-head-text">
      <div class="ac-card-head-title">Needs review <span class="pp-count is-amber<?= $pp_reviews ? '' : ' is-zero'; ?>" id="pp-review-count"><?= count($pp_reviews); ?></span></div>
      <div class="ac-card-head-sub">Incoming partner records that couldn't be applied automatically.</div>
    </div>
  </div>
  <div class="pp-empty" id="pp-review-empty"<?= $pp_reviews ? ' hidden' : ''; ?>>
    <span class="pp-empty-ic"><i class="fas fa-check-circle"></i></span>
    <div class="pp-empty-title">Nothing needs review</div>
    <div class="pp-empty-sub">Incoming partner records are being matched automatically.</div>
  </div>
  <div class="pp-table" id="pp-review-table"<?= $pp_reviews ? '' : ' hidden'; ?>>
    <div class="pp-thead pp-g-review"><span class="pp-th">Reason</span><span class="pp-th">Incoming from partner</span><span class="pp-th">Existing quote(s) / details</span><span class="pp-th"></span></div>
    <?php foreach ($pp_reviews as $r):
      $inc = $r['incoming'];
      $kind = isset($pp_reason[$r['kind']]) ? $r['kind'] : 'rejected';
      $cands = $r['candidate_quotes'];
      ?>
      <div class="pp-trow pp-g-review" data-testid="review-row-<?= (int)$r['id']; ?>" data-id="<?= (int)$r['id']; ?>"
           data-incoming="<?= $pp_e($inc['name'] ?? $r['solicitation_number']); ?>">
        <div><span class="pp-reason is-<?= $pp_e($kind); ?>"><i class="fas <?= $pp_reason[$kind][1]; ?>"></i><?= $pp_reason[$kind][0]; ?></span></div>
        <div class="pp-inc">
          <div class="pp-inc-name"><?= $pp_e(($inc['name'] ?? '') !== '' ? $inc['name'] : $r['solicitation_number']); ?></div>
          <div class="pp-sub">Solicitation # <span class="pp-mono"><?= $pp_e($r['solicitation_number']); ?></span></div>
          <div class="pp-sub"><?= $pp_e($inc['agency'] ?? ''); ?><?= !empty($inc['agency']) ? ' · ' : ''; ?>received <span data-pp-ago="<?= $pp_e($pp_iso($r['created_at'])); ?>"></span></div>
        </div>
        <div>
          <?php if ($kind === 'possible' && $cands): $c0 = $cands[0]; ?>
            <div class="pp-vs"><i class="fas fa-arrow-right"></i>
              <div class="pp-cand">
                <div class="pp-cand-top"><a href="<?= $pp_e(EDITAR_COTIZACION . '/' . (int)$c0['id']); ?>">#<?= (int)$c0['id']; ?></a><span class="pp-mono-chip"><?= $pp_e($c0['email_code']); ?></span></div>
                <div class="pp-cand-name"><?= $pp_e($c0['name']); ?></div>
              </div>
            </div>
          <?php elseif ($kind === 'ambiguous'): ?>
            <div class="pp-cands">
              <div class="pp-cands-title"><?= $cands ? 'Matches ' . count($cands) . ' quotes' : $pp_e($r['reason']); ?></div>
              <?php foreach ($cands as $c): ?>
                <div class="pp-cand-line"><a href="<?= $pp_e(EDITAR_COTIZACION . '/' . (int)$c['id']); ?>">#<?= (int)$c['id']; ?></a><span class="pp-cand-name"><?= $pp_e($c['name']); ?></span><span class="pp-mono-chip"><?= $pp_e($c['email_code']); ?></span></div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="pp-rejected"><i class="fas fa-exclamation-circle"></i><span><strong>Could not be applied.</strong> <?= $pp_e($r['reason']); ?>.</span></div>
          <?php endif; ?>
        </div>
        <div class="pp-act is-col">
          <?php if ($kind === 'possible' && $cands): ?>
            <button type="button" class="pp-btn pp-btn-sm pp-btn-primary" data-pp-review="link" data-quote="<?= (int)$cands[0]['id']; ?>"><i class="fas fa-link"></i> Link to quote #<?= (int)$cands[0]['id']; ?></button>
            <button type="button" class="pp-btn pp-btn-sm" data-pp-review="create"><i class="fas fa-plus"></i> Create new</button>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script>window.PP_ADMIN = { action: <?= json_encode(PARTNER_ADMIN_ACTION); ?>, enabled: <?= $pp_enabled ? 'true' : 'false'; ?> };</script>
