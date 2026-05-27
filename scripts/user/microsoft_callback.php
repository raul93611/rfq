<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}

$error = null;

do {
  if (empty($_GET['code']) || empty($_GET['state'])) {
    $error = 'Missing authorization code.';
    break;
  }

  if ($_GET['state'] !== ($_SESSION['ms_oauth_state'] ?? '')) {
    $error = 'Invalid state parameter.';
    break;
  }
  unset($_SESSION['ms_oauth_state']);

  $token_url = 'https://login.microsoftonline.com/' . GRAPH_TENANT_ID . '/oauth2/v2.0/token';

  $post_data = http_build_query([
    'client_id'     => GRAPH_CLIENT_ID,
    'client_secret' => GRAPH_CLIENT_SECRET,
    'code'          => $_GET['code'],
    'redirect_uri'  => MS_CALLBACK,
    'grant_type'    => 'authorization_code',
    'scope'         => 'openid email profile Mail.Send offline_access',
  ]);

  $ctx = stream_context_create([
    'http' => [
      'method'  => 'POST',
      'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
      'content' => $post_data,
    ],
  ]);

  $response = @file_get_contents($token_url, false, $ctx);
  if ($response === false) {
    $error = 'Failed to contact Microsoft token endpoint.';
    break;
  }

  $data = json_decode($response, true);
  if (empty($data['access_token'])) {
    $error = 'Token exchange failed: ' . ($data['error_description'] ?? 'Unknown error');
    break;
  }

  // Fetch user email via Graph
  $me_ctx = stream_context_create([
    'http' => [
      'method' => 'GET',
      'header' => "Authorization: Bearer {$data['access_token']}\r\n",
    ],
  ]);
  $me_resp = @file_get_contents('https://graph.microsoft.com/v1.0/me?$select=mail,userPrincipalName', false, $me_ctx);
  $me = json_decode($me_resp ?? '{}', true);
  $ms_email = $me['mail'] ?? $me['userPrincipalName'] ?? '';

  $expiry = time() + (int)($data['expires_in'] ?? 3600);

  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();
  RepositorioUsuario::save_ms_tokens(
    $conexion,
    $_SESSION['user']->obtener_id(),
    $data['access_token'],
    $data['refresh_token'] ?? '',
    $expiry,
    $ms_email
  );
  Conexion::cerrar_conexion();

} while (false);

$status  = $error ? 'error' : 'connected';
$message = $error ?: 'Microsoft account connected successfully.';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Microsoft Sign-in</title>
  <style>
    body { font-family: sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background: #f4f6f9; }
    .box { text-align: center; background: #fff; border-radius: 10px; padding: 40px 48px; box-shadow: 0 4px 24px rgba(0,0,0,0.1); max-width: 360px; }
    .icon { font-size: 48px; margin-bottom: 16px; }
    p { color: #555; font-size: 14px; margin: 8px 0 0; }
  </style>
</head>
<body>
<div class="box">
  <?php if ($error): ?>
    <div class="icon">❌</div>
    <strong>Connection failed</strong>
    <p><?= htmlspecialchars($message) ?></p>
  <?php else: ?>
    <div class="icon">✅</div>
    <strong>Connected!</strong>
    <p>Your Microsoft account has been linked. This tab will close automatically.</p>
  <?php endif; ?>
</div>
<script>
  window.close();
</script>
</body>
</html>
