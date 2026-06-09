/* ============================================================
   audit_trail.js — Unified Audit Trail Modal
   ============================================================ */
(function ($) {
  'use strict';

  // ---------- Action type definitions ----------
  var AT_TYPES = {
    'status_change':    { label: 'Status Changed',   color: '#16a34a', bg: '#e8f6ec', fg: '#15803d', group: 'status'   },
    'field_modified':   { label: 'Field Edited',     color: '#2563eb', bg: '#e8f0fc', fg: '#1d4ed8', group: 'edits'    },
    'item_modified':    { label: 'Field Edited',     color: '#2563eb', bg: '#e8f0fc', fg: '#1d4ed8', group: 'edits'    },
    'item_created':     { label: 'Item Created',     color: '#0d9488', bg: '#e0f4f1', fg: '#0f766e', group: 'items'    },
    'item_deleted':     { label: 'Item Deleted',     color: '#dc2626', bg: '#fbe9e9', fg: '#b91c1c', group: 'items'    },
    'invoice_created':  { label: 'Invoice Created',  color: '#ea580c', bg: '#fdebd9', fg: '#c2410c', group: 'invoices' },
    'invoice_updated':  { label: 'Invoice Updated',  color: '#ea580c', bg: '#fdebd9', fg: '#c2410c', group: 'invoices' },
    'invoice_deleted':  { label: 'Invoice Deleted',  color: '#ea580c', bg: '#fdebd9', fg: '#c2410c', group: 'invoices' },
    'document_updated': { label: 'Document Updated', color: '#2563eb', bg: '#e8f0fc', fg: '#1d4ed8', group: 'edits'    },
    'net_30':           { label: 'Field Edited',     color: '#2563eb', bg: '#e8f0fc', fg: '#1d4ed8', group: 'edits'    },
    // Quote lifecycle events. 'quote_created' is a Status-group marker; sync events live
    // in their own 'sync' group and render as compact single-line rows (see at-sync-*).
    'quote_created':    { label: 'Quote Created',   color: '#16a34a', bg: '#e8f6ec', fg: '#15803d', group: 'status' },
    'sync_to_sheet':    { label: 'Synced',          color: '#7c3aed', bg: '#f1ebfc', fg: '#6d28d9', group: 'sync', syncType: 'synced'   },
    'break_sync':       { label: 'Unsynced',        color: '#d97706', bg: '#fdf1de', fg: '#b45309', group: 'sync', syncType: 'unsynced' }
  };
  var AT_DEFAULT = { label: 'Modified', color: '#2563eb', bg: '#e8f0fc', fg: '#1d4ed8', group: 'edits' };

  var SCOPE_LABELS = { quote: 'Quote', requote: 'Re-Quote', fulfillment: 'Fulfillment' };

  // ---------- State ----------
  var atEntries   = [];
  var atActionTab = 'all';
  var atScopeTab  = 'full';
  var atSearch    = '';

  // ---------- Helpers ----------
  function atType(key) {
    return AT_TYPES[key] || AT_DEFAULT;
  }

  function atInitials(name) {
    var caps = name.replace(/[^A-Z]/g, '').slice(0, 2);
    return caps || name.slice(0, 2).toUpperCase();
  }

  function atAvatarColors(name) {
    var h = 0;
    for (var i = 0; i < name.length; i++) { h = (h * 31 + name.charCodeAt(i)) % 360; }
    return { bg: 'hsl(' + h + ',38%,92%)', fg: 'hsl(' + h + ',45%,32%)' };
  }

  function atFormatDate(dateStr) {
    var d   = new Date(dateStr.replace(' ', 'T'));
    var now = new Date();
    var sec = Math.floor((now - d) / 1000);
    var min = Math.floor(sec / 60);
    var hr  = Math.floor(min / 60);
    var day = Math.floor(hr  / 24);

    var rel;
    if (sec  < 60)  rel = 'just now';
    else if (min < 60)  rel = min  + ' minute'  + (min  === 1 ? '' : 's') + ' ago';
    else if (hr  < 24)  rel = hr   + ' hour'    + (hr   === 1 ? '' : 's') + ' ago';
    else if (day < 30)  rel = day  + ' day'     + (day  === 1 ? '' : 's') + ' ago';
    else                rel = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    return {
      abs:      d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      rel:      rel,
      dayLabel: d.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
    };
  }

  // Short clock time (e.g. "3:42 PM") for the compact sync rows + collapsed runs.
  function atTimeShort(dateStr) {
    var d = new Date(dateStr.replace(' ', 'T'));
    return d.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
  }

  function atEsc(s) {
    return String(s)
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
  }

  // ---------- Filter + count ----------
  function atFiltered() {
    return atEntries.filter(function (e) {
      if (atScopeTab !== 'full' && e.scope !== atScopeTab) return false;
      if (atActionTab !== 'all' && atType(e.action_type).group !== atActionTab) return false;
      if (atSearch) {
        var hay = (e.username + ' ' + e.audit_trail).toLowerCase();
        if (hay.indexOf(atSearch.toLowerCase()) === -1) return false;
      }
      return true;
    });
  }

  function atCounts() {
    var base = atEntries.filter(function (e) {
      return atScopeTab === 'full' || e.scope === atScopeTab;
    });
    var g = function (grp) { return base.filter(function (e) { return atType(e.action_type).group === grp; }).length; };
    return { all: base.length, status: g('status'), edits: g('edits'), items: g('items'), invoices: g('invoices'), sync: g('sync') };
  }

  // ---------- Render one entry ----------
  function atRenderEntry(e, showLine) {
    var t  = atType(e.action_type);
    var av = atAvatarColors(e.username);
    var dt = atFormatDate(e.created_date);
    var sl = SCOPE_LABELS[e.scope] || e.scope;
    var shadow = '0 0 0 3px #fff,0 0 0 4px ' + t.color + '33';

    return '<li class="at-entry">' +
      '<div class="at-rail">' +
        '<span class="at-dot" style="background:' + t.color + ';box-shadow:' + shadow + '"></span>' +
        (showLine ? '<span class="at-line"></span>' : '') +
      '</div>' +
      '<div class="at-entry-body">' +
        '<div class="at-entry-head">' +
          '<div class="at-avatar" style="background:' + av.bg + ';color:' + av.fg + '">' + atEsc(atInitials(e.username)) + '</div>' +
          '<div class="at-entry-meta">' +
            '<div class="at-entry-row">' +
              '<span class="at-user">' + atEsc(e.username) + '</span>' +
              '<span class="at-badge" style="background:' + t.bg + ';color:' + t.fg + '">' +
                '<span class="at-badge-dot" style="background:' + t.color + '"></span>' + t.label +
              '</span>' +
              '<span class="at-scope-pill">' + atEsc(sl) + '</span>' +
            '</div>' +
            '<div class="at-entry-row at-entry-row-sub">' +
              '<span class="at-when">' + atEsc(dt.abs) + '</span>' +
              '<span class="at-meta-sep">&middot;</span>' +
              '<span class="at-ts">' +
                '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' +
                atEsc(dt.rel) +
              '</span>' +
            '</div>' +
          '</div>' +
        '</div>' +
        '<div class="at-entry-content"><div class="at-body-text">' + e.audit_trail + '</div></div>' +
      '</div>' +
    '</li>';
  }

  // ---------- Sync rows (compact single-line) ----------
  // A consecutive run of 3+ 'synced' events collapses into one expandable summary so a
  // high-volume auto-sync history doesn't drown the timeline. 'unsynced' is always its own row.
  function atBuildUnits(items) {
    var units = [];
    var i = 0;
    while (i < items.length) {
      var st = atType(items[i].action_type).syncType;
      if (st === 'synced') {
        var j = i;
        while (j < items.length && atType(items[j].action_type).syncType === 'synced') { j++; }
        var run = items.slice(i, j);
        if (run.length >= 3) {
          units.push({ kind: 'run', items: run });
        } else {
          run.forEach(function (r) { units.push({ kind: 'sync', e: r }); });
        }
        i = j;
      } else if (st === 'unsynced') {
        units.push({ kind: 'sync', e: items[i] }); i++;
      } else {
        units.push({ kind: 'entry', e: items[i] }); i++;
      }
    }
    return units;
  }

  function atRenderSyncEntry(e, showLine) {
    var t = atType(e.action_type);
    var shadow = '0 0 0 3px #fff,0 0 0 4px ' + t.color + '33';
    var lineClass = t.syncType === 'unsynced' ? ' at-sync-line-unsynced' : '';
    return '<li class="at-entry at-sync-entry">' +
      '<div class="at-rail">' +
        '<span class="at-dot at-dot-sm" style="background:' + t.color + ';box-shadow:' + shadow + '"></span>' +
        (showLine ? '<span class="at-line"></span>' : '') +
      '</div>' +
      '<div class="at-sync-line' + lineClass + '">' +
        '<span class="at-badge" style="background:' + t.bg + ';color:' + t.fg + '">' +
          '<span class="at-badge-dot" style="background:' + t.color + '"></span>' + t.label +
        '</span>' +
        '<span class="at-sync-text">' + e.audit_trail + '</span>' +
        '<span class="at-sync-spacer"></span>' +
        '<span class="at-sync-actor">' + atEsc(e.username) + '</span>' +
        '<span class="at-sync-ts">' + atEsc(atTimeShort(e.created_date)) + '</span>' +
      '</div>' +
    '</li>';
  }

  function atRenderSyncRun(items, showLine) {
    var t = atType('sync_to_sheet');
    var shadow = '0 0 0 3px #fff,0 0 0 4px ' + t.color + '33';
    var newest = items[0], oldest = items[items.length - 1];
    var listItems = items.map(function (e) {
      return '<li class="at-run-item">' +
        '<span class="at-run-bullet" style="background:' + t.color + '"></span>' +
        '<span class="at-run-item-text">' + e.audit_trail + '</span>' +
        '<span class="at-sync-spacer"></span>' +
        '<span class="at-run-item-actor">' + atEsc(e.username) + '</span>' +
        '<span class="at-run-item-ts">' + atEsc(atTimeShort(e.created_date)) + '</span>' +
      '</li>';
    }).join('');
    return '<li class="at-entry at-run-entry">' +
      '<div class="at-rail">' +
        '<span class="at-dot at-dot-sm" style="background:' + t.color + ';box-shadow:' + shadow + '"></span>' +
        (showLine ? '<span class="at-line"></span>' : '') +
      '</div>' +
      '<div class="at-run-wrap">' +
        '<button type="button" class="at-run-summary">' +
          '<span class="at-badge" style="background:' + t.bg + ';color:' + t.fg + '">' +
            '<span class="at-badge-dot" style="background:' + t.color + '"></span>' + t.label +
          '</span>' +
          '<span class="at-run-count">' + items.length + ' automatic syncs</span>' +
          '<span class="at-run-range">' + atEsc(atTimeShort(oldest.created_date)) + ' &ndash; ' + atEsc(atTimeShort(newest.created_date)) + '</span>' +
          '<span class="at-sync-spacer"></span>' +
          '<span class="at-run-toggle"><span class="at-run-toggle-text">Show all</span>' +
            '<svg class="at-run-chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>' +
          '</span>' +
        '</button>' +
        '<ul class="at-run-list" style="display:none">' + listItems + '</ul>' +
      '</div>' +
    '</li>';
  }

  // ---------- Render grouped timeline ----------
  function atRenderGrouped(filtered) {
    var groups = [];
    var idx = {};
    filtered.forEach(function (e) {
      var dl = atFormatDate(e.created_date).dayLabel;
      if (!idx[dl]) { idx[dl] = []; groups.push({ day: dl, items: idx[dl] }); }
      idx[dl].push(e);
    });

    var html = '';
    groups.forEach(function (g, gi) {
      html += '<div class="at-day-group">' +
        '<div class="at-day-label"><span>' + atEsc(g.day) + '</span>' +
        '<span class="at-day-count">' + g.items.length + (g.items.length === 1 ? ' event' : ' events') + '</span></div>' +
        '<ul class="at-timeline">';
      var units = atBuildUnits(g.items);
      units.forEach(function (u, i) {
        var showLine = i < units.length - 1 || gi < groups.length - 1;
        if (u.kind === 'run')       html += atRenderSyncRun(u.items, showLine);
        else if (u.kind === 'sync') html += atRenderSyncEntry(u.e, showLine);
        else                        html += atRenderEntry(u.e, showLine);
      });
      html += '</ul></div>';
    });
    return html;
  }

  // ---------- Update the whole modal UI ----------
  function atUpdateUI() {
    var filtered = atFiltered();
    var counts   = atCounts();
    var $modal   = $('#audit_trails_modal');

    // Tab counts + active state
    $modal.find('.at-tab[data-tab]').each(function () {
      var tab = $(this).data('tab');
      $(this).find('.at-tab-count').text(counts[tab] !== undefined ? counts[tab] : 0);
      $(this).toggleClass('is-active', tab === atActionTab);
    });

    // Scope active state
    $modal.find('.at-scope[data-scope]').each(function () {
      $(this).toggleClass('is-active', $(this).data('scope') === atScopeTab);
    });

    // Body
    var $wrap = $modal.find('.at-timeline-wrap');
    if (filtered.length === 0) {
      $wrap.html(
        '<div class="at-empty">' +
          '<div class="at-empty-icon">' +
            '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M3 12h18M3 18h12"/></svg>' +
          '</div>' +
          '<div class="at-empty-title">No audit trail entries</div>' +
          '<div class="at-empty-sub">No events match the current filters.</div>' +
        '</div>'
      );
    } else {
      $wrap.html(atRenderGrouped(filtered));
    }

    // Footer count
    $modal.find('.at-footer-count').html(
      filtered.length > 0
        ? 'Showing <strong>' + filtered.length + '</strong> of <strong>' + atEntries.length + '</strong> events'
        : ''
    );
  }

  // ---------- Load entries via AJAX ----------
  function atLoad(idRfq) {
    $('#audit_trails_modal .at-timeline-wrap').html(
      '<div class="at-loading"><div class="at-loading-spinner"></div><span>Loading audit trail&hellip;</span></div>'
    );
    $.post('/rfq/quote/load_unified_audit_trail', { id_rfq: idRfq })
      .done(function (data) { atEntries = data; atUpdateUI(); })
      .fail(function () {
        $('#audit_trails_modal .at-timeline-wrap').html(
          '<div class="at-empty"><div class="at-empty-title">Failed to load audit trail.</div></div>'
        );
      });
  }

  // ---------- Open the modal ----------
  function atOpen(idRfq) {
    atEntries   = [];
    atActionTab = 'all';
    atScopeTab  = 'full';
    atSearch    = '';
    var $modal  = $('#audit_trails_modal');
    $modal.find('.at-subtitle').text('Proposal #' + idRfq);
    $modal.find('.at-search-input').val('');
    $modal.modal('show');
    atLoad(idRfq);
  }

  // ---------- Wire everything up ----------
  $(document).ready(function () {
    var $modal = $('#audit_trails_modal');
    if (!$modal.length) return;

    // Trigger buttons (quote page + fulfillment page)
    $(document).on('click', '#audit_trails_button, #fulfillment_audit_trails_button', function () {
      atOpen($(this).data('id'));
    });

    // Action tab
    $modal.on('click', '.at-tab[data-tab]', function () {
      atActionTab = $(this).data('tab');
      atUpdateUI();
    });

    // Scope tab
    $modal.on('click', '.at-scope[data-scope]', function () {
      atScopeTab  = $(this).data('scope');
      atActionTab = 'all';
      atUpdateUI();
    });

    // Search
    $modal.on('input', '.at-search-input', function () {
      atSearch = $(this).val();
      atUpdateUI();
    });

    // Expand / collapse a run of auto-syncs
    $modal.on('click', '.at-run-summary', function () {
      var $list = $(this).closest('.at-run-wrap').find('.at-run-list');
      var open  = $list.is(':visible');
      $list.toggle(!open);
      $(this).toggleClass('is-open', !open);
      $(this).find('.at-run-toggle-text').text(open ? 'Show all' : 'Hide');
    });

    // Scroll-to-item link (closes modal, highlights row)
    $modal.on('click', '.audit_trail, .audit_trail_link', function (e) {
      e.preventDefault();
      var href   = $(this).attr('href');
      var target = $(this).data('target');
      $modal.modal('hide');
      var $el = href && href !== '#' ? $(href) : (target ? $(target) : $());
      if ($el.length) {
        $el.addClass('highlight');
        setTimeout(function () { $el.removeClass('highlight'); }, 5000);
      }
    });

    // Restore fulfillment item
    $modal.on('click', '.restore-item-btn', function () {
      var $btn = $(this);
      $btn.prop('disabled', true).text('Restoring…');
      $.ajax({
        url: '/rfq/fulfillment/restore_item',
        type: 'POST',
        data: {
          item_type:   $btn.data('item-type'),
          object_data: $btn.data('object-data'),
          parent_id:   $btn.data('parent-id'),
          rfq_id:      $btn.data('rfq-id')
        },
        success: function (res) {
          if (res.success) {
            $btn.replaceWith('<span class="text-success">✓ Restored</span>');
            $('#audit_trails_modal').modal('hide');
            location.reload();
          } else {
            $btn.prop('disabled', false).text('Restore');
            alert('Error restoring item: ' + (res.error || 'Unknown error'));
          }
        },
        error: function () {
          $btn.prop('disabled', false).text('Restore');
          alert('Error restoring item. Please try again.');
        }
      });
    });
  });

}(jQuery));
