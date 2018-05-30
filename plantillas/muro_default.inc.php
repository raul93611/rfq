<?php
list($nombres_usuario, $cotizaciones_completadas, $cotizaciones_ganadas, $cotizaciones_sometidas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
?>
<input type="hidden" id="nombres_usuario" <?php echo "value='" . json_encode($nombres_usuario) . "'"; ?>>
<input type="hidden" id="cotizaciones_completadas" <?php echo "value='" . json_encode($cotizaciones_completadas) . "'"; ?>>
<input type="hidden" id="cotizaciones_ganadas" <?php echo "value='" . json_encode($cotizaciones_ganadas) . "'"; ?>>
<input type="hidden" id="cotizaciones_sometidas" <?php echo "value='" . json_encode($cotizaciones_sometidas) . "'"; ?>>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Inicio</h1>
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
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <!--<div class="col-lg-3 col-6">
                <!-- small box 
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>

                        <p>New Orders</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
                <!-- ./col 
                <div class="col-lg-3 col-6">
                <!-- small box 
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>53<sup style="font-size: 20px">%</sup></h3>

                        <p>Bounce Rate</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>-->
                <!-- ./col -->
                <div class="col-lg-12 col-12">
                    <!-- small box -->
                </div>
                <!-- ./col -->
                <!--<div class="col-lg-3 col-6">
                <!-- small box 
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Unique Visitors</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-6 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <!--<div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3">
                                <i class="fa fa-pie-chart mr-1"></i>
                                Completados
                            </h3>
                            <ul class="nav nav-pills ml-auto p-2">
                                
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content p-0">
                                
                                <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                            </div>
                        </div>
                    </div>-->
                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Completados</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="completados-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fa fa-square text-primary"></i> Mes actual
                                </span>

                                <span>
                                    <i class="fa fa-square text-gray"></i> Mes pasado
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Sometidas</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="sometidas-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fa fa-square text-primary"></i> Mes actual
                                </span>

                                <span>
                                    <i class="fa fa-square text-gray"></i> Mes pasado
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->

                    <!-- DIRECT CHAT -->

                    <!--/.direct-chat -->

                    <!-- TO DO List -->

                    <!-- /.card -->
                </section>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-6 connectedSortable">
                    <div class="card">
                        <div class="card-header no-border">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Ganadas</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- /.d-flex -->

                            <div class="position-relative mb-4">
                                <canvas id="ganadas-chart" height="200"></canvas>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <span class="mr-2">
                                    <i class="fa fa-square text-primary"></i> Mes actual
                                </span>

                                <span>
                                    <i class="fa fa-square text-gray"></i> Mes pasado
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Map card -->

                    <!-- /.card -->

                    <!-- solid sales graph -->

                    <!-- /.card -->

                    <!-- Calendar -->

                    <!-- /.card -->
                </section>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

