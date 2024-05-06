$(document).ready(function () {
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'top',
  });

  const monthTable = $('#month-table');
  const monthDataTable = $('#month-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "pageLength": 10,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/projection/month',
      "type": "POST",
      "data": {
        "id": monthTable.data('id'),
      }
    },
    "columns": [
      {
        "data": "id_quote",
        "visible": false
      },
      { "data": "invoice_date" },
      { "data": "id" },
      { "data": "type_of_contract" },
      { "data": "total_price" },
      {
        "data": "total_cost",
        "visible": false
      },
      {
        "data": "profit",
        "visible": false
      },
      {
        "data": "profit_percentage",
        "visible": false
      },
      {
        "data": "sales_commission",
        "visible": false
      },
      { "data": "total_profit" },
      { "data": "total_profit_percentage" },
      {
        "data": "invoice_acceptance",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `
            <button type="button" class="btn btn-link" data-toggle="tooltip" data-html="true" title="${data}">
              <i class="fas fa-comment fa-2x"></i>
            </button>
            <button type="button" class="edit-invoice-acceptance-button btn btn-sm btn-warning" data-id="${row.id}" data-partial-invoice="${row.partial_invoice}">
              <i class="fas fa-pencil-alt"></i>
            </button>
          `;
          } else {
            return data;
          }
        }
      },
      {
        "data": "partial_invoice",
        "visible": false
      }
    ]
  });

  const editInvoiceAcceptanceModal = $('#edit-invoice-acceptance-modal');
  const editInvoiceAcceptanceForm = $('#edit-invoice-acceptance-form');

  monthTable.on('click', '.edit-invoice-acceptance-button', function () {
    editInvoiceAcceptanceForm.load(`/rfq/projection/invoice_acceptance`, {
      id: $(this).data('id'),
      partialInvoice: $(this).data('partial-invoice')
    }, () => {
      editInvoiceAcceptanceModal.modal();
    });
  });

  editInvoiceAcceptanceForm.validate({
    rules: {
      invoice_acceptance: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/projection/update_invoice_acceptance',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editInvoiceAcceptanceModal.modal('hide');
          monthDataTable.ajax.reload(null, false);
          toastr.success('Successfully saved', 'Success');
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //totals
  const totalsContainer = $('#totals-container');
  totalsContainer.load('/rfq/projection/get_month_totals', { id: totalsContainer.data('id') });

  //type of contracts charts
  $.ajax({
    url: "/rfq/projection/charts",
    type: "POST",
    data: {
      id: totalsContainer.data('id')
    },
    success: function (json) {
      const transformedData = {
        type_of_contract: [],
        value: [],
        total_price: [],
        color: []
      };

      json.typeOfContractData.forEach(item => {
        transformedData.type_of_contract.push(item.type_of_contract);
        transformedData.value.push(item.value);
        transformedData.total_price.push(item.total_price);
        transformedData.color.push(item.color);
      });

      console.log(transformedData);

      const contractCountsCanva = document.getElementById('contract-counts').getContext('2d');
      const contractCountsChart = new Chart(contractCountsCanva, {
        type: 'pie',
        data: {
          labels: transformedData.type_of_contract,
          datasets: [{
            label: 'Contract Counts',
            data: transformedData.value,
            backgroundColor: transformedData.color,
            borderColor: transformedData.color,
            borderWidth: 1
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              mode: 'index',
              intersect: true,
            }
          }
        }
      });

      const contractAmountsCanva = document.getElementById('contract-amounts').getContext('2d');
      const contractAmountsChart = new Chart(contractAmountsCanva, {
        type: 'pie',
        data: {
          labels: transformedData.type_of_contract,
          datasets: [{
            label: 'Contract Amounts',
            data: transformedData.total_price,
            backgroundColor: transformedData.color,
            borderColor: transformedData.color,
            borderWidth: 1
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              mode: 'index',
              intersect: true,
              callbacks: {
                label: function (context) {
                  let label = context.dataset.label || '';

                  if (label) {
                    label += ': ';
                  }
                  if (context.parsed.y !== null) {
                    label += '$' + context.parsed.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                  }
                  return label;
                }
              }
            }
          }
        }
      });
    },
    error: function (xhr, status, error) {
      console.error(xhr.responseText);
    }
  });
});