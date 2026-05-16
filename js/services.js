$(document).ready(function () {
  var $addServiceModal  = $('#add_service_modal');
  var $addServiceForm   = $('#add_service_form');
  var $editServiceModal = $('#edit_service_modal');
  var $editServiceForm  = $('#edit_service_form');

  /* ---------- Helpers ---------- */
  function rfqId() {
    return $addServiceForm.find('[name="id_rfq"]').val() ||
           $('[name="id_rfq"]').first().val();
  }

  function refreshServicesSection() {
    var id = rfqId();
    if (!id) return;
    $.get('/rfq/quote/service/get_services_table/' + id, function (html) {
      $('#services_section').html(html);
    });
  }

  function svcAjaxPost(url, data, onSuccess) {
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
    .fail(function () { toastr.error('Network error. Please try again.'); });
  }

  function svcAjaxGet(url, onSuccess) {
    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .done(function (res) {
      if (res && res.success) {
        onSuccess();
      } else {
        toastr.error(res && res.message ? res.message : 'Could not complete action.');
      }
    })
    .fail(function () { toastr.error('Network error. Please try again.'); });
  }

  /* ---------- Add Service ---------- */
  $('#add_service').on('click', function () {
    $addServiceModal.modal('show');
  });

  $addServiceModal.on('click', '.svc-add-save-btn', function () {
    var data = $addServiceForm.serialize() + '&add_service_button=1';
    svcAjaxPost('/rfq/quote/service/add_service', data, function () {
      $addServiceForm[0].reset();
      refreshServicesSection();
      toastr.success('Service added.');
    });
  });

  $addServiceModal.on('hidden.bs.modal', function () {
    $addServiceForm[0].reset();
  });

  /* ---------- Edit Service (form loaded via AJAX into modal) ---------- */
  function loadEditServiceForm(serviceId) {
    $editServiceForm.html('<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i></div>');
    $editServiceModal.modal('show');
    $editServiceForm.load('/rfq/quote/service/load_service/' + serviceId);
  }

  // Open edit modal — delegated so it works after table refreshes
  $(document).on('click', '.edit_service', function () {
    var serviceId = $(this).attr('data');
    if (serviceId) loadEditServiceForm(serviceId);
  });

  $editServiceModal.on('click', '.svc-edit-save-btn', function () {
    var data = $editServiceForm.serialize() + '&edit_service_button=1';
    svcAjaxPost('/rfq/quote/service/edit_service', data, function () {
      $editServiceModal.modal('hide');
      refreshServicesSection();
      toastr.success('Service updated.');
    });
  });

  $editServiceModal.on('hidden.bs.modal', function () {
    $editServiceForm.html('');
  });

  /* ---------- Delete Service ---------- */
  $(document).on('click', '.svc-delete-btn', function () {
    var url = $(this).data('url');
    window.confirmDelete('Delete this service?', function () {
      svcAjaxGet(url, function () {
        refreshServicesSection();
        toastr.success('Service deleted.');
      });
    });
  });

  /* ---------- Duplicate Service ---------- */
  $(document).on('click', '.svc-duplicate-btn', function () {
    var url = $(this).data('url');
    svcAjaxGet(url, function () {
      refreshServicesSection();
      toastr.success('Service duplicated.');
    });
  });
});
