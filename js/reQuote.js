$(document).ready(function () {
  /***************************************************************************************************/
    if($('#re_quote_form').length != 0){
      var time2 = setInterval(function(){
        var payment_terms = 1;
        var total_ganado = parseFloat($('#total_ganado').html().split(' ')[1]);
        if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
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
        var percentage_profit_rq = ((profit_rq/total_ganado)*100).toFixed(2);
        $('#profit_rq').html('$ ' + profit_rq + '<br>' + percentage_profit_rq + '%');
      }, 100);
    }

  $('#re_quote_form').submit(function () {
    var payment_terms = 1;
    var total_ganado = parseFloat($('#total_ganado').html().split(' ')[1]);
    if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
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
    var percentage_profit_rq = ((profit_rq/total_ganado)*100).toFixed(2);
    $('#profit_rq').html('$ ' + profit_rq + '<br>' + percentage_profit_rq + '%');
  });
  /****************************SERVICES*******************************************/
  const unitPriceFields = [];
  const servicesQuantityFields = [];
  $('#services_table tbody .service_item').each(function () {
    unitPriceFields.push(+$(this).find('td').eq(4).text());
    servicesQuantityFields.push(+$(this).find('td').eq(3).text());
  });

  const calcServices = function () {
    const paymentTerms = $('input:radio[name=services_payment_term]:checked').val() === 'Net 30/CC' ? 1.0299 : 1;
    let totalServices = 0;

    $('#services_table tbody .service_item').each(function (i, element) {
      const newUnitPrice = (unitPriceFields[i] * paymentTerms).toFixed(2);
      const newTotalPrice = (newUnitPrice * servicesQuantityFields[i]).toFixed(2);
      totalServices += +newTotalPrice;

      $(this).find('td').eq(4).html(newUnitPrice);
      $(this).find('td').eq(5).html(newTotalPrice);
    });

    $('#total_service').html('$ ' + totalServices.toFixed(2));
  }

  const servicesPaymentTerms = setInterval(calcServices, 100);
  $('#form_edited_quote').submit(calcServices);

  //edit service modal
  $('#services_table').on('click', '.edit_service', function(){
    $('#edit_service_modal form').load('/rfq/re_quote_sc/load_service/' + $(this).attr('data'), function(){
      console.log($(this).attr('data'));
      $('#edit_service_modal').modal();
    });
  });
});
