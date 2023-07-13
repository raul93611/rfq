<!-- <?php
      $cotizaciones = [];
      if (isset($_POST['buscar'])) {
        Conexion::abrir_conexion();
        $cotizaciones = RepositorioRfq::obtener_resultados_busqueda(Conexion::obtener_conexion(), $_POST['termino_busqueda']);
        Conexion::cerrar_conexion();
      }
      ?> -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Search</h1>
        </div>
        <div class="col-sm-6">

        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                  <form id="search_quotes" role="form" method="post" action="">
                    <div class="form-group">
                      <input type="search" name="termino_busqueda" class="form-control" placeholder="Search ..." required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="buscar">Search</button>
                  </form>
                </div>
                <div class="col-md-4">

                </div>
              </div>
            </div>
          </div>
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search"></i> Quotes</h3>
            </div>
            <div class="card-body">
              <table id="tabla_busqueda" class="table table-bordered table-responsive-md">
                <thead>
                  <tr>
                    <th>PROPOSAL</th>
                    <th>CODE</th>
                    <th>DESIGNATED USER</th>
                    <th>TYPE OF BID</th>
                    <th>COMMENTS</th>
                    <th>TOTAL PRICE</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script src="<?php echo RUTA_JS; ?>searchQuotes.js"></script>