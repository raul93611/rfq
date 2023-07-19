$(document).ready(function () {
  /***********************************VARIABLES INICIALES PARA EL BORRADO*********************/
  var link_to_delete;
  var alert_delete_system = $('#alert_delete_system');
  var continue_button = $('#continue_button');
  function habilitar_continue_button(boton) {
    alert_delete_system.modal();
    link_to_delete = boton.attr('href');
    continue_button.attr('href', link_to_delete);
  }
  /**************************************FONT COLOR FOR TEXTAREAS***********/
  $('.summernote_textarea').summernote({
    callbacks: {
      onPaste: function (e) {
        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
        e.preventDefault();
        bufferText = bufferText.replace(/<\/?[^>]+(>|$)/g, '');
        setTimeout($(this).summernote('insertText', bufferText), 10);
      }
    },
    toolbar: [
      ['color', ['color']],
      ['insert', ['link']],
    ]
  });
  /***********************FILE INPUT*************************************/
  $('#archivos_crear').fileinput({
    theme: 'fa',
    initialPreviewAsData: true,
    showUpload: false,
    overwriteInitial: false,
    fileActionSettings:
    {
      showZoom: false,
      showUpload: false,
      showRemove: false
    }
  });

  if ($('#archivos_ejemplo').length != 0) {
    $.ajax({
      url: '/rfq/quote/get_quote_files/' + $('[name="id_rfq"]').val(),
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function (data) {
        var files = data.files;
        var filesIcon = [];
        var filesConfig = [];
        files.forEach((file, i) => {
          var icon = '<h1><i class="p-3 fas fa-file"></i></h1>';
          filesIcon.push(icon);
          filesConfig.push({
            previewAsData: false,
            caption: file,
            url: '/rfq/quote/delete_document/' + $('input[name="id_rfq"]').val() + '/' + file,
            downloadUrl: '/rfq/documentos/' + $('input[name="id_rfq"]').val() + '/' + file,
            key: '/rfq/quote/delete_document/' + $('input[name="id_rfq"]').val() + '/' + file
          });
        });

        $('#archivos_ejemplo').fileinput({
          theme: 'explorer-fa',
          mainClass: 'input-group-sm',
          uploadUrl: '/rfq/quote/load_img/' + $('input[name="id_rfq"]').val(),
          overwriteInitial: false,
          initialPreviewAsData: true,
          initialPreview: filesIcon,
          initialPreviewConfig: filesConfig,
          showRemove: false,
          showCancel: false,
          fileActionSettings:
          {
            showZoom: false,
          }
        });

        $("#archivos_ejemplo").on('filepredelete', function (event, key, jqXHR, data) {
          alert_delete_system.modal();
          continue_button.attr('href', key);
          continue_button.on('click', function (e) {
            e.preventDefault();
            $.ajax({
              url: key,
              type: 'POST',
              success: function (res) {
                location.reload();
              }
            });
          });
          return true;
        });
      },
      error: function (data) {
        console.log(data);
      }
    });
  }
  /*********************AUDIT TRAILS*********************************************/
  $('.audit_trail').click(function () {
    $('#audit_trails_modal').modal('hide');
    var element = $(this).attr('href');
    $(element).addClass('highlight');
    setTimeout(function () {
      $(element).removeClass('highlight');
    }, 5000);
  });
  $('#audit_trails_button').click(function () {
    $('#audit_trails_modal').modal();
  });
  /**************************************BOTON MOSTRAR COMENTARIOS************************/
  $('#mostrar_comentarios').click(function () {
    $('#todos_commentarios_quote').modal();
  });

  /*************************************NUEVO COMENTARIO***********************************/
  if ($('#nuevo_comment').length != 0) {
    $('#add_comment').click(function () {
      $('#nuevo_comment').modal();
    });
  }
  /***********************************COPY ALERT******************/
  $('#copy_quote').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /***********************************ALERT EN BOTONES PARA BORRAR ITEMS******************/
  $('.delete_item_button').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /**********************************ALERT EN BOTONES PARA BORRAR SUBITEMS******************/
  $('.delete_subitem_button').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR PROVIDER DE ITEMS**************/
  $('.delete_provider_item_button').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR PROVIDER SUBITEMS*************/
  $('.delete_provider_subitem_button').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR DOCUMENTOS********************/
  $('.delete_document_button').click(function () {
    habilitar_continue_button($(this));
    return false;
  });
  /****************************ALERT EN BOTONES PARA BORRAR QUOTES**************************/
  $('#tabla_quotes, #no_bid_table, #not_submitted_table, #cancelled_table').on('click', '.delete_quote_button', function () {
    habilitar_continue_button($(this));
    return false;
  });
  /************************************TOGGLE BUTTON PARA LA BARRA LATERAL*********************/
  $('#sidebar_collapse').on('click', function () {
    $('#footer_item').toggleClass('footer_item1');
  });
  /**************************************DATEPICKER PARA CAMPOS TIPO DATE*********************/
  $('.date').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    autoApply: true
  });

  $('.date').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY'));
  });

  $('.date').on('cancel.daterangepicker', function (ev, picker) {
    $(this).val('');
  });

  $('#end_date').daterangepicker({
    timePicker: true,
    singleDatePicker: true,
    timePicker24Hour: true,
    locale: {
      format: 'MM/DD/YYYY HH:mm'
    },
    autoApply: true
  });
  /************************************DATETABLES JQUERY PARA TABLAS**************************/
  $('#tabla').DataTable({
    'pageLength': 50,
    'order': [[3, "desc"]]
  });

  $('#tabla_quotes').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 50,
    "order": [[4, "desc"]],
    "ajax": {
      "url": '/rfq/quote/created_table',
      "type": "POST",
      "data": {
        "channel": $('#tabla_quotes').data('channel'),
      }
    },
    "rowCallback": function (row, data) {
      if (data.comments == 'Working on it') {
        $(row).addClass('waiting_for');
      }
    },
    "columns": [
      {
        "data": "id",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
          } else {
            return data;
          }
        }
      },
      { "data": "nombre_usuario" },
      { "data": "type_of_bid" },
      { "data": "issue_date" },
      { "data": "end_date" },
      { "data": "email_code" },
      {
        "data": "rfp",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return data ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>';
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
            return '<a href="/rfq/quote/delete_quote/' + row.id + '" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>';
          } else {
            return data;
          }
        }
      },
    ]
  });

  $('#completed_table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 50,
    "order": [[4, "desc"]],
    "ajax": {
      "url": '/rfq/quote/completed_table',
      "type": "POST",
      "data": {
        "channel": $('#completed_table').data('channel'),
      }
    },
    "columns": [
      {
        "data": "id",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
          } else {
            return data;
          }
        }
      },
      { "data": "nombre_usuario" },
      { "data": "type_of_bid" },
      { "data": "fecha_completado" },
      { "data": "email_code" },
      {
        "data": "rfp",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return data ? '<i class="text-success fas fa-check"></i>' : '<i class="text-danger fas fa-times"></i>';
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
            return `
              <a class="btn btn-sm calculate" href="/rfq/quote/proposal/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
            `;
          } else {
            return data;
          }
        }
      },
    ]
  });

  $('#no_bid_table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 50,
    "ajax": {
      "url": '/rfq/quote/no_bid_table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
          } else {
            return data;
          }
        }
      },
      { "data": "nombre_usuario" },
      { "data": "email_code" },
      { "data": "type_of_bid" },
      { "data": "comments" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/quote/delete_quote/' + row.id + '" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>';
          } else {
            return data;
          }
        }
      },
    ]
  });

  $('#not_submitted_table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 50,
    "ajax": {
      "url": '/rfq/quote/not_submitted_table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
          } else {
            return data;
          }
        }
      },
      { "data": "nombre_usuario" },
      { "data": "email_code" },
      { "data": "type_of_bid" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/quote/delete_quote/' + row.id + '" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>';
          } else {
            return data;
          }
        }
      },
    ]
  });

  $('#cancelled_table').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 50,
    "ajax": {
      "url": '/rfq/quote/cancelled_table',
      "type": "POST"
    },
    "columns": [
      {
        "data": "id",
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/perfil/quote/editar_cotizacion/' + data + '">' + data + '</a>';
          } else {
            return data;
          }
        }
      },
      { "data": "nombre_usuario" },
      { "data": "email_code" },
      { "data": "type_of_bid" },
      {
        "data": "options",
        "orderable": false,
        "render": function (data, type, row, meta) {
          if (type === 'display') {
            return '<a href="/rfq/quote/delete_quote/' + row.id + '" class="delete_quote_button text-danger"><i class="fa fa-times"></i> Delete</a>';
          } else {
            return data;
          }
        }
      },
    ]
  });

  $('.invoice_table').DataTable({
    'order': [[3, "desc"]],
    'pageLength': 50
  });

  $('.fulfillment_table').DataTable({
    'order': [[4, "desc"]],
    'pageLength': 50
  });

  $('.regular_table').DataTable();
});
