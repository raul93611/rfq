<?php
list($nombres_usuario, $quotees_completadas, $quotees_completadas_pasadas, $quotees_ganadas, $quotees_ganadas_pasadas, $quotees_sometidas, $quotees_sometidas_pasadas, $quotees_no_sometidas, $quotees_no_sometidas_pasadas) = RepositorioUsuario::obtener_array_nombres_usuario_cotizaciones_completadas_ganadas_sometidas();
Database::open_connection();
$monthly_award_quotes = QuoteRepository::get_award_by_month(Database::get_connection());
$total_monthly_award_quotes = QuoteRepository::get_total_award_by_month(Database::get_connection());
list($no_bid, $manufacturer_in_the_bid, $expired_due_date, $supplier_did_not_provide_a_quote, $others) = QuoteRepository::get_counter_comment_status(Database::get_connection());
$quotees_completadas_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_completadas_por_usuario_y_mes(Database::get_connection());
list($quotees_ganadas_anual_usuarios, $quotees_ganadas_anual_usuarios_monto) = RepositorioUsuario::obtener_cotizaciones_ganadas_por_usuario_y_mes(Database::get_connection());
$quotees_not_submitted_anual_usuarios = RepositorioUsuario::obtener_cotizaciones_not_submitted_por_usuario_y_mes(Database::get_connection());
Database::close_connection();
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-dark">Home</h1>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 col-12">
          <input type="hidden" id="nombres_usuario" <?php echo "value='" . json_encode($nombres_usuario) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas" <?php echo "value='" . json_encode($quotees_completadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas_pasadas" <?php echo "value='" . json_encode($quotees_completadas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas" <?php echo "value='" . json_encode($quotees_ganadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_pasadas" <?php echo "value='" . json_encode($quotees_ganadas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_sometidas" <?php echo "value='" . json_encode($quotees_sometidas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_sometidas_pasadas" <?php echo "value='" . json_encode($quotees_sometidas_pasadas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_no_sometidas" <?php echo "value='" . json_encode($quotees_no_sometidas) . "'"; ?>>
          <input type="hidden" id="cotizaciones_no_sometidas_pasadas" <?php echo "value='" . json_encode($quotees_no_sometidas_pasadas) . "'"; ?>>
          <input type="hidden" id="monthly_award_quotes" <?php echo "value='" . json_encode($monthly_award_quotes) . "'"; ?>>
          <input type="hidden" id="monto_cotizaciones_mes" <?php echo "value='" . json_encode($total_monthly_award_quotes) . "'"; ?>>
          <input type="hidden" id="no_bid" <?php echo "value='" . json_encode($no_bid) . "'"; ?>>
          <input type="hidden" id="manufacturer_in_the_bid" <?php echo "value='" . json_encode($manufacturer_in_the_bid) . "'"; ?>>
          <input type="hidden" id="expired_due_date" <?php echo "value='" . json_encode($expired_due_date) . "'"; ?>>
          <input type="hidden" id="supplier_did_not_provide_a_quote" <?php echo "value='" . json_encode($supplier_did_not_provide_a_quote) . "'"; ?>>
          <input type="hidden" id="others" <?php echo "value='" . json_encode($others) . "'"; ?>>
          <input type="hidden" id="cotizaciones_completadas_anual_usuarios" <?php echo "value='" . json_encode($quotees_completadas_anual_usuarios) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_anual_usuarios" <?php echo "value='" . json_encode($quotees_ganadas_anual_usuarios) . "'"; ?>>
          <input type="hidden" id="cotizaciones_ganadas_anual_usuarios_monto" <?php echo "value='" . json_encode($quotees_ganadas_anual_usuarios_monto) . "'"; ?>>
          <input type="hidden" id="cotizaciones_not_submitted_anual_usuarios" <?php echo "value='" . json_encode($quotees_not_submitted_anual_usuarios) . "'"; ?>>
        </div>
      </div>
      <div id="graficas" class="row">
        <section class="col-lg-6 connectedSortable">
          <div class="card">
            <div class="card-header no-border">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Completed</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="complete-chart" height="200"></canvas>
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
