$(document).ready(function () {
  // Initialize DataTable for Projections
  const projectionsTable = $('#projections-table');
  const projectionsDataTable = projectionsTable.DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, "asc"]],
    ajax: {
      url: '/rfq/projection/table',
      type: 'POST',
    },
    columns: [
      { data: "id", visible: false },
      { data: "year" },
      {
        data: "options",
        orderable: false,
        render: (data, type, row) => {
          if (type === 'display') {
            return `
              <a href="/rfq/perfil/projection/${row.id}" class="btn btn-sm btn-warning" title="View Projection Details">
                <i class="fas fa-info-circle"></i>
              </a>
            `;
          }
          return data;
        },
      },
    ],
  });

  // Handle Add Projection
  const addProjectionButton = $('#add-projection-button');

  addProjectionButton.click(() => {
    $.post('/rfq/projection/save')
      .done((response) => {
        if (response.data) {
          toastr.success('Projection for the current year created successfully!', 'Success');
        } else {
          toastr.warning('Current year projection already exists!', 'Duplicate Projection');
        }
        projectionsDataTable.ajax.reload(null, false); // Reload table without resetting pagination
      })
      .fail((xhr) => {
        console.error(xhr.responseText);
        toastr.error('An error occurred while creating the projection.', 'Error');
      });
  });
});