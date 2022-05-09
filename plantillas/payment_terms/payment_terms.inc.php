<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Payment Terms</h1>
        </div>
        <div class="col-sm-6">
          <button id="add_payment_term" class="float-right btn btn-primary" type="button" name="button"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header align-middle">
              <h3 class="card-title">Payment Terms List</h3>
            </div>
            <div class="card-body">
              <table id="payment_terms_table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>PAYMENT TERMS</th>
                    <th style="width:100px;">OPTIONS</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/add_payment_term_modal.inc.php';
include_once 'modals/edit_payment_term_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>payment_terms.js"></script>
