<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Search Quotes</h1>
      <p class="page-subtitle">Find quotes by proposal, code, user or keyword</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <!-- Search form -->
      <div class="row justify-content-center mb-4">
        <div class="col-lg-6 col-md-8">
          <div class="card chart-card mb-0">
            <div class="card-body">
              <form id="search_quotes" role="form" method="post" action="">
                <div class="input-group">
                  <input type="search" name="termino_busqueda" class="form-control search-input" placeholder="Type a keyword and press Search..." required autofocus>
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-primary" name="buscar">
                      <i class="fas fa-search mr-1"></i> Search
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Results table -->
      <div class="card chart-card">
        <div class="card-body">
          <p class="chart-card-label">Results</p>
          <table id="tabla_busqueda" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Proposal</th>
                <th>Code</th>
                <th>Contract Number</th>
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
  </section>
</div>

<script src="<?= RUTA_JS; ?>searchQuotes.js"></script>
