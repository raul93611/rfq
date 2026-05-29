<?php
class NotificationRepository {

  public static function insert($conexion, $id_user, $id_rfq, $message, $url) {
    if (!isset($conexion)) return;
    try {
      $sql = 'INSERT INTO notifications (id_user, id_rfq, message, url) VALUES (:id_user, :id_rfq, :message, :url)';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);
      $stmt->bindValue(':message', $message, PDO::PARAM_STR);
      $stmt->bindValue(':url', $url, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationRepository::insert error: ' . $ex->getMessage());
    }
  }

  public static function getUnreadCount($conexion, $id_user) {
    if (!isset($conexion)) return 0;
    try {
      $sql = 'SELECT COUNT(*) FROM notifications WHERE id_user = :id_user AND is_read = 0';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->execute();
      return (int) $stmt->fetchColumn();
    } catch (PDOException $ex) {
      return 0;
    }
  }

  public static function getRecent($conexion, $id_user, $limit = 5) {
    $rows = [];
    if (!isset($conexion)) return $rows;
    try {
      $sql = 'SELECT * FROM notifications WHERE id_user = :id_user ORDER BY created_at DESC LIMIT :lim';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      error_log('NotificationRepository::getRecent error: ' . $ex->getMessage());
    }
    return $rows;
  }

  public static function getAll($conexion, $id_user, $offset = 0, $per_page = 15) {
    $rows = [];
    if (!isset($conexion)) return $rows;
    try {
      $sql = 'SELECT * FROM notifications WHERE id_user = :id_user ORDER BY created_at DESC LIMIT :lim OFFSET :off';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
      $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      error_log('NotificationRepository::getAll error: ' . $ex->getMessage());
    }
    return $rows;
  }

  public static function getTotalCount($conexion, $id_user) {
    if (!isset($conexion)) return 0;
    try {
      $sql = 'SELECT COUNT(*) FROM notifications WHERE id_user = :id_user';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->execute();
      return (int) $stmt->fetchColumn();
    } catch (PDOException $ex) {
      return 0;
    }
  }

  public static function markRead($conexion, $id, $id_user) {
    if (!isset($conexion)) return;
    try {
      $sql = 'UPDATE notifications SET is_read = 1 WHERE id = :id AND id_user = :id_user';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationRepository::markRead error: ' . $ex->getMessage());
    }
  }

  public static function markAllRead($conexion, $id_user) {
    if (!isset($conexion)) return;
    try {
      $sql = 'UPDATE notifications SET is_read = 1 WHERE id_user = :id_user';
      $stmt = $conexion->prepare($sql);
      $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('NotificationRepository::markAllRead error: ' . $ex->getMessage());
    }
  }

  /** Parse @username tokens from comment text, return array of distinct usernames */
  public static function parseMentions($text) {
    preg_match_all('/@([A-Za-z0-9_]+)/', $text, $matches);
    return array_unique($matches[1]);
  }
}
