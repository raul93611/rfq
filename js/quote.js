$(document).ready(function () {
  const newItemButton = $('#new-item-button');
  const newItemModal = $('#new-item-modal');
  const newItemForm = $('#new-item-form');
  const quoteTable = $('#quote-table');

  //add item
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
  const deleteModal = $('#alert_delete_system');
  const continueButton = $('#continue_button');

  quoteTable.on('click', '.delete-item-button', function () {
    continueButton.attr('data-id', $(this).attr('data-id'));
    continueButton.attr('data-type', 'item');
    deleteModal.modal('show');
  });

  function deleteItem(id) {
    $.ajax({
      url: '/rfq/quote/equipment/delete',
      data: {
        id: id,
      },
      type: 'POST',
      success: function (response) {
        deleteModal.modal('hide');
        loadQuote(response.id);
      }
    });
  }

  continueButton.click(function (e) {
    e.preventDefault();
    switch ($(this).attr('data-type')) {
      case 'item':
        deleteItem($(this).attr('data-id'));
        break;
      case 'subitem':
        deleteSubitem($(this).attr('data-id'));
        break;
      case 'provider':
        deleteProvider($(this).attr('data-id'));
        break;
      case 'subitem-provider':
        deleteSubitemProvider($(this).attr('data-id'));
        break;
    }
  });

  const addProviderForm = $('#add-provider-form');
  const addProviderModal = $('#add-provider-modal');
  //add provider
  quoteTable.on('click', '.add-provider-button', function (e) {
    addProviderForm[0].reset();
    addProviderForm.find('input[name="id_item"]').val($(this).attr('data-id'));
    addProviderModal.modal('show');
  });

  addProviderForm.validate({
    rules: {
      price: {
        required: true,
        number: true,
        min: 0
      },
      provider: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/save_provider',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addProviderModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //edit provider
  const editProviderModal = $('#edit-provider-modal');
  const editProviderForm = $('#edit-provider-form');
  quoteTable.on('click', '.edit-provider-button', function (e) {
    e.preventDefault();
    editProviderModal.find('.modal-footer .delete-provider-button').attr('data-id', $(this).attr('data-id'));

    editProviderForm.load(`/rfq/quote/equipment/load_provider`, { id: $(this).data('id') }, () => {
      editProviderModal.modal('show');
    });
  });

  editProviderForm.validate({
    rules: {
      price: {
        required: true,
        number: true,
        min: 0
      },
      provider: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/update_provider',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editProviderModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //delete provider
  editProviderModal.on('click', '.delete-provider-button', function () {
    editProviderModal.modal('hide');
    continueButton.attr('data-id', $(this).attr('data-id'));
    continueButton.attr('data-type', 'provider');
    deleteModal.modal('show');
  });

  function deleteProvider(id) {
    $.ajax({
      url: '/rfq/quote/equipment/delete_provider',
      data: {
        id: id,
      },
      type: 'POST',
      success: function (response) {
        deleteModal.modal('hide');
        loadQuote(response.id);
      }
    });
  }

  //add subitem
  const addSubitemForm = $('#add-subitem-form');
  const addSubitemModal = $('#add-subitem-modal');

  quoteTable.on('click', '.add-subitem-button', function () {
    addSubitemForm[0].reset();
    console.log(addSubitemForm.find('input[name="id_item"]'));
    addSubitemForm.find('input[name="id_item"]').val($(this).attr('data-id'));
    addSubitemModal.modal('show');
  });

  addSubitemForm.validate({
    rules: {
      quantity: {
        required: true,
        digits: true,
        min: 0
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/save_subitem',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addSubitemModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //edit subitem
  const editSubitemModal = $('#edit-subitem-modal');
  const editSubitemForm = $('#edit-subitem-form');
  quoteTable.on('click', '.edit-subitem-button', function () {
    editSubitemForm.load(`/rfq/quote/equipment/load_subitem`, { id: $(this).data('id') }, () => {
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
      editSubitemModal.modal('show');
    });
  });

  editSubitemForm.validate({
    rules: {
      quantity: {
        required: true,
        digits: true,
        min: 0
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/update_subitem',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editSubitemModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //delete subitem
  quoteTable.on('click', '.delete-subitem-button', function () {
    continueButton.attr('data-id', $(this).attr('data-id'));
    continueButton.attr('data-type', 'subitem');
    deleteModal.modal('show');
  });

  function deleteSubitem(id) {
    $.ajax({
      url: '/rfq/quote/equipment/delete_subitem',
      data: {
        id: id,
      },
      type: 'POST',
      success: function (response) {
        deleteModal.modal('hide');
        loadQuote(response.id);
      }
    });
  }

  const addSubitemProviderForm = $('#add-subitem-provider-form');
  const addSubitemProviderModal = $('#add-subitem-provider-modal');
  //add subitem provider
  quoteTable.on('click', '.add-subitem-provider-button', function (e) {
    addSubitemProviderForm[0].reset();
    addSubitemProviderForm.find('input[name="id_subitem"]').val($(this).attr('data-id'));
    addSubitemProviderModal.modal('show');
  });

  addSubitemProviderForm.validate({
    rules: {
      price: {
        required: true,
        number: true,
        min: 0
      },
      provider: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/save_subitem_provider',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addSubitemProviderModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //edit subitem provider
  const editSubitemProviderModal = $('#edit-subitem-provider-modal');
  const editSubitemProviderForm = $('#edit-subitem-provider-form');
  quoteTable.on('click', '.edit-subitem-provider-button', function (e) {
    e.preventDefault();
    editSubitemProviderModal.find('.modal-footer .delete-subitem-provider-button').attr('data-id', $(this).attr('data-id'));

    editSubitemProviderForm.load(`/rfq/quote/equipment/load_subitem_provider`, { id: $(this).data('id') }, () => {
      editSubitemProviderModal.modal('show');
    });
  });

  editSubitemProviderForm.validate({
    rules: {
      price: {
        required: true,
        number: true,
        min: 0
      },
      provider: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/equipment/update_subitem_provider',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editSubitemProviderModal.modal('hide');
          loadQuote(response.id);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  //delete subitem provider
  editSubitemProviderModal.on('click', '.delete-subitem-provider-button', function () {
    editSubitemProviderModal.modal('hide');
    continueButton.attr('data-id', $(this).attr('data-id'));
    continueButton.attr('data-type', 'subitem-provider');
    deleteModal.modal('show');
  });

  function deleteSubitemProvider(id) {
    $.ajax({
      url: '/rfq/quote/equipment/delete_subitem_provider',
      data: {
        id: id,
      },
      type: 'POST',
      success: function (response) {
        deleteModal.modal('hide');
        loadQuote(response.id);
      }
    });
  }

  //Payment Terms radiobutton
  quoteTable.on('change', 'input[name="payment_terms"]', function () {
    $.ajax({
      url: '/rfq/quote/equipment/update_payment_terms',
      type: 'POST',
      data: {
        paymentTerms: $('input[name="payment_terms"]:checked').val(),
        id: quoteTable.data('id')
      },
      success: function (response) {
        loadQuote(response.id);
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
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
