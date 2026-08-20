$(document).ready(function () {
  /***************************************************************************************************/
  if ($('#requote_table').length !== 0) {
    const calculateTotals = () => {
      const totalGanado = parseFloat($('#total_ganado').html().split(' ')[1]);
      const paymentTermsMultiplier = $('[name=payment_terms]').val() === 'Net 30/CC' ? 0.029 : 0;
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

      // Sticky bottom bar — Total Price is the original quote's fixed client-facing price
      // (items + services, unaffected by re-quoting); Total Profit/Profit % track the live
      // re-quote cost: items cost (above) plus the re-quote services cost, which is its own
      // independently re-solicited line (re_quote_services), not the original quote's
      // services price — same design as items, and CC there is a real cost, not neutral.
      // #total_service is kept current by calcServices() below on the same 100ms cadence.
      const quoteTotalPrice = parseFloat(window.RE_QUOTE_TOTAL_PRICE) || 0;
      const servicesCostLive = parseFloat($('#total_service').text().replace('$', '').trim()) || 0;
      const totalCostLive = total + servicesCostLive;
      const barProfit = (quoteTotalPrice - totalCostLive).toFixed(2);
      const barProfitPct = (quoteTotalPrice ? (barProfit / quoteTotalPrice) * 100 : 0).toFixed(2);
      $('#rq-bar-total-price').text(`$${quoteTotalPrice.toFixed(2)}`);
      $('#rq-bar-total-profit').text(`$${barProfit}`);
      $('#rq-bar-profit-pct').text(`${barProfitPct}%`);
    };

    // Periodically recalculate totals
    const interval = setInterval(calculateTotals, 100);
  }

  $('#re_quote_form').submit(function () {
    // Recalculate totals before submitting
    const totalGanado = parseFloat($('#total_ganado').html().split(' ')[1]);
    const paymentTermsMultiplier = $('[name=payment_terms]').val() === 'Net 30/CC' ? 0.029 : 0;
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
    // 1.03 matches the multiplier ReQuoteServiceRepository::calc_items_with_CC() persists
    // server-side on Save — this preview must agree with what actually gets saved, or the
    // live total and the saved/PDF total silently diverge by a few cents to a few dollars.
    const paymentTermsMultiplier = $('[name=services_payment_term]').val() === 'Net 30/CC' ? 1.03 : 1;
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

  /****************************PAYMENT TERMS 50/50 MIRRORING*******************************************/
  // 50/50 is one arrangement for the whole job: selecting it on either table mirrors
  // to the other; leaving it resets the other table to Net 30. Net 30 / Net 30/CC
  // stay independently selectable per table.
  const SPLIT_TERM = window.PAYMENT_TERM_SPLIT || '50% Upfront / 50% on Completion';
  $(document).on('change', 'select.js-payment-terms', function () {
    const $changed = $(this);
    const $other = $('select.js-payment-terms').not($changed);
    if (!$other.length) return;
    if ($changed.val() === SPLIT_TERM) {
      if ($other.val() !== SPLIT_TERM) $other.val(SPLIT_TERM).trigger('change');
    } else if ($other.val() === SPLIT_TERM) {
      $other.val('Net 30').trigger('change');
    }
  });

  // Edit service modal logic
  $('#services_table').on('click', '.edit_service', function () {
    const serviceId = $(this).data('service-id'); // Use a descriptive data attribute name
    const modalFormUrl = `/rfq/re_quote_sc/load_service/${serviceId}`;

    $('#edit_service_modal form').load(modalFormUrl, function () {
      $('#edit_service_modal').modal();
    });
  });
});