<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Monthly Projection</h1>
      <p class="page-subtitle">Monthly breakdown and goals</p>
    </div>
    <a class="btn btn-secondary btn-sm" href="<?= htmlspecialchars(DAILY) ?>">
      <i class="fa fa-arrow-left mr-1"></i>Back to Projections
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <div class="card chart-card">
        <div class="card-body">
          <table id="monthly-table" data-id="<?= htmlspecialchars($id_projection) ?>" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Month ID</th>
                <th>Month</th>
                <th>Monthly Goal</th>
                <th>Monthly Goal Result</th>
                <th>Total Monthly Invoice</th>
                <th>Total Cost</th>
                <th>Total Real Profit</th>
                <th class="text-center" style="width:80px;">Options</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data dynamically loaded -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Totals Summary -->
      <div class="card chart-card">
        <div class="card-body">
          <div id="totals-container" data-id="<?= htmlspecialchars($id_projection) ?>"></div>
        </div>
      </div>

    </div>
  </section>

</div>

<?php include_once 'modals/edit_monthly_projection.inc.php'; ?>
<script src="<?= htmlspecialchars(RUTA_JS); ?>monthly.js"></script>
