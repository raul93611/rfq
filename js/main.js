$(document).ready(function () {
  /***********************************VARIABLES INICIALES PARA EL BORRADO*********************/
  var link_to_delete;
  var alert_delete_system = $('#alert_delete_system');
  var continue_button = $('#continue_button');
  function habilitar_continue_button(boton){
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

  if($('#archivos_ejemplo').length != 0){
    $.ajax({
      url: '/rfq/quote/get_quote_files/' + $('[name="id_rfq"]').val(),
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function(data) {
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

        $("#archivos_ejemplo").on('filepredelete', function(event, key, jqXHR, data) {
          alert_delete_system.modal();
          continue_button.attr('href', key);
          continue_button.on('click', function(e){
            e.preventDefault();
            $.ajax({
              url: key,
              type: 'POST',
              success: function(res){
                location.reload();
              }
            });
          });
          return true;
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  }
  /*********************AUDIT TRAILS*********************************************/
  $('.audit_trail').click(function(){
    $('#audit_trails_modal').modal('hide');
    var element = $(this).attr('href');
    $(element).addClass('highlight');
    setTimeout(function() {
      $(element).removeClass('highlight');
    }, 5000);
  });
  $('#audit_trails_button').click(function(){
    $('#audit_trails_modal').modal();
  });
  /***********************QUOTE INFO MODAL****************************************/
  $('#quote_info_button').click(function(){
    $('#quote_info_modal').modal();
  });
  /**************************************BOTON MOSTRAR COMENTARIOS************************/
  $('#mostrar_comentarios').click(function(){
    $('#todos_commentarios_quote').modal();
  });

  /*************************************NUEVO COMENTARIO***********************************/
  if($('#nuevo_comment').length != 0){
    $('#add_comment').click(function(){
      $('#nuevo_comment').modal();
    });
  }
  /***********************************COPY ALERT******************/
  $('#copy_quote').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /***********************************ALERT EN BOTONES PARA BORRAR ITEMS******************/
  $('.delete_item_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /**********************************ALERT EN BOTONES PARA BORRAR SUBITEMS******************/
  $('.delete_subitem_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR PROVIDER DE ITEMS**************/
  $('.delete_provider_item_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR PROVIDER SUBITEMS*************/
  $('.delete_provider_subitem_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /******************************ALERT EN BOTONES PARA BORRAR DOCUMENTOS********************/
  $('.delete_document_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
  /****************************ALERT EN BOTONES PARA BORRAR QUOTES**************************/
  $('.delete_quote_button').click(function(){
    habilitar_continue_button($(this));
    return false;
  });
/************************************TOGGLE BUTTON PARA LA BARRA LATERAL*********************/
  $('#sidebar_collapse').on('click', function(){
    $('#footer_item').toggleClass('footer_item1');
  });
/**************************************DATEPICKER PARA CAMPOS TIPO DATE*********************/
  $('.date').daterangepicker({
    singleDatePicker: true
  });

  $('#end_date').daterangepicker({
    timePicker: true,
    singleDatePicker: true,
    timePicker24Hour: true,
    locale: {
      format: 'MM/DD/YYYY HH:mm'
    }
  });
  /************************************DATETABLES JQUERY PARA TABLAS**************************/
  $.fn.dataTable.moment('MM/DD/YYYY');
  $.fn.dataTable.moment('MM/DD/YYYY HH:mm');

  $('#tabla').DataTable({
    'pageLength': 50,
    'order': [[ 3, "desc" ]]
  });

  $('#tabla_quotes').DataTable({
    'pageLength': 50,
    'order': [[ 4, "desc" ]]
  });

  $('#tabla_usuarios').DataTable({
    'pageLength': 50,
    'order': [[ 4, "asc" ]]
  });

  $('#tabla_busqueda').DataTable({
    'pageLength': 50
  });

  $('.invoice_table').DataTable({
    'order': [[ 3, "desc" ]],
    'pageLength': 50
  });

  $('.fulfillment_table').DataTable({
    'order': [[ 4, "desc" ]],
    'pageLength': 50
  });

  $('.regular_table').DataTable();
});
