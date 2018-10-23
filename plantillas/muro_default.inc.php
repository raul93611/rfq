<?php
list($nombres_usuario, $cotizaciones_completadas, $cotizaciones_completadas_pasadas, $cotizaciones_ganadas, $cotizaciones_ganadas_pasadas, $cotizaciones_sometidas, $cotizaciones_sometidas_pasadas, $cotizaciones_no_sometidas, $cotizaciones_no_sometidas_pasadas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
Conexion::abrir_conexion();
$cotizaciones_mes = RepositorioRfq::obtener_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
$monto_cotizaciones_mes = RepositorioRfq::obtener_monto_cotizaciones_ganadas_por_mes(Conexion::obtener_conexion());
list($no_bid, $manufacturer_in_the_bid, $expired_due_date, $supplier_did_not_provide_a_quote, $others) = RepositorioRfq::obtener_comments(Conexion::obtener_conexion());
$cotizaciones_completadas_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_completadas_por_usuario_y_mes(Conexion::obtener_conexion());
list($cotizaciones_ganadas_anual_usuarios, $cotizaciones_ganadas_anual_usuarios_monto) = RepositorioUsuario::obtener_cotizaciones_ganadas_por_usuario_y_mes(Conexion::obtener_conexion());
$cotizaciones_not_submitted_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_not_submitted_por_usuario_y_mes(Conexion::obtener_conexion());
Conexion::cerrar_conexion();
//print_r($cotizaciones_ganadas_anual_usuarios_monto);
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-8">
          <h1 class="m-0 text-dark">Home</h1>
        </div>
        <div class="col-sm-2">
          <?php
          if($cargo < 3){
            ?>
            <a href="<?php echo PDF_REPORT; ?>" target="_blank" class="btn btn-primary float-right"><i class="fas fa-chart-line"></i> Report</a>
            <?php
          }
          ?>
        </div>
        <div class="col-sm-2">
          <a href="<?php echo EXCEL_REPORT; ?>" class="btn btn-primary float-right"><i class="fas fa-chart-line"></i> Excel report</a>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-12">
          <input type="hidden" id="nombres_usuario" <?php echo "value='" . json_encode($nombres_usuario) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas" <?php echo "value='" . json_encode($cotizaciones_completadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas_pasadas" <?php echo "value='" . json_encode($cotizaciones_completadas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas" <?php echo "value='" . json_encode($cotizaciones_ganadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_pasadas" <?php echo "value='" . json_encode($cotizaciones_ganadas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_sometidas" <?php echo "value='" . json_encode($cotizaciones_sometidas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_sometidas_pasadas" <?php echo "value='" . json_encode($cotizaciones_sometidas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_no_sometidas" <?php echo "value='" . json_encode($cotizaciones_no_sometidas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_no_sometidas_pasadas" <?php echo "value='" . json_encode($cotizaciones_no_sometidas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_mes" <?php echo "value='" . json_encode($cotizaciones_mes) . "'"; ?>>
          <input type="hidden" id="monto_cotizaciones_mes" <?php echo "value='" . json_encode($monto_cotizaciones_mes) . "'"; ?>>
          <input type="hidden" id="no_bid" <?php echo "value='" . json_encode($no_bid) . "'"; ?>>
          <input type="hidden" id="manufacturer_in_the_bid" <?php echo "value='" . json_encode($manufacturer_in_the_bid) . "'"; ?>>
          <input type="hidden" id="expired_due_date" <?php echo "value='" . json_encode($expired_due_date) . "'"; ?>>
          <input type="hidden" id="supplier_did_not_provide_a_quote" <?php echo "value='" . json_encode($supplier_did_not_provide_a_quote) . "'"; ?>>
          <input type="hidden" id="others" <?php echo "value='" . json_encode($others) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas_anual_usuarios" <?php echo "value='" . json_encode($cotizaciones_completadas_anual_usuarios) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_anual_usuarios" <?php echo "value='" . json_encode($cotizaciones_ganadas_anual_usuarios) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_anual_usuarios_monto" <?php echo "value='" . json_encode($cotizaciones_ganadas_anual_usuarios_monto) . "'"; ?>>
          <input type="hidden" id="cotizaciones_not_submitted_anual_usuarios" <?php echo "value='" . json_encode($cotizaciones_not_submitted_anual_usuarios) . "'"; ?>>
        </div>
      </div>
      <div id="graficas" class="row">
        <section class="col-lg-6 connectedSortable">
          <?php
          if ($cargo < 4) {
            ?>
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Completed</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="completados-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fa fa-square text-primary"></i> Current month
                  </span>

                  <span>
                    <i class="fa fa-square text-gray"></i> Last month
                  </span>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Not submitted</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="sometidas-chart" height="200"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fa fa-square text-primary"></i> Current month
                  </span>
                  <span>
                    <i class="fa fa-square text-gray"></i> Last month
                  </span>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Annual awards(by amount)</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="monto_ganados_anual_chart" height="200"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  <i class="fa fa-square text-primary"></i> Current year
                </span>
              </div>
            </div>
          </div>
        </section>
        <section class="col-lg-6 connectedSortable">
          <?php
          if ($cargo < 4) {
            ?>
            <div class="card">
              <div class="card-header no-border">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Awards</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="ganadas-chart" height="200"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fa fa-square text-primary"></i> Current month
                  </span>
                  <span>
                    <i class="fa fa-square text-gray"></i> Last month
                  </span>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Annual awards</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="ganados_anuales_chart" height="200"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  <i class="fa fa-square text-primary"></i> Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title"></h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="pie-chart" style="height: 222px"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
        </section>
        <div class="col-md-12">
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Completed</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_completed" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Award</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_award" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Award(by amount)</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_award_amount" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Not submitted</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="user_by_month_not_submitted" style="height:400px;"></canvas>
              </div>
              <div class="d-flex flex-row justify-content-end">
                <span class="mr-2">
                  Current year
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
