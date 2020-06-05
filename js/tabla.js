$(document).ready(function () {
  $('.audit_trail').click(function(){
    $('#audit_trails_modal').modal('hide');
    var element = $(this).attr('href');
    $(element).addClass('highlight');
    setTimeout(function() {
      $(element).removeClass('highlight');
    }, 5000);
    console.log($(element));
  });
  $('#audit_trails_button').click(function(){
    $('#audit_trails_modal').modal();
  });
  $('#quote_info_button').click(function(){
    $('#quote_info_modal').modal();
  });
  /************************************************************/
  $('#archivos_crear').fileinput({
    theme: 'explorer-fas',
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
    var archivos = $('#archivos').val();
    var array_div_archivos = [];
    var array_opciones = [];
    if(archivos != ''){
      archivos = archivos.split(',');

      for (var i = 0; i < archivos.length; i++) {
        array_div_archivos.push('"<h3>' + "<i class='" + "fas fa-file" + "'></i>" + '</h3>"');
        array_opciones.push('{"previewAsData": false, "caption": "' + archivos[i] + '", "url": "' + 'http://' + document.location.hostname + '/rfq/delete_document/' + $('input[name="id_rfq"]').val() + '/' + archivos[i] + '", "downloadUrl": "' + 'http://' + document.location.hostname + '/rfq/documentos/' + $('input[name="id_rfq"]').val() + '/' + archivos[i] + '", "key": ' + i + '}');
      }
      array_div_archivos.join(',');
      array_div_archivos = '[' + array_div_archivos + ']';
      console.log(array_div_archivos);
      array_div_archivos = jQuery.parseJSON(array_div_archivos);
      array_opciones.join(',');
      array_opciones = '[' + array_opciones + ']';
      array_opciones = jQuery.parseJSON(array_opciones);
      console.log(array_div_archivos);
      console.log(array_opciones);
    }
    $('#archivos_ejemplo').fileinput({
      theme: 'explorer-fas',
      uploadUrl: 'http://' + document.location.hostname + '/rfq/load_img/' + $('input[name="id_rfq"]').val(),
      overwriteInitial: false,
      initialPreviewAsData: true,
      initialPreview: array_div_archivos,
      initialPreviewConfig: array_opciones,
      fileActionSettings:
      {
        showZoom: false
      }
    });
  }
  /**************************************BOTON MOSTRAT COMENTARIOS************************/
  $('#mostrar_comentarios').click(function(){
    $('#todos_commentarios_quote').modal();
  });
  /**************************************BOTON PARA FULLFILLMENT****************************/
  $('#fullfillment').click(function(){
    $('#fullfillment_modal').modal();
  });
/***********************************VARIABLES INICIALES PARA EL BORRADO*********************/
  var link_to_delete;
  var alert_delete_system = $('#alert_delete_system');
  var continue_button = $('#continue_button');
  function habilitar_continue_button(boton){
    alert_delete_system.modal();
    link_to_delete = boton.attr('href');
    continue_button.attr('href', link_to_delete);
  }
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
  /*************************************NUEVO COMENTARIO***********************************/
  if($('#nuevo_comment').length != 0){
    $('#add_comment').click(function(){
      $('#nuevo_comment').modal();
    });
  }
/*************************************VERIFICAR SI CODE ESTA VACIO****************************/
  if($('#form_edited_quote').length != 0){
    var form_edited_quote = $('#form_edited_quote');
    form_edited_quote.submit(function(){
      var email_code =  $('#email_code');
      if(email_code.val() == ''){
        $('#error_alert').modal();
        return false;
      }
    });
  }
/*************************************INPUT FILE MEJORADO************************************/
  $('#file_input_info_create').change(function(e){
    var fileName_create = [];
    for (var i = 0; i < e.target.files.length; i++) {
      fileName_create.push(e.target.files[i].name);
    }
    $('#label_file_create').html(fileName_create.join(', '));
  });
/************************************TOGGLE BUTTON PARA LA BARRA LATERAL*********************/
  $('#sidebar_collapse').on('click', function(){
    $('#footer_item').toggleClass('footer_item1');
  });
/**************************************DATEPICKER PARA CAMPOS TIPO DATE*********************/
  $('#completed_date, .date').daterangepicker({
    singleDatePicker: true
  });
  $('#expiration_date').daterangepicker({
    singleDatePicker: true
  });
  $('#issue_date').daterangepicker({
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
  $('#date_milestone').daterangepicker({
    singleDatePicker: true
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

$('#tabla_busqueda').DataTable({
  'pageLength': 50
});

$('#tabla_usuarios').DataTable({
  'order': [[ 3, "desc" ]],
  'pageLength': 50
});

$('.fulfillment_table').DataTable({
  'order': [[ 4, "desc" ]],
  'pageLength': 50
});
/***************************************************************************************************/
  if($('#re_quote_form').length != 0){
    var time2 = setInterval(function(){
      var payment_terms = 1;
      var total_ganado = parseFloat($('#total_ganado').html().split(' ')[1]);
      if ($('input:radio[name=payment_terms]:checked').val() === 'net_30_cc') {
        payment_terms = total_ganado*0.029;
      } else {
        payment_terms = 0;
      }
      var totales = [];
      $('#re_quote_data tr').each(function(){
        if (!isNaN($(this).find('td').eq(8).text().split(' ')[1])) {
          totales.push($(this).find('td').eq(8).text().split(' ')[1]);
        } else {
          totales.push(0);
        }
      });
      if(!isNaN($('#shipping_cost_rq').val()) && $('#shipping_cost_rq').val() != ''){
        var shipping_cost_rq = $('#shipping_cost_rq').val();
      }else{
        var shipping_cost_rq = 0;
      }
      var total = 0;

      for (var i = 0; i < totales.length; i++) {
        total = total + parseFloat(totales[i]);
      }

      total = total + payment_terms;
      total = total + parseFloat(shipping_cost_rq);

      $('#total_re_quote').html('$ ' + total.toFixed(2));
      $('#total_cost').val(total);
      var profit_rq = (total_ganado - total).toFixed(2);
      var percentage_profit_rq = ((profit_rq/total)*100).toFixed(2);
      $('#profit_rq').html('$ ' + profit_rq + '<br>' + percentage_profit_rq + '%');
    }, 100);
  }

$('#re_quote_form').submit(function () {
  var payment_terms = 1;
  var total_ganado = parseFloat($('#total_ganado').html().split(' ')[1]);
  if ($('input:radio[name=payment_terms]:checked').val() === 'net_30_cc') {
    payment_terms = total_ganado*0.029;
  } else {
    payment_terms = 0;
  }
  var totales = [];
  $('#re_quote_data tr').each(function(){
    if (!isNaN($(this).find('td').eq(8).text().split(' ')[1])) {
      totales.push($(this).find('td').eq(8).text().split(' ')[1]);
    } else {
      totales.push(0);
    }
  });
  if(!isNaN($('#shipping_cost_rq').val()) && $('#shipping_cost_rq').val() != ''){
    var shipping_cost_rq = $('#shipping_cost_rq').val();
  }else{
    var shipping_cost_rq = 0;
  }
  var total = 0;

  for (var i = 0; i < totales.length; i++) {
    total = total + parseFloat(totales[i]);
  }

  total = total + payment_terms;
  total = total + parseFloat(shipping_cost_rq);

  $('#total_re_quote').html('$ ' + total.toFixed(2));
  $('#total_cost').val(total);

  var profit_rq = (total_ganado - total).toFixed(2);
  var percentage_profit_rq = ((profit_rq/total)*100).toFixed(2);
  $('#profit_rq').html('$ ' + profit_rq + '<br>' + percentage_profit_rq + '%');
});
/****************************************************************************************************/
/**********************************CALCULOS EN LA TABLA DE ITEMS***********************************/
/****************************************************************************************************/
  var monto = [];
  var quantity = [];

  $('#items tr').each(function () {
    quantity.push($(this).find('td').eq(5).text());
    if (!isNaN($(this).find('td').eq(8).text().split(' ')[1])) {
      monto.push($(this).find('td').eq(8).text().split(' ')[1]);
    } else {
      monto.push(0);
    }
  });

if($('#form_edited_quote').length != 0){
  var time = setInterval(function(){
    var total_additional = 0;
    var payment_terms = 0;
    if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
      payment_terms = 1.0299;
    } else {
      payment_terms = 1;
    }
    var taxes = $('#taxes').val();
    var profit = $('#profit').val();
    if(!isNaN($('#additional_general').val()) && $('#additional_general').val() != ''){
      var additional_general = $('#additional_general').val();
    }else{
      var additional_general = 0;
    }

    if(!isNaN($('#shipping_cost').val()) && $('#shipping_cost').val() != ''){
      var shipping_cost = $('#shipping_cost').val();
    }else{
      var shipping_cost = 0;
    }
    var contador_subitems = 0;
    var i = 0;
    var j = 1;
    var total1 = 0;
    var total2 = 0 + parseFloat(shipping_cost);
    var partes_total_price = '';
    var partes_total_price_subitems = '';
    var unit_prices = '';
    var unit_prices_subitems = '';
    var additional = '';
    var additional_subitems = '';
    var total_quantity = 0;
    $('#items tr').each(function () {
      if(!isNaN($(this).find('td').eq(5).text())){
        total_quantity = total_quantity + Number($(this).find('td').eq(5).text());
      }
      if(!isNaN($('#add_cost' + j).val()) && $('#add_cost' + j).val() != ''){
        var add_cost = $('#add_cost' + j).val();
      }else{
        var add_cost = 0;
      }

      total_additional = total_additional + (parseFloat(add_cost)*quantity[i]) + (parseFloat(additional_general)*quantity[i]);

      if($(this).hasClass('fila_subitem')){
        if(contador_subitems === 0){
          additional_subitems = additional_subitems + add_cost;
        }else{
          additional_subitems = additional_subitems + ',' + add_cost;
        }
      }else{
        if (i === 0) {
          additional = additional + add_cost;
        } else {
          additional = additional + ',' + add_cost;
        }
      }

        var resul_taxes = parseFloat(additional_general) + parseFloat(add_cost) + ((1 + (taxes / 100)) * monto[i] * payment_terms);
        resul_taxes = resul_taxes.toFixed(2);
        $(this).find('td').eq(8).html('$ ' + resul_taxes);
        if (profit !== 0) {
          var resul_profit = (1 + (profit / 100)) * resul_taxes;
          resul_profit = resul_profit.toFixed(2);
          $(this).find('td').eq(10).html('$ ' + resul_profit);
          if($(this).hasClass('fila_subitem')){
            if (contador_subitems === 0) {
              unit_prices_subitems = unit_prices_subitems + resul_profit;
            } else {
              unit_prices_subitems = unit_prices_subitems + ',' + resul_profit;
            }

          }else{
            if (i === 0) {
              unit_prices = unit_prices + resul_profit;
            } else {
              unit_prices = unit_prices + ',' + resul_profit;
            }
          }
        } else {
          $(this).find('td').eq(10).html('$ ' + resul_taxes);
          if($(this).hasClass('fila_subitem')){
            if (contador_subitems === 0) {
              unit_prices_subitems = unit_prices_subitems + resul_taxes;
            } else {
              unit_prices_subitems = unit_prices_subitems + ',' + resul_taxes;
            }

          }else{
            if (i === 0) {
              unit_prices = unit_prices + resul_taxes;
            } else {
              unit_prices = unit_prices + ',' + resul_taxes;
            }
          }
        }
        var total_cost = resul_taxes * quantity[i];
        total_cost = total_cost.toFixed(2);

        if (!isNaN(total_cost)) {
          total1 = total1 + parseFloat(total_cost);
        }

        $(this).find('td').eq(9).html('$ ' + total_cost);
        var total_price = resul_profit * quantity[i];
        total_price = total_price.toFixed(2);

        if (!isNaN(total_price)) {
          total2 = total2 + parseFloat(total_price);
        }

        $(this).find('td').eq(11).html('$ ' + total_price);
        if($(this).hasClass('fila_subitem')){
          if(contador_subitems === 0){
            partes_total_price_subitems = partes_total_price_subitems + total_price;
          }else{
            partes_total_price_subitems = partes_total_price_subitems + ',' + total_price;
          }
          contador_subitems++;
        }else{
          if (i === 0) {
            partes_total_price = partes_total_price + total_price;
          } else {
            partes_total_price = partes_total_price + ',' + total_price;
          }
        }

        i++;
        j++;
    });
    var dif_total = total2 - total1;
    dif_total = dif_total.toFixed(2);
    $('#additional').val(additional);
    $('#additional_subitems').val(additional_subitems);
    $('#unit_prices').val(unit_prices);
    $('#unit_prices_subitems').val(unit_prices_subitems);
    $('#partes_total_price').val(partes_total_price);
    $('#partes_total_price_subitems').val(partes_total_price_subitems);
    total1 = total1.toFixed(2);
    total2 = total2.toFixed(2);
    var percentage_profit = ((dif_total/total1)*100).toFixed(2);
    $('#total_cost').val(total1);
    $('#total_price').val(total2);
    $('#total1').html('$ ' + total1);
    $('#total2').html('$ ' + total2);
    $('#dif_total').html('$ ' + dif_total + '<br>' + percentage_profit + '%');
    $('#total_quantity').html(total_quantity);
    $('#total_additional').html('$ ' + total_additional);
  }, 100);
}



  $('#form_edited_quote').submit(function () {
    var total_additional = 0;
    var payment_terms = 0;
    if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
      payment_terms = 1.0299;
    } else {
      payment_terms = 1;
    }
    var taxes = $('#taxes').val();
    var profit = $('#profit').val();
    if(!isNaN($('#additional_general').val()) && $('#additional_general').val() != ''){
      var additional_general = $('#additional_general').val();
    }else{
      var additional_general = 0;
    }

    if(!isNaN($('#shipping_cost').val()) && $('#shipping_cost').val() != ''){
      var shipping_cost = $('#shipping_cost').val();
    }else{
      var shipping_cost = 0;
    }
    var contador_subitems = 0;
    var i = 0;
    var j = 1;
    var total1 = 0;
    var total2 = 0 + parseFloat(shipping_cost);
    var partes_total_price = '';
    var partes_total_price_subitems = '';
    var unit_prices = '';
    var unit_prices_subitems = '';
    var additional = '';
    var additional_subitems = '';
    var total_quantity = 0;
    $('#items tr').each(function () {
      if(!isNaN($(this).find('td').eq(5).text())){
        total_quantity = total_quantity + Number($(this).find('td').eq(5).text());
      }
      if(!isNaN($('#add_cost' + j).val()) && $('#add_cost' + j).val() != ''){
        var add_cost = $('#add_cost' + j).val();
      }else{
        var add_cost = 0;
      }

      total_additional = total_additional + (parseFloat(add_cost)*quantity[i]) + (parseFloat(additional_general)*quantity[i]);

      if($(this).hasClass('fila_subitem')){
        if(contador_subitems === 0){
          additional_subitems = additional_subitems + add_cost;
        }else{
          additional_subitems = additional_subitems + ',' + add_cost;
        }
      }else{
        if (i === 0) {
          additional = additional + add_cost;
        } else {
          additional = additional + ',' + add_cost;
        }
      }

        var resul_taxes = parseFloat(additional_general) + parseFloat(add_cost) + ((1 + (taxes / 100)) * monto[i] * payment_terms);
        resul_taxes = resul_taxes.toFixed(2);
        $(this).find('td').eq(8).html('$ ' + resul_taxes);
        if (profit !== 0) {
          var resul_profit = (1 + (profit / 100)) * resul_taxes;
          resul_profit = resul_profit.toFixed(2);
          $(this).find('td').eq(10).html('$ ' + resul_profit);
          if($(this).hasClass('fila_subitem')){
            if (contador_subitems === 0) {
              unit_prices_subitems = unit_prices_subitems + resul_profit;
            } else {
              unit_prices_subitems = unit_prices_subitems + ',' + resul_profit;
            }

          }else{
            if (i === 0) {
              unit_prices = unit_prices + resul_profit;
            } else {
              unit_prices = unit_prices + ',' + resul_profit;
            }
          }
        } else {
          $(this).find('td').eq(10).html('$ ' + resul_taxes);
          if($(this).hasClass('fila_subitem')){
            if (contador_subitems === 0) {
              unit_prices_subitems = unit_prices_subitems + resul_taxes;
            } else {
              unit_prices_subitems = unit_prices_subitems + ',' + resul_taxes;
            }

          }else{
            if (i === 0) {
              unit_prices = unit_prices + resul_taxes;
            } else {
              unit_prices = unit_prices + ',' + resul_taxes;
            }
          }
        }
        var total_cost = resul_taxes * quantity[i];
        total_cost = total_cost.toFixed(2);

        if (!isNaN(total_cost)) {
          total1 = total1 + parseFloat(total_cost);
        }

        $(this).find('td').eq(9).html('$ ' + total_cost);
        var total_price = resul_profit * quantity[i];
        total_price = total_price.toFixed(2);

        if (!isNaN(total_price)) {
          total2 = total2 + parseFloat(total_price);
        }

        $(this).find('td').eq(11).html('$ ' + total_price);
        if($(this).hasClass('fila_subitem')){
          if(contador_subitems === 0){
            partes_total_price_subitems = partes_total_price_subitems + total_price;
          }else{
            partes_total_price_subitems = partes_total_price_subitems + ',' + total_price;
          }
          contador_subitems++;
        }else{
          if (i === 0) {
            partes_total_price = partes_total_price + total_price;
          } else {
            partes_total_price = partes_total_price + ',' + total_price;
          }
        }

        i++;
        j++;
    });
    var dif_total = total2 - total1;
    dif_total = dif_total.toFixed(2);
    $('#additional').val(additional);
    $('#additional_subitems').val(additional_subitems);
    $('#unit_prices').val(unit_prices);
    $('#unit_prices_subitems').val(unit_prices_subitems);
    $('#partes_total_price').val(partes_total_price);
    $('#partes_total_price_subitems').val(partes_total_price_subitems);
    total1 = total1.toFixed(2);
    total2 = total2.toFixed(2);
    var percentage_profit = ((dif_total/total1)*100).toFixed(2);
    $('#total_cost').val(total1);
    $('#total_price').val(total2);
    $('#total1').html('$ ' + total1);
    $('#total2').html('$ ' + total2);
    $('#dif_total').html('$ ' + dif_total + '<br>' + percentage_profit + '%');
    $('#total_quantity').html(total_quantity);
    $('#total_additional').html('$ ' + total_additional);
  });
});
