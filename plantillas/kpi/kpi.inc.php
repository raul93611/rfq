<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1>KPI</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- General Chart Card -->
          <div class="card card-primary mb-4">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> General</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <canvas id="general-chart" height="350"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- RFQ Chart Card -->
          <div class="card card-primary mb-4">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> RFQ</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <canvas id="rfq-chart" height="350"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- RFP Chart Card -->
          <div class="card card-primary mb-4">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> RFP</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <canvas id="rfp-chart" height="350"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="card-footer footer_item text-right">
            <a class="btn btn-secondary" id="go_back" href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
              <i class="fa fa-reply"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="<?= RUTA_JS; ?>kpi.js"></script>