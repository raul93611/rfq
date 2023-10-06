$(document).ready(function () {
  const newItemButton = $('#new-item-button');
  const newItemModal = $('#new-item-modal');
  const newItemForm = $('#new-item-form');
  const quoteTable = $('#quote-table');

  newItemButton.click(function (e) {
    newItemForm[0].reset();
    newItemModal.modal('show');
  });

  newItemForm.validate({
    rules: {
      quantity: {
        required: true,
        digits: true,
        min: 0
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          newItemModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //edit item
  const editItemModal = $('#edit-item-modal');
  const editItemForm = $('#edit-item-form');
  quoteTable.on('click', '.edit-item-button', function () {
    editItemForm.load(`/rfq/quote/equipment/load`, { id: $(this).data('id') }, () => {
      $('.summernote_textarea').summernote({
        callbacks: {
          onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
            e.preventDefault();
            bufferText = bufferText.replace(/<\/?[^>]+(>|$)/g, '');
            setTimeout($(this).summernote('insertText', bufferText), 10);
          }
        },
        toolbar: [
          ['color', ['color']],
          ['insert', ['link']],
        ]
      });
      editItemModal.modal('show');
    });
  });

  editItemForm.validate({
    rules: {
      quantity: {
        required: true,
        digits: true,
        min: 0
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editItemModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //delete item
  const deleteItemModal = $('#alert_delete_system');
  quoteTable.on('click', '.delete-item-button', function () {
    const continueButton = $('#continue_button');
    const idItem = $(this).attr('data-id');
    deleteItemModal.modal('show');
    continueButton.click(function (e) {
      e.preventDefault();
      $.ajax({
        url: '/rfq/quote/equipment/delete',
        data: {
          id: idItem,
        },
        type: 'POST',
        success: function (response) {
          deleteItemModal.modal('hide');
          loadQuote(response.id);
        }
      });
    });
  });


  function loadQuote(id) {
    quoteTable.load('/rfq/quote/load', { id: id });
  }

  loadQuote(quoteTable.data('id'));
  /*-----------------------------------------------------------------*/
  let monto = [];
  let quantity = [];

  //Getting Best Unit Cost column and Quantity column
  $('#items tr').each(function () {
    let quantitycolumnValue = $(this).find('td').eq(5).text();
    let bestUnitCost = $(this).find('td').eq(8).text().split(' ')[1];

    quantity.push(quantitycolumnValue);
    monto.push(!isNaN(bestUnitCost) ? bestUnitCost : 0);
  });

  let totalQuantity = quantity.reduce((a, b) => Number(a) + Number(b), 0);

  const calcQuoteTable = function () {
    const paymentTermsCheckbox = $('input:radio[name=payment_terms]:checked').val();
    const taxesTextBox = (parseFloat($('#taxes').val()) / 100) + 1;
    const profitTextBox = (parseFloat($('#profit').val()) / 100) + 1;
    const additionalGeneralTextBox = $('#additional_general').val();
    const shippingCostTextBox = $('#shipping_cost').val();

    let paymentTerms = paymentTermsCheckbox == 'Net 30/CC' ? 1.0298661174047374 : 1;
    let additionalGeneral = !isNaN(additionalGeneralTextBox) && additionalGeneralTextBox != '' ? parseFloat(additionalGeneralTextBox) : 0;
    let shippingCost = !isNaN(shippingCostTextBox) && shippingCostTextBox != '' ? shippingCostTextBox : 0;

    let totalCost = 0;
    let totalPrice = parseFloat(shippingCost);
    let singleTotalPriceItems = [];
    let singleTotalPriceSubItems = [];
    let unitPricesItems = [];
    let unitPriceSubItems = [];
    let singleAdditionalItems = [];
    let singleAdditionalSubItems = [];
    let totalAdditional = 0;

    $('#items tr').each(function (index) {
      const bestUnitCostField = $(this).find('td').eq(8);
      const singleTotalCostField = $(this).find('td').eq(9);
      const priceForClientField = $(this).find('td').eq(10);
      const singleTotalPriceField = $(this).find('td').eq(11);

      const singleAdditionalTextBox = $('#add_cost' + Number(index + 1)).val();
      let singleAdditional = !isNaN(singleAdditionalTextBox) && singleAdditionalTextBox != '' ? parseFloat(singleAdditionalTextBox) : 0;

      totalAdditional = totalAdditional + (singleAdditional + additionalGeneral) * quantity[index];

      //Getting the best unit cost with the single and general additional, taxes and payment terms
      let finalBestUnitCost = (additionalGeneral + singleAdditional + (taxesTextBox * monto[index] * paymentTerms)).toFixed(2);
      bestUnitCostField.html('$ ' + finalBestUnitCost);

      //Setting the price for client
      let priceForClient = (profitTextBox * finalBestUnitCost).toFixed(2);
      priceForClientField.html('$ ' + priceForClient);

      //Setting the single total cost
      let singleTotalCost = (finalBestUnitCost * quantity[index]).toFixed(2);
      singleTotalCostField.html('$ ' + singleTotalCost);

      //Getting the total cost
      totalCost = totalCost + parseFloat(singleTotalCost);

      //Setting the single total price
      let singleTotalPrice = (priceForClient * quantity[index]).toFixed(2);
      singleTotalPriceField.html('$ ' + singleTotalPrice);

      totalPrice = totalPrice + parseFloat(singleTotalPrice);

      //Collect all the additional, unit prices values in arrays
      if ($(this).hasClass('fila_subitem')) {
        singleAdditionalSubItems.push(singleAdditional);
        unitPriceSubItems.push(priceForClient);
        singleTotalPriceSubItems.push(singleTotalPrice);
      } else {
        singleAdditionalItems.push(singleAdditional);
        unitPricesItems.push(priceForClient);
        singleTotalPriceItems.push(singleTotalPrice);
      }
    });

    let profit = (totalPrice - totalCost).toFixed(2);
    let percentageProfit = ((profit / totalPrice) * 100).toFixed(2);

    //Setting values in input fields
    $('#additional').val(singleAdditionalItems.join());
    $('#additional_subitems').val(singleAdditionalSubItems.join());
    $('#unit_prices').val(unitPricesItems.join());
    $('#unit_prices_subitems').val(unitPriceSubItems.join());
    $('#partes_total_price').val(singleTotalPriceItems.join());
    $('#partes_total_price_subitems').val(singleTotalPriceSubItems.join());
    $('#total_cost').val(totalCost.toFixed(2));
    $('#total_price').val(totalPrice.toFixed(2));

    //Setting values in table
    $('#total1').html('$ ' + totalCost.toFixed(2));
    $('#total2').html('$ ' + totalPrice.toFixed(2));
    $('#dif_total').html('$ ' + profit + '<br>' + percentageProfit + '%');
    $('#total_quantity').html(totalQuantity);
    $('#total_additional').html('$ ' + totalAdditional.toFixed(2));
  };

  if ($('#form_edited_quote').length != 0) {
    let time = setInterval(calcQuoteTable, 100);
    $('#form_edited_quote').submit(calcQuoteTable);
  }
  /***************************************TYPE OF CONTRACT MODAL**************************************************/
  const fulfillmentCheckbox = $('#fulfillment');
  const typeOfContractModal = $('#type_of_contract_modal');
  const typeOfContractSelect = $('select[name="type_of_contract"]');

  fulfillmentCheckbox.change(function () {
    if ($(this).is(':checked')) {
      typeOfContractModal.modal({
        backdrop: 'static',
        keyboard: false
      });
    }
  })
  /***************************************SALES COMMISSION MODAL**************************************************/
  const invoiceCheckbox = $('#invoice');
  const salesComissionModal = $('#sales_commission_modal');

  invoiceCheckbox.change(function () {
    if ($(this).is(':checked')) {
      salesComissionModal.modal({
        backdrop: 'static',
        keyboard: false
      });
    }
  })
  /*************************SERVICES**************/
  const unitPriceFields = [];
  const servicesQuantityFields = [];
  $('#services_table tbody .service_item').each(function () {
    unitPriceFields.push(+$(this).find('td').eq(4).text());
    servicesQuantityFields.push(+$(this).find('td').eq(3).text());
  });

  const calcServices = function () {
    const paymentTerms = $('input:radio[name=services_payment_term]:checked').val() === 'Net 30/CC' ? 1.0299 : 1;
    let totalServices = 0;

    $('#services_table tbody .service_item').each(function (i, element) {
      const newUnitPrice = (unitPriceFields[i] * paymentTerms).toFixed(2);
      const newTotalPrice = (newUnitPrice * servicesQuantityFields[i]).toFixed(2);
      totalServices += +newTotalPrice;

      $(this).find('td').eq(4).html(newUnitPrice);
      $(this).find('td').eq(5).html(newTotalPrice);
    });

    $('#total_service').html('$ ' + totalServices.toFixed(2));
  }

  const servicesPaymentTerms = setInterval(calcServices, 100);
  $('#form_edited_quote').submit(calcServices);

  /*************************LINK QUOTE**************/
  const linkQuoteModal = $('#link_quote_modal');
  const linkQuoteButton = $('#link_quote_button');
  const idSlave = $('#link_quote_form input[name="id_rfq"]').val();

  $('#quote_ids').select2({
    theme: 'bootstrap4',
    ajax: {
      type: 'POST',
      url: '/rfq/quote/ids',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          term: params.term,
          id_rfq: idSlave
        };
      },
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    },
    minimumInputLength: 4
  });

  linkQuoteButton.click(function (e) {
    e.preventDefault();
    linkQuoteModal.modal();
  });


});
