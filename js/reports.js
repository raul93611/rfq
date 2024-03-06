$(document).ready(function () {
  const quarterSelect = $('select[name="quarter"]');
  const monthSelect = $('select[name="month"]');
  const yearSelect = $('select[name="year"]');
  const monthlyOption = $('#monthly');
  const quarterlyOption = $('#quarterly');
  const yearlyOption = $('#yearly');
  const reportSelect = $('#report_select');
  const typeInput = $('input[name="type"]');

  function checkFulfillmentReport() {
    let isSelected = false;
    if (reportSelect.find('[value="fulfillment_pending"]').is(':selected')) {
      monthSelect.attr('disabled', true);
      yearSelect.attr('disabled', true);
      quarterSelect.attr('disabled', true);
      monthSelect.show();
      quarterSelect.hide();
      isSelected = true;
    }
    return isSelected;
  }

  function monthly() {
    monthSelect.attr('disabled', false);
    quarterSelect.attr('disabled', false);
    yearSelect.attr('disabled', false);
    monthSelect.show();
    quarterSelect.hide();
    typeInput.val('monthly');
  }

  function quarterly() {
    monthSelect.attr('disabled', false);
    quarterSelect.attr('disabled', false);
    yearSelect.attr('disabled', false);
    monthSelect.hide();
    quarterSelect.show();
    typeInput.val('quarterly');
  }

  function yearly() {
    monthSelect.attr('disabled', true);
    quarterSelect.attr('disabled', true);
    yearSelect.attr('disabled', false);
    monthSelect.show();
    quarterSelect.hide();
    typeInput.val('yearly');
  }

  function checkReportType() {
    let isSelected = checkFulfillmentReport();
    if (!isSelected) {
      if (monthlyOption.hasClass('active')) {
        monthly();
      } else if (quarterlyOption.hasClass('active')) {
        quarterly();
      } else if (yearlyOption.hasClass('active')) {
        yearly();
      }
    }
  }

  checkReportType();

  monthlyOption.click(function () {
    let isSelected = checkFulfillmentReport();
    if (!isSelected) {
      monthly();
    }
  });

  quarterlyOption.click(function () {
    let isSelected = checkFulfillmentReport();
    if (!isSelected) {
      quarterly();
    }
  });

  yearlyOption.click(function () {
    let isSelected = checkFulfillmentReport();
    if (!isSelected) {
      yearly();
    }
  });

  reportSelect.change(function () {
    checkReportType();
  });


  const generateButton = $('span[data="generate_report"]');
  const reportsForm = $('#reports_form');

  generateButton.click(function (e) {
    $('#reportsTable_wrapper').remove();

    const formData = reportsForm.serializeArray();
    const jsonData = {};

    $(formData).each(function (index, obj) {
      jsonData[obj.name] = obj.value;
    });

    let $table;
    let columns;
    let headerRow;
    switch (jsonData.report) {
      case 'award':
        $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
        $('#report_results_container').append($table);
        headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
        $('<th>').text('PROPOSAL').appendTo(headerRow);
        $('<th>').text('AWARD DATE').appendTo(headerRow);
        $('<th>').text('CONTRACT NUMBER').appendTo(headerRow);
        $('<th>').text('CODE').appendTo(headerRow);
        $('<th>').text('DESIGNATED USER').appendTo(headerRow);
        $('<th>').text('CHANNEL').appendTo(headerRow);
        $('<th>').text('TYPE OF BID').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('PROFIT').appendTo(headerRow);
        $('<th>').text('TYPE OF CONTRACT').appendTo(headerRow);

        columns = [
          {
            "data": "id",
            "render": function (data, type, row, meta) {
              if (type === 'display') {
                return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
              } else {
                return data;
              }
            }
          },
          { "data": "fecha_award" },
          { "data": "contract_number" },
          { "data": "email_code" },
          { "data": "nombre_usuario" },
          { "data": "canal" },
          { "data": "type_of_bid" },
          { "data": "total_cost" },
          { "data": "total_price" },
          { "data": "profit" },
          { "data": "type_of_contract" }
        ];
        break;
      case 'submitted':
        $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
        $('#report_results_container').append($table);
        headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
        $('<th>').text('PROPOSAL').appendTo(headerRow);
        $('<th>').text('SUBMITTED DATE').appendTo(headerRow);
        $('<th>').text('CODE').appendTo(headerRow);
        $('<th>').text('DESIGNATED USER').appendTo(headerRow);
        $('<th>').text('CHANNEL').appendTo(headerRow);
        $('<th>').text('TYPE OF BID').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('PROFIT').appendTo(headerRow);
        $('<th>').text('TYPE OF CONTRACT').appendTo(headerRow);

        columns = [
          {
            "data": "id",
            "render": function (data, type, row, meta) {
              if (type === 'display') {
                return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
              } else {
                return data;
              }
            }
          },
          { "data": "fecha_submitted" },
          { "data": "email_code" },
          { "data": "nombre_usuario" },
          { "data": "canal" },
          { "data": "type_of_bid" },
          { "data": "total_cost" },
          { "data": "total_price" },
          { "data": "profit" },
          { "data": "type_of_contract" }
        ];
        break;
      case 'fulfillment':
        $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
        $('#report_results_container').append($table);
        headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
        $('<th>').text('PROPOSAL').appendTo(headerRow);
        $('<th>').text('FULFILLMENT DATE').appendTo(headerRow);
        $('<th>').text('CONTRACT NUMBER').appendTo(headerRow);
        $('<th>').text('CODE').appendTo(headerRow);
        $('<th>').text('DESIGNATED USER').appendTo(headerRow);
        $('<th>').text('CHANNEL').appendTo(headerRow);
        $('<th>').text('TYPE OF BID').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('PROFIT').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('PROFIT').appendTo(headerRow);
        $('<th>').text('TYPE OF CONTRACT').appendTo(headerRow);

        columns = [
          {
            "data": "id",
            "render": function (data, type, row, meta) {
              if (type === 'display') {
                return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
              } else {
                return data;
              }
            }
          },
          { "data": "fulfillment_date" },
          { "data": "contract_number" },
          { "data": "email_code" },
          { "data": "nombre_usuario" },
          { "data": "canal" },
          { "data": "type_of_bid" },
          { "data": "total_cost" },
          { "data": "total_price" },
          { "data": "profit" },
          { "data": "total_cost_requote" },
          { "data": "total_price_requote" },
          { "data": "profit_requote" },
          { "data": "type_of_contract" }
        ];
        break;
      case 'accounts-payable-fulfillment':
        $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
        $('#report_results_container').append($table);
        headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
        $('<th>').text('PROPOSAL').appendTo(headerRow);
        $('<th>').text('PROVIDER').appendTo(headerRow);
        $('<th>').text('REAL COST').appendTo(headerRow);
        $('<th>').text('PAYMENT TERMS').appendTo(headerRow);
        $('<th>').text('CREATED AT').appendTo(headerRow);

        columns = [
          {
            "data": "id",
            "render": function (data, type, row, meta) {
              if (type === 'display') {
                return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
              } else {
                return data;
              }
            }
          },
          { "data": "provider" },
          { "data": "real_cost" },
          { "data": "payment_term" },
          { "data": "created_at" }
        ];
        break;
      case 'sales-commission':
        $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
        $('#report_results_container').append($table);
        headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
        $('<th>').text('PROPOSAL').appendTo(headerRow);
        $('<th>').text('INVOICE DATE').appendTo(headerRow);
        $('<th>').text('CONTRACT NUMBER').appendTo(headerRow);
        $('<th>').text('CODE').appendTo(headerRow);
        $('<th>').text('DESIGNATED USER').appendTo(headerRow);
        $('<th>').text('STATE').appendTo(headerRow);
        $('<th>').text('CLIENT').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-primary').text('PROFIT').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-warning').text('PROFIT').appendTo(headerRow);
        $('<th>').attr('class', 'bg-danger').text('TOTAL COST').appendTo(headerRow);
        $('<th>').attr('class', 'bg-danger').text('TOTAL PRICE').appendTo(headerRow);
        $('<th>').attr('class', 'bg-danger').text('PROFIT').appendTo(headerRow);
        $('<th>').text('TYPE OF CONTRACT').appendTo(headerRow);
        $('<th>').text('SALES COMMISSION').appendTo(headerRow);

        columns = [
          {
            "data": "id",
            "render": function (data, type, row, meta) {
              if (type === 'display') {
                return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
              } else {
                return data;
              }
            }
          },
          { "data": "invoice_date" },
          { "data": "contract_number" },
          { "data": "email_code" },
          { "data": "nombre_usuario" },
          { "data": "state" },
          { "data": "client" },
          { "data": "total_cost" },
          { "data": "total_price" },
          { "data": "profit" },
          { "data": "total_cost_requote" },
          { "data": "total_price_requote" },
          { "data": "profit_requote" },
          { "data": "total_cost_fulfillment" },
          { "data": "total_price_fulfillment" },
          { "data": "profit_fulfillment" },
          { "data": "type_of_contract" },
          { "data": "sales_commission" },
        ];
        break;
      default:
        break;
    }

    table = $table.DataTable({
      "responsive": true,
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": '/rfq/quote/reports',
        "type": "POST",
        "data": jsonData
      },
      "columns": columns,
      "initComplete": function (settings, json) {
        $('#report_results_container > div > div').eq(1).addClass('table-responsive');
        $('#report_results_container #reportsTable_wrapper').prepend(`
        <div class="my-3">
          <i class="fas fa-square text-primary"></i> Quote <br>
          <i class="fas fa-square text-warning"></i> Re-Quote <br>
          <i class="fas fa-square text-danger"></i> Fulfillment
        </div>
        `);
      }
    });

    return false;
  });
});
