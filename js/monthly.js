$(document).ready(function () {
  const monthlyTable = $('#monthly-table');
  const monthlyDataTable = $('#monthly-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "pageLength": 50,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/projection/monthly',
      "type": "POST",
      "data": {
        "id": monthlyTable.data('id'),
      }
    },
    "columns": [
      {
        "data": "id",
        "visible": false
      },
      {
        "data": "month",
        "visible": false,
      },
      {
        "data": "month_name",
        "orderable": false,
      },
      {
        "data": "projected_amount",
        "orderable": false,
      },
      {
        "data": "total_price",
        "orderable": false,
      },
      {
        "data": "total_cost",
        "orderable": false,
      },
      {
        "data": "profit",
        "orderable": false,
      },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `
            <button data-id="${row.id}" class="edit-projected-amount-button btn btn-sm btn-warning">
              <i class="fas fa-pencil-alt"></i>
            </button>
            <a href="/rfq/perfil/projection/month/${row.id}" class="btn btn-sm btn-warning">
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

  const editProjectedAmountModal = $('#edit-projected-amount-modal');
  const editProjectedAmountForm = $('#edit-projected-amount-form');

  monthlyTable.on('click', '.edit-projected-amount-button', function () {
    editProjectedAmountForm.load(`/rfq/projection/projected_amount`, { id: $(this).data('id') }, () => {
      editProjectedAmountModal.modal();
    });
  });

  editProjectedAmountForm.validate({
    rules: {
      projected_amount: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/projection/update_projected_amount',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editProjectedAmountModal.modal('hide');
          monthlyDataTable.ajax.reload(null, false);
          toastr.success('Successfully saved', 'Success');
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });
});