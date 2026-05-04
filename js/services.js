$(document).ready(function () {
  const servicesTable = $('#services_table');
  const editServiceModal = $('#edit_service_modal');
  const addServiceModal = $('#add_service_modal');

  // Function to handle service editing
  function handleEditService(serviceId) {
    const loadUrl = `/rfq/quote/service/load_service/${serviceId}`;
    editServiceModal.find('form').load(loadUrl, function () {
      editServiceModal.modal('show');
    });
  }

  // Event listener for editing a service
  servicesTable.on('click', '.edit_service', function () {
    const serviceId = $(this).attr('data');
    if (serviceId) {
      handleEditService(serviceId);
    } else {
      console.error('Service ID is missing');
    }
  });

  // Event listener for adding a new service
  $('#add_service').on('click', function () {
    addServiceModal.modal('show');
  });
});
