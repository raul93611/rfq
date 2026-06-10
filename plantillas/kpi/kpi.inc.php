<input type="hidden" name="id_rfq" value="<?= htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">KPI</h1>
      <p class="page-subtitle">Key performance indicators for this proposal</p>
    </div>
    <a class="btn btn-secondary btn-sm" href="<?= EDITAR_COTIZACION . '/' . htmlspecialchars($id_rfq, ENT_QUOTES, 'UTF-8'); ?>">
      <i class="fa fa-arrow-left mr-1"></i>Back
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <div class="card chart-card mb-4">
        <div class="card-body">
          <div class="chart-card-label">General</div>
          <div style="position:relative; height:300px;">
            <canvas id="general-chart"></canvas>
          </div>
        </div>
      </div>

      <div class="card chart-card mb-4">
        <div class="card-body">
          <div class="chart-card-label">RFQ</div>
          <div style="position:relative; height:300px;">
            <canvas id="rfq-chart"></canvas>
          </div>
        </div>
      </div>

      <div class="card chart-card mb-4">
        <div class="card-body">
          <div class="chart-card-label">RFP</div>
          <div style="position:relative; height:300px;">
            <canvas id="rfp-chart"></canvas>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script src="<?= asset_url('js/kpi.js'); ?>"></script>
