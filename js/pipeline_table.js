/* =========================================================================
   Bid Pipeline Metrics — Table view controller (vanilla JS)
   Companion to pipeline_metrics.js. The Charts|Table toggle (owned by
   pipeline_metrics.js) calls window.PipelineTable.activate()/setPeriod();
   this file owns the filters, the paginated table, the watch toggles and
   the Quote Summary modal. Server-side filtered + paginated (25/page).
   ========================================================================= */
(function () {
  'use strict';
  var CFG = window.PM_CONFIG;
  if (!CFG) return;

  var $  = function (s, c) { return (c || document).querySelector(s); };
  var $$ = function (s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); };

  var STATUSES = window.PM_STATUSES || [];
  var STATUS_BY_KEY = {};
  STATUSES.forEach(function (s) { STATUS_BY_KEY[s.key] = s; });

  var EMPTY_FILTERS = function () { return { quoteId: '', channel: '', emailCode: '', statuses: [], bidType: '', user: '' }; };

  var st = {
    period: null,
    filters: EMPTY_FILTERS(),
    page: 0,
    total: 0,
    rows: [],
    token: 0,
    ready: false,
    openId: null   // id of the quote whose modal is open, or null
  };

  /* ---------- formatting ---------- */
  function fmtMoneyFull(n) { return '$' + Math.round(n || 0).toLocaleString('en-US'); }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) {
      return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' })[c];
    });
  }

  function statusPill(key) {
    var s = STATUS_BY_KEY[key];
    if (!s) return '';
    return '<span class="pt-pill" style="--c:' + s.color + '"><span class="pt-pill-dot"></span>' + esc(s.label) + '</span>';
  }
  function watchSvg(on) {
    return '<svg width="16" height="16" viewBox="0 0 24 24" fill="' + (on ? 'currentColor' : 'none') + '" stroke="currentColor" '
      + 'stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">'
      + '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
  }

  /* ---------- filter helpers ---------- */
  function countFilters() {
    var f = st.filters, n = 0;
    if (f.quoteId.trim()) n++;
    if (f.channel) n++;
    if (f.emailCode.trim()) n++;
    if (f.statuses.length) n++;
    if (f.bidType) n++;
    if (f.user) n++;
    return n;
  }

  function buildQuery() {
    var p = st.period, f = st.filters, q = ['mode=' + p.mode, 'year=' + p.year, 'page=' + st.page];
    if (p.mode === 'quarter') q.push('quarter=' + p.quarter);
    if (p.mode === 'month') q.push('month=' + p.month);
    if (p.mode === 'custom') { q.push('from=' + encodeURIComponent(p.from || '')); q.push('to=' + encodeURIComponent(p.to || '')); }
    if (f.quoteId.trim()) q.push('quoteId=' + encodeURIComponent(f.quoteId.trim()));
    if (f.channel) q.push('channel=' + encodeURIComponent(f.channel));
    if (f.emailCode.trim()) q.push('emailCode=' + encodeURIComponent(f.emailCode.trim()));
    if (f.bidType) q.push('bidType=' + encodeURIComponent(f.bidType));
    if (f.user) q.push('user=' + encodeURIComponent(f.user));
    f.statuses.forEach(function (k) { q.push('statuses[]=' + encodeURIComponent(k)); });
    return q.join('&');
  }

  /* ---------- fetch + render ---------- */
  function fetchRows() {
    var token = ++st.token;
    $('#pt-card-sub').textContent = 'Loading…';
    fetch(CFG.tableUrl + '?' + buildQuery(), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (res) {
        if (token !== st.token) return;
        st.rows = res.rows || [];
        st.total = res.total || 0;
        st.page = res.page || 0;
        renderTable();
      })
      .catch(function () { if (token === st.token) $('#pt-card-sub').textContent = 'Failed to load quotes.'; });
  }

  function renderTable() {
    var tbody = $('#pt-tbody');
    var anyFilters = countFilters() > 0;
    $('#pt-card-sub').textContent = st.total + (st.total === 1 ? ' quote' : ' quotes') + ' in this period';

    if (st.rows.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7"><div class="pt-empty">'
        + '<div class="pt-empty-ico"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l3-3 3 2 4-5" opacity="0.45"/></svg></div>'
        + '<div class="pt-empty-title">No data for this period</div>'
        + '<div class="pt-empty-sub">' + (anyFilters ? 'Try removing a filter, or a different period.' : 'Try a different year, quarter, or month.') + '</div>'
        + '</div></td></tr>';
    } else {
      tbody.innerHTML = st.rows.map(function (b) {
        return '<tr data-row="' + b.id + '">'
          + '<td class="pt-td-id"><button type="button" class="pt-idlink" data-open="' + b.id + '">' + esc(b.id) + '</button></td>'
          + '<td class="pt-td-ellip" title="' + esc(b.channel) + '">' + esc(b.channel) + '</td>'
          + '<td class="pt-td-mono">' + esc(b.emailCode) + '</td>'
          + '<td>' + statusPill(b.status) + '</td>'
          + '<td>' + esc(b.bidType) + '</td>'
          + '<td class="pt-td-ellip" title="' + esc(b.user) + '">' + esc(b.user) + '</td>'
          + '<td class="pt-td-watch"><button type="button" class="pt-watch ' + (b.watched ? 'is-on' : '') + '" data-watch="' + b.id + '" '
          + 'aria-pressed="' + (b.watched ? 'true' : 'false') + '" title="' + (b.watched ? 'Watching — click to stop' : 'Watch this quote') + '">'
          + watchSvg(b.watched) + '</button></td>'
          + '</tr>';
      }).join('');
    }
    renderPager();
    renderFilterFoot();
  }

  function renderPager() {
    var host = $('#pt-pager');
    if (st.total === 0) { host.innerHTML = ''; return; }
    var pages = Math.max(1, Math.ceil(st.total / 25));
    var from = st.page * 25 + 1, to = Math.min(st.total, (st.page + 1) * 25);
    var nums = [], win = 2;
    for (var i = 0; i < pages; i++) {
      if (i === 0 || i === pages - 1 || (i >= st.page - win && i <= st.page + win)) nums.push(i);
      else if (nums[nums.length - 1] !== '…') nums.push('…');
    }
    var btns = nums.map(function (n) {
      return n === '…' ? '<span class="pt-page-gap">…</span>'
        : '<button class="pt-page-btn ' + (n === st.page ? 'is-active' : '') + '" data-page="' + n + '">' + (n + 1) + '</button>';
    }).join('');
    host.className = 'pt-pager-bar';
    host.innerHTML = '<div class="pt-pager-meta">Showing <strong>' + from + '–' + to + '</strong> of <strong>' + st.total + '</strong> · 25 per page</div>'
      + '<div class="pt-pager">'
      + '<button class="pt-page-btn" data-page="' + (st.page - 1) + '" ' + (st.page === 0 ? 'disabled' : '') + ' aria-label="Previous page">‹</button>'
      + btns
      + '<button class="pt-page-btn" data-page="' + (st.page + 1) + '" ' + (st.page >= pages - 1 ? 'disabled' : '') + ' aria-label="Next page">›</button>'
      + '</div>';
  }

  function renderFilterFoot() {
    var n = countFilters();
    $('#pt-filters-count').textContent = n === 0 ? 'No filters applied' : (n + ' filter' + (n === 1 ? '' : 's') + ' applied');
    $('#pt-clear').disabled = n === 0;
  }

  /* ---------- status multi-select ---------- */
  function buildStatusMs() {
    var host = $('#pt-status-ms');
    if (!host || host.dataset.built) return;
    host.dataset.built = '1';
    host.innerHTML =
      '<button type="button" class="pt-input pt-ms-trigger" id="pt-ms-trigger">'
      + '<span class="pt-ms-summary" id="pt-ms-summary"><span class="pt-ms-placeholder">Any status</span></span>'
      + '<svg class="pt-ms-caret" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>'
      + '</button>'
      + '<div class="pt-ms-menu" id="pt-ms-menu" hidden>'
      + '<div class="pt-ms-menu-head"><span id="pt-ms-head">All statuses match</span><button type="button" class="pt-ms-clear" id="pt-ms-clear">Clear</button></div>'
      + '<div class="pt-ms-list">'
      + STATUSES.map(function (s) {
          return '<button type="button" class="pt-ms-opt" data-key="' + s.key + '">'
            + '<span class="pt-ms-check"></span>'
            + '<span class="pt-ms-dot" style="background:' + s.color + '"></span>'
            + '<span class="pt-ms-label">' + esc(s.label) + '</span></button>';
        }).join('')
      + '</div></div>';

    var trigger = $('#pt-ms-trigger'), menu = $('#pt-ms-menu');
    trigger.addEventListener('click', function () {
      var open = menu.hidden;
      menu.hidden = !open;
      trigger.classList.toggle('is-open', open);
    });
    document.addEventListener('mousedown', function (e) {
      if (!menu.hidden && !host.contains(e.target)) { menu.hidden = true; trigger.classList.remove('is-open'); }
    });
    $('#pt-ms-clear').addEventListener('click', function () { st.filters.statuses = []; syncStatusMs(); onFilterChange(); });
    $$('.pt-ms-opt', menu).forEach(function (opt) {
      opt.addEventListener('click', function () {
        var k = opt.dataset.key, arr = st.filters.statuses, i = arr.indexOf(k);
        if (i >= 0) arr.splice(i, 1); else arr.push(k);
        syncStatusMs(); onFilterChange();
      });
    });
  }

  function syncStatusMs() {
    var sel = st.filters.statuses;
    $$('.pt-ms-opt', $('#pt-ms-menu')).forEach(function (opt) {
      var on = sel.indexOf(opt.dataset.key) >= 0;
      opt.classList.toggle('is-on', on);
      opt.querySelector('.pt-ms-check').classList.toggle('is-on', on);
      opt.querySelector('.pt-ms-check').innerHTML = on
        ? '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>' : '';
    });
    $('#pt-ms-head').textContent = sel.length === 0 ? 'All statuses match' : (sel.length + ' selected');
    var sum = $('#pt-ms-summary');
    if (sel.length === 0) sum.innerHTML = '<span class="pt-ms-placeholder">Any status</span>';
    else if (sel.length <= 2) sum.innerHTML = '<span class="pt-ms-pills">' + sel.map(statusPill).join('') + '</span>';
    else sum.innerHTML = '<span class="pt-ms-pills">' + statusPill(sel[0]) + '<span class="pt-ms-more">+' + (sel.length - 1) + ' more</span></span>';
  }

  /* ---------- filter wiring ---------- */
  var debounceTimer = null;
  function onFilterChange(immediate) {
    st.page = 0;
    if (immediate) { fetchRows(); return; }
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchRows, 250);
  }

  function wireFilters() {
    var map = { 'pt-f-quoteId': 'quoteId', 'pt-f-emailCode': 'emailCode' };
    Object.keys(map).forEach(function (id) {
      var el = document.getElementById(id);
      if (el) el.addEventListener('input', function () { st.filters[map[id]] = el.value; onFilterChange(); });
    });
    ['pt-f-channel', 'pt-f-bidType', 'pt-f-user'].forEach(function (id) {
      var el = document.getElementById(id), key = id.replace('pt-f-', '');
      if (el) el.addEventListener('change', function () { st.filters[key] = el.value; onFilterChange(true); });
    });
    var clear = $('#pt-clear');
    if (clear) clear.addEventListener('click', function () {
      st.filters = EMPTY_FILTERS();
      ['pt-f-quoteId', 'pt-f-emailCode', 'pt-f-channel', 'pt-f-bidType', 'pt-f-user'].forEach(function (id) {
        var el = document.getElementById(id); if (el) el.value = '';
      });
      syncStatusMs();
      onFilterChange(true);
    });
  }

  /* ---------- watch toggle ---------- */
  function toggleWatch(id) {
    var row = st.rows.filter(function (r) { return r.id === id; })[0];
    if (!row) return;
    var next = !row.watched;
    // optimistic
    row.watched = next;
    reflectWatch(id, next);
    var body = new URLSearchParams({ id_rfq: String(id), action: next ? 'watch' : 'unwatch' });
    fetch(CFG.watchUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: body })
      .then(function (r) { return r.json(); })
      .then(function (res) { if (res && typeof res.watching === 'boolean') { row.watched = res.watching; reflectWatch(id, res.watching); } })
      .catch(function () { row.watched = !next; reflectWatch(id, !next); });
  }
  function reflectWatch(id, on) {
    var btn = $('.pt-watch[data-watch="' + id + '"]');
    if (btn) { btn.classList.toggle('is-on', on); btn.setAttribute('aria-pressed', on ? 'true' : 'false'); btn.innerHTML = watchSvg(on); btn.title = on ? 'Watching — click to stop' : 'Watch this quote'; }
    if (st.openId === id) {
      var mb = $('#qs-watch-btn');
      if (mb) { mb.classList.toggle('is-on', on); mb.innerHTML = watchSvg(on) + (on ? 'Watching' : 'Watch'); }
    }
  }

  /* ---------- Quote Summary modal ---------- */
  var DOC_COLOR = { pdf: '#dc2626', xlsx: '#16a34a', xls: '#16a34a', docx: '#2563eb', doc: '#2563eb' };
  function docColor(name) { var ext = String(name).split('.').pop().toLowerCase(); return DOC_COLOR[ext] || '#5a6a7e'; }

  function openModal(id) {
    var b = st.rows.filter(function (r) { return r.id === id; })[0];
    if (!b) return;
    st.openId = id;
    var docs = (b.docs || []).map(function (d) {
      return '<a class="qs-doc" href="' + CFG.editBase + b.id + '" title="Open quote to view documents">'
        + '<span class="qs-doc-ico" style="--dc:' + docColor(d) + '"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></span>'
        + '<span class="qs-doc-name">' + esc(d) + '</span></a>';
    }).join('');
    var docsSection = docs
      ? '<div class="qs-section"><div class="qs-section-title">Attached documents <span class="qs-count">' + b.docs.length + '</span></div><div class="qs-docs">' + docs + '</div></div>'
      : '';

    $('#qs-modal').innerHTML =
      '<div class="qs-head"><div class="qs-head-main">'
      + '<div class="qs-eyebrow"><span class="qs-code">' + esc(b.code) + '</span><span class="qs-eyebrow-sep">·</span>Quote #' + esc(b.id) + '</div>'
      + '<div class="qs-title" id="qs-title">' + esc(b.id) + '</div>'
      + '<div class="qs-desc">' + esc(b.name) + '</div></div>'
      + '<button class="qs-close" id="qs-close" aria-label="Close"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button></div>'
      + '<div class="qs-body">'
      + '<div class="qs-amount-row"><div class="qs-amount-block"><div class="qs-amount-label">Total amount</div><div class="qs-amount">' + fmtMoneyFull(b.value) + '</div></div>'
      + '<button type="button" class="qs-watch-btn ' + (b.watched ? 'is-on' : '') + '" id="qs-watch-btn">' + watchSvg(b.watched) + (b.watched ? 'Watching' : 'Watch') + '</button></div>'
      + docsSection
      + '<div class="qs-section"><div class="qs-section-title">Details</div><div class="qs-fields">'
      + '<div class="qs-field"><span class="qs-field-label">Status</span><span class="qs-field-val">' + statusPill(b.status) + '</span></div>'
      + '<div class="qs-field"><span class="qs-field-label">Designated User</span><span class="qs-field-val">' + esc(b.user) + '</span></div>'
      + '<div class="qs-field"><span class="qs-field-label">Channel</span><span class="qs-field-val">' + esc(b.channel) + '</span></div>'
      + '<div class="qs-field"><span class="qs-field-label">Type of Bid</span><span class="qs-field-val">' + esc(b.bidType) + '</span></div>'
      + '</div></div></div>'
      + '<div class="qs-foot">'
      + '<div class="qs-confirm" id="qs-confirm" hidden><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Comment posted</div>'
      + '<div class="qs-comment">'
      + '<textarea class="qs-comment-input" id="comment_rfq" rows="1" placeholder="Post a comment… use @ to mention"></textarea>'
      + '<button class="qs-send" id="qs-send" disabled aria-label="Post comment"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></button>'
      + '</div></div>';

    $('#qs-scrim').hidden = false;
    $('#qs-close').addEventListener('click', closeModal);
    $('#qs-watch-btn').addEventListener('click', function () { toggleWatch(b.id); });

    var ta = $('#comment_rfq'), send = $('#qs-send');
    ta.addEventListener('input', function () { send.disabled = !ta.value.trim(); });
    send.addEventListener('click', function () { postComment(b.id, ta, send); });
    ta.addEventListener('keydown', function (e) {
      // Enter posts, unless the @mention popup is handling it
      var pop = document.querySelector('.cm-mention-pop');
      var popOpen = pop && pop.style.display !== 'none';
      if (e.key === 'Enter' && !e.shiftKey && !popOpen) { e.preventDefault(); if (ta.value.trim()) postComment(b.id, ta, send); }
    });
    if (window.MentionAutocomplete) window.MentionAutocomplete.attach(ta);
    ta.focus();
  }

  function closeModal() { $('#qs-scrim').hidden = true; $('#qs-modal').innerHTML = ''; st.openId = null; }

  function postComment(id, ta, send) {
    var text = ta.value.trim();
    if (!text) return;
    send.disabled = true;
    var body = new FormData();
    body.append('guardar_comment', '1');
    body.append('id_rfq', String(id));
    body.append('comment_rfq', text);
    body.append('place', 'quote');
    // Reuses the exact quote-page comment endpoint: same validation, @mention parsing,
    // recipient logic and notifications. It redirects on success; we ignore the response.
    fetch(CFG.commentUrl, { method: 'POST', credentials: 'same-origin', body: body })
      .then(function () {
        ta.value = '';
        var c = $('#qs-confirm'); if (c) { c.hidden = false; setTimeout(function () { if (c) c.hidden = true; }, 2600); }
      })
      .catch(function () {})
      .finally(function () { send.disabled = true; });
  }

  /* ---------- delegated table clicks ---------- */
  function wireTable() {
    $('#pt-tbody').addEventListener('click', function (e) {
      var open = e.target.closest('[data-open]');
      if (open) { openModal(parseInt(open.dataset.open, 10)); return; }
      var watch = e.target.closest('[data-watch]');
      if (watch) { toggleWatch(parseInt(watch.dataset.watch, 10)); return; }
    });
    $('#pt-pager').addEventListener('click', function (e) {
      var b = e.target.closest('[data-page]');
      if (b && !b.disabled) { st.page = parseInt(b.dataset.page, 10); fetchRows(); }
    });
    $('#qs-scrim').addEventListener('mousedown', function (e) { if (e.target === $('#qs-scrim')) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !$('#qs-scrim').hidden) closeModal(); });
  }

  function ensureReady() {
    if (st.ready) return;
    st.ready = true;
    buildStatusMs();
    syncStatusMs();
    wireFilters();
    wireTable();
  }

  /* ---------- public API (called by pipeline_metrics.js) ---------- */
  window.PipelineTable = {
    activate: function (period) {
      ensureReady();
      st.period = period;
      st.page = 0;
      fetchRows();
    },
    setPeriod: function (period) {
      st.period = period;
      st.page = 0;
      fetchRows();
    }
  };
})();
