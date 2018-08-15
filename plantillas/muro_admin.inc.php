<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Home</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-12">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                <?php
                                Conexion::abrir_conexion();
                                echo RepositorioUsuario::contar_usuarios(Conexion::obtener_conexion());
                                Conexion::cerrar_conexion();
                                ?>
                            </h3>
                            <p>Registered users</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <section class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Registered users</h3>
                        </div>
                        <div id="content" class="card-body table-responsive">
                            <div class="row">
                                <div class="col-md-8">
                                    <input class="form-control" id="myInput" type="text" onkeyup="myFunction4()" placeholder="Search.." autofocus>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="tipo">
                                        <option>First names</option>
                                        <option>Last names</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <?php
                            RepositorioUsuario::escribir_usuarios();
                            ?>
                        </div>
                    </div>
                    <!--<div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Extra info</h3>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <input class="form-control" id="myInputadmin" type="text" onkeyup="myFunction6()" placeholder="Search.." autofocus>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="tipo_extra">
                                    <option>Rfq id</option>
                                    <option>User</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <?php
                        //RepositorioItem::escribir_items_admin();
                        ?>
                      </div>
                    </div>-->
                </section>
            </div>
        </div>
    </section>
</div>
