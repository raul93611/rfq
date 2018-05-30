<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">FedBid</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <!--<ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v2</li>
                    </ol>-->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <section class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cotizaciones</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <input class="form-control" id="myInput" type="text" placeholder="Search.." autofocus>
                            <br>
                            <?php
                            RepositorioRfq::escribir_cotizaciones_por_canal_usuario_cargo($canal, $_SESSION['id_usuario'], $cargo);
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>

