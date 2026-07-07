<?php

/**
 * NotificationMailboxRepository
 *
 * Storage + token handling for the single shared Microsoft mailbox that sends all
 * system notification emails (@mention comments and quote-watcher alerts). This
 * replaces the old per-user delegated connection as the email sender, so emails
 * send reliably regardless of who triggered them.
 *
 * The connection lives in the single-row `notification_mailbox` table (id = 1) and
 * reuses the existing delegated OAuth app / Mail.Send scope — no app-only Graph auth.
 * Token refresh mirrors the per-user behaviour: refresh-on-expiry, silent failure.
 */
class NotificationMailboxRepository {

  const ROW_ID = 1;

  /** Full connection row (assoc) or null when never connected. */
  public static function get($conexion) {
    if (!isset($conexion)) return null;
    try {
      $stmt = $conexion->prepare('SELECT * FROM notification_mailbox WHERE id = :id');
      $stmt->bindValue(':id', self::ROW_ID, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    } catch (PDOException $ex) {
      error_log('NotificationMailboxRepository::get error: ' . $ex->getMessage());
      return null;
    }
  }

  /** True when a refresh token is stored (i.e. the shared mailbox is connected). */
  public static function isConnected($conexion) {
    $row = self::get($conexion);
    return !empty($row['ms_refresh_token']);
  }

  /** Upsert the connection after a successful OAuth round-trip. */
  public static function save($conexion, $access_token, $refresh_token, $expiry, $ms_email, $connected_by) {
    if (!isset($conexion)) return;
    try {
      $sql = 'INSERT INTO notification_mailbox (id, ms_access_token, ms_refresh_token, ms_token_expiry, ms_email, connected_by)
              VALUES (:id, :at, :rt, :exp, :em, :by)
              ON DUPLICATE KEY UPDATE ms_access_token = :at, ms_refresh_token = :rt,
                                      ms_token_expiry = :exp, ms_email = :em, connected_by = :by';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id', self::ROW_ID, PDO::PARAM_INT);
      $stmt->bindValue(':at', $access_token, PDO::PARAM_STR);
      $stmt->bindValue(':rt', $refresh_token, PDO::PARAM_STR);
      $stmt->bindValue(':exp', $expiry, PDO::PARAM_INT);
      $stmt->bindValue(':em', $ms_email, PDO::PARAM_STR);
      $stmt->bindValue(':by', $connected_by, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationMailboxRepository::save error: ' . $ex->getMessage());
    }
  }

  /** Remove the connection (Disconnect). */
  public static function clear($conexion) {
    if (!isset($conexion)) return;
    try {
      $stmt = $conexion->prepare('DELETE FROM notification_mailbox WHERE id = :id');
      $stmt->bindValue(':id', self::ROW_ID, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationMailboxRepository::clear error: ' . $ex->getMessage());
    }
  }

  /**
   * A valid Graph access token for the shared mailbox, or null when not connected /
   * refresh fails. Refreshes on expiry via the stored refresh token and persists the
   * new access token so the next call is cheap. Silent failure matches the old per-user path.
   */
  public static function getAccessToken($conexion) {
    $row = self::get($conexion);
    if (empty($row['ms_refresh_token'])) return null;

    $access = $row['ms_access_token'] ?? '';
    $expiry = (int)($row['ms_token_expiry'] ?? 0);

    if (time() < $expiry - 60 && !empty($access)) {
      return $access;
    }

    $resp = @file_get_contents(
      'https://login.microsoftonline.com/' . GRAPH_TENANT_ID . '/oauth2/v2.0/token',
      false,
      stream_context_create(['http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query([
          'client_id'     => GRAPH_CLIENT_ID,
          'client_secret' => GRAPH_CLIENT_SECRET,
          'refresh_token' => $row['ms_refresh_token'],
          'grant_type'    => 'refresh_token',
          'scope'         => 'openid email profile Mail.Send offline_access',
        ]),
        'ignore_errors' => true,
      ]])
    );
    if (!$resp) return null;

    $data = json_decode($resp, true);
    if (empty($data['access_token'])) return null;

    $new_access  = $data['access_token'];
    $new_expiry  = time() + (int)($data['expires_in'] ?? 3600);
    // Microsoft may issue a rotated refresh token; keep the old one when absent.
    $new_refresh = !empty($data['refresh_token']) ? $data['refresh_token'] : $row['ms_refresh_token'];
    try {
      $stmt = $conexion->prepare('UPDATE notification_mailbox SET ms_access_token = :at, ms_token_expiry = :exp, ms_refresh_token = :rt WHERE id = :id');
      $stmt->bindValue(':at', $new_access, PDO::PARAM_STR);
      $stmt->bindValue(':exp', $new_expiry, PDO::PARAM_INT);
      $stmt->bindValue(':rt', $new_refresh, PDO::PARAM_STR);
      $stmt->bindValue(':id', self::ROW_ID, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationMailboxRepository::getAccessToken persist error: ' . $ex->getMessage());
    }
    return $new_access;
  }
}
