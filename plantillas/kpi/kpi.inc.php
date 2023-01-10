<input type="hidden" name="id_rfq" value="<?php echo $id_rfq; ?>">
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
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> General</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="general-chart" height="350"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> RFQ</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="rfq-chart" height="350"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-dollar-sign"></i> RFP</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="rfp-chart" height="350"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="card-footer footer_item">
          <a class="btn btn-primary" id="go_back" href="<?php echo EDITAR_COTIZACION . '/' . $id_rfq; ?>"><i class="fa fa-reply"></i></a>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo RUTA_JS; ?>kpi.js"></script>