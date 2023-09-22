$(document).ready(function () {
  const personnelTable = $('#personnel-table');
  const personnelDataTable = $('#personnel-table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/fulfillment/personnel/table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "visible": false
      },
      { "data": "name" },
      { "data": "criteria" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `
            <button data-id="${row.id}" class="edit-personnel-button btn btn-sm btn-warning">
              <i class="fas fa-pen"></i>
            </button>
            <button data-id="${row.id}" class="delete-personnel-button btn btn-sm btn-danger">
              <i class="fas fa-trash"></i>
            </button>
            `;
          } else {
            return data;
          }
        }
      },
    ]
  });

  const addPersonnelButton = $('#add-personnel-button');
  const addPersonnelModal = $('#add-personnel-modal');
  const addPersonnelForm = $('#add-personnel-form');

  addPersonnelButton.click(() => {
    addPersonnelForm[0].reset();
    addPersonnelModal.modal();
  });

  addPersonnelForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/personnel/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addPersonnelModal.modal('hide');
          personnelDataTable.ajax.reload(null, false);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  const editPersonnelModal = $('#edit-personnel-modal');
  const editPersonnelForm = $('#edit-personnel-form');

  personnelTable.on('click', '.edit-personnel-button', function () {
    editPersonnelForm.load(`/rfq/fulfillment/personnel/load`, { id: $(this).data('id') }, () => {
      editPersonnelModal.modal();
    });
  });

  editPersonnelForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/personnel/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editPersonnelModal.modal('hide');
          personnelDataTable.ajax.reload(null, false);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  personnelTable.on('click', '.delete-personnel-button', function (e) {
    $.ajax({
      url: '/rfq/fulfillment/personnel/delete',
      data: {
        id: $(this).data('id'),
      },
      type: 'POST',
      success: function (res) {
        personnelDataTable.ajax.reload(null, false);
      }
    });
  });
});