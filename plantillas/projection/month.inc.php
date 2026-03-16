<?php
// Fetch projections
Conexion::abrir_conexion();
$monthly_projection = MonthlyProjectionRepository::getById(Conexion::obtener_conexion(), $id_month);
$yearly_projection = YearlyProjectionRepository::getById(Conexion::obtener_conexion(), $monthly_projection->getYearlyProjectionId());
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">
        <?= htmlspecialchars(date('F', mktime(0, 0, 0, $monthly_projection->getMonth(), 1))) . ' ' . htmlspecialchars($yearly_projection->getYear()); ?>
      </h1>
      <p class="page-subtitle">Monthly invoice and profit breakdown</p>
    </div>
    <div class="d-flex" style="gap:8px;">
      <a class="btn btn-secondary btn-sm" href="<?= htmlspecialchars(PROJECTION . $monthly_projection->getYearlyProjectionId()) ?>">
        <i class="fa fa-arrow-left mr-1"></i>Back
      </a>
      <a href="<?= htmlspecialchars(MONTH_EXCEL . $id_month) ?>" target="_blank" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel mr-1"></i>Export
      </a>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <!-- Main Table Card -->
      <div class="card chart-card">
        <div class="card-body">
          <table id="month-table" data-id="<?= htmlspecialchars($id_month) ?>" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>RFQ ID</th>
                <th>Invoice Date</th>
                <th>Invoice</th>
                <th>Type of Contract</th>
                <th>Invoice Amount</th>
                <th>Total Cost</th>
                <th>Profit ($)</th>
                <th>Profit (%)</th>
                <th>Sales Commission ($)</th>
                <th>Total Profit ($)</th>
                <th>Total Profit (%)</th>
                <th>Invoice Acceptance</th>
                <th>Partial Invoice</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be dynamically loaded -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Totals Summary Card -->
      <div class="card chart-card">
        <div class="card-body">
          <div id="totals-container" data-id="<?= htmlspecialchars($id_month) ?>"></div>
        </div>
      </div>

      <!-- Charts Row -->
      <div class="row">
        <div class="col-md-4">
          <div class="card chart-card">
            <div class="card-body">
              <h6 class="chart-card-label">Distribution of Projects</h6>
              <div style="position:relative;height:260px;">
                <canvas id="contract-counts"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card chart-card">
            <div class="card-body">
              <h6 class="chart-card-label">Total Price Value</h6>
              <div style="position:relative;height:260px;">
                <canvas id="contract-amounts"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card chart-card">
            <div class="card-body">
              <h6 class="chart-card-label">Total Profit Value</h6>
              <div style="position:relative;height:260px;">
                <canvas id="contract-total-profit-amount"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

</div>

<?php include_once 'modals/edit_invoice_acceptance.inc.php'; ?>
<script src="<?= htmlspecialchars(RUTA_JS . 'month.js') ?>"></script>
