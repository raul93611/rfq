$(document).ready(function () {
  const $input = $('input[name="termino_busqueda"]');
  const MIN_CHARS = 3;
  const DEBOUNCE_MS = 350;
  let debounceTimer = null;
  let dataTable = null;
  let invoicesTable = null;

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

  function initializeDataTable(searchTerm) {
    if (dataTable) {
      dataTable.destroy();
    }
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
        {
          data: 'id',
          render: function (data, type) {
            if (type === 'display') {
              return `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`;
            }
            return data;
          },
        },
        { data: 'email_code' },
        { data: 'contract_number', defaultContent: '—' },
        { data: 'nombre_usuario' },
        { data: 'type_of_bid' },
        { data: 'comments' },
        { data: 'total_price' },
        {
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
        },
      ],
    });
  }

  function triggerSearch() {
    const term = $input.val().trim();
    if (term.length >= MIN_CHARS) {
      initializeDataTable(term);
      initializeInvoicesTable(term);
    }
  }

  // Live search with debounce
  $input.on('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(triggerSearch, DEBOUNCE_MS);
  });

  // Keep Enter key / button click working
  $('#search_quotes').on('submit', function (e) {
    e.preventDefault();
    clearTimeout(debounceTimer);
    triggerSearch();
  });
});
