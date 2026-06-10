<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Payment Terms</h1>
      <p class="page-subtitle">Manage available payment term options</p>
    </div>
    <button id="add_payment_term" class="btn btn-primary btn-sm" type="button">
      <i class="fas fa-plus mr-1"></i>Add Payment Term
    </button>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="payment_terms_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Payment Terms</th>
                <th class="text-center" style="width:100px;">Options</th>
              </tr>
            </thead>
            <tbody>
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>

<?php
include_once 'modals/add_payment_term_modal.inc.php';
include_once 'modals/edit_payment_term_modal.inc.php';
?>
<script src="<?= asset_url('js/payment_terms.js'); ?>"></script>
