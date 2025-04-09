$(document).ready(function () {
  const usersTable = $('#tabla_usuarios').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: '/rfq/user/users',
      type: 'POST',
    },
    columns: [
      { data: 'id' },
      { data: 'role_names' },
      { data: 'nombres' },
      { data: 'apellidos' },
      { data: 'nombre_usuario' },
      {
        data: 'status',
        render: (data, type) => {
          if (type === 'display') {
            const statusClass = data === 'Enabled' ? 'success' : 'danger';
            return `<span class="text-${statusClass}">${data}</span>`;
          }
          return data;
        },
      },
      {
        data: 'options',
        orderable: false,
        render: (data, type, row) => {
          if (type === 'display') {
            const isEnabled = row.status === 'Enabled';
            const statusToggle = isEnabled ? 'disable' : 'enable';
            const buttonClass = isEnabled ? 'danger' : 'success';
            const iconClass = isEnabled ? 'ban' : 'check';

            return `
              <a class="btn btn-sm btn-info" href="/rfq/perfil/user/edit_user/${row.id}">
                <i class="fas fa-highlighter"></i>
              </a>
              <a class="btn btn-sm btn-secondary" href="/rfq/perfil/user/update_password/${row.id}">
                <i class="fas fa-key"></i>
              </a>
              <a href="/rfq/user/${statusToggle}_user" 
                 data-type="${statusToggle}" 
                 data-id="${row.id}" 
                 class="enable-disable-user btn btn-sm btn-${buttonClass}">
                <i class="fa fa-${iconClass}"></i>
              </a>
            `;
          }
          return data;
        },
      },
    ],
  });

  // Handle Enable/Disable User Button Click
  $('#tabla_usuarios').on('click', '.enable-disable-user', function (e) {
    e.preventDefault();

    const actionType = $(this).data('type');
    const userId = $(this).data('id');

    $.ajax({
      url: `/rfq/user/${actionType}_user`,
      method: 'POST',
      data: { id: userId },
      success: () => {
        usersTable.ajax.reload(null, false); // Refresh without resetting pagination
      },
      error: (xhr, status, error) => {
        console.error(`Error during ${actionType} action:`, error);
      }
    });
  });

  // Custom Validator for Checkbox Group
  $.validator.addMethod(
    'checkboxGroup',
    function (value, element) {
      const checkboxes = $(element)
        .closest('form')
        .find(`input[name="${$(element).attr('name')}"]`);
      return checkboxes.filter(':checked').length > 0;
    },
    'Please select at least one checkbox.'
  );

  // Edit User Form Validation and Submission
  $('#edit-user-form').validate({
    rules: {
      username: {
        required: true,
        pattern: /^[a-zA-Z0-9_-]+$/,
      },
      nombres: {
        required: true,
      },
      apellidos: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      'cargo[]': {
        checkboxGroup: true,
      },
    },
    messages: {
      username: {
        pattern: 'Only letters, numbers, hyphens, and underscores are allowed.',
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/update',
        type: 'POST',
        data: $(form).serialize(),
        success: (response) => {
          const message = response.errors
            ? `<label class="error">${response.errors}</label>`
            : `<label class="text-success">User updated successfully</label>`;

          $('#errors').html(message);
        },
        error: (xhr, status, error) => {
          console.error('Error updating user:', error);
        },
      });
    },
    errorPlacement: function (error, element) {
      if (element.is(':checkbox')) {
        error.insertAfter(element.closest('div.form-group'));
      } else {
        error.insertAfter(element);
      }
    },
  });

  // Update Password Form Validation and Submission
  $('#update-password-form').validate({
    rules: {
      password: {
        required: true,
        minlength: 6,
      },
      'password-confirmation': {
        required: true,
        equalTo: '#password',
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/update_password',
        type: 'POST',
        data: $(form).serialize(),
        success: (response) => {
          const message = response.errors
            ? `<label class="error">${response.errors}</label>`
            : `<label class="text-success">Password updated successfully</label>`;
          $('#errors').html(message);
        },
        error: (xhr, status, error) => {
          console.error('Error updating password:', error);
        },
      });
    },
  });

  // Add User Form Validation and Submission
  $('#add-user-form').validate({
    rules: {
      username: {
        required: true,
        pattern: /^[a-zA-Z0-9_-]+$/,
      },
      nombres: {
        required: true,
      },
      apellidos: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 6,
      },
      'password-confirmation': {
        required: true,
        equalTo: '#password',
      },
      'cargo[]': {
        checkboxGroup: true,
      },
    },
    messages: {
      username: {
        pattern: 'Only letters, numbers, hyphens, and underscores are allowed.',
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/user/create',
        type: 'POST',
        data: $(form).serialize(),
        success: (response) => {
          const message = response.errors
            ? `<label class="error">${response.errors}</label>`
            : `<label class="text-success">User created successfully</label>`;
          $('#errors').html(message);
        },
        error: (xhr, status, error) => {
          console.error('Error creating user:', error);
        },
      });
    },
    errorPlacement: function (error, element) {
      if (element.is(':checkbox')) {
        error.insertAfter(element.closest('div.form-group'));
      } else {
        error.insertAfter(element);
      }
    },
  });
});