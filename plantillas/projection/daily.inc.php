<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Projections</h1>
      <p class="page-subtitle">Yearly projection records</p>
    </div>
    <button id="add-projection-button" class="btn btn-primary btn-sm" type="button">
      <i class="fas fa-plus mr-1"></i>Add Projection
    </button>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="projections-table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Year</th>
                <th class="text-center" style="width:100px;">Options</th>
              </tr>
            </thead>
            <tbody>
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="<?= asset_url('js/projections.js'); ?>"></script>
