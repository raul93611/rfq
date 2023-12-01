$(document).ready(function () {
  /***************************************Invoices*************************************/
  const addInvoiceModal = $('#add_invoice_modal');
  const addInvoiceForm = $('#add_invoice_form');
  const invoiceDropdown = $('#invoice-dropdown');
  const idRfq = $('#id-rfq').val();
  const isPartialInvoices = $('input[name="is-partial-invoices"]').val();

  console.log(idRfq + 'asdfsdafsdafsadfsdfds');

  function loadInvoiceDropdown(idRfq) {
    console.log(idRfq + 'asdfsdafsdafsadfsdfds');
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
          addInvoiceModal.modal('hide');
          loadInvoiceDropdown(idRfq);
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
          editInvoiceModal.modal('hide');
          loadInvoiceDropdown(idRfq);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  function deleteInvoice(e) {
    console.log('sadfsdfsadfsd');
    $.ajax({
      url: '/rfq/fulfillment/invoice/delete',
      type: 'POST',
      data: {
        id: $(e.target).data('id')
      },
      success: function (response) {
        editInvoiceModal.modal('hide');
        loadInvoiceDropdown(idRfq);
      },
      error: function (xhr, status, error) {
        console.error(error);
      }
    });
  }

  loadInvoiceDropdown(idRfq);
  /****************************************************************************/
  $('#fulfillment_page').on('click', '.reviewed_button', function () {
    $.ajax({
      url: '/rfq/fulfillment/equipment/mark_as_reviewed/',
      data: {
        id_fulfillment_item: $(this).attr('data'),
        id_item: $(this).attr('id_item')
      },
      type: 'POST',
      success: function (res) {
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.subitem_reviewed_button', function () {
    $.ajax({
      url: '/rfq/fulfillment/equipment/mark_subitem_as_reviewed/',
      data: {
        id_fulfillment_subitem: $(this).attr('data'),
        id_subitem: $(this).attr('id_subitem'),
        id_rfq: $(this).attr('id_rfq')
      },
      type: 'POST',
      success: function (res) {
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });
  /***************************************NET30 FULFILLMENT*************************************/
  const net30Checkbox = $('#net30_cc');
  const fulfillmentPage = $('#fulfillment_page');
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
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
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
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
  /***********************************TOOLTIP INITIALIZATION******************/
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'top',
  });
  /***********************************FULFILLMENT AUDIT TRAILS******************/
  const btnAuditTrail = $('#fulfillment_audit_trails_button');
  const auditTrailModal = $('#fulfillment_audit_trails_modal');
  const auditTrailModalBody = $('#fulfillment_audit_trails_modal .modal-body');
  const auditTrailLink = $('.audit_trail_link');

  btnAuditTrail.click(function () {
    auditTrailModalBody.load('/rfq/fulfillment/load_fulfillment_audit_trails', { id_rfq: $(this).attr('data') }, function () {
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

  $('#fulfillment_page').on('click', '#edit_fulfillment_shipping', function () {
    $('#edit_fulfillment_shipping_modal form').load('/rfq/fulfillment/equipment/load_fulfillment_shipping/' + $(this).attr('data'), function () {
      if (!+counterShipping) $('.remove_shipping').attr('disabled', 'disabled');
      $('#edit_fulfillment_shipping_modal').modal();
    });
  });

  $('#edit_fulfillment_shipping_form').submit(function () {
    $.post('/rfq/fulfillment/equipment/update_fulfillment_shipping', $(this).serialize(), function (res) {
      $('#edit_fulfillment_shipping_form')[0].reset();
      $('#edit_fulfillment_shipping_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });



  $('#edit_fulfillment_shipping_modal form').on('click', '.add_shipping', function () {
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
    if (counterShipping) $('.remove_shipping').removeAttr('disabled');
  });

  $('#edit_fulfillment_shipping_modal form').on('click', '.remove_shipping', function () {
    $(`.shipping${counterShipping}`).remove();
    counterShipping--;
    $('input[name="shipping_counter"]').val(counterShipping);
    if (!counterShipping) $('.remove_shipping').attr('disabled', 'disabled');
  });
  /***********************************FULFILLMENT SERVICES******************/
  $('#fulfillment_page').on('click', '.add_fulfillment_service_button', function () {
    var id_service = $(this).attr('name');
    $('#new_fulfillment_service_modal #id_service').val(id_service);
    $('#new_fulfillment_service_modal').modal();
  });

  $('#add_fulfillment_service_form').submit(function () {
    $.post('/rfq/fulfillment/service/save_fulfillment_service', $(this).serialize(), function (res) {
      $('#add_fulfillment_service_form')[0].reset();
      $('#new_fulfillment_service_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_service_button', function () {
    $('#edit_fulfillment_service_modal form').load('/rfq/fulfillment/service/load_fulfillment_service/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      $('#edit_fulfillment_service_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_service_form').submit(function () {
    $.post('/rfq/fulfillment/service/save_edit_fulfillment_service', $(this).serialize(), function (res) {
      $('#edit_fulfillment_service_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_service_button', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/rfq/fulfillment/service/delete_fulfillment_service/',
      data: {
        id_fulfillment_service: $(this).attr('data'),
        id_service: $(this).attr('id_service')
      },
      type: 'POST',
      success: function (res) {
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });
  /***********************************FULFILLMENT***************************/
  $('#fulfillment_page').on('click', '.add_fulfillment_item_button', function () {
    var id_item = $(this).attr('name');
    $('#new_fulfillment_item_modal #id_item').val(id_item);
    $('#new_fulfillment_item_modal').modal();
  });

  $('#add_fulfillment_item_form').submit(function () {
    $.post('/rfq/fulfillment/equipment/save_fulfillment_item', $(this).serialize(), function (res) {
      $('#add_fulfillment_item_form')[0].reset();
      $('#new_fulfillment_item_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_item_button', function () {
    $('#edit_fulfillment_item_modal form').load('/rfq/fulfillment/equipment/load_fulfillment_item/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      $('#edit_fulfillment_item_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_item_form').submit(function () {
    $.post('/rfq/fulfillment/equipment/save_edit_fulfillment_item', $(this).serialize(), function (res) {
      $('#edit_fulfillment_item_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_item_button', function (e) {
    e.preventDefault();
    $.ajax({
      url: '/rfq/fulfillment/equipment/delete_fulfillment_item/',
      data: {
        id_fulfillment_item: $(this).attr('data'),
        id_item: $(this).attr('id_item')
      },
      type: 'POST',
      success: function (res) {
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });

  $('#fulfillment_page').on('click', '.add_fulfillment_subitem_button', function () {
    var id_subitem = $(this).attr('name');
    $('#new_fulfillment_subitem_modal #id_subitem').val(id_subitem);
    $('#new_fulfillment_subitem_modal').modal();
  });

  $('#add_fulfillment_subitem_form').submit(function () {
    $.post('/rfq/fulfillment/equipment/save_fulfillment_subitem', $(this).serialize(), function (res) {
      $('#add_fulfillment_subitem_form')[0].reset();
      $('#new_fulfillment_subitem_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_subitem_button', function () {
    $('#edit_fulfillment_subitem_modal form').load('/rfq/fulfillment/equipment/load_fulfillment_subitem/' + $(this).attr('data'), {
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      $('#edit_fulfillment_subitem_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_subitem_form').submit(function () {
    $.post('/rfq/fulfillment/equipment/save_edit_fulfillment_subitem', $(this).serialize(), function (res) {
      $('#edit_fulfillment_subitem_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_subitem_button', function (e) {
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
        $('#fulfillment_page').load('/rfq/fulfillment/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
});
