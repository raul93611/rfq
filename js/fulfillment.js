$(document).ready(function () {
  /***************************************CLEAN DATE FIELD***********************************/
  /**
 * Initializes a date picker on the provided date field.
 * @param {jQuery} dateField - jQuery selector for the date field.
 */
  function setDateField(dateField) {
    const dateOptions = {
      singleDatePicker: true,
      autoUpdateInput: false,
      autoApply: true
    };

    dateField.daterangepicker(dateOptions);

    dateField.on('apply.daterangepicker', function (_, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    dateField.on('cancel.daterangepicker', function () {
      $(this).val('');
    });
  }
  /***************************************Invoices*************************************/
  // Selectors for elements on the fulfillment page
  const fulfillmentPage = $('#fulfillment_page');
  const addInvoiceModal = $('#add_invoice_modal');
  const addInvoiceForm = $('#add_invoice_form');
  const invoiceDropdown = $('#invoice-dropdown');

  // Retrieve values
  const idRfq = $('#id-rfq').val();
  const isPartialInvoices = $('input[name="is-partial-invoices"]').val();

  /**
   * Loads the invoice dropdown with the specified RFQ ID.
   * @param {string} idRfq - The RFQ ID to load invoices for.
   */
  function loadInvoiceDropdown(idRfq) {
    invoiceDropdown.load('/rfq/fulfillment/invoice/load_dropdown', { id: idRfq });
  }

  // Event listener to open the Add Invoice modal and initialize date picker
  invoiceDropdown.on('click', '#add_invoice', function (e) {
    e.preventDefault();

    // Reset the add invoice form
    addInvoiceForm[0].reset();

    // Date picker configuration for the 'created_at' field
    const dateOptions = {
      singleDatePicker: true,
      autoApply: true
    };

    $('#created_at').daterangepicker(dateOptions);

    // Open the Add Invoice modal
    addInvoiceModal.modal();
  });

  // Validation rules for the edit invoice form
  const invoiceFormValidationRules = {
    name: {
      required: true
    },
    created_at: {
      required: true
    }
  };

  addInvoiceForm.validate({
    rules: invoiceFormValidationRules,
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/invoice/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          if (response.error) {
            const errorMessage = `<label class="error">${response.error}</label>`;
            addInvoiceForm.find('#error').html(errorMessage);
          } else {
            addInvoiceModal.modal('hide');
            loadInvoiceDropdown(idRfq);
          }
        },
        error: function (xhr, status, error) {
          console.error("An error occurred:", error);
        }
      });
    }
  });

  // Modal and form elements for editing an invoice
  const editInvoiceModal = $('#edit-invoice-modal');
  const editInvoiceForm = $('#edit-invoice-form');

  // Date picker options for reuse
  const datePickerOptions = {
    singleDatePicker: true,
    autoApply: true
  };

  // Event listener to open the Edit Invoice modal and initialize form
  invoiceDropdown.on('click', '.edit-invoice-button', function (e) {
    e.preventDefault();
    const invoiceId = $(this).data('id');

    // Load the invoice data into the form and initialize the date picker
    editInvoiceForm.load(`/rfq/fulfillment/invoice/load`, { id: invoiceId }, function () {
      editInvoiceModal.modal('show');
      editInvoiceForm.find('#created_at').daterangepicker(datePickerOptions);
    });

    // Attach delete functionality to the delete button inside the form
    editInvoiceForm.off('click', '.delete-invoice-button').on('click', '.delete-invoice-button', deleteInvoice);
  });

  // Initialize form validation and handle submission with AJAX
  editInvoiceForm.validate({
    rules: invoiceFormValidationRules,
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/invoice/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          const errorMessageElement = editInvoiceModal.find('#error');
          errorMessageElement.empty(); // Clear previous messages

          if (response.error) {
            const message = `<label class="error">${response.error}</label>`;
            errorMessageElement.html(message);
          } else {
            editInvoiceModal.modal('hide');
            loadInvoiceDropdown(idRfq);
            fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${idRfq}`);
          }
        },
        error: function (xhr, status, error) {
          console.error("An error occurred:", error);
        }
      });
    }
  });

  // Helper function to perform AJAX requests
  function performAjaxRequest(url, type, data, successCallback) {
    $.ajax({
      url: url,
      type: type,
      data: data,
      success: successCallback,
      error: function (xhr, status, error) {
        console.error("An error occurred:", error);
      }
    });
  }

  // Function to delete an invoice
  function deleteInvoice(e) {
    const invoiceId = $(e.target).data('id');
    performAjaxRequest('/rfq/fulfillment/invoice/delete', 'POST', { id: invoiceId }, function (response) {
      editInvoiceModal.modal('hide');
      loadInvoiceDropdown(idRfq);
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${idRfq}`);
    });
  }

  // Event listener to attach sales commission to an invoice
  fulfillmentPage.on('click', '.attach-sales-commission-button', function () {
    const invoiceId = $(this).data('id');
    performAjaxRequest('/rfq/fulfillment/invoice/attach_sales_commission', 'POST', { id: invoiceId, idRfq: idRfq }, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  });

  // Event listener to load and edit an invoice
  invoiceDropdown.on('click', '.edit-invoice-button', function (e) {
    e.preventDefault();
    const invoiceId = $(this).data('id');

    editInvoiceForm.load(`/rfq/fulfillment/invoice/load`, { id: invoiceId }, function () {
      editInvoiceModal.modal('show');
      editInvoiceForm.find('#created_at').daterangepicker({
        singleDatePicker: true,
        autoApply: true
      });
    });

    // Use .off() to avoid multiple bindings
    editInvoiceForm.off('click', '.delete-invoice-button').on('click', '.delete-invoice-button', deleteInvoice);
  });

  // Initial load of invoice dropdown
  loadInvoiceDropdown(idRfq);
  /****************************************REVIEWED CHECK************************************/
  // Event listener for marking main items as reviewed
  fulfillmentPage.on('click', '.reviewed_button', function () {
    const data = {
      id_fulfillment_item: $(this).data('id'),
      id_item: $(this).data('id_item')
    };
    performAjaxRequest('/rfq/fulfillment/equipment/mark_as_reviewed/', 'POST', data, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
    return false;
  });

  // Event listener for marking subitems as reviewed
  fulfillmentPage.on('click', '.subitem_reviewed_button', function () {
    const data = {
      id_fulfillment_subitem: $(this).data('id'),
      id_subitem: $(this).data('id_subitem'),
      id_rfq: $(this).data('id_rfq')
    };
    performAjaxRequest('/rfq/fulfillment/equipment/mark_subitem_as_reviewed/', 'POST', data, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
    return false;
  });
  /****************************************REVIEWED SERVICE CHECK************************************/
  // Event listener for marking services as reviewed
  fulfillmentPage.on('click', '.reviewed_service_button', function () {
    const data = {
      id_fulfillment_service: $(this).data('id'),
      id_service: $(this).data('id_service')
    };

    performAjaxRequest('/rfq/fulfillment/service/mark_as_reviewed_service/', 'POST', data, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });

    return false;
  });
  /***************************************NET30 FULFILLMENT*************************************/
  // Helper function to handle Net 30 changes
  function handleNet30Change(selector, url) {
    fulfillmentPage.on('change', selector, function () {
      const net30Value = $(this).is(':checked') ? 1 : 0;
      const data = {
        id_rfq: $(this).data('id'),
        value: net30Value
      };

      performAjaxRequest(url, 'POST', data, function (res) {
        fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
      });
    });
  }

  // Initialize Net 30 change handlers for equipment and services
  handleNet30Change('#net30_cc', '/rfq/fulfillment/equipment/save_net_30/');
  handleNet30Change('#net30_cc_services', '/rfq/fulfillment/service/save_net_30/');
  /***********************************FULFILLMENT AUDIT TRAILS******************/
  const btnAuditTrail = $('#fulfillment_audit_trails_button');
  const auditTrailModal = $('#fulfillment_audit_trails_modal');
  const auditTrailModalBody = auditTrailModal.find('.modal-body');

  // Open Audit Trail modal and load data
  btnAuditTrail.on('click', function () {
    const data = { id_rfq: $(this).data('id') };
    auditTrailModalBody.load('/rfq/fulfillment/load_fulfillment_audit_trails', data, function () {
      auditTrailModal.modal('show');
    });
  });

  // Highlight selected audit trail item
  auditTrailModal.on('click', '.audit_trail_link', function () {
    auditTrailModal.modal('hide');
    const $targetElement = $($(this).data('target'));
    console.log($(this).data('target'));
    
    if ($targetElement.length) {
      $targetElement.addClass('highlight');
      setTimeout(() => $targetElement.removeClass('highlight'), 5000);
    }
  });
  /***********************************FULFILLMENT SHIPPING******************/
  let counterShipping = +$('#edit_fulfillment_shipping_modal form').data('counter') || 0;
  const editFulfillmentShippingModal = $('#edit_fulfillment_shipping_modal');
  const editFulfillmentShippingForm = $('#edit_fulfillment_shipping_form');

  // Open modal and load shipping data
  fulfillmentPage.on('click', '#edit_fulfillment_shipping', function () {
    const id = $(this).data('id');
    editFulfillmentShippingModal.find('form').load(`/rfq/fulfillment/equipment/load_fulfillment_shipping/${id}`, function () {
      toggleRemoveButton();
      editFulfillmentShippingModal.modal('show');
    });
  });

  // Handle form submission for updating shipping
  editFulfillmentShippingForm.on('submit', function (e) {
    e.preventDefault();
    $.post('/rfq/fulfillment/equipment/update_fulfillment_shipping', $(this).serialize(), function (res) {
      editFulfillmentShippingForm[0].reset();
      editFulfillmentShippingModal.modal('hide');
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  });

  // Add new shipping field set
  editFulfillmentShippingForm.on('click', '.add_shipping', function () {
    counterShipping++;
    updateShippingCounter();
    const shippingFields = createShippingFields(counterShipping);
    editFulfillmentShippingForm.find('.shipping_container').append(shippingFields);
    toggleRemoveButton();
  });

  // Remove last shipping field set
  editFulfillmentShippingForm.on('click', '.remove_shipping', function () {
    $(`.shipping${counterShipping}`).remove();
    counterShipping = Math.max(0, counterShipping - 1);
    updateShippingCounter();
    toggleRemoveButton();
  });

  // Helper function to toggle remove button
  function toggleRemoveButton() {
    editFulfillmentShippingForm.find('.remove_shipping').prop('disabled', counterShipping === 0);
  }

  // Helper function to update hidden shipping counter
  function updateShippingCounter() {
    $('input[name="shipping_counter"]').val(counterShipping);
  }

  // Helper function to create shipping fields
  function createShippingFields(counter) {
    return `
    <div class="shipping${counter}">
      <div class="form-group">
        <label for="fulfillment_shipping${counter}">Description:</label>
        <input type="hidden" name="fulfillment_shipping_original${counter}" value="">
        <input type="text" class="form-control form-control-sm" id="fulfillment_shipping${counter}" name="fulfillment_shipping${counter}" value="">
      </div>
      <div class="form-group">
        <label for="amount${counter}">Amount:</label>
        <input type="hidden" name="amount_original${counter}" value="">
        <input type="number" step="0.01" id="amount${counter}" class="form-control form-control-sm" name="amount${counter}" value="">
      </div>
    </div>
  `;
  }
  /***********************************FULFILLMENT SERVICES******************/
  const addFulfillmentServiceModal = $('#new_fulfillment_service_modal');
  const addFulfillmentServiceForm = $('#add_fulfillment_service_form');
  const editFulfillmentServiceModal = $('#edit_fulfillment_service_modal');
  const editFulfillmentServiceForm = $('#edit_fulfillment_service_form');

  // Open modal and load form for adding a new fulfillment service
  fulfillmentPage.on('click', '.add_fulfillment_service_button', function () {
    const idService = $(this).attr('name');
    loadFulfillmentServiceForm(addFulfillmentServiceModal, null, idService);
  });

  // Submit form for adding a new fulfillment service
  addFulfillmentServiceForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentService(addFulfillmentServiceForm, addFulfillmentServiceModal);
  });

  // Open modal and load form for editing a fulfillment service
  fulfillmentPage.on('click', '.edit_fulfillment_service_button', function (e) {
    e.preventDefault();
    const idFulfillmentService = $(this).data('id');
    loadFulfillmentServiceForm(editFulfillmentServiceModal, idFulfillmentService, null);
  });

  // Submit form for editing a fulfillment service
  editFulfillmentServiceForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentService(editFulfillmentServiceForm, editFulfillmentServiceModal, true);
  });

  // Delete fulfillment service
  fulfillmentPage.on('click', '.delete_fulfillment_service_button', function (e) {
    e.preventDefault();
    deleteFulfillmentService($(this).data('id'), $(this).attr('id_service'));
  });

  /**
   * Loads the fulfillment service form into the specified modal.
   */
  function loadFulfillmentServiceForm(modal, idFulfillmentService, idService) {
    modal.find('form').load('/rfq/fulfillment/service/load_fulfillment_service/', {
      idService: idService,
      idFulfillmentService: idFulfillmentService,
      isPartialInvoices: isPartialInvoices,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      modal.modal('show');
    });
  }

  /**
   * Submits the fulfillment service form (either add or edit) and reloads the page content.
   */
  function saveFulfillmentService(form, modal, isEdit = false) {
    const url = isEdit ? '/rfq/fulfillment/service/save_edit_fulfillment_service' : '/rfq/fulfillment/service/save_fulfillment_service';
    $.post(url, form.serialize(), function (res) {
      form[0].reset();
      modal.modal('hide');
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }

  /**
   * Deletes a fulfillment service and reloads the page content.
   */
  function deleteFulfillmentService(idFulfillmentService, idService) {
    $.post('/rfq/fulfillment/service/delete_fulfillment_service/', { id_fulfillment_service: idFulfillmentService, id_service: idService }, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }
  /***********************************FULFILLMENT***************************/
  /**
   * Fulfillment Items
   */
  const addFulfillmentItemModal = $('#new_fulfillment_item_modal');
  const addFulfillmentItemForm = $('#add_fulfillment_item_form');
  const editFulfillmentItemModal = $('#edit_fulfillment_item_modal');
  const editFulfillmentItemForm = $('#edit_fulfillment_item_form');

  // Open modal and load form for adding a new fulfillment item
  fulfillmentPage.on('click', '.add_fulfillment_item_button', function () {
    const idItem = $(this).attr('name');
    loadFulfillmentItemForm(addFulfillmentItemModal, null, idItem);
  });

  // Submit form for adding a new fulfillment item
  addFulfillmentItemForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentItem(addFulfillmentItemForm, addFulfillmentItemModal);
  });

  // Open modal and load form for editing a fulfillment item
  fulfillmentPage.on('click', '.edit_fulfillment_item_button', function () {
    const idFulfillmentItem = $(this).data('id');
    loadFulfillmentItemForm(editFulfillmentItemModal, idFulfillmentItem, null);
  });

  // Submit form for editing a fulfillment item
  editFulfillmentItemForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentItem(editFulfillmentItemForm, editFulfillmentItemModal, true);
  });

  // Delete fulfillment item
  fulfillmentPage.on('click', '.delete_fulfillment_item_button', function (e) {
    e.preventDefault();
    deleteFulfillmentItem($(this).data('id'), $(this).attr('id_item'));
  });

  /**
   * Loads the fulfillment item form into the specified modal.
   */
  function loadFulfillmentItemForm(modal, idFulfillmentItem, idItem) {
    modal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_item/', {
      isPartialInvoices: isPartialInvoices,
      idItem: idItem,
      idFulfillmentItem: idFulfillmentItem,
      idRfq: idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      modal.modal('show');
    });
  }

  /**
   * Submits the fulfillment item form (either add or edit) and reloads the page content.
   */
  function saveFulfillmentItem(form, modal, isEdit = false) {
    const url = isEdit ? '/rfq/fulfillment/equipment/save_edit_fulfillment_item' : '/rfq/fulfillment/equipment/save_fulfillment_item';
    $.post(url, form.serialize(), function (res) {
      form[0].reset();
      modal.modal('hide');
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }

  /**
   * Deletes a fulfillment item and reloads the page content.
   */
  function deleteFulfillmentItem(idFulfillmentItem, idItem) {
    $.post('/rfq/fulfillment/equipment/delete_fulfillment_item/', { id_fulfillment_item: idFulfillmentItem, id_item: idItem }, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }

  /***********************************FULFILLMENT SUBITEMS***************************/

  /**
   * Fulfillment Subitems
   */
  const addFulfillmentSubitemModal = $('#new_fulfillment_subitem_modal');
  const addFulfillmentSubitemForm = $('#add_fulfillment_subitem_form');
  const editFulfillmentSubitemModal = $('#edit_fulfillment_subitem_modal');
  const editFulfillmentSubitemForm = $('#edit_fulfillment_subitem_form');

  // Open modal and load form for adding a new fulfillment subitem
  fulfillmentPage.on('click', '.add_fulfillment_subitem_button', function () {
    const idSubitem = $(this).attr('name');
    loadFulfillmentSubitemForm(addFulfillmentSubitemModal, null, idSubitem);
  });

  // Submit form for adding a new fulfillment subitem
  addFulfillmentSubitemForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentSubitem(addFulfillmentSubitemForm, addFulfillmentSubitemModal);
  });

  // Open modal and load form for editing a fulfillment subitem
  fulfillmentPage.on('click', '.edit_fulfillment_subitem_button', function () {
    const idFulfillmentSubitem = $(this).data('id');
    loadFulfillmentSubitemForm(editFulfillmentSubitemModal, idFulfillmentSubitem, null);
  });

  // Submit form for editing a fulfillment subitem
  editFulfillmentSubitemForm.on('submit', function (e) {
    e.preventDefault();
    saveFulfillmentSubitem(editFulfillmentSubitemForm, editFulfillmentSubitemModal, true);
  });

  // Delete fulfillment subitem
  fulfillmentPage.on('click', '.delete_fulfillment_subitem_button', function (e) {
    e.preventDefault();
    const idFulfillmentSubitem = $(this).data('id');
    const idSubitem = $(this).attr('id_subitem');
    const idRfq = $(this).attr('id_rfq');
    deleteFulfillmentSubitem(idFulfillmentSubitem, idSubitem, idRfq);
  });

  /**
   * Loads the fulfillment subitem form into the specified modal.
   */
  function loadFulfillmentSubitemForm(modal, idFulfillmentSubitem, idSubitem) {
    modal.find('form').load('/rfq/fulfillment/equipment/load_fulfillment_subitem/', {
      isPartialInvoices,
      idFulfillmentSubitem,
      idSubitem,
      idRfq
    }, function () {
      setDateField($(this).find('#transaction_date'));
      modal.modal('show');
    });
  }

  /**
   * Submits the fulfillment subitem form (either add or edit) and reloads the page content.
   */
  function saveFulfillmentSubitem(form, modal, isEdit = false) {
    const url = isEdit ? '/rfq/fulfillment/equipment/save_edit_fulfillment_subitem' : '/rfq/fulfillment/equipment/save_fulfillment_subitem';
    $.post(url, form.serialize(), function (res) {
      form[0].reset();
      modal.modal('hide');
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }

  /**
   * Deletes a fulfillment subitem and reloads the page content.
   */
  function deleteFulfillmentSubitem(idFulfillmentSubitem, idSubitem, idRfq) {
    $.post('/rfq/fulfillment/equipment/delete_fulfillment_subitem/', { id_fulfillment_subitem: idFulfillmentSubitem, id_subitem: idSubitem, id_rfq: idRfq }, function (res) {
      fulfillmentPage.load(`/rfq/fulfillment/load_fulfillment_page/${res.id_rfq}`);
    });
  }
});