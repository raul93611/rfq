<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Quotes</h1>
                </div>
                <div class="col-sm-6">
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
                            <h3 class="card-title">RFP</h3>
                        </div>
                        <div class="card-body">
                            <?php
                            RepositorioRfq::escribir_cotizaciones_rfp($cargo, $_SESSION['id_usuario']);
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
