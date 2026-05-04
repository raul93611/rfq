$(document).ready(function () {
  const monthlyTable = $('#monthly-table');
  const monthlyTableId = monthlyTable.data('id');

  // Initialize DataTable
  const monthlyDataTable = monthlyTable.DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    pageLength: 50,
    order: [[1, "asc"]],
    ajax: {
      url: '/rfq/projection/monthly',
      type: 'POST',
      data: { id: monthlyTableId }
    },
    columns: [
      { data: "id", visible: false },
      { data: "month", visible: false },
      { data: "month_name", orderable: false },
      { data: "projected_amount", orderable: false },
      {
        data: "projected_result",
        orderable: false,
        render: (data) => {
          if (!data) return '';
          const value = parseFloat(data.replace(/,/g, ''));
          const textClass = value > 0 ? 'text-success' : 'text-danger';
          return `<span class="${textClass}">${data}</span>`;
        }
      },
      { data: "total_price", orderable: false },
      { data: "total_cost", orderable: false, visible: false },
      { data: "profit", orderable: false },
      {
        data: "options",
        orderable: false,
        render: (data, type, row) => {
          if (type === 'display') {
            return `
              <button 
                data-id="${row.id}" 
                class="edit-projected-amount-button btn btn-sm btn-secondary"
                title="Edit Projected Amount"
              >
                <i class="fas fa-pencil-alt"></i>
              </button>
              <a 
                href="/rfq/perfil/projection/month/${row.id}" 
                class="btn btn-sm btn-secondary"
                title="View Projection Details"
              >
                <i class="fas fa-info-circle"></i>
              </a>
            `;
          }
          return data;
        }
      }
    ]
  });

  const editProjectedAmountModal = $('#edit-projected-amount-modal');
  const editProjectedAmountForm = $('#edit-projected-amount-form');
  const totalsContainer = $('#totals-container');

  // Load totals dynamically
  const loadTotals = () => {
    totalsContainer.load('/rfq/projection/get_totals', { id: monthlyTableId });
  };
  loadTotals(); // Initial load

  // Handle edit button click for projected amounts
  monthlyTable.on('click', '.edit-projected-amount-button', function () {
    const rowId = $(this).data('id');
    editProjectedAmountForm.load(`/rfq/projection/projected_amount`, { id: rowId }, () => {
      editProjectedAmountModal.modal('show');
    });
  });

  // Initialize form validation and submission
  editProjectedAmountForm.validate({
    rules: {
      projected_amount: {
        required: true,
        number: true, // Ensures the input is numeric
        min: 0 // Prevents negative amounts
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/projection/update_projected_amount',
        type: 'POST',
        data: $(form).serialize(),
        success: function () {
          editProjectedAmountModal.modal('hide');
          monthlyDataTable.ajax.reload(null, false); // Reload table data
          loadTotals(); // Reload totals
          toastr.success('Projected amount updated successfully', 'Success');
        },
        error: function (xhr) {
          console.error('Error updating projected amount:', xhr.responseText);
          toastr.error('Failed to update projected amount', 'Error');
        }
      });
    }
  });
});