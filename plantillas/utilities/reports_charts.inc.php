<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Reports</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Report Selection Form -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Choose</h3>
            </div>
            <div class="card-body">
              <form id="reports_charts_form" action="<?= REPORTS_CHARTS; ?>" method="post">
                <input type="hidden" name="type" value="<?= htmlspecialchars($type); ?>">

                <div class="row">
                  <div class="col-md-12">
                    <h4><i class="fas fa-calendar-alt"></i> Year</h4>
                    <div class="mt-4">
                      <?php $year = $_POST['year'] ?? ''; ?>
                      <?php Input::print_year_select($year); ?>
                    </div>
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="generate_report">
                      <i class="fas fa-play"></i> Generate
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Report Charts -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-chart-bar"></i> Report</h3>
            </div>
            <div class="card-body">
              <?php
              $charts = ['quote', 'requote', 'fulfillment'];
              foreach ($charts as $chart) {
                echo '
                <div class="row justify-content-center mb-4">
                  <div class="col-md-8">
                    <div class="position-relative">
                      <canvas id="' . $chart . '" height="350"></canvas>
                    </div>
                  </div>
                </div>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- External Scripts -->
<script src="<?= RUTA_JS; ?>reports.js"></script>
<script src="<?= RUTA_JS; ?>reports_charts.js"></script>