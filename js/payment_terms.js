$(document).ready(function () {
  // Initialize DataTable
  const paymentTermsTable = $('#payment_terms_table').DataTable({
    ajax: '/rfq/payment_term/load_payment_terms_table/',
    columnDefs: [
      { className: "text-center", targets: [1] }
    ]
  });

  // Show Add Payment Term Modal
  $('#add_payment_term').click(function () {
    $('#add_payment_term_modal').modal('show');
  });

  // Handle Add Payment Term Form Submission
  $('#add_payment_term_form').submit(function (e) {
    e.preventDefault(); // Prevent default form submission
    $.post('/rfq/payment_term/save_payment_term', $(this).serialize(), function (res) {
      if (res.result === false) {
        $('.error_message').show();
      } else {
        $('#add_payment_term_form')[0].reset();
        $('#add_payment_term_modal').modal('hide');
        $('.error_message').hide();
        paymentTermsTable.ajax.reload(null, false);
        toastr.success('Payment term added successfully', 'Success');
      }
    }).fail(function () {
      toastr.error('Failed to save payment term', 'Error');
    });
  });

  // Show Edit Payment Term Modal
  $('#payment_terms_table').on('click', '.edit_button', function () {
    const paymentTermId = $(this).data('id');
    $('#edit_payment_term_form').load(`/rfq/payment_term/load_payment_term/${paymentTermId}`, function () {
      $('#edit_payment_term_modal').modal('show');
    });
  });

  // Handle Edit Payment Term Form Submission
  $('#edit_payment_term_form').submit(function (e) {
    e.preventDefault(); // Prevent default form submission
    $.post('/rfq/payment_term/update_payment_term', $(this).serialize(), function (res) {
      if (res.result === false) {
        $('.error_message').show();
      } else {
        $('#edit_payment_term_form')[0].reset();
        $('#edit_payment_term_modal').modal('hide');
        $('.error_message').hide();
        paymentTermsTable.ajax.reload(null, false);
        toastr.success('Payment term updated successfully', 'Success');
      }
    }).fail(function () {
      toastr.error('Failed to update payment term', 'Error');
    });
  });

  // Show Delete Confirmation Modal
  $('#payment_terms_table').on('click', '.delete_button', function () {
    const paymentTermId = $(this).data('id');
    $('#continue_button').data('id', paymentTermId); // Store ID in button data
    $('#alert_delete_system').modal('show');
  });

  // Handle Delete Payment Term Confirmation
  $('#continue_button').click(function () {
    const paymentTermId = $(this).data('id');
    $.ajax({
      url: '/rfq/payment_term/delete_payment_term/',
      type: 'POST',
      data: { id_payment_term: paymentTermId },
      success: function () {
        paymentTermsTable.ajax.reload(null, false);
        $('#alert_delete_system').modal('hide');
        toastr.success('Payment term deleted successfully', 'Success');
      },
      error: function () {
        toastr.error('Failed to delete payment term', 'Error');
      }
    });
  });
});