$(document).ready(function () {
  const colorpickerOptions = {
    extensions: [
      {
        name: 'swatches',
        options: {
          colors: {
            '#000000': '#000000',
            '#888888': '#888888',
            '#ffffff': '#ffffff',
            '#ff0000': '#ff0000',
            '#777777': '#777777',
            '#337ab7': '#337ab7',
            '#5cb85c': '#5cb85c',
            '#5bc0de': '#5bc0de',
            '#f0ad4e': '#f0ad4e',
            '#d9534f': '#d9534f',
            '#007bff': '#007bff',
            '#6610f2': '#6610f2',
            '#fd7e14': '#fd7e14',
            '#dc3545': '#dc3545',
            '#e83e8c': '#e83e8c',
            '#6f42c1': '#6f42c1'
          },
          namesAsValues: true
        }
      }
    ]
  };

  const idRfq = $('input[name="id_rfq"]').val();

  // Utility: Initialize color picker
  function initColorPicker(element) {
    element.colorpicker(colorpickerOptions);
    element.on('colorpickerChange', function (event) {
      $(this).parent().find('.fa-square').css('color', event.color.toString());
    });
  }

  // Utility: Show modal with loaded content
  function loadModal(modal, form, url, data, callback) {
    form.load(url, data, function () {
      form[0].reset();
      const colorPickerElement = form.find('#color');
      initColorPicker(colorPickerElement);
      modal.modal();
      if (callback) callback();
    });
  }

  // Utility: Handle form submission
  function handleFormSubmission(form, url, modal) {
    form.validate({
      rules: {
        name: { required: true }
      },
      submitHandler: function () {
        $.ajax({
          url: url,
          type: 'POST',
          data: form.serialize(),
          success: function () {
            modal.modal('hide');
            location.reload();
          },
          error: function (xhr, status, error) {
            console.error('Form submission failed:', error);
          }
        });
      }
    });
  }

  // Add Room Modal Logic
  const addRoomModal = $('#add-room-modal');
  const addRoomForm = $('#add-room-form');
  $('#add-room-button').on('click', function (e) {
    e.preventDefault();
    loadModal(addRoomModal, addRoomForm, '/rfq/quote/rooms/load/', { idRfq });
  });
  handleFormSubmission(addRoomForm, '/rfq/quote/rooms/save', addRoomModal);

  // Edit Room Modal Logic
  const editRoomModal = $('#edit-room-modal');
  const editRoomForm = $('#edit-room-form');
  $('.edit-room-button').on('click', function (e) {
    e.preventDefault();
    const idRoom = $(this).data('id');
    loadModal(editRoomModal, editRoomForm, '/rfq/quote/rooms/load', { idRoom });
  });
  handleFormSubmission(editRoomForm, '/rfq/quote/rooms/update', editRoomModal);

  // Delete Room Logic
  editRoomForm.on('click', '#delete-room-button', function () {
    const roomId = $(this).data('id');
    $.ajax({
      url: '/rfq/quote/rooms/delete',
      type: 'POST',
      data: { id: roomId },
      success: function () {
        editRoomModal.modal('hide');
        location.reload();
      },
      error: function (xhr, status, error) {
        console.error('Failed to delete room:', error);
      }
    });
  });
});