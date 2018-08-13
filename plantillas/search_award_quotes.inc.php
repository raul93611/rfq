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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Awards</h3>
                        </div>
                        <div class="card-body">
                          <div class="row">
                              <div class="col-md-8">
                                  <input class="form-control" id="myInput" type="text" onkeyup="myFunction7()" placeholder="Search.." autofocus>
                              </div>
                              <div class="col-md-4">
                                  <select class="form-control" id="tipo">
                                      <option>Proposal</option>
                                      <option>Code</option>
                                  </select>
                              </div>
                          </div>
                          <br>
                          <?php
                          RepositorioRfq::escribir_todas_cotizaciones_awards();
                          ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
