<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-dark">Home</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div id="graficas" class="row">

        <!-- Left Column -->
        <section class="col-lg-6 connectedSortable">
          <!-- Completed Chart Card -->
          <div class="card">
            <div class="card-header no-border">
              <h3 class="card-title">Completed</h3>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="completados-chart" height="250"></canvas>
              </div>
            </div>
          </div>

          <!-- Annual Awards by Amount Card -->
          <div id="annual_awards_amounts" class="card">
            <div class="card-header no-border">
              <h3 class="card-title">Annual Awards (by Amount)</h3>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="monto_ganados_anual_chart" height="250"></canvas>
              </div>
              <i class="fas fa-square text-primary"></i> Total: <span class="current"></span><br>
              <i class="fas fa-square text-dark"></i> Total: <span class="past"></span>
            </div>
          </div>
        </section>

        <!-- Right Column -->
        <section class="col-lg-6 connectedSortable">
          <!-- Awards Chart Card -->
          <div class="card">
            <div class="card-header no-border">
              <h3 class="card-title">Awards</h3>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="ganadas-chart" height="250"></canvas>
              </div>
            </div>
          </div>

          <!-- Annual Awards Card -->
          <div id="annual_awards" class="card">
            <div class="card-header no-border">
              <h3 class="card-title">Annual Awards</h3>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="ganados_anuales_chart" height="250"></canvas>
              </div>
              <i class="fas fa-square text-primary"></i> Total: <span class="current"></span><br>
              <i class="fas fa-square text-dark"></i> Total: <span class="past"></span>
            </div>
          </div>
        </section>

      </div>
    </div>
  </section>
</div>

<script src="<?= RUTA_JS ?>main_charts.js"></script>