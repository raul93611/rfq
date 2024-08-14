<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Daily</h1>
        </div>
        <div class="col-sm-6">
          <button id="add-projection-button" class="float-right btn btn-primary"><i class="fas fa-plus"></i></button>
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
              <table id="projections-table" class="table table-bordered">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>YEAR</th>
                    <th>OPTIONS</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
<script src="<?= RUTA_JS; ?>projections.js"></script>