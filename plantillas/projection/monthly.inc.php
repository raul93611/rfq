<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Projection</h1>
        </div>
        <div class="col-sm-6">
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
              <h3 class="card-title">List</h3>
            </div>
            <div class="card-body">
              <table id="monthly-table" data-id="<?= $id_projection ?>" class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>MONTH ID</th>
                    <th>MONTH</th>
                    <th>MONTHLY GOAL</th>
                    <th>MONTHLY GOAL RESULT</th>
                    <th>TOTAL MONTHLY INVOICE</th>
                    <th>TOTAL COST</th>
                    <th>TOTAL REAL PROFIT</th>
                    <th>OPTIONS</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <div class="mt-3" id="totals-container" data-id="<?= $id_projection ?>"></div>
            </div>
            <div class="card-footer footer_item">
              <a class="btn btn-primary" id="go_back" href="<?= DAILY ?>"><i class="fa fa-reply"></i></a>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/edit_monthly_projection.inc.php';
?>
<script src="<?= RUTA_JS; ?>monthly.js"></script>