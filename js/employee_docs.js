$(document).ready(function () {
  $('#add-employee-doc-button').click(function (e) {
    e.preventDefault(); // Prevent the default button action if it's inside a form
    $('#add_employee_doc_modal').modal('show'); // Open the modal
  });

  $('#add_employee_doc_form').submit(function (e) {
    e.preventDefault(); // Prevent default form submission

    // Create a FormData object to handle file upload
    var formData = new FormData(this);

    // Send the form data via AJAX
    $.ajax({
      url: '/rfq/employee_docs/upload', // Target route
      type: 'POST',
      data: formData,
      processData: false, // Prevent jQuery from automatically processing data
      contentType: false, // Prevent jQuery from overriding the content type
      success: function (response) {
        // Reload the employee docs container
        $('#employee-docs-container').load('/rfq/employee_docs/container', function () {
          console.log('Employee docs container reloaded.');
        });
        // Handle success response
        $('#add_employee_doc_modal').modal('hide'); // Close the modal
      },
      error: function (xhr, status, error) {
        // Handle error response
        console.error('Upload failed:', xhr.responseText);
      }
    });
  });

  // Event listener for trash button click
  $('#employee-docs-container').on('click', '.delete-employee-doc-button', function (e) {
    e.preventDefault(); // Prevent the default button action

    // Get the file path from the data-path attribute
    var filePath = $(this).data('path');

    // Confirm deletion
    if (confirm('Are you sure you want to delete this file?')) {
      // Send the file path via AJAX to the server for deletion
      $.ajax({
        url: '/rfq/employee_docs/delete', // The PHP route to handle file deletion
        type: 'POST',
        data: { path: filePath },
        success: function (response) {
          // Handle successful deletion
          // Reload the employee docs container
          $('#employee-docs-container').load('/rfq/employee_docs/container', function () {
            console.log('Employee docs container reloaded.');
            toastr.success('File deleted successfully', 'Success');
          });
        },
        error: function (xhr, status, error) {
          // Handle errors
          console.error('Deletion failed:', xhr.responseText);
          toastr.error('An error occurred while deleting the file', 'Error');
        }
      });
    }
  });
});