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

  const addRoomModal = $('#add-room-modal');
  const addRoomForm = $('#add-room-form');
  const addRoomButton = $('#add-room-button');

  addRoomButton.on('click', function (e) {
    e.preventDefault();

    addRoomForm.load('/rfq/quote/rooms/load/', {
      idRfq: idRfq
    }, function () {
      addRoomForm[0].reset();
      addRoomForm.find('#color').colorpicker(colorpickerOptions);
      addRoomForm.find('#color').on('colorpickerChange', function (event) {
        $(this).parent().find('.fa-square').css('color', event.color.toString());
      })
      addRoomModal.modal();
    });
  });

  addRoomForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/rooms/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addRoomForm.modal('hide');
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  const editRoomModal = $('#edit-room-modal');
  const editRoomForm = $('#edit-room-form');
  const editRoomButton = $('.edit-room-button');

  editRoomButton.on('click', function (e) {
    e.preventDefault();
    const idRoom = $(this).data('id');
    editRoomForm.load(`/rfq/quote/rooms/load`, {
      idRoom: idRoom
    }, function () {
      editRoomForm.find('#color').colorpicker(colorpickerOptions);
      editRoomForm.find('#color').on('colorpickerChange', function (event) {
        $(this).parent().find('.fa-square').css('color', event.color.toString());
      })
      editRoomModal.modal();
    });
  });

  editRoomForm.validate({
    rules: {
      name: {
        required: true
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/quote/rooms/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editRoomModal.modal('hide');
          location.reload();
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  editRoomForm.on('click', '#delete-room-button', function (e) {
    $.ajax({
      url: '/rfq/quote/rooms/delete',
      data: {
        id: $(this).data('id'),
      },
      type: 'POST',
      success: function (res) {
        editRoomModal.modal('hide');
        location.reload();
      }
    });
  });
});