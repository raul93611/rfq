<div class="row">
  <div class="col-md-12">
    <?php
    Conexion::abrir_conexion();
    ReQuoteServiceRepository::display_services(Conexion::obtener_conexion(), $re_quote);
    Conexion::cerrar_conexion();
    ?>
  </div>
</div>
