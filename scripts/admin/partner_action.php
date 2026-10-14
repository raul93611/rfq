<?php
/**
 * Partner Integration admin actions — POST, JSON, admin only.
 *   admin/partner/settings   enabled + partner connection fields (blank secret = keep stored one)
 *   admin/partner/token      op = issue | rotate | revoke   (plaintext token returned exactly once)
 *   admin/partner/retry      id = outbox row → one immediate re-send
 *   admin/partner/review     id, op = link | create, quote_id → resolve a held incoming record
 * $partes_ruta is set by index.php.
 */
header('Content-Type: application/json');

function partner_json($code, array $body) {
  http_response_code($code);
  echo json_encode($body);
  exit;
}

if (!ControlSesion::sesion_iniciada() || !$_SESSION['user']->is_admin()) {
  partner_json(403, ['success' => false, 'message' => 'Only admins can manage the Partner Integration.']);
}
// A custom header cannot be sent cross-site without a CORS preflight — cheap CSRF barrier for
// actions that issue credentials and change where data is sent.
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'XMLHttpRequest') {
  partner_json(400, ['success' => false, 'message' => 'Bad request.']);
}

$action = $partes_ruta[3] ?? '';
$userId = (int)$_SESSION['user']->obtener_id();

function partner_url_error($label, $url) {
  $url = trim($url);
  if ($url === '') return null;
  $p = parse_url($url);
  $ok = filter_var($url, FILTER_VALIDATE_URL) && isset($p['scheme'], $p['host'])
    && ($p['scheme'] === 'https' || ($p['scheme'] === 'http' && in_array($p['host'], ['localhost', '127.0.0.1'], true)));
  return $ok ? null : "$label must be a full https:// URL.";
}

try {
  Conexion::abrir_conexion();
  $c = Conexion::obtener_conexion();

  switch ($action) {
    case 'settings':
      foreach (['partner_base_url' => 'Partner base URL', 'partner_webhook_url' => 'Partner webhook URL'] as $k => $label) {
        if ($err = partner_url_error($label, $_POST[$k] ?? '')) partner_json(422, ['success' => false, 'message' => $err]);
      }
      // Only keys actually posted are touched: the switch posts `enabled` alone and must not
      // blank the stored URLs. A blank secret means "keep the stored one" (see saveSettings).
      $settings = [];
      if (isset($_POST['enabled'])) $settings['enabled'] = $_POST['enabled'] === '1';
      foreach (['partner_base_url', 'partner_webhook_url', 'partner_token', 'signing_secret'] as $k) {
        if (isset($_POST[$k])) $settings[$k] = (string)$_POST[$k];
      }
      PartnerIntegrationRepository::saveSettings($c, $settings);
      partner_json(200, ['success' => true]);

    case 'token':
      $op = $_POST['op'] ?? '';
      if ($op === 'issue' || $op === 'rotate') {
        partner_json(200, ['success' => true, 'token' => PartnerIntegrationRepository::issueToken($c, $userId), 'rotated' => $op === 'rotate']);
      }
      if ($op === 'revoke') {
        PartnerIntegrationRepository::revokeToken($c);
        partner_json(200, ['success' => true]);
      }
      partner_json(422, ['success' => false, 'message' => 'Unknown token action.']);

    case 'retry':
      $id = (int)($_POST['id'] ?? 0);
      $outcome = PartnerSyncService::retryNow($c, $id);
      $row = PartnerOutboxRepository::find($c, $id);
      partner_json(200, [
        'success' => true, 'outcome' => $outcome,
        'attempts' => $row ? (int)$row['attempts'] : null, 'error' => $row ? $row['last_error'] : null,
      ]);

    case 'review':
      $op = $_POST['op'] ?? '';
      if (!in_array($op, ['link', 'create'], true)) partner_json(422, ['success' => false, 'message' => 'Unknown review action.']);
      $res = PartnerSyncService::resolveReview($c, (int)($_POST['id'] ?? 0), $op, $userId, (int)($_POST['quote_id'] ?? 0) ?: null);
      if (!in_array($res['outcome'], ['created', 'linked', 'updated'], true)) {
        partner_json(409, ['success' => false, 'message' => 'That record could not be resolved — it may already have been handled. Reload the page.']);
      }
      $quote = PartnerShareRepository::row($c, $res['id_rfq']);
      partner_json(200, [
        'success' => true, 'outcome' => $res['outcome'], 'id_rfq' => $res['id_rfq'],
        'quote_name' => $quote['name'] ?: $quote['email_code'], 'quote_code' => $quote['email_code'],
      ]);

    default:
      partner_json(404, ['success' => false, 'message' => 'Unknown action.']);
  }
} catch (Throwable $e) {
  error_log('Partner admin action failed: ' . $e->getMessage());
  partner_json(500, ['success' => false, 'message' => 'Something went wrong. Please try again.']);
}
