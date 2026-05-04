$(document).ready(function () {
  const typeOfProjectTable = $('#type-of-project-table');
  const typeOfProjectDataTable = typeOfProjectTable.DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    order: [[1, 'asc']],
    ajax: {
      url: '/rfq/fulfillment/type_of_project/table',
      type: 'POST',
    },
    columns: [
      { data: 'id', visible: false },
      { data: 'name' },
      {
        data: 'options',
        orderable: false,
        render: (data, type, row) => {
          if (type === 'display') {
            return `
              <button data-id="${row.id}" class="edit-type-of-project-button btn btn-sm btn-secondary">
                <i class="fas fa-pen"></i>
              </button>
              <button data-id="${row.id}" class="delete-type-of-project-button btn btn-sm btn-secondary">
                <i class="fas fa-trash"></i>
              </button>
            `;
          }
          return data;
        },
      },
    ],
  });

  // Utility function to reset and open a modal
  function openModal(modal, form = null) {
    if (form) form[0].reset();
    modal.modal('show');
  }

  // Utility function for AJAX form submission
  function handleFormSubmission(form, url, onSuccess) {
    form.validate({
      rules: {
        name: {
          required: true,
        },
      },
      submitHandler: (validatedForm) => {
        $.ajax({
          url,
          type: 'POST',
          data: $(validatedForm).serialize(),
          success: onSuccess,
          error: (xhr, status, error) => {
            console.error(error);
          },
        });
      },
    });
  }

  // Add Type of Project
  const addTypeOfProjectModal = $('#add-type-of-project-modal');
  const addTypeOfProjectForm = $('#add-type-of-project-form');
  $('#add-type-of-project-button').click(() => openModal(addTypeOfProjectModal, addTypeOfProjectForm));

  handleFormSubmission(addTypeOfProjectForm, '/rfq/fulfillment/type_of_project/save', () => {
    addTypeOfProjectModal.modal('hide');
    typeOfProjectDataTable.ajax.reload(null, false);
  });

  // Edit Type of Project
  const editTypeOfProjectModal = $('#edit-type-of-project-modal');
  const editTypeOfProjectForm = $('#edit-type-of-project-form');

  typeOfProjectTable.on('click', '.edit-type-of-project-button', function () {
    const typeId = $(this).data('id');
    editTypeOfProjectForm.load(`/rfq/fulfillment/type_of_project/load`, { id: typeId }, () => {
      editTypeOfProjectModal.modal('show');
    });
  });

  handleFormSubmission(editTypeOfProjectForm, '/rfq/fulfillment/type_of_project/update', () => {
    editTypeOfProjectModal.modal('hide');
    typeOfProjectDataTable.ajax.reload(null, false);
  });

  // Delete Type of Project
  typeOfProjectTable.on('click', '.delete-type-of-project-button', function () {
    const typeId = $(this).data('id');
    $.ajax({
      url: '/rfq/fulfillment/type_of_project/delete',
      type: 'POST',
      data: { id: typeId },
      success: () => {
        typeOfProjectDataTable.ajax.reload(null, false);
      },
      error: (xhr, status, error) => {
        console.error(error);
      },
    });
  });
});