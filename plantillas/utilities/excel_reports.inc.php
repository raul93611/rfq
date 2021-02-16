<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Excel reports</h1>
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
              <form action="<?php echo GENERATE_EXCEL_REPORT; ?>" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control form-control-sm" name="quote_type">
                      <option value="submitted">Submitted</option>
                      <option value="award">Award</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> From</h4>
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm date" name="date_from" value="">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h4><i class="fas fa-calendar-alt"></i> To</h4>
                    <div class="row mt-4">
                      <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm date" name="date_to" value="">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block" name="generate_excel_report"><i class="fas fa-play"></i> Generate</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
