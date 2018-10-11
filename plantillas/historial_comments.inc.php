<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Comments</h1>
        </div>
        <div class="col-sm-6">

        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <?php
          RepositorioComment::escribir_comments($id_rfq);
          ?>
          <div class="card-footer footer_item">
            <a href="<?php echo EDITAR_COTIZACION . '/' . $id_rfq; ?>" class="btn btn-primary" id="go_back"><i class="fa fa-reply"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
