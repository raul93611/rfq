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
      <!-- Report Selection Card -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Choose</h3>
            </div>
            <div class="card-body">
              <?php
              $type = $_POST['type'] ?? 'monthly';
              $report = $_POST['report'] ?? '';
              $month = $_POST['month'] ?? '';
              $quarter = $_POST['quarter'] ?? '';
              $year = $_POST['year'] ?? '';
              ?>

              <!-- Report Type Selection -->
              <div class="btn-group btn-block btn-group-toggle mb-4" data-toggle="buttons">
                <?php
                $types = ['monthly', 'quarterly', 'yearly'];
                foreach ($types as $optionType):
                ?>
                  <label id="<?= $optionType; ?>" class="btn bg-olive <?= $type === $optionType ? 'active' : ''; ?>">
                    <input type="radio" name="type" value="<?= $optionType; ?>" autocomplete="off" <?= $type === $optionType ? 'checked' : ''; ?>> <?= ucfirst($optionType); ?>
                  </label>
                <?php endforeach; ?>
              </div>

              <!-- Report Form -->
              <form id="reports_form" action="<?= REPORTS_TABLES; ?>" method="post">
                <input type="hidden" name="type" value="<?= $type; ?>">

                <!-- Report Selection -->
                <div class="form-group">
                  <select id="report_select" class="form-control form-control-sm" name="report">
                    <?php
                    $reports = [
                      'submitted' => 'Submitted',
                      'award' => 'Award',
                      'fulfillment' => 'Fulfillment',
                      'accounts-payable-fulfillment' => 'Accounts Payable Fulfillment',
                      'sales-commission' => 'Sales Commission',
                      'no-bid' => 'No Bid',
                      'not-submitted' => 'Not Submitted'
                    ];
                    foreach ($reports as $value => $label):
                    ?>
                      <option value="<?= $value; ?>" <?= $report === $value ? 'selected' : ''; ?>><?= $label; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Time Period Selection -->
                <div class="row mt-4">
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> Month(s)</h4>
                    <div class="form-group">
                      <select class="custom-select" name="month">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                          <option value="<?= $i; ?>" <?= $month == $i ? 'selected' : ''; ?>><?= date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                        <?php endfor; ?>
                      </select>
                      <select class="custom-select" name="quarter">
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                          <option value="<?= $i; ?>" <?= $quarter == $i ? 'selected' : ''; ?>>Q<?= $i; ?></option>
                        <?php endfor; ?>
                      </select>
                    </div>
                  </div>

                  <!-- Year Selection -->
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> Year</h4>
                    <div class="form-group">
                      <?php Input::print_year_select($year); ?>
                    </div>
                  </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" name="generate_excel_report"><i class="fas fa-file-excel"></i> Excel</button>
                    <span class="btn btn-primary" data="generate_report"><i class="fas fa-play"></i> Generate</span>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Report Results Card -->
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Report</h3>
            </div>
            <div class="card-body" id="report_results_container">
              <!-- Report results will be dynamically loaded here -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="<?= RUTA_JS; ?>reports.js"></script>