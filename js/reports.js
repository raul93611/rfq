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

    console.log(jsonData);

    const $table = $('<table>').attr('id', 'reportsTable').attr('class', 'table table-bordered table-hover');
    $('#report_results_container').append($table);
    const headerRow = $('<tr>').appendTo($('<thead>').appendTo($table));
    $('<th>').text('PROPOSAL').appendTo(headerRow);
    $('<th>').text('AWARD DATE').appendTo(headerRow);
    $('<th>').text('CONTRACT NUMBER').appendTo(headerRow);
    $('<th>').text('CODE').appendTo(headerRow);
    $('<th>').text('DESIGNATED USER').appendTo(headerRow);
    $('<th>').text('CHANNEL').appendTo(headerRow);
    $('<th>').text('TYPE OF BID').appendTo(headerRow);
    $('<th>').text('TOTAL COST').appendTo(headerRow);
    $('<th>').text('TOTAL PRICE').appendTo(headerRow);
    $('<th>').text('PROFIT').appendTo(headerRow);
    $('<th>').text('TYPE OF CONTRACT').appendTo(headerRow);

    table = $table.DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": '/rfq/quote/awards_report',
        "type": "POST",
        "data": jsonData
      },
      "columns": [
        {
          "data": "id",
          "render": function (data, type, row, meta) {
            if (type === 'display') {
              return '<a href="/rfq/perfil/quote/editar_cotizacion/'+data+'">' + data + '</a>';
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
      ]
    });
    // e.preventDefault();
    return false;
  });
});
