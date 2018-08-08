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
                            <h3 class="card-title">No submitted</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input class="form-control" id="myInput" type="text" onkeyup="myFunction5()" placeholder="Search.." autofocus>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="tipo">
                                        <option>Code</option>
                                        <option>Designated user</option>
                                        <option>Type of Bid</option>
                                        <option>Issue Date</option>
                                        <option>End Date</option>
                                        <option>Proposal</option>
                                        <option>Comments</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <?php
                            RepositorioRfq::escribir_tabla_cotizaciones_no_submitted();
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
