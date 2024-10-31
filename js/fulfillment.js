$(document).ready(function () {
  /***************************************CLEAN DATE FIELD***********************************/
  function setDateField(dateField) {
    dateField.daterangepicker({
      singleDatePicker: true,
      autoUpdateInput: false,
      autoApply: true
    });
    dateField.on('apply.daterangepicker', function (ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });
  
    dateField.on('cancel.daterangepicker', function (ev, picker) {
      $(this).val('');
    });
  }
  /***********************************TOOLTIP INITIALIZATION******************/
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'top',
  });
  /***************************************Invoices*************************************/
  const fulfillmentPage = $('#fulfillment_page');
  const addInvoiceModal = $('#add_invoice_modal');
  const addInvoiceForm = $('#add_invoice_form');
  const invoiceDropdown = $('#invoice-dropdown');
  const idRfq = $('#id-rfq').val();
  const isPartialInvoices = $('input[name="is-partial-invoices"]').val();

  function loadInvoiceDropdown(idRfq) {
    invoiceDropdown.load('/rfq/fulfillment/invoice/load_dropdown', { id: idRfq });
  }

  invoiceDropdown.on('click', '#add_invoice', function (e) {
    e.preventDefault();
    addInvoiceForm[0].reset();
    $('#created_at').daterangepicker({
      singleDatePicker: true,
      autoApply: true
    });
    addInvoiceModal.modal();
  });

  addInvoiceForm.validate({
    rules: {
      name: {
        required: true
      },
      created_at: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/invoice/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          let message = '';
          if (response.error != null) {
            message = $(`<label class="error">${response.error}</label>`);
            addInvoiceForm.find('#error').html(message);
          } else {
            addInvoiceModal.modal('hide');
            loadInvoiceDropdown(idRfq);
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  const editInvoiceModal = $('#edit-invoice-modal');
  const editInvoiceForm = $('#edit-invoice-form');

  invoiceDropdown.on('click', '.edit-invoice-button', function (e) {
    e.preventDefault();
    editInvoiceForm.load(`/rfq/fulfillment/invoice/load`, { id: $(this).data('id') }, function () {
      editInvoiceModal.modal();
      editInvoiceForm.find('#created_at').daterangepicker({
        singleDatePicker: true,
        autoApply: true
      });
    });
    editInvoiceForm.on('click', '.delete-invoice-button', deleteInvoice);
  });

  editInvoiceForm.validate({
    rules: {
      name: {
        required: true
      },
      created_at: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/invoice/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          let message = '';
          if (response.error != null) {
            message = $(`<label class="error">${response.error}</label>`);
            editInvoiceModal.find('#error').html(message);
          } else {
            editInvoiceModal.modal('hide');
            loadInvoiceDropdown(idRfq);
            location.reload();
            fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + idRfq);
          }
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  function deleteInvoice(e) {
    $.ajax({
      url: '/rfq/fulfillment/invoice/delete',
      type: 'POST',
      data: {
        id: $(e.target).data('id')
      },
      success: function (response) {
        editInvoiceModal.modal('hide');
        loadInvoiceDropdown(idRfq);
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + idRfq);

      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }

  fulfillmentPage.on('click', '.attach-sales-commission-button', function () {
    console.log('asdfsafdsaf');
    $.ajax({
      url: '/rfq/fulfillment/invoice/attach_sales_commission',
      type: 'POST',
      data: {
        id: $(this).data('id'),
        idRfq: idRfq
      },
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  });

  invoiceDropdown.on('click', '.edit-invoice-button', function (e) {
    e.preventDefault();
    editInvoiceForm.load(`/rfq/fulfillment/invoice/load`, { id: $(this).data('id') }, function () {
      editInvoiceModal.modal();
      editInvoiceForm.find('#created_at').daterangepicker({
        singleDatePicker: true,
        autoApply: true
      });
    });
    editInvoiceForm.on('click', '.delete-invoice-button', deleteInvoice);
  });

  loadInvoiceDropdown(idRfq);
  /****************************************REVIEWED CHECK************************************/
  fulfillmentPage.on('click', '.reviewed_button', function () {
    $.ajax({
      url: '/rfq/fulfillment/equipment/mark_as_reviewed/',
      data: {
        id_fulfillment_item: $(this).attr('data'),
        id_item: $(this).attr('id_item')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });

  fulfillmentPage.on('click', '.subitem_reviewed_button', function () {
    $.ajax({
      url: '/rfq/fulfillment/equipment/mark_subitem_as_reviewed/',
      data: {
        id_fulfillment_subitem: $(this).attr('data'),
        id_subitem: $(this).attr('id_subitem'),
        id_rfq: $(this).attr('id_rfq')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });

  /****************************************REVIEWED SERVICE CHECK************************************/
  fulfillmentPage.on('click', '.reviewed_service_button', function () {
    $.ajax({
      url: '/rfq/fulfillment/service/mark_as_reviewed_service/',
      data: {
        id_fulfillment_service: $(this).attr('data'),
        id_service: $(this).attr('id_service')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });
  /***************************************NET30 FULFILLMENT*************************************/
  let net30Fulfillment;
  fulfillmentPage.on('change', '#net30_cc', function () {
    if ($(this).is(':checked')) {
      net30Fulfillment = 1;
    } else {
      net30Fulfillment = 0;
    }
    $.ajax({
      url: '/rfq/fulfillment/equipment/save_net_30/',
      data: {
        id_rfq: $(this).attr('data'),
        value: net30Fulfillment
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });

  let net30FulfillmentServices;
  fulfillmentPage.on('change', '#net30_cc_services', function () {
    net30FulfillmentServices = $(this).is(':checked') ? 1 : 0;
    $.ajax({
      url: '/rfq/fulfillment/service/save_net_30/',
      data: {
        id_rfq: $(this).attr('data'),
        value: net30FulfillmentServices
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
  /***********************************FULFILLMENT AUDIT TRAILS******************/
  const btnAuditTrail = $('#fulfillment_audit_trails_button');
  const auditTrailModal = $('#fulfillment_audit_trails_modal');
  const auditTrailModalBody = $('#fulfillment_audit_trails_modal .modal-body');

  btnAuditTrail.click(function () {
    auditTrailModalBody.load('/rfq/fulfillment/load_fulfillment_audit_trails', { id_rfq: $(this).attr('data-id') }, function () {
      auditTrailModal.modal();
    });
  });

  auditTrailModal.on('click', '.audit_trail_link', function () {
    auditTrailModal.modal('hide');
    const id = $(this).attr('data');
    $(id).addClass('highlight');
    setTimeout(function () {
      $(id).removeClass('highlight');
    }, 5000);
  });
  /***********************************FULFILLMENT SHIPPING******************/
  let counterShipping = $('#edit_fulfillment_shipping_modal form').attr('data');
  const editFulfillmentShippingModal = $('#edit_fulfillment_shipping_modal');
  const editFulfillmentShippingForm = $('#edit_fulfillment_shipping_form');
  const removeShippingButton = $('.remove_shipping');

  fulfillmentPage.on('click', '#edit_fulfillment_shipping', function () {
    editFulfillmentShippingModal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_shipping/' + $(this).attr('data'), function () {
      if (!+counterShipping) removeShippingButton.attr('disabled', 'disabled');
      editFulfillmentShippingModal.modal();
    });
  });

  editFulfillmentShippingForm.submit(function () {
    $.post('/rfq/fulfillment/equipment/update_fulfillment_shipping', $(this).serialize(), function (res) {
      editFulfillmentShippingForm[0].reset();
      editFulfillmentShippingModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  editFulfillmentShippingModal.find('form').on('click', '.add_shipping', function () {
    counterShipping++;
    $('input[name="shipping_counter"]').val(counterShipping);
    const shippingFields = `
    <div class="shipping${counterShipping}">
      <div class="form-group">
      <label for="fulfillment_shipping${counterShipping}">Description:</label>
      <input type="hidden" name="fulfillment_shipping_original${counterShipping}" value="">
      <input type="text" class="form-control form-control-sm" id="fulfillment_shipping${counterShipping}" name="fulfillment_shipping${counterShipping}" value="">
      </div>
      <div class="form-group">
      <label for="amount${counterShipping}">Amount:</label>
      <input type="hidden" name="amount_original${counterShipping}" value="">
      <input type="number" step=".01" id="amount${counterShipping}" class="form-control form-control-sm" name="amount${counterShipping}" value="">
      </div>
    </div>
    `;
    $('.shipping_container').append(shippingFields);
    if (counterShipping) removeShippingButton.removeAttr('disabled');
  });

  editFulfillmentShippingModal.find('form').on('click', '.remove_shipping', function () {
    $(`.shipping${counterShipping}`).remove();
    counterShipping--;
    $('input[name="shipping_counter"]').val(counterShipping);
    if (!counterShipping) removeShippingButton.attr('disabled', 'disabled');
  });
  /***********************************FULFILLMENT SERVICES******************/
  const addFulfillmentServiceModal = $('#new_fulfillment_service_modal');
  const addFulfillmentServiceForm = $('#add_fulfillment_service_form');

  fulfillmentPage.on('click', '.add_fulfillment_service_button', function () {
    const idService = $(this).attr('name');
    addFulfillmentServiceModal.find('form').load('/rfq/fulfillment/service/load_fulfillment_service/', {
      idService: idService,
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      addFulfillmentServiceModal.modal();
    });
  });

  addFulfillmentServiceForm.submit(function () {
    $.post('/rfq/fulfillment/service/save_fulfillment_service', $(this).serialize(), function (res) {
      addFulfillmentServiceForm[0].reset();
      addFulfillmentServiceModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  const editFulfillmentServiceModal = $('#edit_fulfillment_service_modal');
  const editFulfillmentServiceForm = $('#edit_fulfillment_service_form');

  fulfillmentPage.on('click', '.edit_fulfillment_service_button', function () {
    editFulfillmentServiceModal.find('form').load('/rfq/fulfillment/service/load_fulfillment_service/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      editFulfillmentServiceModal.modal();
    });
    return false;
  });

  editFulfillmentServiceForm.submit(function () {
    $.post('/rfq/fulfillment/service/save_edit_fulfillment_service', $(this).serialize(), function (res) {
      editFulfillmentServiceModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  fulfillmentPage.on('click', '.delete_fulfillment_service_button', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/rfq/fulfillment/service/delete_fulfillment_service/',
      data: {
        id_fulfillment_service: $(this).attr('data'),
        id_service: $(this).attr('id_service')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });
  /***********************************FULFILLMENT***************************/
  /**
   * items
   */
  const addFulfillmentItemModal = $('#new_fulfillment_item_modal');
  const addFulfillmentItemForm = $('#add_fulfillment_item_form');

  fulfillmentPage.on('click', '.add_fulfillment_item_button', function () {
    var idItem = $(this).attr('name');
    addFulfillmentItemModal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_item/', {
      isPartialInvoices: isPartialInvoices,
      idItem: idItem,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      addFulfillmentItemModal.modal();
    });
  });

  addFulfillmentItemForm.submit(function () {
    $.post('/rfq/fulfillment/equipment/save_fulfillment_item', $(this).serialize(), function (res) {
      addFulfillmentItemForm[0].reset();
      addFulfillmentItemModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  const editFulfillmentItemModal = $('#edit_fulfillment_item_modal');
  const editFulfillmentItemForm = $('#edit_fulfillment_item_form');

  fulfillmentPage.on('click', '.edit_fulfillment_item_button', function () {
    editFulfillmentItemModal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_item/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      editFulfillmentItemModal.modal();
    });
    return false;
  });

  editFulfillmentItemForm.submit(function () {
    $.post('/rfq/fulfillment/equipment/save_edit_fulfillment_item', $(this).serialize(), function (res) {
      editFulfillmentItemModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  fulfillmentPage.on('click', '.delete_fulfillment_item_button', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/rfq/fulfillment/equipment/delete_fulfillment_item/',
      data: {
        id_fulfillment_item: $(this).attr('data'),
        id_item: $(this).attr('id_item')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });

  /**
   * subitems
   */
  const addFulfillmentSubitemModal = $('#new_fulfillment_subitem_modal');
  const addFulfillmentSubitemForm = $('#add_fulfillment_subitem_form');

  fulfillmentPage.on('click', '.add_fulfillment_subitem_button', function () {
    var idSubitem = $(this).attr('name');
    addFulfillmentSubitemModal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_subitem/', {
      isPartialInvoices: isPartialInvoices,
      idSubitem: idSubitem,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      addFulfillmentSubitemModal.modal();
    });
  });

  addFulfillmentSubitemForm.submit(function () {
    $.post('/rfq/fulfillment/equipment/save_fulfillment_subitem', $(this).serialize(), function (res) {
      addFulfillmentSubitemForm[0].reset();
      addFulfillmentSubitemModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  const editFulfillmentSubitemModal = $('#edit_fulfillment_subitem_modal');
  const editFulfillmentSubitemForm = $('#edit_fulfillment_subitem_form');

  fulfillmentPage.on('click', '.edit_fulfillment_subitem_button', function () {
    editFulfillmentSubitemModal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_subitem/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      editFulfillmentSubitemModal.modal();
    });
    return false;
  });

  editFulfillmentSubitemForm.submit(function () {
    $.post('/rfq/fulfillment/equipment/save_edit_fulfillment_subitem', $(this).serialize(), function (res) {
      editFulfillmentSubitemModal.modal('hide');
      fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  fulfillmentPage.on('click', '.delete_fulfillment_subitem_button', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/rfq/fulfillment/equipment/delete_fulfillment_subitem/',
      data: {
        id_fulfillment_subitem: $(this).attr('data'),
        id_subitem: $(this).attr('id_subitem'),
        id_rfq: $(this).attr('id_rfq')
      },
      type: 'POST',
      success: function (res) {
        fulfillmentPage.load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
});
