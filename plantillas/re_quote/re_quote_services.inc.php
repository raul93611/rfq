<div class="row">
  <div class="col-12">
    <?php
    try {
      // Open connection
      Conexion::abrir_conexion();

      // Display the services related to the ReQuote
      ReQuoteServiceRepository::display_services(Conexion::obtener_conexion(), $re_quote);
    } catch (Exception $e) {
      // Handle connection or repository errors
      die('Error fetching services: ' . $e->getMessage());
    } finally {
      // Ensure connection is closed
      Conexion::cerrar_conexion();
    }
    ?>
  </div>
</div>