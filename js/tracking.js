$(document).ready(function () {
  // Initialize tooltips
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'left',
  });

  // Initialize date pickers
  function initializeDatePicker(selector) {
    $(selector).daterangepicker({
      singleDatePicker: true,
    });
  }

  // Reload tracking box
  function reloadTrackingBox(rfqId) {
    $('#tracking_box').load(`/rfq/tracking/load_tracking_box/${rfqId}`);
  }

  // Open modal for adding tracking
  $('#tracking_box').on('click', '.add_tracking_button', function () {
    const idItem = $(this).attr('name');
    $('#new_tracking #id_item').val(idItem);
    $('#new_tracking').modal('show');
  });

  // Open modal for editing tracking
  $('#tracking_box').on('click', '.edit_tracking', function (e) {
    e.preventDefault();
    const url = `/rfq/tracking/load_tracking/${$(this).attr('data')}`;
    $('#edit_tracking_modal form').load(url, function () {
      initializeDatePicker('.date');
      $('#edit_tracking_modal').modal('show');
    });
  });

  // Handle tracking edit form submission
  $('#edit_tracking_form').on('submit', function (e) {
    e.preventDefault();
    $.post('/rfq/tracking/save_edit_tracking', $(this).serialize(), function (res) {
      $('#edit_tracking_modal').modal('hide');
      reloadTrackingBox(res.id_rfq);
    });
  });

  // Open modal for adding tracking subitem
  $('#tracking_box').on('click', '.add_tracking_subitem_button', function () {
    const idSubitem = $(this).attr('name');
    $('#new_tracking_subitem #id_subitem').val(idSubitem);
    $('#new_tracking_subitem').modal('show');
  });

  // Open modal for editing tracking subitem
  $('#tracking_box').on('click', '.edit_tracking_subitem', function (e) {
    e.preventDefault();
    const url = `/rfq/tracking/load_tracking_subitem/${$(this).attr('data')}`;
    $('#edit_tracking_subitem_modal form').load(url, function () {
      initializeDatePicker('.date');
      $('#edit_tracking_subitem_modal').modal('show');
    });
  });

  // Handle tracking subitem edit form submission
  $('#edit_tracking_subitem_form').on('submit', function (e) {
    e.preventDefault();
    $.post('/rfq/tracking/save_edit_tracking_subitem', $(this).serialize(), function (res) {
      $('#edit_tracking_subitem_modal').modal('hide');
      reloadTrackingBox(res.id_rfq);
    });
  });

  // Initialize date pickers
  initializeDatePicker('#delivery_date, #due_date');
  initializeDatePicker('#delivery_date_subitem');
});