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
              <form action="<?php echo REPORTS; ?>" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <?php $report = $_POST['report'] ?? ''; ?>
                    <select class="form-control form-control-sm" name="report">
                      <option value="profit" <?php echo $report == 'profit' ? 'selected' : ''; ?>>Profit</option>
                      <option value="award" <?php echo $report == 'award' ? 'selected' : ''; ?>>Award</option>
                      <option value="submitted" <?php echo $report == 'submitted' ? 'selected' : ''; ?>>Submitted</option>
                      <option value="re_quote" <?php echo $report == 're_quote' ? 'selected' : ''; ?>>Re-Quote</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> Month</h4>
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <?php $month = $_POST['month'] ?? ''; ?>
                        <select class="custom-select" name="month">
                          <option value="1" <?php echo $month == 1 ? 'selected' : ''; ?>>January</option>
                          <option value="2" <?php echo $month == 2 ? 'selected' : ''; ?>>February</option>
                          <option value="3" <?php echo $month == 3 ? 'selected' : ''; ?>>March</option>
                          <option value="4" <?php echo $month == 4 ? 'selected' : ''; ?>>April</option>
                          <option value="5" <?php echo $month == 5 ? 'selected' : ''; ?>>May</option>
                          <option value="6" <?php echo $month == 6 ? 'selected' : ''; ?>>June</option>
                          <option value="7" <?php echo $month == 7 ? 'selected' : ''; ?>>July</option>
                          <option value="8" <?php echo $month == 8 ? 'selected' : ''; ?>>August</option>
                          <option value="9" <?php echo $month == 9 ? 'selected' : ''; ?>>September</option>
                          <option value="10" <?php echo $month == 10 ? 'selected' : ''; ?>>October</option>
                          <option value="11" <?php echo $month == 11 ? 'selected' : ''; ?>>November</option>
                          <option value="12" <?php echo $month == 12 ? 'selected' : ''; ?>>December</option>
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
                    <button type="submit" class="btn btn-primary" name="generate_report"><i class="fas fa-play"></i> Generate</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      if(isset($_POST['generate_report'])){
        ?>
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-hand-point-right"></i> Report</h3>
              </div>
              <div class="card-body">
                <?php
                include_once 'plantillas/utilities/report_' . $_POST['report'] . '.inc.php';
                ?>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </section>
</div>
