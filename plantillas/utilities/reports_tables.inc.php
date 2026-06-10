<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Reports</h1>
      <p class="page-subtitle">Generate and export data reports by period</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <?php
      $type    = $_POST['type']    ?? 'monthly';
      $report  = $_POST['report']  ?? '';
      $month   = $_POST['month']   ?? '';
      $quarter = $_POST['quarter'] ?? '';
      $year    = $_POST['year']    ?? '';
      ?>

      <!-- Filter Card -->
      <div class="chart-card mb-4">
        <div class="chart-card-header">
          <i class="fas fa-sliders-h mr-2"></i> Report Parameters
        </div>
        <div class="card-body">

          <!-- Period Type Toggle -->
          <div class="mb-4">
            <label class="d-block mb-2" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">Period Type</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="type-toggle">
              <?php
              $types = ['monthly' => 'Monthly', 'quarterly' => 'Quarterly', 'yearly' => 'Yearly'];
              foreach ($types as $val => $label):
              ?>
                <label class="btn btn-sm report-type-btn <?= $type === $val ? 'active' : ''; ?>" style="min-width:100px;">
                  <input type="radio" name="type" value="<?= $val; ?>" autocomplete="off" <?= $type === $val ? 'checked' : ''; ?>>
                  <?= $label; ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <form id="reports_form" action="<?= REPORTS_TABLES; ?>" method="post">
            <input type="hidden" name="type" id="selected_type" value="<?= $type; ?>">

            <div class="form-row align-items-end">

              <!-- Report Type -->
              <div class="col-md-4">
                <div class="form-group mb-0">
                  <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">Report</label>
                  <select id="report_select" class="custom-select custom-select-sm" name="report">
                    <?php
                    $reports = [
                      'submitted'                    => 'Submitted',
                      'award'                        => 'Award',
                      'fulfillment'                  => 'Fulfillment',
                      'accounts-payable-fulfillment' => 'Accounts Payable Fulfillment',
                      'sales-commission'             => 'Sales Commission',
                      'no-bid'                       => 'No Bid',
                      'not-submitted'                => 'Not Submitted',
                      'cancelled'                    => 'Cancelled',
                    ];
                    foreach ($reports as $value => $label):
                    ?>
                      <option value="<?= $value; ?>" <?= $report === $value ? 'selected' : ''; ?>><?= $label; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Month (shown for monthly) -->
              <div class="col-md-2" id="month_col" <?= $type !== 'monthly' ? 'style="display:none;"' : ''; ?>>
                <div class="form-group mb-0">
                  <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">Month</label>
                  <select class="custom-select custom-select-sm" name="month">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                      <option value="<?= $i; ?>" <?= $month == $i ? 'selected' : ''; ?>><?= date('F', mktime(0, 0, 0, $i, 1)); ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>

              <!-- Quarter (shown for quarterly) -->
              <div class="col-md-2" id="quarter_col" <?= $type !== 'quarterly' ? 'style="display:none;"' : ''; ?>>
                <div class="form-group mb-0">
                  <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">Quarter</label>
                  <select class="custom-select custom-select-sm" name="quarter">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                      <option value="<?= $i; ?>" <?= $quarter == $i ? 'selected' : ''; ?>>Q<?= $i; ?></option>
                    <?php endfor; ?>
                  </select>
                </div>
              </div>

              <!-- Year -->
              <div class="col-md-2">
                <div class="form-group mb-0">
                  <label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;">Year</label>
                  <?php Input::print_year_select($year); ?>
                </div>
              </div>

              <!-- Actions -->
              <div class="col-md-4 d-flex justify-content-end" style="gap:8px;">
                <button type="submit" class="btn btn-sm btn-outline-success" name="generate_excel_report">
                  <i class="fas fa-file-excel mr-1"></i> Excel
                </button>
                <span class="btn btn-sm btn-primary" data="generate_report">
                  <i class="fas fa-play mr-1"></i> Generate
                </span>
              </div>

            </div>
          </form>
        </div>
      </div>

      <!-- Results Card -->
      <div class="chart-card">
        <div class="chart-card-header">
          <i class="fas fa-table mr-2"></i> Results
        </div>
        <div class="card-body" id="report_results_container">
          <div class="text-center py-5 text-muted" id="report_empty_state">
            <i class="fas fa-chart-bar fa-3x mb-3 d-block" style="opacity:0.15;"></i>
            <p class="mb-0" style="font-size:14px;">Select your parameters above and click <strong>Generate</strong> to view the report.</p>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
// Show/hide month and quarter selectors based on period type
document.querySelectorAll('#type-toggle input[type=radio]').forEach(function(radio) {
  radio.addEventListener('change', function() {
    var val = this.value;
    document.getElementById('selected_type').value = val;
    document.getElementById('month_col').style.display   = val === 'monthly'   ? '' : 'none';
    document.getElementById('quarter_col').style.display = val === 'quarterly' ? '' : 'none';
  });
});

// Hide empty state when Generate is clicked
document.querySelector('span[data="generate_report"]').addEventListener('click', function() {
  var el = document.getElementById('report_empty_state');
  if (el) el.style.display = 'none';
});
</script>

<script src="<?= asset_url('js/reports.js'); ?>"></script>
