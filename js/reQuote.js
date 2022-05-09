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
});
