$(document).ready(function () {
  const searchQuotesForm = $('#search_quotes');

  searchQuotesForm.submit((e) => {
    e.preventDefault();

    $('#tabla_busqueda').DataTable({
      "destroy": true,
      "searching": true,
      "processing": true,
      "serverSide": true,
      "pageLength": 10,
      "ajax": {
        "url": '/rfq/utilities/search_quotes',
        "type": "POST",
        "data": {
          "searchTerm": $('input[name="termino_busqueda"]').val(),
        }
      },
      "columns": [
        {
          "data": "id",
          "render": function (data, type, row, meta) {
            if (type === 'display') {
              return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
            } else {
              return data;
            }
          }
        },
        { "data": "email_code" },
        { "data": "nombre_usuario" },
        { "data": "type_of_bid" },
        { "data": "comments" },
        { "data": "total_price" },
        {
          "data": "options",
          "orderable": false,
          "render": function (data, type, row, meta) {
            if (type === 'display') {
              return `
              <a class="btn btn-sm calculate" href="/rfq/quote/proposal/${row.id}" target="_blank">
                <i class="fa fa-file"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-balance-scale"></i>
              </a>
              <a class="btn btn-secondary btn-sm" href="/rfq/quote/proposal_room/${row.id}" target="_blank">
                <i class="fa fa-th"></i>
              </a>
            `;
            } else {
              return data;
            }
          }
        }
      ]
    });
  });
});