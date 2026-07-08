<?php

/**
 * WatcherNotificationService
 *
 * Fans a quote change out to its watchers: one in-app notification each (respecting the
 * per-user in-app preference) plus one email each via the shared notification mailbox
 * (respecting the per-user email preference). The acting user is never notified of their
 * own change. Watch/unwatch itself is never audited — only the resulting notifications fire.
 *
 * Triggers (owned by the caller): a status change, or an edit to Type of Bid /
 * Designated User / Comments (the status-encoding field). Item/pricing edits are out of scope.
 */
class WatcherNotificationService {

  /** Auto-subscribe a quote's designated user (on creation and on reassignment). Idempotent. */
  public static function autoSubscribeDesignated($conexion, $id_rfq, $designated_user_id) {
    $id = (int)$designated_user_id;
    if ($id > 0) {
      QuoteWatcherRepository::watch($conexion, (int)$id_rfq, $id);
    }
  }

  /**
   * Notify every watcher of a change. $message is the one-line headline shown in the bell
   * and used as the email subject/body (e.g. "Quote #123 status changed to Submitted").
   */
  public static function notify($conexion, $id_rfq, $actor_id, $message) {
    if (!isset($conexion)) return;
    $id_rfq   = (int)$id_rfq;
    $actor_id = (int)$actor_id;

    $watcher_ids = QuoteWatcherRepository::getWatcherIds($conexion, $id_rfq);
    if (empty($watcher_ids)) return;

    $url = EDITAR_COTIZACION . '/' . $id_rfq;

    foreach ($watcher_ids as $uid) {
      if ($uid === $actor_id) continue; // never notify the person who made the change

      $prefs = RepositorioUsuario::get_notif_prefs($conexion, $uid);

      if (!empty($prefs['notif_inapp'])) {
        NotificationRepository::insert($conexion, $uid, $id_rfq, $message, $url);
      }

      if (!empty($prefs['notif_email'])) {
        $user = RepositorioUsuario::obtener_usuario_por_id($conexion, $uid);
        if ($user && !empty($user->obtener_email())) {
          NotificationEmail::send($conexion, $user->obtener_email(), $message, $url);
        }
      }
    }
  }
}
