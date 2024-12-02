$(document).ready(function () {
  // Initialize Providers DataTable
  const providersTable = $('#providers_table').DataTable({
    ajax: '/rfq/provider/load_providers_table/',
    columnDefs: [{ className: "text-center", targets: [1] }],
  });

  // Handle Add Provider Modal
  const addProviderModal = $('#add_provider_modal');
  const addProviderForm = $('#add_provider_form');

  $('#add_provider').click(() => {
    addProviderForm[0].reset(); // Clear the form
    $('.error_message').hide(); // Hide error messages
    addProviderModal.modal();
  });

  addProviderForm.submit(function (e) {
    e.preventDefault();
    $.post('/rfq/provider/save_provider', $(this).serialize())
      .done((res) => {
        if (res.result === false) {
          $('.error_message').show();
        } else {
          addProviderForm[0].reset();
          addProviderModal.modal('hide');
          $('.error_message').hide();
          providersTable.ajax.reload(null, false); // Reload table without resetting pagination
          toastr.success('Provider added successfully!', 'Success');
        }
      })
      .fail(() => {
        toastr.error('Failed to add provider. Please try again.', 'Error');
      });
  });

  // Handle Edit Provider Modal
  const editProviderModal = $('#edit_provider_modal');
  const editProviderForm = $('#edit_provider_form');

  $('#providers_table').on('click', '.edit_button', function () {
    const providerId = $(this).attr('data');
    editProviderForm.load(`/rfq/provider/load_provider/${providerId}`, () => {
      $('.error_message').hide(); // Hide error messages
      editProviderModal.modal();
    });
  });

  editProviderForm.submit(function (e) {
    e.preventDefault();
    $.post('/rfq/provider/update_provider', $(this).serialize())
      .done((res) => {
        if (res.result === false) {
          $('.error_message').show();
        } else {
          editProviderForm[0].reset();
          editProviderModal.modal('hide');
          $('.error_message').hide();
          providersTable.ajax.reload(null, false); // Reload table without resetting pagination
          toastr.success('Provider updated successfully!', 'Success');
        }
      })
      .fail(() => {
        toastr.error('Failed to update provider. Please try again.', 'Error');
      });
  });

  // Handle Delete Provider Modal
  $('#providers_table').on('click', '.delete_button', function () {
    const providerId = $(this).attr('data');
    $('#continue_button').data('id-provider', providerId); // Store ID for deletion
    $('#alert_delete_system').modal();
  });

  $('#continue_button').click(() => {
    const providerId = $('#continue_button').data('id-provider');
    $.post('/rfq/provider/delete_provider/', { id_provider: providerId })
      .done(() => {
        providersTable.ajax.reload(null, false); // Reload table without resetting pagination
        $('#alert_delete_system').modal('hide');
        toastr.success('Provider deleted successfully!', 'Success');
      })
      .fail(() => {
        toastr.error('Failed to delete provider. Please try again.', 'Error');
      });
  });
});