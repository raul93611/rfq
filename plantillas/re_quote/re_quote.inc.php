<?php
Conexion::abrir_conexion();
$id_requote = ReQuoteRepository::create_re_quote(Conexion::obtener_conexion(), $id_rfq);
$re_quote = ReQuoteRepository::get_re_quote_by_id_rfq(Conexion::obtener_conexion(), $id_rfq);
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Re quote</h1>
        </div>
        <div class="text-center col-sm-10">
          <button type="button" name="button" class="btn btn-info" id="audit_trails_button">Audit Trails</button>
          <a href="<?php echo RELOAD_REQUOTE . $id_rfq; ?>" class="btn btn-primary">Reload</a>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <form class="" id="re_quote_form" action="<?php echo SAVE_RE_QUOTE; ?>" method="post">
              <input type="hidden" name="id_re_quote" value="<?php echo $re_quote-> get_id(); ?>">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
              </div>
              <div class="card-body">
                <?php
                  ReQuoteItemRepository::print_re_quote_items($re_quote-> get_id());
                ?>
              </div>
              <div class="card-footer footer_item">
                <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $re_quote-> get_id_rfq(); ?>"><i class="fa fa-reply"></i></a>
                <button type="submit" class="btn btn-success" name="save_re_quote"><i class="fas fa-check"></i> Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?php include_once 'plantillas/re_quote/modals/audit_trails_modal.inc.php'; ?>
<script src="<?php echo RUTA_JS; ?>reQuote.js"></script>
