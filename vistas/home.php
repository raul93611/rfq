<?php
if (ControlSesion::sesion_iniciada()) Redireccion::redirigir(CHARTS);
include_once 'plantillas/user/validacion_login.inc.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in</title>
  <link rel="shortcut icon" href="<?= RUTA_IMG; ?>eP_favicon.png" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Manrope', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: url('<?= RUTA_IMG; ?>gif.gif') center / cover no-repeat fixed;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background: linear-gradient(135deg, rgba(20, 30, 48, 0.82) 0%, rgba(19, 90, 130, 0.65) 100%);
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
    }

    .card {
      position: relative;
      z-index: 1;
      width: 420px;
      max-width: 95vw;
      background: rgba(255, 255, 255, 0.07);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 24px;
      padding: 48px 44px 44px;
      box-shadow: 0 32px 64px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255,255,255,0.15);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      animation: fadeUp 0.5s ease both;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .card-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 28px;
    }

    .card-logo img {
      height: 42px;
      width: auto;
      filter: brightness(0) invert(1);
      opacity: 0.92;
    }

    .card-heading {
      color: #ffffff;
      font-size: 22px;
      font-weight: 800;
      text-align: center;
      letter-spacing: -0.3px;
      margin-bottom: 6px;
    }

    .card-sub {
      color: rgba(255, 255, 255, 0.45);
      font-size: 13px;
      font-weight: 500;
      text-align: center;
      margin-bottom: 36px;
      letter-spacing: 0.1px;
    }

    .field {
      position: relative;
      margin-bottom: 16px;
    }

    .field i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.35);
      font-size: 13px;
      pointer-events: none;
      transition: color 0.2s;
    }

    .field input {
      width: 100%;
      padding: 13px 16px 13px 42px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      color: #ffffff;
      font-family: 'Manrope', sans-serif;
      font-size: 14px;
      font-weight: 500;
      outline: none;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }

    .field input::placeholder {
      color: rgba(255, 255, 255, 0.32);
      font-weight: 400;
    }

    .field input:focus {
      border-color: #13A8F0;
      background: rgba(19, 168, 240, 0.08);
      box-shadow: 0 0 0 3px rgba(19, 168, 240, 0.18);
    }

    .field input:focus + i,
    .field input:focus ~ i {
      color: #13A8F0;
    }

    .field input.error {
      border-color: rgba(255, 100, 100, 0.7);
      background: rgba(255, 80, 80, 0.06);
    }

    /* Override the ValidadorLogin label output */
    .error-wrap label,
    .error-wrap br { display: none; }
    .error-wrap::before {
      content: attr(data-msg);
      display: block;
      color: #ff7070;
      font-size: 12px;
      font-weight: 500;
      margin-top: 8px;
      padding-left: 4px;
    }

    .error-msg {
      color: #ff7070;
      font-size: 12px;
      font-weight: 500;
      margin-top: 8px;
      padding-left: 4px;
    }

    .error-msg label { display: inline; color: inherit; }
    .error-msg br { display: none; }

    .btn-login {
      width: 100%;
      padding: 14px;
      margin-top: 8px;
      background: linear-gradient(135deg, #13A8F0 0%, #0d7ab5 100%);
      border: none;
      border-radius: 12px;
      color: #ffffff;
      font-family: 'Manrope', sans-serif;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.2px;
      cursor: pointer;
      box-shadow: 0 6px 24px rgba(19, 168, 240, 0.38);
      transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 32px rgba(19, 168, 240, 0.5);
      filter: brightness(1.05);
    }

    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 4px 14px rgba(19, 168, 240, 0.35);
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 28px 0 0;
    }

    .divider span {
      flex: 1;
      height: 1px;
      background: rgba(255,255,255,0.1);
    }

    .divider p {
      color: rgba(255,255,255,0.25);
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.8px;
      text-transform: uppercase;
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="card-logo">
      <img src="<?= RUTA_IMG; ?>eP_logo_home.png" alt="Logo">
    </div>

    <h1 class="card-heading">Welcome back</h1>
    <p class="card-sub">Sign in to your account to continue</p>

    <?php $failed = isset($_POST['iniciar_sesion']) && $validador->obtener_error() !== ''; ?>

    <form action="<?= SERVIDOR; ?>" method="post" novalidate>
      <div class="field">
        <input
          type="text"
          name="nombre_usuario"
          placeholder="Username"
          autocomplete="username"
          autofocus
          required
          <?= (isset($_POST['nombre_usuario']) && !empty($_POST['nombre_usuario'])) ? 'value="' . htmlspecialchars($_POST['nombre_usuario'], ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
        <i class="fa fa-user"></i>
      </div>

      <div class="field">
        <input
          type="password"
          name="password"
          placeholder="Password"
          autocomplete="current-password"
          required
          <?= $failed ? 'class="error"' : ''; ?>>
        <i class="fa fa-lock"></i>
        <?php if ($failed): ?>
          <div class="error-msg"><?php $validador->mostrar_error(); ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" name="iniciar_sesion" class="btn-login">Sign in</button>
    </form>

    <div class="divider">
      <span></span>
      <p>E-logic Platform</p>
      <span></span>
    </div>
  </div>
</body>

</html>