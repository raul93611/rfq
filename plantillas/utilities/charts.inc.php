<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Dashboard</h1>
      <p class="page-subtitle">Overview of your team's activity</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div id="graficas" class="row">

        <!-- Left Column -->
        <div class="col-lg-6">

          <!-- Completed Chart Card -->
          <div class="card chart-card">
            <div class="card-body">
              <p class="chart-card-label">Completed</p>
              <div style="position:relative;height:220px;"><canvas id="completados-chart"></canvas></div>
            </div>
          </div>

          <!-- Annual Awards by Amount Card -->
          <div id="annual_awards_amounts" class="card chart-card">
            <div class="card-body">
              <p class="chart-card-label">Annual Awards <span class="chart-card-label-sub">by Amount</span></p>
              <div style="position:relative;height:220px;"><canvas id="monto_ganados_anual_chart"></canvas></div>
              <div class="chart-legend mt-3">
                <div class="chart-legend-item">
                  <span class="chart-legend-dot" style="background:#13A8F0"></span>
                  <span class="chart-legend-text">Current year</span>
                  <span class="chart-legend-value current"></span>
                </div>
                <div class="chart-legend-item">
                  <span class="chart-legend-dot" style="background:#39485a"></span>
                  <span class="chart-legend-text">Past year</span>
                  <span class="chart-legend-value past"></span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-6">

          <!-- Awards Chart Card -->
          <div class="card chart-card">
            <div class="card-body">
              <p class="chart-card-label">Awards</p>
              <div style="position:relative;height:220px;"><canvas id="ganadas-chart"></canvas></div>
            </div>
          </div>

          <!-- Annual Awards Card -->
          <div id="annual_awards" class="card chart-card">
            <div class="card-body">
              <p class="chart-card-label">Annual Awards <span class="chart-card-label-sub">by Count</span></p>
              <div style="position:relative;height:220px;"><canvas id="ganados_anuales_chart"></canvas></div>
              <div class="chart-legend mt-3">
                <div class="chart-legend-item">
                  <span class="chart-legend-dot" style="background:#13A8F0"></span>
                  <span class="chart-legend-text">Current year</span>
                  <span class="chart-legend-value current"></span>
                </div>
                <div class="chart-legend-item">
                  <span class="chart-legend-dot" style="background:#39485a"></span>
                  <span class="chart-legend-text">Past year</span>
                  <span class="chart-legend-value past"></span>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

</div>

<script src="<?= RUTA_JS ?>main_charts.js"></script>
