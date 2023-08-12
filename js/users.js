$(document).ready(function () {
  const usersTable = $('#tabla_usuarios').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": '/rfq/user/users',
      "type": "POST"
    },
    "columns": [
      { "data": "id" },
      { "data": "role_names" },
      { "data": "nombres" },
      { "data": "apellidos" },
      { "data": "nombre_usuario" },
      {
        "data": "status",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return `<span class="text-${data == 'Enabled' ? 'success' : 'danger'}">${data}</span>`;
          } else {
            return data;
          }
        }
      },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            let column = `
            <a class="btn btn-sm btn-info" href="/rfq/perfil/user/edit_user/${row.id}">
              <i class="fas fa-highlighter"></i>
            </a>
            <a class="btn btn-sm btn-warning" href="/rfq/perfil/user/update_password/${row.id}">
              <i class="fas fa-key"></i>
            </a>
            `;
            column += `
            <a href="/rfq/user/${row.status == 'Enabled' ? 'disable' : 'enable'}_user" data-type="${row.status == 'Enabled' ? 'disable' : 'enable'}" data-id="${row.id}" class="enable-disable-user btn btn-sm btn-${row.status == 'Enabled' ? 'danger' : 'success'}">
              <i class="fa fa-${row.status == 'Enabled' ? 'ban' : 'check'}"></i>
            </a>
            `;
            return column;
          } else {
            return data;
          }
        }
      }
    ]
  });

  $('#tabla_usuarios').on('click', '.enable-disable-user', function (e) {
    e.preventDefault();
    $.ajax({
      url: `/rfq/user/${$(this).data('type')}_user`,
      method: 'POST',
      data: {
        id: `${$(this).data('id')}`,
      },
      success: function (response) {
        usersTable.ajax.reload(null, false);
      },
      error: function (xhr, status, error) {
        console.log(error);
      }
    });
  })

  $.validator.addMethod('checkboxGroup', function (value, element) {
    var checkboxes = $(element).closest('form').find('input[name="' + $(element).attr('name') + '"]');
    return checkboxes.filter(':checked').length > 0;
  }, 'Please select at least one checkbox.');

  $('#edit-user-form').validate({
    rules: {
      username: {
        required: true,
        pattern: /^[a-zA-Z0-9_-]+$/
      },
      nombres: {
        required: true
      },
      apellidos: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      'cargo[]': {
        checkboxGroup: true
      }
    },
    messages: {
      username: {
        pattern: 'Only letters, numbers, hyphens, and underscores are allowed'
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          let message = '';
          if (response.errors) {
            message = $(`<label class="error">${response.errors}</label>`);
          } else {
            message = $(`<label class="text-success">User updated correctly</label>`);
          }
          $('form #errors').html(message);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    },
    errorPlacement: function (error, element) {
      if (element.is(':checkbox')) {
        error.insertAfter(element.closest('div.form-group'));
      } else {
        error.insertAfter(element);
      }
    }
  });

  $('#update-password-form').validate({
    rules: {
      password: {
        required: true,
        minlength: 6
      },
      'password-confirmation': {
        required: true,
        equalTo: '#password'
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/update_password',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          let message = '';
          if (!response.errors) {
            message = $(`<label class="text-success">Password updated correctly</label>`);
          }
          $('form #errors').html(message);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  $('#add-user-form').validate({
    rules: {
      username: {
        required: true,
        pattern: /^[a-zA-Z0-9_-]+$/
      },
      nombres: {
        required: true
      },
      apellidos: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      },
      'password-confirmation': {
        required: true,
        equalTo: '#password'
      },
      'cargo[]': {
        checkboxGroup: true
      }
    },
    messages: {
      username: {
        pattern: 'Only letters, numbers, hyphens, and underscores are allowed'
      }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/create',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          let message = '';
          if (response.errors) {
            message = $(`<label class="error">${response.errors}</label>`);
          } else {
            message = $(`<label class="text-success">User created correctly</label>`);
          }
          $('form #errors').html(message);
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    },
    errorPlacement: function (error, element) {
      if (element.is(':checkbox')) {
        error.insertAfter(element.closest('div.form-group'));
      } else {
        error.insertAfter(element);
      }
    }
  });
});