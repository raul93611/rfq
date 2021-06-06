<?php
Conexion::abrir_conexion();
$quote = RepositorioRfq::obtener_cotizacion_por_id(Conexion::obtener_conexion(), $id_rfq);
$providers_list = ProviderListRepository::get_all(Conexion::obtener_conexion());
$items_exists = RepositorioItem::items_exists(Conexion::obtener_conexion(), $id_rfq);
$total_services = ServiceRepository::get_total(Conexion::obtener_conexion(), $id_rfq);
$payment_terms = PaymentTermRepository::get_all(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-2">
          <h1>Fulfillment table</h1>
        </div>
        <div class="col-sm-8 text-center">
          <?php
          Conexion::abrir_conexion();
          $cantidad_de_comentarios = RepositorioComment::contar_todos_comentarios_quote(Conexion::obtener_conexion(), $quote-> obtener_id());
          Conexion::cerrar_conexion();
          ?>
          <a href="#" id="mostrar_comentarios" class="btn btn-info"><i class="fas fa-comment"></i> Comments(<?php echo $cantidad_de_comentarios; ?>)</a>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div id="fulfillment_page" class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> RFQ</h3>
            </div>
            <div id="fulfillment_box" class="card-body">
              <?php
              if($items_exists){
                FulfillmentRepository::items_list($id_rfq);
              }else{
                ?>
                <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Items to display!</h3>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> RFP</h3>
            </div>
            <div id="fulfillment_services_box" class="card-body">
              <?php
              if($quote-> obtener_type_of_bid() == 'Services'){
                FulfillmentRepository::services_list($id_rfq);
              }else{
                ?>
                <h3 class="text-info text-center"><i class="fas fa-exclamation-circle"></i> No Services to display!</h3>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> Total</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h3 class="text-info text-center">Total: $ <?php echo number_format($quote-> obtener_total_price() + $total_services, 2); ?></h3>
                </div>
                <div class="col-md-6">
                  <h3 class="text-info text-center">Total profit: $ <?php echo number_format($quote-> obtener_services_fulfillment_profit() + $quote-> obtener_fulfillment_profit(), 2); ?></h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer footer_item">
          <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $quote-> obtener_id(); ?>"><i class="fa fa-reply"></i></a>
          <a href="#" id="add_comment" class="btn btn-primary add_item_charter"><i class="fas fa-plus"></i> Add comment</a>
        </div>
      </div>
    </div>
  </section>
</div>
<!--******************************************EDIT FULFILLMENT SUBITEM MODAL**********************************-->
<div class="modal fade" id="edit_fulfillment_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_fulfillment_subitem_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_fulfillment_subitem_form" method="post" action="">

        </form>
    </div>
  </div>
</div>
<!--******************************************EDIT FULFILLMENT ITEM MODAL**********************************-->
<div class="modal fade" id="edit_fulfillment_item_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_fulfillment_item_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_fulfillment_item_form" method="post" action="">

        </form>
    </div>
  </div>
</div>
<!--******************************************ADD FULFILLMENT ITEM MODAL**********************************-->
<div class="modal fade" id="new_fulfillment_item_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_fulfillment_item_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_FULFILLMENT_ITEM; ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Provider:</label>
                <select name="provider" class="custom-select">
                  <?php
                  foreach ($providers_list as $key => $provider) {
                    ?>
                    <option><?php echo $provider-> get_company_name(); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="">
              </div>
              <div class="form-group">
                <label for="unit_cost">Unit Cost:</label>
                <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="">
              </div>
              <div class="form-group">
                <label for="other_cost">Other Cost:</label>
                <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="">
              </div>
              <div class="form-group">
                <label for="payment_term">Payment Term:</label>
                <select class="custom-select" name="payment_term">
                  <?php
                    foreach ($payment_terms as $key => $payment_term) {
                      ?>
                      <option><?php echo $payment_term-> get_payment_term(); ?></option>
                      <?php
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" id="id_item" name="id_item" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_fulfillment_item" form="add_fulfillment_item_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--******************************************ADD FULFILLMENT SUBITEM MODAL**********************************-->
<div class="modal fade" id="new_fulfillment_subitem_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_fulfillment_subitem_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_FULFILLMENT_SUBITEM; ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Provider:</label>
                <select name="provider" class="custom-select">
                  <?php
                  foreach ($providers_list as $key => $provider) {
                    ?>
                    <option><?php echo $provider-> get_company_name(); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="">
              </div>
              <div class="form-group">
                <label for="unit_cost">Unit Cost:</label>
                <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="">
              </div>
              <div class="form-group">
                <label for="other_cost">Other Cost:</label>
                <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="">
              </div>
              <div class="form-group">
                <label for="payment_term">Payment Term:</label>
                <select class="custom-select" name="payment_term">
                  <?php
                    foreach ($payment_terms as $key => $payment_term) {
                      ?>
                      <option><?php echo $payment_term-> get_payment_term(); ?></option>
                      <?php
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" id="id_subitem" name="id_subitem" value="">
          <input type="hidden" id="id_rfq" name="id_rfq" value="<?php echo $quote-> obtener_id(); ?>">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_fulfillment_subitem" form="add_fulfillment_subitem_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--******************************************ADD FULFILLMENT SERVICE MODAL**********************************-->
<div class="modal fade" id="new_fulfillment_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_fulfillment_service_form" method="post" enctype="multipart/form-data" action="<?php echo SAVE_FULFILLMENT_SERVICE; ?>">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Provider:</label>
                <select name="provider" class="custom-select">
                  <?php
                  foreach ($providers_list as $key => $provider) {
                    ?>
                    <option><?php echo $provider-> get_company_name(); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" class="form-control form-control-sm" name="quantity" value="">
              </div>
              <div class="form-group">
                <label for="unit_cost">Unit Cost:</label>
                <input type="number" step=".01" id="unit_cost" class="form-control form-control-sm" name="unit_cost" value="">
              </div>
              <div class="form-group">
                <label for="other_cost">Other Cost:</label>
                <input type="number" step=".01" id="other_cost" class="form-control form-control-sm" name="other_cost" value="">
              </div>
              <div class="form-group">
                <label for="payment_term">Payment Term:</label>
                <select class="custom-select" name="payment_term">
                  <?php
                    foreach ($payment_terms as $key => $payment_term) {
                      ?>
                      <option><?php echo $payment_term-> get_payment_term(); ?></option>
                      <?php
                    }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type="hidden" id="id_service" name="id_service" value="">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save_fulfillment_service" form="add_fulfillment_service_form" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--******************************************EDIT FULFILLMENT SERVICE MODAL**********************************-->
<div class="modal fade" id="edit_fulfillment_service_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <input type="hidden" name="id_rfq" form="edit_fulfillment_service_form" value="<?php echo $quote-> obtener_id(); ?>">
        <form id="edit_fulfillment_service_form" method="post" action="">

        </form>
    </div>
  </div>
</div>
<!--*************************************************MODAL COMMENT*************************************************************-->
<div class="modal fade" id="nuevo_comment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_comment" method="post" enctype="multipart/form-data" action="<?php echo GUARDAR_COMMENT; ?>">
          <div class="form-group">
            <label for="comment_rfq">Comment:</label>
            <textarea class="form-control form-control-sm" name="comment_rfq" rows="10" id="comment_rfq" autofocus></textarea>
          </div>
          <input type="hidden" name="id_rfq" value="<?php echo $quote-> obtener_id(); ?>">
          <input type="hidden" name="place" value="fulfillment">
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="guardar_comment" form="form_nuevo_comment" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Cancel</button>
      </div>
    </div>
  </div>
</div>
<!--*************************************************MODAL TO SHOW COMMENTS*************************************************************-->
<div class="modal fade" id="todos_commentarios_quote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        RepositorioComment::escribir_comments($quote-> obtener_id());
        ?>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo RUTA_JS; ?>fulfillment.js"></script>
