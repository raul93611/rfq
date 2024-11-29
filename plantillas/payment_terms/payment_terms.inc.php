<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Payment Terms</h1>
        </div>
        <div class="col-sm-6 text-right">
          <button id="add_payment_term" class="btn btn-primary" type="button">
            <i class="fas fa-plus"></i> Add Payment Term
          </button>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mb-0">Payment Terms List</h3>
            </div>
            <div class="card-body">
              <table id="payment_terms_table" class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                  <tr>
                    <th>Payment Terms</th>
                    <th class="text-center" style="width:100px;">Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dynamic content will be loaded here -->
                </tbody>
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
<script src="<?= RUTA_JS; ?>payment_terms.js"></script>