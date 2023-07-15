$(document).ready(function () {
  const searchQuotesForm = $('#search_quotes');

  searchQuotesForm.submit((e) => {
    e.preventDefault();

    $('#tabla_busqueda').DataTable({
      "destroy": true,
      "searching": false,
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
                <i class="fa fa-copy"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
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