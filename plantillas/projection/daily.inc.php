<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Daily</h1>
        </div>
        <div class="col-sm-6 text-right">
          <button id="add-projection-button" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Projection
          </button>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Projections</h3>
            </div>
            <div class="card-body">
              <table id="projections-table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Year</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table rows to be populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<script src="<?= htmlspecialchars(RUTA_JS . 'projections.js') ?>"></script>