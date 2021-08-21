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
        <section class="col-lg-6 connectedSortable">
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Completed</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="completados-chart" height="250"></canvas>
              </div>
            </div>
          </div>
          <div id="annual_awards_amounts" class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Annual awards(by amount)</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="monto_ganados_anual_chart" height="250"></canvas>
              </div>
              <i class="fas fa-square text-primary"></i> Total: <span class="current"></span> <br>
              <i class="fas fa-square text-dark"></i> Total: <span class="past"></span>
            </div>
          </div>
        </section>
        <section class="col-lg-6 connectedSortable">
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Awards</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="ganadas-chart" height="250"></canvas>
              </div>
            </div>
          </div>
          <div id="annual_awards" class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Annual awards</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="ganados_anuales_chart" height="250"></canvas>
              </div>
              <i class="fas fa-square text-primary"></i> Total: <span class="current"></span> <br>
              <i class="fas fa-square text-dark"></i> Total: <span class="past"></span>
            </div>
          </div>
        </section>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Completed</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_completed" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Award</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_award" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Award(by amount)</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_award_amount" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo RUTA_JS; ?>main_charts.js"></script>
