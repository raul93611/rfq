<?php
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
$pm_current_year = (int)date('Y');
?>
<div class="content-wrapper">
  <section class="content" style="padding-top:18px;padding-bottom:30px;">
    <div class="container-fluid pm-dashboard" id="pm-dashboard">

      <!-- header -->
      <div class="pm-head">
        <div>
          <h1 class="pm-title">Bid Pipeline Metrics</h1>
          <div class="pm-sub" id="pm-subtitle">Reports · loading…</div>
        </div>
        <div class="pm-head-actions">
          <button class="pm-btn" id="pm-export-all" type="button">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export report
          </button>
        </div>
      </div>

      <!-- KPI row -->
      <div class="pm-kpis" id="pm-kpis">
        <?php
        $pm_kpis = [
          ['id' => 'total',     'label' => 'Total Pipeline', 'accent' => '#2db4e8', 'ico' => '<path d="M3 3v18h18"/><rect x="7" y="11" width="3" height="6"/><rect x="12" y="7" width="3" height="10"/><rect x="17" y="13" width="3" height="4"/>'],
          ['id' => 'submitted', 'label' => 'Submitted',      'accent' => '#4f6ef0', 'ico' => '<path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7z"/>'],
          ['id' => 'awarded',   'label' => 'Awarded',        'accent' => '#16a34a', 'ico' => '<circle cx="12" cy="8" r="6"/><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"/>'],
          ['id' => 'winrate',   'label' => 'Win Rate',       'accent' => '#15803d', 'ico' => '<line x1="19" y1="5" x2="5" y2="19"/><circle cx="6.5" cy="6.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>'],
          ['id' => 'lost',      'label' => 'Lost',           'accent' => '#dc2626', 'ico' => '<path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/>'],
          ['id' => 'pending',   'label' => 'Pending',        'accent' => '#d97706', 'ico' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>'],
        ];
        foreach ($pm_kpis as $k):
        ?>
        <div class="pm-kpi" id="pm-kpi-<?= $k['id']; ?>" style="--pm-accent:<?= $k['accent']; ?>;">
          <div class="pm-kpi-top">
            <span class="pm-kpi-label"><?= $k['label']; ?></span>
            <span class="pm-kpi-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?= $k['ico']; ?></svg></span>
          </div>
          <div class="pm-kpi-value" data-kpi-value>—</div>
          <div class="pm-kpi-sub" data-kpi-sub>&nbsp;</div>
          <div class="pm-kpi-bar"><span></span></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- period filter bar -->
      <div class="pm-periodbar">
        <div class="pm-periodbar-left">
          <div class="pm-seg" id="pm-mode" role="group" aria-label="Period mode">
            <button class="pm-seg-btn is-active" data-mode="year" type="button">Year</button>
            <button class="pm-seg-btn" data-mode="quarter" type="button">Quarter</button>
            <button class="pm-seg-btn" data-mode="month" type="button">Month</button>
          </div>

          <div class="pm-period-divider"></div>

          <div class="pm-stepper">
            <button class="pm-step-btn" id="pm-year-prev" type="button" aria-label="Previous year">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <span class="pm-step-value" id="pm-year-value"><?= $pm_current_year; ?></span>
            <button class="pm-step-btn" id="pm-year-next" type="button" aria-label="Next year">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
          </div>

          <div class="pm-seg pm-seg-sm" id="pm-quarter" style="display:none;" role="group" aria-label="Quarter">
            <button class="pm-seg-btn is-active" data-quarter="1" type="button">Q1</button>
            <button class="pm-seg-btn" data-quarter="2" type="button">Q2</button>
            <button class="pm-seg-btn" data-quarter="3" type="button">Q3</button>
            <button class="pm-seg-btn" data-quarter="4" type="button">Q4</button>
          </div>

          <div class="pm-select-wrap" id="pm-month-wrap" style="display:none;">
            <label for="pm-month" class="sr-only" style="position:absolute;left:-9999px;">Month</label>
            <select class="pm-select" id="pm-month">
              <?php
              $pm_months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
              foreach ($pm_months as $i => $mn):
              ?>
                <option value="<?= $i + 1; ?>" <?= ((int)date('n') === $i + 1) ? 'selected' : ''; ?>><?= $mn; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="pm-periodbar-right">
          <span class="pm-mode-label">Show</span>
          <div class="pm-seg pm-seg-sm" id="pm-show" role="group" aria-label="Count or percent">
            <button class="pm-seg-btn is-active" data-show="count" type="button">Count</button>
            <button class="pm-seg-btn" data-show="percent" type="button">Percent</button>
          </div>
        </div>
      </div>

      <!-- chart grid -->
      <div class="pm-grid">
        <?php
        $pm_cards = [
          ['key' => 'status',    'span' => 'full', 'title' => 'Status distribution',           'hint' => 'All pipeline buckets'],
          ['key' => 'winloss',   'span' => '',     'title' => 'Win / Loss rate',                'hint' => 'Awarded · No Award · Pending'],
          ['key' => 'awards',    'span' => '',     'title' => 'Awards by service category',     'hint' => 'Won bids per category'],
          ['key' => 'submitted', 'span' => '',     'title' => 'Submitted by service category',  'hint' => 'Submissions per category'],
          ['key' => 'pricing',   'span' => '',     'title' => 'Pricing effort',                 'hint' => 'Where priced bids landed'],
        ];
        foreach ($pm_cards as $c):
        ?>
        <div class="pm-card <?= $c['span'] === 'full' ? 'pm-card-full' : ''; ?>" data-card="<?= $c['key']; ?>">
          <div class="pm-card-head">
            <div class="pm-card-titles">
              <div class="pm-card-title"><?= $c['title']; ?></div>
              <div class="pm-card-hint" data-card-hint><?= $c['hint']; ?></div>
            </div>
            <button class="pm-iconbtn" data-export="<?= $c['key']; ?>" title="Export to Excel" type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </button>
          </div>
          <div class="pm-card-body">
            <div class="pm-chart" id="pm-chart-<?= $c['key']; ?>"></div>
            <div class="pm-empty" data-empty hidden>
              <div class="pm-empty-ico">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-3 3 2 4-5" opacity="0.45"/></svg>
              </div>
              <div class="pm-empty-title">No data for this period</div>
              <div class="pm-empty-sub">Try a different year, quarter, or month.</div>
            </div>
            <div class="pm-shimmer" data-shimmer hidden></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="pm-foot">
        <span class="pm-foot-hint">Click any chart segment to see the underlying quotes.</span>
      </div>
    </div>
  </section>
</div>

<!-- drill-down drawer (fixed to viewport) -->
<div class="pm-dashboard">
  <div class="pm-drawer-scrim" id="pm-scrim"></div>
  <aside class="pm-drawer" id="pm-drawer" aria-hidden="true">
    <div class="pm-drawer-head">
      <div class="pm-drawer-head-row">
        <span class="pm-drawer-dot" id="pm-drawer-dot"></span>
        <span class="pm-drawer-eyebrow">Drill-down</span>
        <button class="pm-drawer-close" id="pm-drawer-close" type="button" aria-label="Close">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="pm-drawer-title" id="pm-drawer-title">—</div>
      <div class="pm-drawer-meta" id="pm-drawer-meta"></div>
    </div>
    <div class="pm-drawer-list" id="pm-drawer-list"></div>
  </aside>
</div>

<script>
  window.PM_CONFIG = {
    dataUrl: '<?= PIPELINE_METRICS_DATA; ?>',
    drillUrl: '<?= PIPELINE_METRICS_DRILLDOWN; ?>',
    exportUrl: '<?= PIPELINE_METRICS_EXPORT; ?>',
    year: <?= $pm_current_year; ?>,
    month: <?= (int)date('n'); ?>,
    minYear: 2015,
    maxYear: <?= $pm_current_year + 1; ?>
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js"></script>
<script src="<?= asset_url('js/pipeline_metrics.js'); ?>"></script>
