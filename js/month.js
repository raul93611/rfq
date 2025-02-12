$(document).ready(function () {
  // Initialize tooltips
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'top',
  });

  // Function to initialize DataTable
  function initializeDataTable(selector, options) {
    const defaultOptions = {
      processing: true,
      serverSide: true,
      pageLength: 10,
      searching: false,
      order: [[1, 'asc']],
    };

    $(selector).DataTable($.extend(true, defaultOptions, options));
  }

  // DataTable for #month-table
  const monthTable = $('#month-table');
  initializeDataTable('#month-table', {
    ajax: {
      url: '/rfq/projection/month',
      type: 'POST',
      data: {
        id: monthTable.data('id'),
      },
    },
    columns: [
      { data: 'id_quote', visible: false },
      { data: 'invoice_date' },
      { data: 'id' },
      { data: 'type_of_contract' },
      { data: 'total_price' },
      { data: 'total_cost', visible: false },
      { data: 'profit', visible: false },
      { data: 'profit_percentage', visible: false },
      { data: 'sales_commission', visible: false },
      { data: 'total_profit' },
      { data: 'total_profit_percentage' },
      {
        data: 'invoice_acceptance',
        orderable: false,
        render: function (data, type, row) {
          if (type === 'display') {
            return `
              <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="${data}">
                <i class="fas fa-comment fa-2x"></i>
              </button>
              <button type="button" class="edit-invoice-acceptance-button btn btn-sm btn-warning" 
                data-id="${row.id}" 
                data-partial-invoice="${row.partial_invoice}">
                <i class="fas fa-pencil-alt"></i>
              </button>
            `;
          }
          return data;
        },
      },
      { data: 'partial_invoice', visible: false },
    ],
  });

  const editInvoiceAcceptanceModal = $('#edit-invoice-acceptance-modal');
  const editInvoiceAcceptanceForm = $('#edit-invoice-acceptance-form');

  // Event delegation for handling button click
  $('#month-table').on('click', '.edit-invoice-acceptance-button', function () {
    const id = $(this).data('id');
    const partialInvoice = $(this).data('partial-invoice');

    // Load form dynamically into the modal
    editInvoiceAcceptanceForm.load('/rfq/projection/invoice_acceptance', { id, partialInvoice }, () => {
      editInvoiceAcceptanceModal.modal('show');
    });
  });

  // Form validation and submission
  editInvoiceAcceptanceForm.validate({
    rules: {
      invoice_acceptance: {
        required: true,
      },
    },
    submitHandler: function (form) {
      // Submit form via AJAX
      $.ajax({
        url: '/rfq/projection/update_invoice_acceptance',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          // Hide modal, reload DataTable, and show success notification
          editInvoiceAcceptanceModal.modal('hide');
          $('#month-table').DataTable().ajax.reload(null, false);
          toastr.success('Invoice acceptance updated successfully!', 'Success');
        },
        error: function (xhr) {
          // Log error for debugging
          console.error('Error:', xhr.responseText || xhr.statusText);
          toastr.error('Failed to update invoice acceptance. Please try again.', 'Error');
        },
      });
    },
  });

  const totalsContainer = $('#totals-container');
  const totalsContainerId = totalsContainer.data('id');

  // Load totals dynamically
  totalsContainer.load('/rfq/projection/get_month_totals', { id: totalsContainerId });

  // Fetch and render charts for types of contracts
  $.ajax({
    url: "/rfq/projection/charts",
    type: "POST",
    data: { id: totalsContainerId },
    success: function (json) {
      if (!json || !json.typeOfContractData) {
        console.warn("No data returned for contract charts.");
        return;
      }

      // Transform the data for charts
      const transformedData = json.typeOfContractData.reduce((acc, item) => {
        acc.type_of_contract.push(item.type_of_contract);
        acc.value.push(item.value);
        acc.total_price.push(item.total_price);
        acc.total_profit.push(item.total_profit);
        acc.color.push(item.color);
        return acc;
      }, {
        type_of_contract: [],
        value: [],
        total_price: [],
        total_profit: [],
        color: []
      });

      // Log transformed data for debugging
      console.log(transformedData);

      // Helper function to create a pie chart
      const createPieChart = (elementId, label, data) => {
        const context = document.getElementById(elementId).getContext('2d');
        return new Chart(context, {
          type: 'pie',
          data: {
            labels: transformedData.type_of_contract,
            datasets: [{
              label,
              data,
              backgroundColor: transformedData.color,
              borderColor: transformedData.color,
              borderWidth: 1
            }]
          },
          options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
              legend: { position: 'top' },
              tooltip: {
                callbacks: {
                  label: function (context) {
                    const value = context.raw || 0;
                    const isContractCounts = context.dataset.label === 'Contract Counts';

                    // Format numbers based on chart type
                    const formattedValue = isContractCounts
                      ? value.toLocaleString('en-US') // Just add commas for counts
                      : `$${value.toLocaleString('en-US', { maximumFractionDigits: 2 })}`; // Currency format for amounts

                    return `${context.label || ''}: ${formattedValue}`;
                  }
                }
              }
            }
          }
        });
      };

      // Create Contract Counts Chart
      createPieChart('contract-counts', 'Contract Counts', transformedData.value);

      // Create Contract Amounts Chart
      createPieChart('contract-amounts', 'Contract Amounts', transformedData.total_price);

      // Create Contract Total Profit Chart
      createPieChart('contract-total-profit-amount', 'Contract Total Profit', transformedData.total_profit);
    },
    error: function (xhr) {
      console.error("Failed to fetch chart data:", xhr.responseText);
    }
  });
});