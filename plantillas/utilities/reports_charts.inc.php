<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Reports</h1>
        </div>
        <div class="col-sm-6">

        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Choose</h3>
            </div>
            <div class="card-body">
              <!-- <?php $type = $_POST['type'] ?? 'monthly'; ?> -->
              <!-- <div class="btn-group btn-block btn-group-toggle mb-4" data-toggle="buttons">
                <label id="monthly" class="btn bg-olive <?php echo $type == 'monthly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="monthly" <?php echo $type == 'monthly' ? 'checked' : ''; ?>> Monthly
                </label>
                <label id="quarterly" class="btn bg-olive <?php echo $type == 'quarterly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="quarterly" <?php echo $type == 'quarterly' ? 'checked' : ''; ?>> Quarterly
                </label>
                <label id="yearly" class="btn bg-olive <?php echo $type == 'yearly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="yearly" <?php echo $type == 'yearly' ? 'checked' : ''; ?>> Yearly
                </label>
              </div> -->
              <form id="reports_charts_form" action="<?php echo REPORTS_CHARTS; ?>" method="post">
                <input type="hidden" name="type" value="<?php echo $type; ?>">
                <div class="row">
                  <div class="col-md-12">
                    <h4><i class="fas fa-calendar-alt"></i> Year</h4>
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <?php
                        $year = $_POST['year'] ?? '';
                        Input::print_year_select($year);
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="generate_report"><i class="fas fa-play"></i> Generate</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Report</h3>
            </div>
            <div class="card-body">
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="quote" height="350"></canvas>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="requote" height="350"></canvas>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-8">
                  <div class="position-relative mb-4">
                    <canvas id="fulfillment" height="350"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo RUTA_JS; ?>reports.js"></script>
<script src="<?php echo RUTA_JS; ?>reports_charts.js"></script>