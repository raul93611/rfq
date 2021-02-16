<?php
Conexion::abrir_conexion();
ReQuoteRepository::create_re_quote(Conexion::obtener_conexion(), $id_rfq);
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
                <a href="<?php echo ADD_RE_QUOTE_ITEM . $re_quote-> get_id(); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add item</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<!--*************************************************MODAL TO SHOW AUDIT TRAILS*************************************************************-->
<div class="modal fade" id="audit_trails_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Audit Trails</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        Conexion::abrir_conexion();
        ReQuoteAuditTrailRepository::display_audit_trails(Conexion::obtener_conexion(), $re_quote-> get_id());
        Conexion::cerrar_conexion();
        ?>
      </div>
    </div>
  </div>
</div>
