$(document).ready(function () {
  const $input = $('input[name="termino_busqueda"]');
  const MIN_CHARS = 3;
  const DEBOUNCE_MS = 350;
  let debounceTimer = null;
  let dataTable = null;
  let invoicesTable = null;

  // ---------- advanced mode state ----------
  let advanced = false;
  const statusByKey = {};
  (window.SQ_STATUSES || []).forEach(function (s) { statusByKey[s.key] = s; });
  let selectedStatuses = [];

  const $toggle = $('#sq_adv_toggle');
  const $reveal = $('#sq_filters_reveal');
  const $searchCol = $('#sq_search_col');
  const $invoicesCard = $('#sq_invoices_card');
  const $help = $('#sq_search_help');
  const $advCount = $('#sq_adv_count');

  function pillHtml(key) {
    const st = statusByKey[key];
    if (!st) return key || '—';
    return `<span class="sq-pill" style="--c:${st.color}"><span class="sq-pill-dot"></span>${st.label}</span>`;
  }

  function collectFilters() {
    return {
      statuses: selectedStatuses.slice(),
      f_user: $('#sq_f_user').val() || '',
      f_bid_type: $('#sq_f_bid_type').val() || '',
      f_contract_type: $('#sq_f_contract_type').val() || '',
      f_date_field: $('#sq_date_field_seg .is-on').data('date-field') || 'created',
      f_date_from: $('#sq_f_date_from').val() || '',
      f_date_to: $('#sq_f_date_to').val() || '',
      f_price_min: $('#sq_f_price_min').val() || '',
      f_price_max: $('#sq_f_price_max').val() || '',
      f_client: $('#sq_f_client').val().trim(),
      f_state: $('#sq_f_state').val().trim(),
    };
  }

  function activeFilterCount() {
    const f = collectFilters();
    let n = 0;
    if (f.statuses.length) n++;
    if (f.f_user) n++;
    if (f.f_bid_type) n++;
    if (f.f_contract_type) n++;
    if (f.f_date_from || f.f_date_to) n++;
    if (f.f_price_min !== '' || f.f_price_max !== '') n++;
    if (f.f_client) n++;
    if (f.f_state) n++;
    return n;
  }

  function refreshFilterChrome() {
    const n = activeFilterCount();
    $advCount.toggle(advanced && n > 0).text(n);
    $('#sq_filters_hint').text(n === 0 ? 'No filters set — all quotes match' : n + ' filter' + (n > 1 ? 's' : '') + ' applied');
    $('#sq_clear_filters').prop('disabled', n === 0);
  }

  // ---------- status multi-select ----------
  const $msTrigger = $('#sq_status_trigger');
  const $msMenu = $('#sq_status_menu');

  function renderStatusSummary() {
    const $summary = $('#sq_status_summary');
    if (selectedStatuses.length === 0) {
      $summary.html('<span class="sq-ms-placeholder">Any status</span>');
    } else if (selectedStatuses.length <= 2) {
      $summary.html('<span class="sq-ms-pills">' + selectedStatuses.map(pillHtml).join('') + '</span>');
    } else {
      $summary.html('<span class="sq-ms-pills">' + pillHtml(selectedStatuses[0])
        + '<span class="sq-ms-more">+' + (selectedStatuses.length - 1) + ' more</span></span>');
    }
    $('#sq_status_menu_count').text(selectedStatuses.length === 0 ? 'All statuses match' : selectedStatuses.length + ' selected');
    $('#sq_status_clear').toggle(selectedStatuses.length > 0);
    $msMenu.find('.sq-ms-opt').each(function () {
      const on = selectedStatuses.indexOf($(this).data('status')) !== -1;
      $(this).toggleClass('is-on', on).find('.sq-ms-check').toggleClass('is-on', on);
    });
  }

  $msTrigger.on('click', function () {
    const open = $msMenu.is(':visible');
    $msMenu.toggle(!open);
    $msTrigger.toggleClass('is-open', !open);
  });
  $(document).on('mousedown', function (e) {
    if (!$(e.target).closest('#sq_status_field').length) {
      $msMenu.hide();
      $msTrigger.removeClass('is-open');
    }
  });
  $msMenu.on('click', '.sq-ms-opt', function () {
    const key = $(this).data('status');
    const i = selectedStatuses.indexOf(key);
    if (i === -1) selectedStatuses.push(key); else selectedStatuses.splice(i, 1);
    renderStatusSummary();
    refreshFilterChrome();
    scheduleSearch();
  });
  $('#sq_status_clear').on('click', function () {
    selectedStatuses = [];
    renderStatusSummary();
    refreshFilterChrome();
    scheduleSearch();
  });

  // ---------- tables ----------
  function initializeInvoicesTable(searchTerm) {
    if (invoicesTable) {
      invoicesTable.destroy();
    }
    invoicesTable = $('#tabla_invoices').DataTable({
      searching: false,
      processing: true,
      serverSide: true,
      pageLength: 10,
      order: [[1, 'desc']],
      ajax: {
        url: '/rfq/utilities/search_invoices',
        type: 'POST',
        data: { searchTerm },
      },
      columns: [
        {
          data: 'invoice_name',
          render: function (data, type) {
            if (type === 'display') {
              return `<span class="badge badge-info" style="font-size:13px; font-weight:600;">${data}</span>`;
            }
            return data;
          },
        },
        { data: 'invoice_date' },
        {
          data: 'quote_id',
          render: function (data, type) {
            if (type === 'display') {
              return `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`;
            }
            return data;
          },
        },
        { data: 'nombre_usuario' },
      ],
      createdRow: function (row) {
        $(row).addClass('table-info');
      },
    });
  }

  const optionsColumn = {
    data: 'options',
    orderable: false,
    render: function (data, type, row) {
      if (type === 'display') {
        return `
          <a class="btn btn-sm btn-secondary" data-toggle="tooltip" data-original-title="Proposal" href="/rfq/quote/proposal/${row.id}" target="_blank">
            <i class="fa fa-file"></i>
          </a>
          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-original-title="GSA Proposal" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
            <i class="fa fa-balance-scale"></i>
          </a>
          <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-original-title="Rooms" href="/rfq/quote/proposal_room/${row.id}" target="_blank">
            <i class="fa fa-th"></i>
          </a>
        `;
      }
      return data;
    },
  };

  const proposalColumn = {
    data: 'id',
    render: function (data, type) {
      if (type === 'display') {
        return `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`;
      }
      return data;
    },
  };

  /** Quotes table thead must match the column defs: Status th only in advanced mode. */
  function syncStatusHeader(show) {
    const $thead = $('#tabla_busqueda thead tr');
    const exists = $thead.find('th.sq-status-th').length > 0;
    if (show && !exists) {
      $('<th class="sq-status-th">Status</th>').insertAfter($thead.find('th').eq(4));
    } else if (!show && exists) {
      $thead.find('th.sq-status-th').remove();
    }
  }

  function destroyQuotesTable() {
    if (dataTable) {
      dataTable.destroy();
      dataTable = null;
      $('#tabla_busqueda tbody').empty();
    }
  }

  function initializeDataTable(searchTerm) {
    destroyQuotesTable();
    syncStatusHeader(false);
    dataTable = $('#tabla_busqueda').DataTable({
      searching: false,
      processing: true,
      serverSide: true,
      pageLength: 10,
      ajax: {
        url: '/rfq/utilities/search_quotes',
        type: 'POST',
        data: { searchTerm },
      },
      columns: [
        proposalColumn,
        { data: 'email_code' },
        { data: 'contract_number', defaultContent: '—' },
        { data: 'nombre_usuario' },
        { data: 'type_of_bid' },
        { data: 'comments' },
        { data: 'total_price' },
        optionsColumn,
      ],
    });
  }

  function initializeAdvancedTable(searchTerm) {
    destroyQuotesTable();
    syncStatusHeader(true);
    dataTable = $('#tabla_busqueda').DataTable({
      searching: false,
      processing: true,
      serverSide: true,
      pageLength: 10,
      language: {
        zeroRecords:
          '<div class="sq-empty">' +
          '<div class="sq-empty-icon"><i class="fas fa-inbox"></i></div>' +
          '<div class="sq-empty-title">No matching records</div>' +
          '<div class="sq-empty-sub">Try removing a filter or broadening the date or price range.</div>' +
          '</div>',
      },
      ajax: {
        url: '/rfq/utilities/search_quotes',
        type: 'POST',
        data: function (d) {
          d.searchTerm = searchTerm;
          d.advanced = '1';
          const f = collectFilters();
          Object.keys(f).forEach(function (k) { d[k] = f[k]; });
        },
      },
      columns: [
        proposalColumn,
        { data: 'email_code' },
        { data: 'contract_number', defaultContent: '—' },
        { data: 'nombre_usuario' },
        { data: 'type_of_bid' },
        {
          data: 'derived_status',
          render: function (data, type) {
            if (type === 'display') return pillHtml(data);
            return data;
          },
        },
        { data: 'comments' },
        { data: 'total_price' },
        optionsColumn,
      ],
    });
  }

  // ---------- search triggers ----------
  function triggerSearch() {
    const term = $input.val().trim();
    if (advanced) {
      // Keyword optional in advanced mode; below the minimum it's ignored.
      initializeAdvancedTable(term.length >= MIN_CHARS ? term : '');
    } else if (term.length >= MIN_CHARS) {
      initializeDataTable(term);
      initializeInvoicesTable(term);
    }
  }

  function scheduleSearch() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(triggerSearch, DEBOUNCE_MS);
  }

  // Live search with debounce
  $input.on('input', scheduleSearch);

  // Keep Enter key / button click working
  $('#search_quotes').on('submit', function (e) {
    e.preventDefault();
    clearTimeout(debounceTimer);
    triggerSearch();
  });

  // ---------- filter wiring ----------
  $('#sq_f_user, #sq_f_bid_type, #sq_f_contract_type').on('change', function () {
    $(this).toggleClass('is-placeholder', $(this).val() === '');
    refreshFilterChrome();
    scheduleSearch();
  });
  $('#sq_f_date_from, #sq_f_date_to').on('change', function () {
    refreshFilterChrome();
    scheduleSearch();
  });
  $('#sq_f_price_min, #sq_f_price_max, #sq_f_client, #sq_f_state').on('input', function () {
    refreshFilterChrome();
    scheduleSearch();
  });
  $('#sq_date_field_seg').on('click', '.sq-seg-btn', function () {
    $('#sq_date_field_seg .sq-seg-btn').removeClass('is-on');
    $(this).addClass('is-on');
    refreshFilterChrome();
    // Date field only matters when a range is set.
    if ($('#sq_f_date_from').val() || $('#sq_f_date_to').val()) scheduleSearch();
  });

  function clearFilters() {
    selectedStatuses = [];
    renderStatusSummary();
    $('#sq_f_user, #sq_f_bid_type, #sq_f_contract_type').val('').addClass('is-placeholder');
    $('#sq_f_date_from, #sq_f_date_to, #sq_f_price_min, #sq_f_price_max, #sq_f_client, #sq_f_state').val('');
    $('#sq_date_field_seg .sq-seg-btn').removeClass('is-on').filter('[data-date-field="created"]').addClass('is-on');
    refreshFilterChrome();
  }

  $('#sq_clear_filters').on('click', function () {
    clearFilters();
    scheduleSearch();
  });

  // ---------- advanced toggle ----------
  $reveal.on('transitionend', function (e) {
    if (advanced && e.originalEvent.propertyName === 'max-height') {
      $reveal.addClass('is-settled');
    }
  });

  $toggle.on('click', function () {
    advanced = !advanced;
    clearTimeout(debounceTimer);
    $toggle.toggleClass('is-on', advanced).attr('aria-expanded', advanced);
    $reveal.toggleClass('is-open', advanced).removeClass('is-settled');
    $searchCol.toggleClass('col-lg-6 col-md-8', !advanced).toggleClass('col-lg-11 col-xl-10', advanced);
    $invoicesCard.toggle(!advanced);
    $input.attr('placeholder', advanced ? 'Keyword (optional in advanced search)' : 'Type at least 3 characters to search...');
    $help.text(advanced
      ? 'Keyword is optional — combine any filters below, or leave everything blank to browse all quotes.'
      : 'Results appear automatically after 3 characters.');

    if (advanced) {
      if (invoicesTable) {
        invoicesTable.destroy();
        invoicesTable = null;
        $('#tabla_invoices tbody').empty();
      }
      refreshFilterChrome();
      triggerSearch();
    } else {
      // Collapsing resets filters and restores the basic page.
      clearFilters();
      $advCount.hide();
      $msMenu.hide();
      $msTrigger.removeClass('is-open');
      destroyQuotesTable();
      syncStatusHeader(false);
      triggerSearch();
    }
  });
});
