$('#tabla_usuarios').DataTable({
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
          `;
          column += `
          <a href="/rfq/user/${row.status == 'Enabled'? 'disable' : 'enable'}_user/${row.id}" class="btn btn-sm btn-${row.status == 'Enabled'? 'danger' : 'success'}">
            <i class="fa fa-${row.status == 'Enabled'? 'ban' : 'check'}"></i>
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