<?php
$cotizaciones = [];
if(isset($_POST['buscar'])){
  echo $_POST['termino_busqueda'];
  Conexion::abrir_conexion();
  $cotizaciones = RepositorioRfq::obtener_resultados_busqueda(Conexion::obtener_conexion(), $_POST['termino_busqueda']);
  Conexion::cerrar_conexion();
}
?>
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
                            <h3 class="card-title">Quotes</h3>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-4">

                            </div>
                            <div class="col-4">
                              <form role="form" method="post" action="<?php echo SEARCH_QUOTES; ?>">
                                <div class="form-group">
                                  <input type="search" name="termino_busqueda" class="form-control" placeholder="Search ..." required autofocus <?php if(isset($_POST['buscar'])){echo 'value="' . $_POST['termino_busqueda'] . '"';} ?>>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" name="buscar">Search</button>
                              </form>
                            </div>
                            <div class="col-4">

                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="card card-primary">
                      <div class="card-header">
                          <h3 class="card-title">Quotes</h3>
                      </div>
                      <div class="card-body">
                        <?php
                        RepositorioRfq::escribir_resultados_busqueda($cotizaciones);
                        ?>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
