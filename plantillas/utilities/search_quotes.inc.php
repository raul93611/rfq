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
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                  </div>
                  <input type="search" name="termino_busqueda" class="form-control search-input" placeholder="Type at least 3 characters to search..." autofocus>
                </div>
                <small class="form-text text-muted mt-1">Results appear automatically after 3 characters.</small>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Quotes results table -->
      <div class="card chart-card mb-4">
        <div class="card-body">
          <p class="chart-card-label">Quotes</p>
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
            <tbody></tbody>
          </table>
        </div>
      </div>

      <!-- Partial invoices results table -->
      <div class="card chart-card">
        <div class="card-body">
          <p class="chart-card-label"><i class="fas fa-file-invoice-dollar mr-1"></i> Partial Invoices</p>
          <table id="tabla_invoices" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Invoice Name</th>
                <th>Invoice Date</th>
                <th>Parent Quote</th>
                <th>Designated User</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<script src="<?= RUTA_JS; ?>searchQuotes.js"></script>
