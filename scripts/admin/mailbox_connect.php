<?php
/**
 * Start the Microsoft OAuth round-trip for the shared Notification Mailbox.
 * Admin-only. Reuses the existing delegated OAuth app + the already-registered
 * redirect URI (MS_CALLBACK); a session marker tells the shared callback to store
 * the tokens in the notification_mailbox record instead of the acting user's row.
 */
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
if (!$_SESSION['user']->is_admin()) {
  Redireccion::redirigir1(SERVIDOR);
}

$tenant_id    = GRAPH_TENANT_ID;
$client_id    = GRAPH_CLIENT_ID;
$redirect_uri = urlencode(MS_CALLBACK);
$scope        = urlencode('openid email profile Mail.Send offline_access');
$state        = bin2hex(random_bytes(16));

$_SESSION['ms_oauth_state']  = $state;
$_SESSION['ms_oauth_target'] = 'mailbox';

$auth_url = "https://login.microsoftonline.com/{$tenant_id}/oauth2/v2.0/authorize"
  . "?client_id={$client_id}"
  . "&response_type=code"
  . "&redirect_uri={$redirect_uri}"
  . "&scope={$scope}"
  . "&state={$state}"
  . "&prompt=consent";

header('Location: ' . $auth_url);
exit;
