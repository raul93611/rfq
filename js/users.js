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
    { "data": "options" }
  ]
});