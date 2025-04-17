$(document).ready(function () {
  const searchQuotesForm = $('#search_quotes');

  // Initialize DataTable
  function initializeDataTable(searchTerm) {
    $('#tabla_busqueda').DataTable({
      destroy: true, // Allows reinitialization
      searching: true,
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

  // Form submission handler
  searchQuotesForm.on('submit', (e) => {
    e.preventDefault();
    const searchTerm = $('input[name="termino_busqueda"]').val();
    initializeDataTable(searchTerm);
  });
});