$(document).ready(function () {
  /***************************************************************************************************/
  if ($('#requote_table').length !== 0) {
    const calculateTotals = () => {
      const totalGanado = parseFloat($('#total_ganado').html().split(' ')[1]);
      const paymentTermsMultiplier = $('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC' ? 0.029 : 0;
      const paymentTerms = totalGanado * paymentTermsMultiplier;

      const totales = $('#re_quote_data tr').map((_, row) => {
        const value = parseFloat($(row).find('td').eq(8).text().split(' ')[1]);
        return isNaN(value) ? 0 : value;
      }).get();

      const shippingCostRq = parseFloat($('#shipping_cost_rq').val()) || 0;

      // Calculate total
      const total = totales.reduce((acc, value) => acc + value, 0) + paymentTerms + shippingCostRq;

      // Update totals
      $('#total_re_quote').html(`$ ${total.toFixed(2)}`);
      $('#total_cost').val(total);

      // Calculate and update profit
      const profitRq = (totalGanado - total).toFixed(2);
      const percentageProfitRq = ((profitRq / totalGanado) * 100).toFixed(2);
      $('#profit_rq').html(`$ ${profitRq}<br>${percentageProfitRq}%`);
    };

    // Periodically recalculate totals
    const interval = setInterval(calculateTotals, 100);
  }

  $('#re_quote_form').submit(function () {
    // Recalculate totals before submitting
    const totalGanado = parseFloat($('#total_ganado').html().split(' ')[1]);
    const paymentTermsMultiplier = $('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC' ? 0.029 : 0;
    const paymentTerms = totalGanado * paymentTermsMultiplier;

    const totales = $('#re_quote_data tr').map((_, row) => {
      const value = parseFloat($(row).find('td').eq(8).text().split(' ')[1]);
      return isNaN(value) ? 0 : value;
    }).get();

    const shippingCostRq = parseFloat($('#shipping_cost_rq').val()) || 0;

    // Calculate total
    const total = totales.reduce((acc, value) => acc + value, 0) + paymentTerms + shippingCostRq;

    // Update totals
    $('#total_re_quote').html(`$ ${total.toFixed(2)}`);
    $('#total_cost').val(total);

    // Calculate and update profit
    const profitRq = (totalGanado - total).toFixed(2);
    const percentageProfitRq = ((profitRq / totalGanado) * 100).toFixed(2);
    $('#profit_rq').html(`$ ${profitRq}<br>${percentageProfitRq}%`);
  });
  /****************************SERVICES*******************************************/
  const unitPriceFields = [];
  const servicesQuantityFields = [];

  // Cache unit prices and quantities
  $('#services_table tbody .service_item').each(function () {
    unitPriceFields.push(parseFloat($(this).find('td').eq(4).text()) || 0);
    servicesQuantityFields.push(parseFloat($(this).find('td').eq(3).text()) || 0);
  });

  // Calculate service totals
  const calcServices = () => {
    const paymentTermsMultiplier = $('input:radio[name=services_payment_term]:checked').val() === 'Net 30/CC' ? 1.0299 : 1;
    let totalServices = 0;

    $('#services_table tbody .service_item').each(function (i) {
      const newUnitPrice = (unitPriceFields[i] * paymentTermsMultiplier).toFixed(2);
      const newTotalPrice = (newUnitPrice * servicesQuantityFields[i]).toFixed(2);
      totalServices += parseFloat(newTotalPrice);

      // Update unit price and total price in the table
      $(this).find('td').eq(4).html(newUnitPrice);
      $(this).find('td').eq(5).html(newTotalPrice);
    });

    // Update total service amount
    $('#total_service').html(`$ ${totalServices.toFixed(2)}`);
  };

  // Periodically recalculate service totals
  const servicesPaymentTerms = setInterval(calcServices, 100);

  // Recalculate totals before submitting the form
  $('#form_edited_quote').submit(calcServices);

  // Edit service modal logic
  $('#services_table').on('click', '.edit_service', function () {
    const serviceId = $(this).data('service-id'); // Use a descriptive data attribute name
    const modalFormUrl = `/rfq/re_quote_sc/load_service/${serviceId}`;

    $('#edit_service_modal form').load(modalFormUrl, function () {
      $('#edit_service_modal').modal();
    });
  });
});