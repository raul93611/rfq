<?php
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
          <h1 class="m-0 text-dark"><?= date('F', mktime(0, 0, 0, $monthly_projection->getMonth(), 1)) . ' - ' . $yearly_projection->getYear(); ?></h1>
        </div>
        <div class="col-sm-6">
          <a href="<?= MONTH_EXCEL . $id_month ?>" target="_blank" class="float-right btn btn-success"><i class="fas fa-file-excel"></i></a>
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
              <table id="month-table" data-id="<?= $id_month ?>" class="table table-bordered">
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
                </tbody>
              </table>
            </div>
            <div class="card-footer footer_item">
              <a class="btn btn-primary" id="go_back" href="<?= PROJECTION . $monthly_projection->getYearlyProjectionId() ?>"><i class="fa fa-reply"></i></a>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="mt-3" id="totals-container" data-id="<?= $id_month ?>"></div>
            </div>
          </div>
        </section>
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
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/edit_invoice_acceptance.inc.php';
?>
<script src="<?= RUTA_JS; ?>month.js"></script>