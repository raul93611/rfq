$(document).ready(function () {
  const monto = [];
  const quantity = [];

  // Parse table data and populate `monto` and `quantity` arrays
  $('#items tr').each(function () {
    const quantityColumnValue = parseFloat($(this).find('td').eq(5).text()) || 0;
    const bestUnitCost = parseFloat($(this).find('td').eq(8).text().split(' ')[1]) || 0;

    quantity.push(quantityColumnValue);
    monto.push(bestUnitCost);
  });

  const totalQuantity = quantity.reduce((sum, qty) => sum + qty, 0);

  const calcQuoteTable = () => {
    const paymentTerms = $('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC'
      ? 1.0298661174047374
      : 1;
    const taxesMultiplier = (parseFloat($('#taxes').val()) / 100 || 0) + 1;
    const profitMultiplier = (parseFloat($('#profit').val()) / 100 || 0) + 1;
    const additionalGeneral = parseFloat($('#additional_general').val()) || 0;
    const shippingCost = parseFloat($('#shipping_cost').val()) || 0;

    let totalCost = 0;
    let totalPrice = shippingCost;
    let totalAdditional = 0;

    const singleTotalPriceItems = [];
    const singleTotalPriceSubItems = [];
    const unitPricesItems = [];
    const unitPricesSubItems = [];
    const singleAdditionalItems = [];
    const singleAdditionalSubItems = [];

    $('#items tr').each(function (index) {
      const $row = $(this);

      const bestUnitCostField = $row.find('td').eq(8);
      const singleTotalCostField = $row.find('td').eq(9);
      const priceForClientField = $row.find('td').eq(10);
      const singleTotalPriceField = $row.find('td').eq(11);

      const singleAdditional = parseFloat($(`#add_cost${index + 1}`).val()) || 0;

      // Calculate total additional costs
      totalAdditional += (singleAdditional + additionalGeneral) * quantity[index];

      // Calculate final best unit cost
      const finalBestUnitCost = (additionalGeneral + singleAdditional + taxesMultiplier * monto[index] * paymentTerms).toFixed(2);
      bestUnitCostField.html(`$ ${finalBestUnitCost}`);

      // Calculate price for client
      const priceForClient = (profitMultiplier * finalBestUnitCost).toFixed(2);
      priceForClientField.html(`$ ${priceForClient}`);

      // Calculate single total cost
      const singleTotalCost = (finalBestUnitCost * quantity[index]).toFixed(2);
      singleTotalCostField.html(`$ ${singleTotalCost}`);
      totalCost += parseFloat(singleTotalCost);

      // Calculate single total price
      const singleTotalPrice = (priceForClient * quantity[index]).toFixed(2);
      singleTotalPriceField.html(`$ ${singleTotalPrice}`);
      totalPrice += parseFloat(singleTotalPrice);

      // Store additional and unit prices in respective arrays
      if ($row.hasClass('fila_subitem')) {
        singleAdditionalSubItems.push(singleAdditional);
        unitPricesSubItems.push(priceForClient);
        singleTotalPriceSubItems.push(singleTotalPrice);
      } else {
        singleAdditionalItems.push(singleAdditional);
        unitPricesItems.push(priceForClient);
        singleTotalPriceItems.push(singleTotalPrice);
      }
    });

    const profit = (totalPrice - totalCost).toFixed(2);
    const percentageProfit = ((profit / totalPrice) * 100).toFixed(2);

    // Update input fields
    $('#additional').val(singleAdditionalItems.join());
    $('#additional_subitems').val(singleAdditionalSubItems.join());
    $('#unit_prices').val(unitPricesItems.join());
    $('#unit_prices_subitems').val(unitPricesSubItems.join());
    $('#partes_total_price').val(singleTotalPriceItems.join());
    $('#partes_total_price_subitems').val(singleTotalPriceSubItems.join());
    $('#total_cost').val(totalCost.toFixed(2));
    $('#total_price').val(totalPrice.toFixed(2));

    // Update table values
    $('#total1').html(`$ ${totalCost.toFixed(2)}`);
    $('#total2').html(`$ ${totalPrice.toFixed(2)}`);
    $('#dif_total').html(`$ ${profit}<br>${percentageProfit}%`);
    $('#total_quantity').html(totalQuantity);
    $('#total_additional').html(`$ ${totalAdditional.toFixed(2)}`);
  };

  // Set interval for dynamic updates and trigger on form submission
  if ($('#form_edited_quote').length) {
    const interval = setInterval(calcQuoteTable, 100);
    $('#form_edited_quote').submit(() => {
      clearInterval(interval);
      calcQuoteTable();
    });
  }
  /***************************************TYPE OF CONTRACT MODAL**************************************************/
  const $fulfillmentCheckbox = $('#fulfillment');
  const $typeOfContractModal = $('#type_of_contract_modal');

  // Show the modal when the checkbox is checked
  $fulfillmentCheckbox.on('change', function () {
    if (this.checked) {
      $typeOfContractModal.modal({
        backdrop: 'static', // Prevent closing by clicking outside the modal
        keyboard: false     // Disable closing the modal with the Esc key
      });
    }
  });
  /***************************************SALES COMMISSION MODAL**************************************************/
  const $invoiceCheckbox = $('#invoice');
  const $salesCommissionModal = $('#sales_commission_modal');

  // Show the sales commission modal when the checkbox is checked
  $invoiceCheckbox.on('change', function () {
    if (this.checked) {
      $salesCommissionModal.modal({
        backdrop: 'static', // Prevent modal from being closed by clicking outside
        keyboard: false     // Disable closing the modal with the Esc key
      });
    }
  });
  /************************* SERVICES CALCULATION *************************/
  const $servicesTable = $('#services_table');
  const $totalServiceField = $('#total_service');
  const unitPriceFields = [];
  const servicesQuantityFields = [];

  // Initialize unit price and quantity arrays from table rows
  $servicesTable.find('tbody .service_item').each(function () {
    const $cells = $(this).find('td');
    unitPriceFields.push(parseFloat($cells.eq(4).text()) || 0); // Ensure numerical values
    servicesQuantityFields.push(parseFloat($cells.eq(3).text()) || 0);
  });

  const calculateServices = () => {
    const paymentTermsMultiplier = $('input:radio[name=services_payment_term]:checked').val() === 'Net 30/CC' ? 1.0299 : 1;
    let totalServices = 0;

    $servicesTable.find('tbody .service_item').each(function (i) {
      const newUnitPrice = (unitPriceFields[i] * paymentTermsMultiplier).toFixed(2);
      const newTotalPrice = (newUnitPrice * servicesQuantityFields[i]).toFixed(2);
      totalServices += parseFloat(newTotalPrice);

      const $cells = $(this).find('td');
      $cells.eq(4).html(newUnitPrice);
      $cells.eq(5).html(newTotalPrice);
    });

    $totalServiceField.html(`$ ${totalServices.toFixed(2)}`);
  };

  // Recalculate services periodically and on form submission
  const servicesCalculationInterval = setInterval(calculateServices, 100);
  $('#form_edited_quote').on('submit', calculateServices);

  // Clear interval when no longer needed (optional, for cleanup)
  $('#form_edited_quote').on('submit', () => clearInterval(servicesCalculationInterval));
  /************************* LINK QUOTE MODAL *************************/
  const $linkQuoteModal = $('#link_quote_modal');
  const $linkQuoteButton = $('#link_quote_button');
  const idSlave = $('#link_quote_form input[name="id_rfq"]').val();

  // Initialize Select2 with optimized settings
  $('#quote_ids').select2({
    theme: 'bootstrap4',
    ajax: {
      type: 'POST',
      url: '/rfq/quote/ids',
      dataType: 'json',
      delay: 250,
      data: params => ({
        term: params.term,
        id_rfq: idSlave
      }),
      processResults: (data) => ({
        results: data
      }),
      cache: true
    },
    minimumInputLength: 4
  });

  // Open modal when the link quote button is clicked
  $linkQuoteButton.on('click', function (e) {
    e.preventDefault();
    $linkQuoteModal.modal('show');
  });
});
