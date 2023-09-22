$(document).ready(function () {
  const typeOfProjectTable = $('#type-of-project-table');
  const typeOfProjectDataTable = $('#type-of-project-table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/fulfillment/type_of_project/table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "visible": false
      },
      { "data": "name" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `
            <button data-id="${row.id}" class="edit-type-of-project-button btn btn-sm btn-warning">
              <i class="fas fa-pen"></i>
            </button>
            <button data-id="${row.id}" class="delete-type-of-project-button btn btn-sm btn-danger">
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

  const addTypeOfProjectButton = $('#add-type-of-project-button');
  const addTypeOfProjectModal = $('#add-type-of-project-modal');
  const addTyepOfProjectForm = $('#add-type-of-project-form');

  addTypeOfProjectButton.click(() => {
    addTyepOfProjectForm[0].reset();
    addTypeOfProjectModal.modal();
  });

  addTyepOfProjectForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/type_of_project/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addTypeOfProjectModal.modal('hide');
          typeOfProjectDataTable.ajax.reload(null, false);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  const editTypeOfProjectModal = $('#edit-type-of-project-modal');
  const editTypeOfProjectForm = $('#edit-type-of-project-form');

  typeOfProjectTable.on('click', '.edit-type-of-project-button', function () {
    console.log('asdfasdf');
    editTypeOfProjectForm.load(`/rfq/fulfillment/type_of_project/load`, { id: $(this).data('id') }, () => {
      editTypeOfProjectModal.modal();
    });
  });

  editTypeOfProjectForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/type_of_project/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editTypeOfProjectModal.modal('hide');
          typeOfProjectDataTable.ajax.reload(null, false);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  typeOfProjectTable.on('click', '.delete-type-of-project-button', function (e) {
    $.ajax({
      url: '/rfq/fulfillment/type_of_project/delete',
      data: {
        id: $(this).data('id'),
      },
      type: 'POST',
      success: function (res) {
        typeOfProjectDataTable.ajax.reload(null, false);
      }
    });
  });
});