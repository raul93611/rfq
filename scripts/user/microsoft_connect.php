<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

$tenant_id   = GRAPH_TENANT_ID;
$client_id   = GRAPH_CLIENT_ID;
$redirect_uri = urlencode(MS_CALLBACK);
$scope       = urlencode('openid email profile Mail.Send offline_access');
$state       = bin2hex(random_bytes(16));

$_SESSION['ms_oauth_state'] = $state;

$auth_url = "https://login.microsoftonline.com/{$tenant_id}/oauth2/v2.0/authorize"
  . "?client_id={$client_id}"
  . "&response_type=code"
  . "&redirect_uri={$redirect_uri}"
  . "&scope={$scope}"
  . "&state={$state}"
  . "&prompt=consent";

header('Location: ' . $auth_url);
exit;
