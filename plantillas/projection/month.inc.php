<?php
// Fetch projections
Conexion::abrir_conexion();
$monthly_projection = MonthlyProjectionRepository::getById(Conexion::obtener_conexion(), $id_month);
$yearly_projection = YearlyProjectionRepository::getById(Conexion::obtener_conexion(), $monthly_projection->getYearlyProjectionId());
Conexion::cerrar_conexion();
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">
            <?= htmlspecialchars(date('F', mktime(0, 0, 0, $monthly_projection->getMonth(), 1))) . ' - ' . htmlspecialchars($yearly_projection->getYear()); ?>
          </h1>
        </div>
        <div class="col-sm-6 text-right">
          <a href="<?= htmlspecialchars(MONTH_EXCEL . $id_month) ?>" target="_blank" class="btn btn-primary">
            <i class="fas fa-file-excel"></i> Export
          </a>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Main Table Card -->
        <section class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">List</h3>
            </div>
            <div class="card-body">
              <table id="month-table" data-id="<?= htmlspecialchars($id_month) ?>" class="table table-bordered">
                <thead>
                  <tr>
                    <th>RFQ ID</th>
                    <th>INVOICE DATE</th>
                    <th>INVOICE</th>
                    <th>TYPE OF CONTRACT</th>
                    <th>INVOICE AMOUNT</th>
                    <th>TOTAL COST</th>
                    <th>PROFIT($)</th>
                    <th>PROFIT(%)</th>
                    <th>SALES COMMISSION($)</th>
                    <th>TOTAL PROFIT($)</th>
                    <th>TOTAL PROFIT(%)</th>
                    <th>INVOICE ACCEPTANCE</th>
                    <th>PARTIAL INVOICE</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be dynamically loaded -->
                </tbody>
              </table>
            </div>
            <div class="card-footer text-right">
              <a class="btn btn-secondary" id="go_back" href="<?= htmlspecialchars(PROJECTION . $monthly_projection->getYearlyProjectionId()) ?>">
                <i class="fa fa-reply"></i>
              </a>
            </div>
          </div>
          <!-- Totals Summary Card -->
          <div class="card">
            <div class="card-body">
              <div id="totals-container" data-id="<?= htmlspecialchars($id_month) ?>"></div>
            </div>
          </div>
        </section>

        <!-- Chart Cards -->
        <section class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Type of Contract: Distribution of Projects</h3>
            </div>
            <div class="card-body">
              <canvas id="contract-counts" height="400"></canvas>
            </div>
          </div>
        </section>
        <section class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Type of Contract: Total Price Value</h3>
            </div>
            <div class="card-body">
              <canvas id="contract-amounts" height="400"></canvas>
            </div>
          </div>
        </section>
        <section class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Type of Contract: Total Profit Value</h3>
            </div>
            <div class="card-body">
              <canvas id="contract-total-profit-amount" height="400"></canvas>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php include_once 'modals/edit_invoice_acceptance.inc.php'; ?>
<script src="<?= htmlspecialchars(RUTA_JS . 'month.js') ?>"></script>