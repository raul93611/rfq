$(document).ready(function () {
  // DOM Elements
  const quarterSelect = $('select[name="quarter"]');
  const monthSelect = $('select[name="month"]');
  const yearSelect = $('select[name="year"]');
  const monthlyOption = $('#monthly');
  const quarterlyOption = $('#quarterly');
  const yearlyOption = $('#yearly');
  const reportSelect = $('#report_select');
  const typeInput = $('input[name="type"]');

  // Helper to toggle visibility and enable/disable attributes
  const toggleSelectOptions = (monthVisible, quarterVisible, yearEnabled) => {
    monthSelect.toggle(monthVisible).attr('disabled', !monthVisible);
    quarterSelect.toggle(quarterVisible).attr('disabled', !quarterVisible);
    yearSelect.attr('disabled', !yearEnabled);
  };

  // Check if "fulfillment_pending" report is selected
  const isFulfillmentPendingSelected = () => {
    const isSelected = reportSelect.find('[value="fulfillment_pending"]').is(':selected');
    if (isSelected) {
      toggleSelectOptions(true, false, false);
    }
    return isSelected;
  };

  // Handlers for each report type
  const setMonthly = () => {
    toggleSelectOptions(true, false, true);
    typeInput.val('monthly');
  };

  const setQuarterly = () => {
    toggleSelectOptions(false, true, true);
    typeInput.val('quarterly');
  };

  const setYearly = () => {
    toggleSelectOptions(false, false, true);
    typeInput.val('yearly');
  };

  // Check and update based on report type
  const updateReportType = () => {
    if (!isFulfillmentPendingSelected()) {
      if (monthlyOption.hasClass('active')) setMonthly();
      else if (quarterlyOption.hasClass('active')) setQuarterly();
      else if (yearlyOption.hasClass('active')) setYearly();
    }
  };

  // Initial check
  updateReportType();

  // Event Handlers
  monthlyOption.click(() => {
    if (!isFulfillmentPendingSelected()) setMonthly();
  });

  quarterlyOption.click(() => {
    if (!isFulfillmentPendingSelected()) setQuarterly();
  });

  yearlyOption.click(() => {
    if (!isFulfillmentPendingSelected()) setYearly();
  });

  reportSelect.change(updateReportType);

  const generateButton = $('span[data="generate_report"]');
  const reportsForm = $('#reports_form');

  /**
   * Helper function to create table headers dynamically.
   * @param {Array} headers - Array of header names.
   * @param {Object} table - The table element.
   */
  function createTableHeaders(headers, table) {
    const headerRow = $('<tr>').appendTo($('<thead>').appendTo(table));
    headers.forEach(header => {
      const th = $('<th>').text(header.text);
      if (header.class) th.addClass(header.class);
      th.appendTo(headerRow);
    });
  }

  /**
   * Helper function to define column mappings for each report type.
   * @param {String} reportType - The type of the report.
   * @returns {Array} Array of column configurations.
   */
  function getColumnMappings(reportType) {
    const defaultColumns = [
      {
        data: "id",
        render: (data, type) =>
          type === 'display' ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>` : data,
      }
    ];

    const mappings = {
      award: [
        ...defaultColumns,
        { data: "fecha_award" },
        { data: "contract_number" },
        { data: "email_code" },
        { data: "nombre_usuario" },
        { data: "canal" },
        { data: "type_of_bid" },
        { data: "total_cost" },
        { data: "total_price" },
        { data: "profit" },
        { data: "type_of_contract" },
      ],
      submitted: [
        ...defaultColumns,
        { data: "fecha_submitted" },
        { data: "email_code" },
        { data: "nombre_usuario" },
        { data: "canal" },
        { data: "type_of_bid" },
        { data: "total_cost" },
        { data: "total_price" },
        { data: "profit" },
        { data: "type_of_contract" },
      ],
      fulfillment: [
        ...defaultColumns,
        { data: "fulfillment_date" },
        { data: "contract_number" },
        { data: "email_code" },
        { data: "nombre_usuario" },
        { data: "canal" },
        { data: "type_of_bid" },
        { data: "total_cost" },
        { data: "total_price" },
        { data: "profit" },
        { data: "total_cost_requote" },
        { data: "total_price_requote" },
        { data: "profit_requote" },
        { data: "type_of_contract" },
        { data: "set_side" },
      ],
      'accounts-payable-fulfillment': [
        ...defaultColumns,
        { data: "provider" },
        { data: "real_cost" },
        { data: "payment_term" },
        { data: "transaction_date" },
      ],
      'sales-commission': [
        ...defaultColumns,
        { data: "invoice_date" },
        { data: "contract_number" },
        { data: "nombre_usuario" },
        { data: "state" },
        { data: "client" },
        { data: "total_cost", visible: false },
        { data: "total_price", visible: false },
        { data: "profit", visible: false },
        { data: "total_cost_requote" },
        { data: "total_price_requote" },
        { data: "profit_equipment_requote" },
        { data: "profit_service_requote" },
        { data: "profit_requote_percentage" },
        { data: "total_cost_fulfillment" },
        { data: "total_price_fulfillment" },
        { data: "profit_equipment_fulfillment" },
        { data: "profit_service_fulfillment" },
        { data: "profit_fulfillment_percentage" },
        { data: "type_of_contract" },
        { data: "sales_commission" },
        { data: "sales_commission_amount" },
      ],
      'no-bid': [
        ...defaultColumns,
        { data: "nombre_usuario" },
        { data: "email_code" },
        { data: "type_of_bid" },
        { data: "comments" },
        { data: "issue_date" }
      ]
    };

    return mappings[reportType] || [];
  }

  /**
   * Generate report based on the selected type.
   */
  generateButton.click(function (e) {
    e.preventDefault();
    $('#reportsTable_wrapper').remove();

    const formData = reportsForm.serializeArray();
    const jsonData = formData.reduce((acc, obj) => ({ ...acc, [obj.name]: obj.value }), {});

    const reportType = jsonData.report;
    const columnMappings = getColumnMappings(reportType);

    if (!columnMappings.length) {
      console.error("Invalid report type:", reportType);
      return;
    }

    const table = $('<table>')
      .attr({ id: 'reportsTable', class: 'table table-bordered table-hover' })
      .appendTo($('#report_results_container'));

    const headers = {
      award: [
        { text: 'PROPOSAL' },
        { text: 'AWARD DATE' },
        { text: 'CONTRACT NUMBER' },
        { text: 'CODE' },
        { text: 'DESIGNATED USER' },
        { text: 'CHANNEL' },
        { text: 'TYPE OF BID' },
        { text: 'TOTAL COST', class: 'bg-primary' },
        { text: 'TOTAL PRICE', class: 'bg-primary' },
        { text: 'PROFIT', class: 'bg-primary' },
        { text: 'TYPE OF CONTRACT' }
      ],
      submitted: [
        { text: 'PROPOSAL' },
        { text: 'SUBMITTED DATE' },
        { text: 'CODE' },
        { text: 'DESIGNATED USER' },
        { text: 'CHANNEL' },
        { text: 'TYPE OF BID' },
        { text: 'TOTAL COST', class: 'bg-primary' },
        { text: 'TOTAL PRICE', class: 'bg-primary' },
        { text: 'PROFIT', class: 'bg-primary' },
        { text: 'TYPE OF CONTRACT' }
      ],
      fulfillment: [
        { text: 'PROPOSAL' },
        { text: 'FULFILLMENT DATE' },
        { text: 'CONTRACT NUMBER' },
        { text: 'CODE' },
        { text: 'DESIGNATED USER' },
        { text: 'CHANNEL' },
        { text: 'TYPE OF BID' },
        { text: 'TOTAL COST', class: 'bg-primary' },
        { text: 'TOTAL PRICE', class: 'bg-primary' },
        { text: 'PROFIT', class: 'bg-primary' },
        { text: 'TOTAL COST', class: 'bg-warning' },
        { text: 'TOTAL PRICE', class: 'bg-warning' },
        { text: 'PROFIT', class: 'bg-warning' },
        { text: 'TYPE OF CONTRACT' },
        { text: 'SET ASIDE' }
      ],
      'accounts-payable-fulfillment': [
        { text: 'PROPOSAL' },
        { text: 'PROVIDER' },
        { text: 'REAL COST' },
        { text: 'PAYMENT TERMS' },
        { text: 'TRANSACTION DATE' }
      ],
      'sales-commission': [
        { text: 'PROPOSAL' },
        { text: 'INVOICE DATE' },
        { text: 'CONTRACT NUMBER' },
        { text: 'DESIGNATED USER' },
        { text: 'STATE' },
        { text: 'CLIENT' },
        { text: 'TOTAL COST', class: 'bg-primary' },
        { text: 'TOTAL PRICE', class: 'bg-primary' },
        { text: 'PROFIT', class: 'bg-primary' },
        { text: 'TOTAL COST', class: 'bg-warning' },
        { text: 'TOTAL PRICE', class: 'bg-warning' },
        { text: 'PROFIT RFQ', class: 'bg-warning' },
        { text: 'PROFIT RFP', class: 'bg-warning' },
        { text: 'PROFIT %', class: 'bg-warning' },
        { text: 'TOTAL COST', class: 'bg-danger' },
        { text: 'TOTAL PRICE', class: 'bg-danger' },
        { text: 'PROFIT RFQ', class: 'bg-danger' },
        { text: 'PROFIT RFP', class: 'bg-danger' },
        { text: 'PROFIT %', class: 'bg-danger' },
        { text: 'TYPE OF CONTRACT' },
        { text: 'SALES COMMISSION' },
        { text: 'SALES COMMISSION ($)' }
      ],
      'no-bid': [
        { text: 'PROPOSAL' },
        { text: 'DESIGNATED USER' },
        { text: 'CODE' },
        { text: 'TYPE OF BID' },
        { text: 'COMMENTS' },
        { text: 'ISSUE DATE' }
      ]
    };

    createTableHeaders(headers[reportType] || [], table);

    table.DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: {
        url: '/rfq/quote/reports',
        type: 'POST',
        data: jsonData,
      },
      columns: columnMappings,
      initComplete: function () {
        $('#report_results_container > div > div')
          .eq(1)
          .addClass('table-responsive');
        $('#report_results_container #reportsTable_wrapper').prepend(`
        <div class="my-3">
          <i class="fas fa-square text-primary"></i> Quote <br>
          <i class="fas fa-square text-warning"></i> Re-Quote <br>
          <i class="fas fa-square text-danger"></i> Fulfillment
        </div>
      `);
      },
    });
  });
});