<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Search</h1>
        </div>
        <div class="col-sm-6">
          <!-- Empty column can be removed or used for additional buttons -->
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-10">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <form id="search_quotes" role="form" method="post" action="">
                <div class="form-group">
                  <input type="search" name="termino_busqueda" class="form-control" placeholder="Search ..." required autofocus>
                </div>
                <button type="submit" class="btn btn-secondary btn-block" name="buscar">Search</button>
              </form>
            </div>
          </div>

          <div class="card card-primary mt-4">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <table id="tabla_busqueda" class="table table-bordered table-responsive-md">
                <thead>
                  <tr>
                    <th>Proposal</th>
                    <th>Code</th>
                    <th>Designated User</th>
                    <th>Type of Bid</th>
                    <th>Comments</th>
                    <th>Total Price</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data gets dynamically populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="<?= RUTA_JS; ?>searchQuotes.js"></script>