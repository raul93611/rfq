$(document).ready(function () {
  // Initialize DataTable
  const personnelTable = $('#personnel-table');
  const personnelDataTable = personnelTable.DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, "asc"]],
    ajax: {
      url: '/rfq/fulfillment/personnel/table',
      type: 'POST',
    },
    columns: [
      { data: "id", visible: false },
      { data: "name" },
      { data: "criteria" },
      { data: "type" },
      {
        data: "options",
        orderable: false,
        render: (data, type, row) => {
          if (type === 'display') {
            return `
              <button data-id="${row.id}" class="edit-personnel-button btn btn-sm btn-warning">
                <i class="fas fa-pen"></i>
              </button>
              <button data-id="${row.id}" class="delete-personnel-button btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
              </button>
            `;
          }
          return data;
        }
      },
    ],
  });

  // Add Personnel
  const addPersonnelModal = $('#add-personnel-modal');
  const addPersonnelForm = $('#add-personnel-form');

  $('#add-personnel-button').click(() => {
    addPersonnelForm[0].reset();
    addPersonnelModal.modal('show');
  });

  addPersonnelForm.validate({
    rules: {
      name: { required: true },
    },
    submitHandler: (form) => {
      $.post('/rfq/fulfillment/personnel/save', $(form).serialize())
        .done(() => {
          addPersonnelModal.modal('hide');
          personnelDataTable.ajax.reload(null, false);
          toastr.success('Personnel added successfully', 'Success');
        })
        .fail(() => {
          toastr.error('Failed to add personnel', 'Error');
        });
    },
  });

  // Edit Personnel
  const editPersonnelModal = $('#edit-personnel-modal');
  const editPersonnelForm = $('#edit-personnel-form');

  personnelTable.on('click', '.edit-personnel-button', function () {
    const personnelId = $(this).data('id');
    editPersonnelForm.load(`/rfq/fulfillment/personnel/load`, { id: personnelId }, () => {
      editPersonnelModal.modal('show');
    });
  });

  editPersonnelForm.validate({
    rules: {
      name: { required: true },
    },
    submitHandler: (form) => {
      $.post('/rfq/fulfillment/personnel/update', $(form).serialize())
        .done(() => {
          editPersonnelModal.modal('hide');
          personnelDataTable.ajax.reload(null, false);
          toastr.success('Personnel updated successfully', 'Success');
        })
        .fail(() => {
          toastr.error('Failed to update personnel', 'Error');
        });
    },
  });

  // Delete Personnel
  personnelTable.on('click', '.delete-personnel-button', function () {
    const personnelId = $(this).data('id');
    $.ajax({
      url: '/rfq/fulfillment/personnel/delete',
      type: 'POST',
      data: { id: personnelId },
      success: () => {
        personnelDataTable.ajax.reload(null, false);
        toastr.success('Personnel deleted successfully', 'Success');
      },
      error: () => {
        toastr.error('Failed to delete personnel', 'Error');
      },
    });
  });
});