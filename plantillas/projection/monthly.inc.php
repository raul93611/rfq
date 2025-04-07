<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Projection</h1>
        </div>
        <div class="col-sm-6 text-right">
          <!-- Reserved for potential future controls or actions -->
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <!-- Monthly Projection Table Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">List</h3>
            </div>
            <div class="card-body">
              <table id="monthly-table" data-id="<?= htmlspecialchars($id_projection) ?>" class="table table-bordered">
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
                  <!-- Data dynamically loaded here -->
                </tbody>
              </table>
            </div>
            <div class="card-footer text-right">
              <a class="btn btn-secondary" id="go_back" href="<?= htmlspecialchars(DAILY) ?>">
                <i class="fa fa-reply"></i> Back
              </a>
            </div>
          </div>

          <!-- Totals Summary Card -->
          <div class="card">
            <div class="card-body">
              <div id="totals-container" data-id="<?= htmlspecialchars($id_projection) ?>"></div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include_once 'modals/edit_monthly_projection.inc.php'; ?>
<script src="<?= htmlspecialchars(RUTA_JS); ?>monthly.js"></script>