<?php

/**
 * PartnerIntegrationRepository
 *
 * Storage + token handling for the single partner integration (row id = 1, same shape as
 * NotificationMailboxRepository). Two distinct credentials live here:
 *
 *   • issued_token_hash — the token WE issue to the partner so they can call our API. Only the
 *     SHA-256 hash is stored; the plaintext is returned once by issueToken() and never again.
 *   • partner_token / signing_secret — credentials WE use when calling / signing for the partner.
 *     These must be readable to be used, so they are stored as given and never echoed back to the UI.
 *
 * All timestamps are UTC, written from PHP.
 */
class PartnerIntegrationRepository {

  const ROW_ID = 1;
  const TOKEN_PREFIX = 'elg_live_';

  /** The integration row, created on first read so callers never see null. */
  public static function get($conexion) {
    $stmt = $conexion->prepare('SELECT * FROM partner_integration WHERE id = :id');
    $stmt->execute([':id' => self::ROW_ID]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $conexion->prepare('INSERT IGNORE INTO partner_integration (id, enabled) VALUES (:id, 0)')->execute([':id' => self::ROW_ID]);
      $stmt->execute([':id' => self::ROW_ID]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return $row;
  }

  public static function isEnabled($conexion) {
    return (int)self::get($conexion)['enabled'] === 1;
  }

  /**
   * Update settings. Only keys present in $settings are touched, and a blank secret (partner_token /
   * signing_secret) means "keep the stored one" — the UI never receives the stored secrets back.
   * Keys: enabled, partner_base_url, partner_webhook_url, partner_token, signing_secret.
   */
  public static function saveSettings($conexion, array $settings) {
    self::get($conexion);
    $sets = []; $params = [':id' => self::ROW_ID];
    if (array_key_exists('enabled', $settings)) {
      $sets[] = 'enabled = :enabled';
      $params[':enabled'] = $settings['enabled'] ? 1 : 0;
    }
    foreach (['partner_base_url', 'partner_webhook_url'] as $k) {
      if (array_key_exists($k, $settings)) {
        $sets[] = "$k = :$k";
        $v = trim((string)$settings[$k]);
        $params[":$k"] = $v === '' ? null : rtrim($v, '/');
      }
    }
    foreach (['partner_token', 'signing_secret'] as $k) {
      if (isset($settings[$k]) && trim($settings[$k]) !== '') {
        $sets[] = "$k = :$k";
        $params[":$k"] = trim($settings[$k]);
      }
    }
    if (!$sets) return;
    $conexion->prepare('UPDATE partner_integration SET ' . implode(', ', $sets) . ' WHERE id = :id')->execute($params);
    // The first pull starts at enable time — turning the integration on must not import the
    // partner's entire history as new quotes.
    $conexion->prepare('UPDATE partner_integration SET last_pull_at = :t WHERE id = :id AND enabled = 1 AND last_pull_at IS NULL')
      ->execute([':t' => gmdate('Y-m-d H:i:s'), ':id' => self::ROW_ID]);
  }

  /** Issue (or rotate — the previous token stops working immediately). Returns the plaintext once. */
  public static function issueToken($conexion, $userId) {
    self::get($conexion);
    $token = self::TOKEN_PREFIX . bin2hex(random_bytes(16));
    $conexion->prepare('UPDATE partner_integration SET issued_token_hash = :h, issued_token_last4 = :l,
        token_created_at = :t, token_created_by = :u, token_revoked_at = NULL, token_last_used_at = NULL WHERE id = :id')
      ->execute([':h' => hash('sha256', $token), ':l' => substr($token, -4), ':t' => gmdate('Y-m-d H:i:s'),
                 ':u' => $userId, ':id' => self::ROW_ID]);
    return $token;
  }

  public static function revokeToken($conexion) {
    self::get($conexion);
    $conexion->prepare('UPDATE partner_integration SET issued_token_hash = NULL, issued_token_last4 = NULL,
        token_revoked_at = :t WHERE id = :id')->execute([':t' => gmdate('Y-m-d H:i:s'), ':id' => self::ROW_ID]);
  }

  /**
   * True only when the integration is enabled and the Authorization header carries the currently
   * issued token. Constant-time compare on the hashes; touches token_last_used_at on success.
   */
  public static function verifyBearer($conexion, $authorizationHeader) {
    $row = self::get($conexion);
    if ((int)$row['enabled'] !== 1 || empty($row['issued_token_hash'])) return false;
    if (!is_string($authorizationHeader) || !preg_match('/^Bearer\s+(\S+)$/i', trim($authorizationHeader), $m)) return false;
    if (!hash_equals($row['issued_token_hash'], hash('sha256', $m[1]))) return false;
    $conexion->prepare('UPDATE partner_integration SET token_last_used_at = :t WHERE id = :id')
      ->execute([':t' => gmdate('Y-m-d H:i:s'), ':id' => self::ROW_ID]);
    return true;
  }

  public static function setLastPull($conexion, $utc) {
    self::get($conexion);
    $conexion->prepare('UPDATE partner_integration SET last_pull_at = :t WHERE id = :id')->execute([':t' => $utc, ':id' => self::ROW_ID]);
  }

  public static function markWebhookSuccess($conexion, $utc = null) {
    self::get($conexion);
    $conexion->prepare('UPDATE partner_integration SET last_webhook_success_at = :t WHERE id = :id')
      ->execute([':t' => $utc ?: gmdate('Y-m-d H:i:s'), ':id' => self::ROW_ID]);
  }
}
