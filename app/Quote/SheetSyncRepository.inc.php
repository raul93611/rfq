<?php
class SheetSyncRepository {
  public static function updateSyncStatus($connection, $id_rfq, $status, $sheet_row = null) {
    try {
      if ($sheet_row !== null) {
        $sql = 'UPDATE rfq SET sheet_sync_status = :status, sheet_sync_at = NOW(), sheet_row = :sheet_row WHERE id = :id';
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(':sheet_row', $sheet_row, PDO::PARAM_INT);
      } else {
        $sql = 'UPDATE rfq SET sheet_sync_status = :status, sheet_sync_at = NOW() WHERE id = :id';
        $stmt = $connection->prepare($sql);
      }
      $stmt->bindValue(':status', $status, PDO::PARAM_STR);
      $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('SheetSyncRepository::updateSyncStatus error: ' . $ex->getMessage());
    }
  }

  public static function clearSheetRow($connection, $id_rfq) {
    try {
      $sql = 'UPDATE rfq SET sheet_row = NULL WHERE id = :id';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('SheetSyncRepository::clearSheetRow error: ' . $ex->getMessage());
    }
  }

  // After a sheet row is deleted with shift='Up', every row below it moves up by one.
  // Keep the stored pointers in sync by decrementing sheet_row for all quotes below the
  // deleted row, so later syncRow() calls don't write to a neighbour's (shifted) row.
  public static function shiftRowsAfterDelete($connection, $deletedRow) {
    try {
      $sql = 'UPDATE rfq SET sheet_row = sheet_row - 1 WHERE sheet_row > :deleted_row';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(':deleted_row', $deletedRow, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('SheetSyncRepository::shiftRowsAfterDelete error: ' . $ex->getMessage());
    }
  }

  public static function getSyncInfo($connection, $id_rfq) {
    try {
      $sql = 'SELECT sheet_sync_status, sheet_sync_at, sheet_row FROM rfq WHERE id = :id';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
      error_log('SheetSyncRepository::getSyncInfo error: ' . $ex->getMessage());
      return null;
    }
  }

  public static function updateNameAndResetSync($connection, $id_rfq, $name) {
    try {
      $sql = 'UPDATE rfq SET name = :name WHERE id = :id';
      $stmt = $connection->prepare($sql);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':id', $id_rfq, PDO::PARAM_INT);
      $stmt->execute();
    } catch (PDOException $ex) {
      error_log('SheetSyncRepository::updateNameAndResetSync error: ' . $ex->getMessage());
    }
  }
}
