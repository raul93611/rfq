<?php
class Conexion {
  private static $conexion;

  public static function abrir_conexion() {
    if (!isset(self::$conexion)) {
      try {
        self::$conexion = new PDO(
          'mysql:host=' . NOMBRE_SERVIDOR_DB . '; dbname=' . NOMBRE_BD,
          NOMBRE_USUARIO,
          PASSWORD,
          [PDO::ATTR_PERSISTENT => true]
        );
        self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$conexion->exec('SET CHARACTER SET utf8');
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
        die();
      }
    }
  }

  public static function cerrar_conexion() {
    // No-op: persistent connections are managed by PHP and reused across requests.
    // Explicitly closing them would defeat the purpose of connection pooling.
  }

  public static function obtener_conexion() {
    self::abrir_conexion();
    return self::$conexion;
  }
}
?>
