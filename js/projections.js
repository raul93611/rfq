$(document).ready(function () {
  const projectionsTable = $('#projections-table');
  const projectionsDataTable = $('#projections-table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/projection/table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "visible": false
      },
      { "data": "year" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `
            <a href="/rfq/perfil/projection/${row.id}" class="btn btn-sm btn-warning">
              <i class="fas fa-info-circle"></i>
            </a>
            `;
          } else {
            return data;
          }
        }
      },
    ]
  });

  const addProjectionButton = $('#add-projection-button');

  addProjectionButton.click(() => {
    $.ajax({
      url: '/rfq/projection/save',
      type: 'POST',
      success: function (response) {
        if (!response.data) {
          toastr.error('Current year projection already created!', 'Failed to create');
        }
        projectionsDataTable.ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  });
});