$(document).ready(function () {
  const monto = [];
  const quantity = [];

  function populateCalcArrays() {
    monto.length = 0;
    quantity.length = 0;
    $('#items tr').each(function () {
      quantity.push(parseFloat($(this).find('td').eq(5).text()) || 0);
      monto.push(parseFloat($(this).find('td').eq(8).text().split(' ')[1]) || 0);
    });
  }

  populateCalcArrays();

  // Exposed so the inline-editing IIFE can re-sync arrays after a table refresh
  window.iemReinitCalc = function () {
    populateCalcArrays();
  };

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
    const percentageProfit = (totalPrice > 0 ? ((profit / totalPrice) * 100) : 0).toFixed(2);
    const currentTotalQty = quantity.reduce((s, q) => s + q, 0);

    // Update input fields
    $('#additional').val(singleAdditionalItems.join());
    $('#additional_subitems').val(singleAdditionalSubItems.join());
    $('#unit_prices').val(unitPricesItems.join());
    $('#unit_prices_subitems').val(unitPricesSubItems.join());
    $('#partes_total_price').val(singleTotalPriceItems.join());
    $('#partes_total_price_subitems').val(singleTotalPriceSubItems.join());
    $('#total_cost').val(totalCost.toFixed(2));
    $('#total_price').val(totalPrice.toFixed(2));

    // Update table footer
    $('#total1').html(`$ ${totalCost.toFixed(2)}`);
    $('#total2').html(`$ ${totalPrice.toFixed(2)}`);
    $('#dif_total').html(`$ ${profit}<br>${percentageProfit}%`);
    $('#total_quantity').html(currentTotalQty);
    $('#total_additional').html(`$ ${totalAdditional.toFixed(2)}`);

    // Services calculation — runs in the same tick so the total feeds into the bottom bar
    const svcPaymentMultiplier = $('input:radio[name=services_payment_term]:checked').val() === 'Net 30/CC' ? 1.03 : 1;
    let totalServices = 0;
    $('#services_table tbody .service_item').each(function () {
      const $cells = $(this).find('td');
      const $unitCell = $cells.eq(4);
      const basePrice = parseFloat($unitCell.data('base-price') || $unitCell.text()) || 0;
      if (!$unitCell.data('base-price')) $unitCell.data('base-price', basePrice);
      const qty = parseFloat($cells.eq(3).text()) || 0;
      const newUnitPrice = (basePrice * svcPaymentMultiplier).toFixed(2);
      const newTotalPrice = (basePrice * svcPaymentMultiplier * qty).toFixed(2);
      totalServices += parseFloat(newTotalPrice);
      $unitCell.html(newUnitPrice);
      $cells.eq(5).html(newTotalPrice);
    });
    $('#total_service').html(`$ ${totalServices.toFixed(2)}`);
    totalPrice += totalServices;

    // Update sticky bottom bar
    $('#bar-total-price').text(`$${totalPrice.toFixed(2)}`);
    $('#bar-total-profit').text(`$${profit}`);
    $('#bar-profit-pct').text(`${percentageProfit}%`);
  };

  // Set interval for dynamic updates and trigger on form submission
  if ($('#form_edited_quote').length) {
    const interval = setInterval(calcQuoteTable, 100);
    $('#form_edited_quote').submit(() => {
      clearInterval(interval);
      calcQuoteTable();
    });
  }

  /************************* AUTO-SAVE *************************/
  if ($('#form_edited_quote').length) {
    const autosaveUrl = '/rfq/quote/autosave/' + ($('[name="id_rfq"]').val() || '');
    let autosaveTimer = null;

    function setOriginals() {
      $('input[name="taxes_original"]').val($('[name="taxes"]').val());
      $('input[name="profit_original"]').val($('[name="profit"]').val());
      $('input[name="additional_general_original"]').val($('[name="additional_general"]').val());
      $('input[name="payment_terms_original"]').val($('input[name="payment_terms"]:checked').val());
      $('input[name="shipping_original"]').val($('[name="shipping"]').val());
      $('input[name="shipping_cost_original"]').val($('[name="shipping_cost"]').val());
    }

    function doAutosave() {
      $.ajax({
        url: autosaveUrl,
        type: 'POST',
        data: $('#form_edited_quote').serialize(),
        dataType: 'json',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .done(function (res) {
        if (res && res.success) {
          setOriginals();
          toastr.success('Changes saved', '', { timeOut: 1500, positionClass: 'toast-top-right' });
        } else {
          toastr.error('Auto-save failed', '', { timeOut: 3000, positionClass: 'toast-top-right' });
        }
      })
      .fail(function () {
        toastr.error('Auto-save failed', '', { timeOut: 3000, positionClass: 'toast-top-right' });
      });
    }

    function scheduleAutosave() {
      clearTimeout(autosaveTimer);
      autosaveTimer = setTimeout(doAutosave, 1500);
    }

    // Watch quote-level fields — input fires on every keystroke, change catches blur/select
    $('#form_edited_quote').on(
      'input change',
      '#taxes, #profit, #additional_general, #shipping_cost',
      scheduleAutosave
    );
    $('#form_edited_quote').on(
      'change',
      'input[name="payment_terms"], input[name="services_payment_term"]',
      scheduleAutosave
    );
    $('#form_edited_quote').on('input blur', '#shipping', scheduleAutosave);

    // Watch per-item additional cost inputs (dynamically rendered)
    $(document).on('input change', 'input[id^="add_cost"]', scheduleAutosave);

    // Manual save clears the pending timer
    $('#form_edited_quote').on('submit', function () {
      clearTimeout(autosaveTimer);
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
  /************************* IMPORT ITEMS MODAL *************************/
  const importItemsButton = $('#import-items-modal-button');
  const importItemsModal = $('#import-items-modal');

  // Open modal when the link quote button is clicked
  importItemsButton.on('click', function (e) {
    e.preventDefault();
    importItemsModal.modal('show');
  });
});

/* ============================================================
   Inline Editing Modals
   ============================================================ */
(function ($) {
  'use strict';

  var BASE = '/rfq/quote/equipment/';

  // Read id_rfq injected by PHP into the add-item modal data attribute
  function rfqId() {
    return $('#add-item-modal').data('rfq-id');
  }

  /* ---------- Table refresh ---------- */
  function syncIdInputs() {
    var itemIds = [], subitemIds = [];
    $('#items tr[id^="item"]').each(function () { itemIds.push(this.id.replace('item', '')); });
    $('#items tr[id^="subitem"]').each(function () { subitemIds.push(this.id.replace('subitem', '')); });
    $('#id_items').val(itemIds.join(','));
    $('#id_subitems').val(subitemIds.join(','));
  }

  function refreshItemsTable() {
    $.get('/rfq/quote/get_items_table/' + rfqId(), function (html) {
      $('#items').html(html);
      if (typeof window.iemReinitCalc === 'function') window.iemReinitCalc();
      syncIdInputs();
    });
  }

  /* ---------- Summernote helpers ---------- */
  function destroySummernote($ctx) {
    $ctx.find('.summernote_textarea').each(function () {
      if ($(this).hasClass('note-editor')) return;
      try { $(this).summernote('destroy'); } catch (e) {}
    });
  }

  function initSummernote($ctx) {
    destroySummernote($ctx);
    $ctx.find('.summernote_textarea').summernote({
      height: 80,
      toolbar: [
        ['style', ['bold', 'italic', 'underline']],
        ['para', ['ul', 'ol']],
        ['insert', ['link']]
      ]
    });
  }

  /* ---------- Dirty-check ---------- */
  function captureSnapshot($form) {
    $form.data('iem-snapshot', $form.serialize());
  }

  function isDirty($form) {
    return $form.data('iem-snapshot') !== $form.serialize();
  }

  function showDirtyWarn($modal, warnId) {
    $modal.find('.iem-footer-row:last').hide();
    $('#' + warnId).show();
  }

  function hideDirtyWarn($modal, warnId) {
    $modal.find('.iem-footer-row:last').show();
    $('#' + warnId).hide();
  }

  /* ---------- Generic modal close guard ---------- */
  function wireCloseGuard($modal, $form, warnId) {
    // Bootstrap fires hide.bs.modal before actually closing
    $modal.on('hide.bs.modal.iemguard', function (e) {
      if ($modal.data('iem-discard')) return; // discard flag set — allow close
      if (isDirty($form)) {
        e.preventDefault();
        showDirtyWarn($modal, warnId);
      }
    });

    $modal.on('hidden.bs.modal.iemguard', function () {
      $modal.removeData('iem-discard');
      hideDirtyWarn($modal, warnId);
      $modal.find('.iem-footer-row:last').show();
    });

    $modal.on('click', '.iem-keep-editing', function () {
      hideDirtyWarn($modal, warnId);
    });

    $modal.on('click', '.iem-btn-discard', function () {
      $modal.data('iem-discard', true);
    });
  }

  /* ---------- Generic AJAX form submit ---------- */
  function ajaxSubmit($form, url, buttonName, onSuccess) {
    var data = $form.serialize() + '&' + buttonName + '=1';
    // Collect Summernote values (they don't serialize via .serialize())
    $form.find('.summernote_textarea').each(function () {
      var name = $(this).attr('name');
      if (name) {
        try {
          var val = $(this).summernote('code');
          data += '&' + encodeURIComponent(name) + '=' + encodeURIComponent(val);
        } catch (e) {}
      }
    });

    $.ajax({
      url: url,
      type: 'POST',
      data: data,
      dataType: 'json',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .done(function (res) {
      if (res && res.success) {
        onSuccess();
      } else {
        toastr.error(res && res.message ? res.message : 'Error saving. Please try again.');
      }
    })
    .fail(function () {
      toastr.error('Network error. Please try again.');
    });
  }

  /* ---------- Generic AJAX load into edit modal ---------- */
  function loadEditModal($modal, $body, loadUrl, onLoaded) {
    $body.html('<div class="iem-loading"><div class="iem-spinner"></div></div>');
    $.ajax({
      url: loadUrl,
      type: 'GET',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .done(function (html) {
      $body.html(html);
      initSummernote($body);
      if (typeof onLoaded === 'function') onLoaded();
    })
    .fail(function () {
      $body.html('<p class="text-danger p-3">Failed to load form.</p>');
    });
  }

  /* ======================================================
     ADD ITEM
  ====================================================== */
  var $addItemModal = $('#add-item-modal');
  var $addItemForm  = $('#iem-add-item-form');

  wireCloseGuard($addItemModal, $addItemForm, 'iem-add-item-warn');

  $addItemModal.on('shown.bs.modal', function () {
    captureSnapshot($addItemForm);
    initSummernote($addItemForm);
    $addItemForm.find('input[type="text"]:first').focus();
  });

  $addItemModal.on('hidden.bs.modal', function () {
    destroySummernote($addItemForm);
    $addItemForm[0].reset();
  });

  $addItemModal.on('click', '.iem-save-btn', function () {
    var url = BASE + 'guardar_add_item/' + rfqId();
    ajaxSubmit($addItemForm, url, 'guardar_item', function () {
      destroySummernote($addItemForm);
      $addItemForm[0].reset();
      initSummernote($addItemForm);
      captureSnapshot($addItemForm);
      refreshItemsTable();
      toastr.success('Item added.');
      $addItemForm.find('input[type="text"]:first').focus();
    });
  });

  /* ======================================================
     EDIT ITEM
  ====================================================== */
  var $editItemModal = $('#edit-item-modal');
  var $editItemForm  = $('#iem-edit-item-form');
  var $editItemBody  = $('#iem-edit-item-body');

  wireCloseGuard($editItemModal, $editItemForm, 'iem-edit-item-warn');

  $editItemModal.on('hidden.bs.modal', function () {
    destroySummernote($editItemBody);
    $editItemBody.html('<div class="iem-loading"><div class="iem-spinner"></div></div>');
    $('#edit-item-subtitle').text('Loading…');
  });

  $(document).on('click', '.iem-edit-item', function () {
    var loadUrl = $(this).data('load-url');
    $editItemModal.modal('show');
    loadEditModal($editItemModal, $editItemBody, loadUrl, function () {
      captureSnapshot($editItemForm);
    });
  });

  $editItemModal.on('click', '.iem-save-btn', function () {
    var idItem = $editItemForm.find('input[name="id_item"]').val();
    var url    = BASE + 'guardar_edit_item/' + idItem;
    ajaxSubmit($editItemForm, url, 'guardar_cambios_item', function () {
      $editItemModal.data('iem-discard', true);
      $editItemModal.modal('hide');
      refreshItemsTable();
      toastr.success('Item updated.');
    });
  });

  /* ======================================================
     ADD SUBITEM
  ====================================================== */
  var $addSubModal = $('#add-subitem-modal');
  var $addSubForm  = $('#iem-add-subitem-form');

  wireCloseGuard($addSubModal, $addSubForm, 'iem-add-subitem-warn');

  $addSubModal.on('shown.bs.modal', function () {
    captureSnapshot($addSubForm);
    initSummernote($addSubForm);
    $addSubForm.find('input[type="text"]:first').focus();
  });

  $addSubModal.on('hidden.bs.modal', function () {
    destroySummernote($addSubForm);
    $addSubForm[0].reset();
  });

  $(document).on('click', '.iem-add-subitem', function () {
    var idItem = $(this).data('id-item');
    $('#iem-add-subitem-id-item').val(idItem);
    $addSubModal.modal('show');
  });

  $addSubModal.on('click', '.iem-save-btn', function () {
    var idItem = $('#iem-add-subitem-id-item').val();
    var url    = BASE + 'guardar_add_subitem/' + idItem;
    ajaxSubmit($addSubForm, url, 'guardar_subitem', function () {
      var savedIdItem = idItem;
      destroySummernote($addSubForm);
      $addSubForm[0].reset();
      $('#iem-add-subitem-id-item').val(savedIdItem);
      initSummernote($addSubForm);
      captureSnapshot($addSubForm);
      refreshItemsTable();
      toastr.success('Subitem added.');
      $addSubForm.find('input[type="text"]:first').focus();
    });
  });

  /* ======================================================
     EDIT SUBITEM
  ====================================================== */
  var $editSubModal = $('#edit-subitem-modal');
  var $editSubForm  = $('#iem-edit-subitem-form');
  var $editSubBody  = $('#iem-edit-subitem-body');

  wireCloseGuard($editSubModal, $editSubForm, 'iem-edit-subitem-warn');

  $editSubModal.on('hidden.bs.modal', function () {
    destroySummernote($editSubBody);
    $editSubBody.html('<div class="iem-loading"><div class="iem-spinner"></div></div>');
    $('#edit-subitem-subtitle').text('Loading…');
  });

  $(document).on('click', '.iem-edit-subitem', function () {
    var loadUrl = $(this).data('load-url');
    $editSubModal.modal('show');
    loadEditModal($editSubModal, $editSubBody, loadUrl, function () {
      captureSnapshot($editSubForm);
    });
  });

  $editSubModal.on('click', '.iem-save-btn', function () {
    var idSub = $editSubForm.find('input[name="id_subitem"]').val();
    var url   = BASE + 'guardar_edit_subitem/' + idSub;
    ajaxSubmit($editSubForm, url, 'guardar_cambios_subitem', function () {
      $editSubModal.data('iem-discard', true);
      $editSubModal.modal('hide');
      refreshItemsTable();
      toastr.success('Subitem updated.');
    });
  });

  /* ======================================================
     ADD PROVIDER (for item)
  ====================================================== */
  var $addProvModal = $('#add-provider-modal');
  var $addProvForm  = $('#iem-add-provider-form');

  wireCloseGuard($addProvModal, $addProvForm, 'iem-add-provider-warn');

  $addProvModal.on('shown.bs.modal', function () {
    captureSnapshot($addProvForm);
    $addProvForm.find('input[type="text"]:first').focus();
  });

  $addProvModal.on('hidden.bs.modal', function () {
    $addProvForm[0].reset();
  });

  $(document).on('click', '.iem-add-provider', function () {
    var idItem = $(this).data('id-item');
    $('#iem-add-provider-id-item').val(idItem);
    $addProvModal.modal('show');
  });

  $addProvModal.on('click', '.iem-save-btn', function () {
    var idItem = $('#iem-add-provider-id-item').val();
    var url    = BASE + 'guardar_add_provider/' + idItem;
    ajaxSubmit($addProvForm, url, 'guardar_provider', function () {
      var savedIdItem = idItem;
      $addProvForm[0].reset();
      $('#iem-add-provider-id-item').val(savedIdItem);
      captureSnapshot($addProvForm);
      refreshItemsTable();
      toastr.success('Provider added.');
      $addProvForm.find('input[type="text"]:first').focus();
    });
  });

  /* ======================================================
     EDIT PROVIDER (for item)
  ====================================================== */
  var $editProvModal = $('#edit-provider-modal');
  var $editProvForm  = $('#iem-edit-provider-form');
  var $editProvBody  = $('#iem-edit-provider-body');

  wireCloseGuard($editProvModal, $editProvForm, 'iem-edit-provider-warn');

  $editProvModal.on('hidden.bs.modal', function () {
    $editProvBody.html('<div class="iem-loading"><div class="iem-spinner"></div></div>');
    $('#edit-provider-subtitle').text('Loading…');
  });

  $(document).on('click', '.iem-edit-provider', function () {
    var loadUrl = $(this).data('load-url');
    $editProvModal.modal('show');
    loadEditModal($editProvModal, $editProvBody, loadUrl, function () {
      captureSnapshot($editProvForm);
    });
  });

  $editProvModal.on('click', '.iem-save-btn', function () {
    var idProv = $editProvForm.find('input[name="id_provider"]').val();
    var url    = BASE + 'guardar_edit_provider/' + idProv;
    ajaxSubmit($editProvForm, url, 'guardar_cambios_provider', function () {
      $editProvModal.data('iem-discard', true);
      $editProvModal.modal('hide');
      refreshItemsTable();
      toastr.success('Provider updated.');
    });
  });

  $editProvModal.on('click', '.iem-delete-provider-btn', function () {
    var idProv = $editProvForm.find('input[name="id_provider"]').val();
    window.confirmDelete('Delete this provider?', function () {
      $.ajax({ url: BASE + 'delete_provider/' + idProv, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) {
            $editProvModal.data('iem-discard', true);
            $editProvModal.modal('hide');
            refreshItemsTable();
            toastr.success('Provider deleted.');
          } else {
            toastr.error('Could not delete provider.');
          }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

  /* ======================================================
     ADD PROVIDER SUBITEM
  ====================================================== */
  var $addPsiModal = $('#add-provider-subitem-modal');
  var $addPsiForm  = $('#iem-add-provider-subitem-form');

  wireCloseGuard($addPsiModal, $addPsiForm, 'iem-add-psi-warn');

  $addPsiModal.on('shown.bs.modal', function () {
    captureSnapshot($addPsiForm);
    $addPsiForm.find('input[type="text"]:first').focus();
  });

  $addPsiModal.on('hidden.bs.modal', function () {
    $addPsiForm[0].reset();
  });

  $(document).on('click', '.iem-add-provider-subitem', function () {
    var idSub = $(this).data('id-subitem');
    $('#iem-add-psi-id-subitem').val(idSub);
    $addPsiModal.modal('show');
  });

  $addPsiModal.on('click', '.iem-save-btn', function () {
    var idSub = $('#iem-add-psi-id-subitem').val();
    var url   = BASE + 'guardar_add_provider_subitem/' + idSub;
    ajaxSubmit($addPsiForm, url, 'guardar_provider_subitem', function () {
      var savedIdSub = idSub;
      $addPsiForm[0].reset();
      $('#iem-add-psi-id-subitem').val(savedIdSub);
      captureSnapshot($addPsiForm);
      refreshItemsTable();
      toastr.success('Provider added.');
      $addPsiForm.find('input[type="text"]:first').focus();
    });
  });

  /* ======================================================
     EDIT PROVIDER SUBITEM
  ====================================================== */
  var $editPsiModal = $('#edit-provider-subitem-modal');
  var $editPsiForm  = $('#iem-edit-provider-subitem-form');
  var $editPsiBody  = $('#iem-edit-provider-subitem-body');

  wireCloseGuard($editPsiModal, $editPsiForm, 'iem-edit-psi-warn');

  $editPsiModal.on('hidden.bs.modal', function () {
    $editPsiBody.html('<div class="iem-loading"><div class="iem-spinner"></div></div>');
    $('#edit-provider-subitem-subtitle').text('Loading…');
  });

  $(document).on('click', '.iem-edit-provider-subitem', function () {
    var loadUrl = $(this).data('load-url');
    $editPsiModal.modal('show');
    loadEditModal($editPsiModal, $editPsiBody, loadUrl, function () {
      captureSnapshot($editPsiForm);
    });
  });

  $editPsiModal.on('click', '.iem-save-btn', function () {
    var idPsi = $editPsiForm.find('input[name="id_provider_subitem"]').val();
    var url   = BASE + 'guardar_edit_provider_subitem/' + idPsi;
    ajaxSubmit($editPsiForm, url, 'guardar_cambios_provider_subitem', function () {
      $editPsiModal.data('iem-discard', true);
      $editPsiModal.modal('hide');
      refreshItemsTable();
      toastr.success('Provider updated.');
    });
  });

  $editPsiModal.on('click', '.iem-delete-provider-subitem-btn', function () {
    var idPsi = $editPsiForm.find('input[name="id_provider_subitem"]').val();
    window.confirmDelete('Delete this provider?', function () {
      $.ajax({ url: BASE + 'delete_provider_subitem/' + idPsi, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) {
            $editPsiModal.data('iem-discard', true);
            $editPsiModal.modal('hide');
            refreshItemsTable();
            toastr.success('Provider deleted.');
          } else {
            toastr.error('Could not delete provider.');
          }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

  /* ======================================================
     DELETE handlers (delegated — rows are AJAX-refreshed)
  ====================================================== */
  $(document).on('click', '.iem-delete-item', function () {
    var url = $(this).data('url');
    window.confirmDelete('Delete this item and all its subitems?', function () {
      $.ajax({ url: url, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) { refreshItemsTable(); toastr.success('Item deleted.'); }
          else { toastr.error('Could not delete item.'); }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

  $(document).on('click', '.iem-delete-subitem', function () {
    var url = $(this).data('url');
    window.confirmDelete('Delete this subitem?', function () {
      $.ajax({ url: url, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) { refreshItemsTable(); toastr.success('Subitem deleted.'); }
          else { toastr.error('Could not delete subitem.'); }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

  /* ---------- Provider delete links (rendered inline as part of the provider name cell) ---------- */
  $(document).on('click', '.iem-delete-provider', function () {
    var url = $(this).data('url');
    window.confirmDelete('Delete this provider?', function () {
      $.ajax({ url: url, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) { refreshItemsTable(); toastr.success('Provider deleted.'); }
          else { toastr.error('Could not delete provider.'); }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

  $(document).on('click', '.iem-delete-provider-subitem', function () {
    var url = $(this).data('url');
    window.confirmDelete('Delete this provider?', function () {
      $.ajax({ url: url, type: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .done(function (res) {
          if (res && res.success) { refreshItemsTable(); toastr.success('Provider deleted.'); }
          else { toastr.error('Could not delete provider.'); }
        })
        .fail(function () { toastr.error('Network error.'); });
    });
  });

}(jQuery));
