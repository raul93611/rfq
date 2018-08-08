<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Submitted quotes</h1>
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
                            <h3 class="card-title">FBO submitted</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <input class="form-control" id="myInput" type="text" onkeyup="myFunction2()" placeholder="Search.." autofocus>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" id="tipo">
                                        <option>Code</option>
                                        <option>Designated user</option>
                                        <option>Type of Bid</option>
                                        <option>Issue Date</option>
                                        <option>End Date</option>
                                        <option>Amount</option>
                                        <option>Submitted date</option>
                                        <option>Proposal</option>
                                        <option>Comments</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <?php
                            RepositorioRfq::escribir_cotizaciones_submitted_por_canal($canal, $_SESSION['id_usuario'], $cargo);
                            ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
