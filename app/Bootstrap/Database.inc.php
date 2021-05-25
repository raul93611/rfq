<?php
class Database {
  private static $database;

  public static function open_connection() {
    if (!isset(self::$database)) {
      try {
        self::$database = new PDO('mysql:host=' . DATABASE_SERVER . '; dbname=' . DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
        self::$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$database->exec('SET CHARACTER SET utf8');
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
        die();
      }
    }
  }

  public static function close_connection() {
    if (isset(self::$database)) {
      self::$database = null;
    }
  }

  public static function get_connection() {
    return self::$database;
  }
}
?>
