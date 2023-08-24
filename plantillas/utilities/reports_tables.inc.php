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
              <?php $type = $_POST['type'] ?? 'monthly'; ?>
              <div class="btn-group btn-block btn-group-toggle mb-4" data-toggle="buttons">
                <label id="monthly" class="btn bg-olive <?= $type == 'monthly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="monthly" <?= $type == 'monthly' ? 'checked' : ''; ?>> Monthly
                </label>
                <label id="quarterly" class="btn bg-olive <?= $type == 'quarterly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="quarterly" <?= $type == 'quarterly' ? 'checked' : ''; ?>> Quarterly
                </label>
                <label id="yearly" class="btn bg-olive <?= $type == 'yearly' ? 'active' : ''; ?>">
                  <input type="radio" autocomplete="off" value="yearly" <?= $type == 'yearly' ? 'checked' : ''; ?>> Yearly
                </label>
              </div>
              <form id="reports_form" action="<?= REPORTS_TABLES; ?>" method="post">
                <input type="hidden" name="type" value="<?= $type; ?>">
                <div class="row">
                  <div class="col-md-12">
                    <?php $report = $_POST['report'] ?? ''; ?>
                    <select id="report_select" class="form-control form-control-sm" name="report">
                      <option value="submitted" <?= $report == 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                      <option value="award" <?= $report == 'award' ? 'selected' : ''; ?>>Award</option>
                      <option value="fulfillment" <?= $report == 'fulfillment' ? 'selected' : ''; ?>>Fulfillment</option>
                      <option value="accounts-payable-fulfillment" <?= $report == 'accounts-payable-fulfillment' ? 'selected' : ''; ?>>Accounts Payable Fulfillment</option>
                      <option value="sales-commission" <?= $report == 'sales-commission' ? 'selected' : ''; ?>>Sales Commission</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> Month(s)</h4>
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <?php $month = $_POST['month'] ?? ''; ?>
                        <select class="custom-select" name="month">
                          <option value="1" <?= $month == 1 ? 'selected' : ''; ?>>January</option>
                          <option value="2" <?= $month == 2 ? 'selected' : ''; ?>>February</option>
                          <option value="3" <?= $month == 3 ? 'selected' : ''; ?>>March</option>
                          <option value="4" <?= $month == 4 ? 'selected' : ''; ?>>April</option>
                          <option value="5" <?= $month == 5 ? 'selected' : ''; ?>>May</option>
                          <option value="6" <?= $month == 6 ? 'selected' : ''; ?>>June</option>
                          <option value="7" <?= $month == 7 ? 'selected' : ''; ?>>July</option>
                          <option value="8" <?= $month == 8 ? 'selected' : ''; ?>>August</option>
                          <option value="9" <?= $month == 9 ? 'selected' : ''; ?>>September</option>
                          <option value="10" <?= $month == 10 ? 'selected' : ''; ?>>October</option>
                          <option value="11" <?= $month == 11 ? 'selected' : ''; ?>>November</option>
                          <option value="12" <?= $month == 12 ? 'selected' : ''; ?>>December</option>
                        </select>
                        <?php $quarter = $_POST['quarter'] ?? ''; ?>
                        <select class="custom-select" name="quarter">
                          <option value="1" <?= $quarter == 1 ? 'selected' : ''; ?>>Q1</option>
                          <option value="2" <?= $quarter == 2 ? 'selected' : ''; ?>>Q2</option>
                          <option value="3" <?= $quarter == 3 ? 'selected' : ''; ?>>Q3</option>
                          <option value="4" <?= $quarter == 4 ? 'selected' : ''; ?>>Q4</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
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
                    <button type="submit" class="btn btn-primary" name="generate_excel_report"><i class="fas fa-file-excel"></i> Excel</button>
                    <span class="btn btn-primary" data="generate_report"><i class="fas fa-play"></i> Generate</span>
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
            <div class="card-body" id="report_results_container">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?= RUTA_JS; ?>reports.js"></script>