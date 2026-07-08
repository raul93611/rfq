/* =========================================================================
   Bid Pipeline Metrics — dashboard controller (vanilla JS + ApexCharts)
   Ported from the Claude Design React prototype. Every view is a live
   aggregation fetched from quote/pipeline_metrics; changing the period
   refetches and re-renders the charts in place. Clicking a segment fetches
   the underlying quotes (quote/pipeline_metrics_drilldown) into a drawer.
   ========================================================================= */
(function () {
  'use strict';
  var CFG = window.PM_CONFIG;
  if (!CFG) return;

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

  var APEX_FONT = "'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
  var INK_500 = '#5a6a7e', INK_400 = '#7d8ba0', LINE = '#e3e7ee';
  var MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  var animCfg = { enabled: true, easing: 'easeinout', speed: 520, animateGradually: { enabled: true, delay: 90 } };
  var noToolbar = { show: false };

  var state = {
    period: { mode: 'year', year: CFG.year, quarter: Math.floor((CFG.month - 1) / 3) + 1, month: CFG.month },
    show: 'count',
    view: 'charts',
    data: null
  };
  var charts = {};        // key -> ApexCharts instance
  var fetchToken = 0;     // guards against out-of-order responses

  /* ---- deterministic color per category name ---- */
  var CAT_PALETTE = ['#2db4e8','#4f6ef0','#16a34a','#d97706','#7c5cf0','#0e7490','#14b8a6','#f0734f','#dc2626','#1aa2dc','#9333ea','#b45309'];
  var catColors = {};
  function categoryColor(name) {
    if (catColors[name]) return catColors[name];
    var h = 0;
    for (var i = 0; i < name.length; i++) { h = (h * 31 + name.charCodeAt(i)) >>> 0; }
    return (catColors[name] = CAT_PALETTE[h % CAT_PALETTE.length]);
  }

  /* ---- formatting ---- */
  function fmtMoney(n) {
    n = n || 0;
    if (n >= 1e6) return '$' + (n / 1e6).toFixed(n >= 1e7 ? 1 : 2) + 'M';
    if (n >= 1e3) return '$' + Math.round(n / 1e3) + 'K';
    return '$' + Math.round(n).toLocaleString('en-US');
  }
  function fmtMoneyFull(n) { return '$' + Math.round(n || 0).toLocaleString('en-US'); }

  function tip(color, title, sub) {
    return '<div class="pm-tip"><span class="pm-tip-dot" style="background:' + color + '"></span>'
      + '<span class="pm-tip-body"><span class="pm-tip-title">' + title + '</span>'
      + '<span class="pm-tip-sub">' + sub + '</span></span></div>';
  }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c];
    });
  }

  function periodLabel() {
    var p = state.period;
    if (p.mode === 'quarter') return 'Q' + p.quarter + ' ' + p.year;
    if (p.mode === 'month') return MONTHS[p.month - 1] + ' ' + p.year;
    return '' + p.year;
  }

  /* ================= data fetch ================= */
  function buildQuery(extra) {
    var p = state.period, q = ['mode=' + p.mode, 'year=' + p.year];
    if (p.mode === 'quarter') q.push('quarter=' + p.quarter);
    if (p.mode === 'month') q.push('month=' + p.month);
    if (extra) for (var k in extra) if (extra.hasOwnProperty(k)) q.push(k + '=' + encodeURIComponent(extra[k]));
    return q.join('&');
  }

  function fetchMetrics() {
    var token = ++fetchToken;
    setLoading(true);
    fetch(CFG.dataUrl + '?' + buildQuery(), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (token !== fetchToken) return; // a newer request superseded this one
        state.data = data;
        render();
        setLoading(false);
      })
      .catch(function () { if (token === fetchToken) setLoading(false); });
  }

  function setLoading(on) {
    $$('[data-shimmer]').forEach(function (el) { el.hidden = !on; });
    if (on) $$('[data-empty]').forEach(function (el) { el.hidden = true; });
  }

  /* ================= render ================= */
  function render() {
    var d = state.data;
    if (!d) return;
    $('#pm-subtitle').textContent = 'Reports · ' + periodLabel() + ' · ' + d.count + (d.count === 1 ? ' bid' : ' bids') + ' in pipeline';
    renderKpis(d);
    renderStatus(d);
    renderWinLoss(d);
    renderCategory('awards', d.awardsByCategory, d);
    renderCategory('submitted', d.submittedByCategory, d);
    renderPricing(d);
  }

  function renderKpis(d) {
    var wl = d.winLoss || { denominator: 0, awarded: 0 };
    setKpi('total', d.count, 'count', '<strong>' + fmtMoney(d.totalValue) + '</strong> est. value');
    setKpi('submitted', d.submittedCount, 'count', '<strong>' + fmtMoney(d.submittedValue) + '</strong> submitted');
    setKpi('awarded', d.awardedCount, 'count', '<strong>' + fmtMoney(d.awardedValue) + '</strong> won');
    setKpi('winrate', d.winRate, 'percent', wl.denominator === 0
      ? 'no submitted bids'
      : '<strong>' + wl.awarded + '/' + wl.denominator + '</strong> awarded');
    setKpi('lost', d.lostCount, 'count', 'no-award outcomes');
    setKpi('pending', d.pendingCount, 'count', 'awaiting decision');
  }

  function setKpi(id, value, kind, subHtml) {
    var card = $('#pm-kpi-' + id);
    if (!card) return;
    var valEl = $('[data-kpi-value]', card), subEl = $('[data-kpi-sub]', card);
    subEl.innerHTML = subHtml;
    if (value == null) {
      valEl.classList.add('is-na');
      valEl.textContent = 'N/A';
      return;
    }
    valEl.classList.remove('is-na');
    countUp(valEl, kind === 'percent' ? Math.round(value * 100) : value, kind === 'percent');
  }

  function countUp(el, target, isPercent) {
    var dur = 900, t0 = performance.now(), from = 0;
    function tick(t) {
      var p = Math.min(1, (t - t0) / dur), eased = 1 - Math.pow(1 - p, 3);
      var v = Math.round(from + (target - from) * eased);
      el.textContent = isPercent ? v + '%' : v.toLocaleString('en-US');
      if (p < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  /* ---- per-card empty / chart lifecycle ---- */
  function card(key) { return $('[data-card="' + key + '"]'); }
  function setEmpty(key, isEmpty) {
    var c = card(key);
    if (!c) return;
    $('[data-empty]', c).hidden = !isEmpty;
    if (isEmpty && charts[key]) { charts[key].destroy(); delete charts[key]; }
  }
  function mountOrUpdate(key, cfg) {
    var el = $('#pm-chart-' + key);
    if (!el) return;
    if (charts[key]) {
      charts[key].updateOptions(cfg, false, true);
    } else {
      charts[key] = new ApexCharts(el, cfg);
      charts[key].render();
    }
  }
  function setHint(key, text) {
    var c = card(key), h = c && $('[data-card-hint]', c);
    if (h) h.textContent = text;
  }

  /* ================= chart 1: status distribution donut ================= */
  function statusNonZero(d) { return (d.status || []).filter(function (s) { return s.count > 0; }); }

  function renderStatus(d) {
    if (d.empty) { setEmpty('status', true); return; }
    setEmpty('status', false);
    var s = statusNonZero(d);
    var total = s.reduce(function (a, b) { return a + b.count; }, 0);
    mountOrUpdate('status', {
      chart: {
        type: 'donut', height: 320, fontFamily: APEX_FONT, animations: animCfg, toolbar: noToolbar,
        events: { dataPointSelection: function (e, ctx, cf) {
          var cur = statusNonZero(state.data), it = cur[cf.dataPointIndex];
          if (it) openDrill({ type: 'status', key: it.key }, it.label, it.color);
        } }
      },
      series: s.map(function (x) { return x.count; }),
      labels: s.map(function (x) { return x.label; }),
      colors: s.map(function (x) { return x.color; }),
      stroke: { width: 2, colors: ['#fff'] },
      legend: { position: 'right', fontSize: '12px', labels: { colors: INK_500 }, itemMargin: { vertical: 2 },
        markers: { width: 9, height: 9, radius: 3 },
        formatter: function (name, opts) { return name + '  ·  ' + opts.w.globals.series[opts.seriesIndex]; } },
      dataLabels: { enabled: false },
      plotOptions: { pie: { donut: { size: '66%', labels: {
        show: true,
        name: { fontSize: '11px', color: INK_400, offsetY: 18 },
        value: { fontSize: '26px', fontWeight: 700, color: '#0f1623', offsetY: -18, formatter: function (v) { return v; } },
        total: { show: true, label: 'Total bids', fontSize: '11px', color: INK_400, fontWeight: 600, formatter: function () { return String(total); } }
      } } } },
      tooltip: { custom: function (o) {
        var cur = statusNonZero(state.data), it = cur[o.seriesIndex];
        var tot = cur.reduce(function (a, b) { return a + b.count; }, 0);
        var pct = tot ? Math.round(it.count / tot * 100) : 0;
        return tip(it.color, it.label, state.show === 'percent' ? pct + '% · ' + it.count + ' bids' : it.count + ' bids · ' + pct + '%');
      } },
      states: { active: { filter: { type: 'darken', value: 0.85 } } }
    });
  }

  /* ================= chart 2: win / loss donut (3-way) ================= */
  function renderWinLoss(d) {
    var wl = d.winLoss || { series: [], denominator: 0 };
    if (d.empty) { setEmpty('winloss', true); setHint('winloss', 'Awaiting decisions'); return; }
    setEmpty('winloss', false);
    setHint('winloss', wl.denominator === 0 ? 'No submitted bids' : wl.denominator + ' submitted (excl. sources sought)');
    var slices = (wl.series || []).filter(function (x) { return x.count > 0; });
    var rate = d.winRate;
    mountOrUpdate('winloss', {
      chart: {
        type: 'donut', height: 320, fontFamily: APEX_FONT, animations: animCfg, toolbar: noToolbar,
        events: { dataPointSelection: function (e, ctx, cf) {
          var cur = (state.data.winLoss.series || []).filter(function (x) { return x.count > 0; });
          var it = cur[cf.dataPointIndex];
          if (it) openDrill({ type: 'winloss', key: it.key }, it.label, it.color);
        } }
      },
      series: slices.map(function (x) { return x.count; }),
      labels: slices.map(function (x) { return x.label; }),
      colors: slices.map(function (x) { return x.color; }),
      stroke: { width: 2, colors: ['#fff'] },
      legend: { position: 'bottom', fontSize: '12px', labels: { colors: INK_500 }, markers: { width: 9, height: 9, radius: 3 },
        formatter: function (name, opts) { return name + '  ·  ' + opts.w.globals.series[opts.seriesIndex]; } },
      dataLabels: { enabled: false },
      plotOptions: { pie: { donut: { size: '70%', labels: {
        show: true,
        name: { fontSize: '11px', color: INK_400, offsetY: 20, formatter: function () { return 'Win rate'; } },
        value: { fontSize: '38px', fontWeight: 800, color: rate == null ? INK_400 : '#16a34a', offsetY: -10,
          formatter: function () { return rate == null ? 'N/A' : Math.round(rate * 100) + '%'; } },
        total: { show: true, label: 'Win rate', fontSize: '11px', color: INK_400, fontWeight: 600,
          formatter: function () { return rate == null ? 'N/A' : Math.round(rate * 100) + '%'; } }
      } } } },
      noData: { text: 'No submitted bids', style: { color: INK_400, fontSize: '13px' } },
      tooltip: { custom: function (o) {
        var cur = (state.data.winLoss.series || []).filter(function (x) { return x.count > 0; });
        var it = cur[o.seriesIndex], denom = state.data.winLoss.denominator;
        var pct = denom ? Math.round(it.count / denom * 100) : 0;
        return tip(it.color, it.label, it.count + ' bids · ' + pct + '%');
      } },
      states: { active: { filter: { type: 'darken', value: 0.85 } } }
    });
  }

  /* ================= charts 3 & 4: category bars ================= */
  function renderCategory(kind, data, d) {
    var key = kind === 'awards' ? 'awards' : 'submitted';
    data = data || [];
    if (d.empty || data.length === 0) {
      setEmpty(key, true);
      return;
    }
    setEmpty(key, false);
    var total = data.reduce(function (a, b) { return a + b.count; }, 0);
    var asPct = state.show === 'percent';
    var vals = data.map(function (x) { return asPct ? (total ? +(x.count / total * 100).toFixed(1) : 0) : x.count; });
    var drillLabel = kind === 'awards' ? 'Awards' : 'Submitted';
    mountOrUpdate(key, {
      chart: {
        type: 'bar', height: 280, fontFamily: APEX_FONT, animations: animCfg, toolbar: noToolbar,
        events: { dataPointSelection: function (e, ctx, cf) {
          var cur = (key === 'awards' ? state.data.awardsByCategory : state.data.submittedByCategory) || [];
          var it = cur[cf.dataPointIndex];
          if (it && it.count > 0) openDrill(
            { type: 'category', bucket: key, category: it.category },
            drillLabel + ': ' + it.category, categoryColor(it.category));
        } }
      },
      series: [{ name: drillLabel, data: vals }],
      colors: data.map(function (x) { return categoryColor(x.category); }),
      plotOptions: { bar: { distributed: true, borderRadius: 5, columnWidth: '58%', borderRadiusApplication: 'end' } },
      dataLabels: { enabled: true, formatter: function (v) { return v === 0 ? '' : (asPct ? v + '%' : v); },
        offsetY: -16, style: { fontSize: '11px', fontWeight: 700, colors: ['#5a6a7e'] }, background: { enabled: false } },
      legend: { show: false },
      grid: { borderColor: LINE, strokeDashArray: 4, padding: { left: 6, right: 6 } },
      xaxis: { categories: data.map(function (x) { return x.category; }),
        labels: { rotate: -32, rotateAlways: true, hideOverlappingLabels: false, trim: true, maxHeight: 70,
          style: { fontSize: '10px', colors: INK_400 } }, axisBorder: { color: LINE }, axisTicks: { color: LINE } },
      yaxis: { min: 0, forceNiceScale: !asPct, decimalsInFloat: 0,
        labels: { style: { fontSize: '11px', colors: INK_400 }, formatter: function (v) { return asPct ? Math.round(v) + '%' : Math.round(v); } } },
      tooltip: { custom: function (o) {
        var cur = (key === 'awards' ? state.data.awardsByCategory : state.data.submittedByCategory) || [];
        var it = cur[o.dataPointIndex], tot = cur.reduce(function (a, b) { return a + b.count; }, 0);
        var pct = tot ? Math.round(it.count / tot * 100) : 0;
        return tip(categoryColor(it.category), it.category, it.count + ' ' + drillLabel.toLowerCase() + ' · ' + pct + '%');
      } },
      states: { active: { filter: { type: 'darken', value: 0.85 } } }
    });
  }

  /* ================= chart 5: pricing effort (horizontal stacked) ================= */
  function renderPricing(d) {
    var pr = d.pricing || { total: 0, buckets: [] };
    setHint('pricing', pr.total + (pr.total === 1 ? ' bid' : ' bids') + ' reached pricing');
    if (d.empty || pr.total === 0) { setEmpty('pricing', true); return; }
    setEmpty('pricing', false);
    var asPct = state.show === 'percent';
    var total = pr.total;
    var val = function (n) { return asPct ? (total ? +(n / total * 100).toFixed(1) : 0) : n; };
    mountOrUpdate('pricing', {
      chart: {
        type: 'bar', height: 280, stacked: true, fontFamily: APEX_FONT, animations: animCfg, toolbar: noToolbar,
        events: { dataPointSelection: function (e, ctx, cf) {
          var b = state.data.pricing.buckets[cf.seriesIndex];
          if (b && b.count > 0) openDrill({ type: 'priced', key: b.key }, 'Priced → ' + b.label, b.color);
        } }
      },
      series: pr.buckets.map(function (b) { return { name: b.label, data: [val(b.count)] }; }),
      colors: pr.buckets.map(function (b) { return b.color; }),
      plotOptions: { bar: { horizontal: true, borderRadius: 4, barHeight: '46%' } },
      dataLabels: { enabled: true, formatter: function (v) { return v === 0 ? '' : (asPct ? v + '%' : v); },
        style: { fontSize: '12px', fontWeight: 700, colors: ['#fff'] } },
      legend: { position: 'bottom', fontSize: '12px', labels: { colors: INK_500 }, markers: { width: 9, height: 9, radius: 3 } },
      grid: { borderColor: LINE, strokeDashArray: 4, padding: { left: 8, right: 8 } },
      xaxis: { categories: ['Priced bids'], labels: { style: { fontSize: '11px', colors: INK_400 },
        formatter: function (v) { return asPct ? Math.round(v) + '%' : Math.round(v); } }, axisBorder: { show: false }, axisTicks: { show: false } },
      yaxis: { labels: { style: { fontSize: '11px', colors: INK_400, fontWeight: 600 } } },
      tooltip: { custom: function (o) {
        var b = state.data.pricing, seg = b.buckets[o.seriesIndex];
        var pct = b.total ? Math.round(seg.count / b.total * 100) : 0;
        return tip(seg.color, seg.label, seg.count + ' bids · ' + pct + '%');
      } },
      states: { active: { filter: { type: 'darken', value: 0.85 } } }
    });
  }

  /* ================= drill-down drawer ================= */
  function openDrill(spec, label, color) {
    var token = ++fetchToken; // reuse guard so a period change cancels a pending drill
    var scrim = $('#pm-scrim'), drawer = $('#pm-drawer');
    $('#pm-drawer-dot').style.background = color || '#2db4e8';
    $('#pm-drawer-title').textContent = label;
    $('#pm-drawer-meta').innerHTML = 'Loading…';
    $('#pm-drawer-list').innerHTML = '';
    scrim.classList.add('is-open');
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');

    fetch(CFG.drillUrl + '?' + buildQuery(spec), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (token !== fetchToken) return;
        var quotes = (res && res.quotes) || [];
        var totalVal = quotes.reduce(function (s, q) { return s + (q.value || 0); }, 0);
        $('#pm-drawer-meta').innerHTML = '<strong>' + quotes.length + '</strong> ' + (quotes.length === 1 ? 'quote' : 'quotes')
          + '<span class="pm-drawer-meta-sep">·</span>' + fmtMoneyFull(totalVal) + ' total value';
        var list = $('#pm-drawer-list');
        if (quotes.length === 0) {
          list.innerHTML = '<div class="pm-drawer-empty">No quotes in this bucket.</div>';
          return;
        }
        list.innerHTML = quotes.map(function (q) {
          return '<a class="pm-quote" href="' + esc(q.url) + '" title="Open quote">'
            + '<div class="pm-quote-main"><div class="pm-quote-name">' + esc(q.name) + '</div>'
            + '<div class="pm-quote-sub"><span class="pm-quote-id">#' + esc(q.id) + '</span>'
            + '<span class="pm-quote-code">' + esc(q.code) + '</span>'
            + '<span class="pm-quote-cat">' + esc(q.category) + '</span></div></div>'
            + '<div class="pm-quote-right"><div class="pm-quote-value">' + fmtMoneyFull(q.value) + '</div>'
            + '<div class="pm-quote-date">' + esc(q.date) + '</div></div>'
            + '<span class="pm-quote-arrow"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg></span></a>';
        }).join('');
      })
      .catch(function () {
        if (token === fetchToken) $('#pm-drawer-meta').innerHTML = 'Failed to load quotes.';
      });
  }
  function closeDrill() {
    $('#pm-scrim').classList.remove('is-open');
    var dr = $('#pm-drawer');
    dr.classList.remove('is-open');
    dr.setAttribute('aria-hidden', 'true');
  }

  /* ================= export to Excel ================= */
  // Downloads an .xlsx built server-side (PhpSpreadsheet). `key` exports a single
  // report; omit it (or 'all') for the full workbook.
  function exportExcel(key) {
    var extra = (key && key !== 'all') ? { chart: key } : null;
    var url = CFG.exportUrl + '?' + buildQuery(extra);
    var a = document.createElement('a');
    a.href = url;
    a.rel = 'noopener';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
  }

  /* ================= controls wiring ================= */
  function changePeriod(next) {
    state.period = next;
    closeDrill();
    syncPeriodUI();
    if (state.view === 'table') {
      if (window.PipelineTable) window.PipelineTable.setPeriod(state.period);
    } else {
      fetchMetrics();
    }
  }
  function syncPeriodUI() {
    var p = state.period, custom = p.mode === 'custom';
    $$('#pm-mode .pm-seg-btn').forEach(function (b) { b.classList.toggle('is-active', b.dataset.mode === p.mode); });
    $('#pm-year-value').textContent = p.year;
    $('#pm-year-prev').disabled = p.year <= CFG.minYear;
    $('#pm-year-next').disabled = p.year >= CFG.maxYear;
    var stepper = $('.pm-stepper'); if (stepper) stepper.style.display = custom ? 'none' : '';
    $('#pm-quarter').style.display = p.mode === 'quarter' ? '' : 'none';
    $('#pm-month-wrap').style.display = p.mode === 'month' ? '' : 'none';
    $('#pm-daterange').style.display = custom ? '' : 'none';
    if (p.mode === 'quarter') $$('#pm-quarter .pm-seg-btn').forEach(function (b) { b.classList.toggle('is-active', +b.dataset.quarter === p.quarter); });
    if (p.mode === 'month') $('#pm-month').value = p.month;
    if (custom) { $('#pm-date-from').value = p.from || ''; $('#pm-date-to').value = p.to || ''; }
  }

  /* charts <-> table view toggle */
  function applyView(view) {
    // custom range is table-only; drop back to year when returning to charts
    if (view === 'charts' && state.period.mode === 'custom') {
      state.period = Object.assign({}, state.period, { mode: 'year' });
    }
    state.view = view;
    var isTable = view === 'table';
    $$('#pm-view .pm-seg-btn').forEach(function (b) { b.classList.toggle('is-active', b.dataset.view === view); });
    $('#pm-kpis').style.display = isTable ? 'none' : '';
    $('#pm-periodbar-right').style.display = isTable ? 'none' : '';
    $('#pm-charts-body').hidden = isTable;
    $('#pm-table-view').hidden = !isTable;
    var customBtn = $('.pm-mode-custom'); if (customBtn) customBtn.style.display = isTable ? '' : 'none';
    syncPeriodUI();
    if (isTable) {
      if (window.PipelineTable) window.PipelineTable.activate(state.period);
    } else {
      if (!state.data) fetchMetrics(); else { render(); window.dispatchEvent(new Event('resize')); }
    }
  }

  function wire() {
    $$('#pm-mode .pm-seg-btn').forEach(function (b) {
      b.addEventListener('click', function () {
        var next = Object.assign({}, state.period, { mode: b.dataset.mode });
        if (next.mode === 'quarter' && !next.quarter) next.quarter = 1;
        if (next.mode === 'month' && !next.month) next.month = CFG.month;
        if (next.mode === 'custom') {
          if (!next.from) next.from = next.year + '-01-01';
          if (!next.to) next.to = next.year + '-12-31';
        }
        changePeriod(next);
      });
    });
    $('#pm-year-prev').addEventListener('click', function () { changePeriod(Object.assign({}, state.period, { year: state.period.year - 1 })); });
    $('#pm-year-next').addEventListener('click', function () { changePeriod(Object.assign({}, state.period, { year: state.period.year + 1 })); });
    $$('#pm-quarter .pm-seg-btn').forEach(function (b) {
      b.addEventListener('click', function () { changePeriod(Object.assign({}, state.period, { quarter: +b.dataset.quarter })); });
    });
    $('#pm-month').addEventListener('change', function () { changePeriod(Object.assign({}, state.period, { month: +this.value })); });

    $$('#pm-show .pm-seg-btn').forEach(function (b) {
      b.addEventListener('click', function () {
        if (state.show === b.dataset.show) return;
        state.show = b.dataset.show;
        $$('#pm-show .pm-seg-btn').forEach(function (x) { x.classList.toggle('is-active', x.dataset.show === state.show); });
        if (state.data) render();
      });
    });

    $$('[data-export]').forEach(function (b) { b.addEventListener('click', function () { exportExcel(b.dataset.export); }); });
    $('#pm-export-all').addEventListener('click', function () { exportExcel('all'); });

    $('#pm-drawer-close').addEventListener('click', closeDrill);
    $('#pm-scrim').addEventListener('click', closeDrill);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDrill(); });

    // Charts | Table view toggle
    $$('#pm-view .pm-seg-btn').forEach(function (b) {
      b.addEventListener('click', function () { if (state.view !== b.dataset.view) applyView(b.dataset.view); });
    });
    // custom date range inputs (table view only)
    var df = $('#pm-date-from'), dt = $('#pm-date-to');
    if (df) df.addEventListener('change', function () { changePeriod(Object.assign({}, state.period, { mode: 'custom', from: this.value })); });
    if (dt) dt.addEventListener('change', function () { changePeriod(Object.assign({}, state.period, { mode: 'custom', to: this.value })); });
  }

  // expose the live period so the table controller can read it on activate
  window.PM_getPeriod = function () { return state.period; };

  document.addEventListener('DOMContentLoaded', function () {
    if (!$('#pm-dashboard')) return;
    wire();
    syncPeriodUI();
    fetchMetrics();
  });
})();
