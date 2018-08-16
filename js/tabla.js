function myFunction() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla_cotizaciones");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'Code':
                td = tr[i].getElementsByTagName("a")[0];
                break;
            case 'Designated user':
                td = tr[i].getElementsByTagName("td")[1];
                break;
            case 'Type of Bid':
                td = tr[i].getElementsByTagName("td")[2];
                break;
            case 'Issue Date':
                td = tr[i].getElementsByTagName("td")[3];
                break;
            case 'End Date':
                td = tr[i].getElementsByTagName("td")[4];
                break;
            case 'Proposal':
                td = tr[i].getElementsByTagName("td")[5];
                break;
        }

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function myFunction1() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla_cotizaciones_completados");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'Code':
                td = tr[i].getElementsByTagName("a")[0];
                break;
            case 'Designated user':
                td = tr[i].getElementsByTagName("td")[1];
                break;
            case 'Type of Bid':
                td = tr[i].getElementsByTagName("td")[2];
                break;
            case 'Issue Date':
                td = tr[i].getElementsByTagName("td")[3];
                break;
            case 'End Date':
                td = tr[i].getElementsByTagName("td")[4];
                break;
            case 'Amount':
                td = tr[i].getElementByTagName('td')[5];
                break;
            case 'Completed date':
                td = tr[i].getElementByTagName('td')[6];
                break;
            case 'Proposal':
                td = tr[i].getElementsByTagName("td")[7];
                break;
            case 'Comments':
                td = tr[i].getElementByTagName('td')[8];
                break;
        }

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function myFunction2() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla_cotizaciones_submitted");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'Code':
                td = tr[i].getElementsByTagName("a")[0];
                break;
            case 'Designated user':
                td = tr[i].getElementsByTagName("td")[1];
                break;
            case 'Type of Bid':
                td = tr[i].getElementsByTagName("td")[2];
                break;
            case 'Issue Date':
                td = tr[i].getElementsByTagName("td")[3];
                break;
            case 'End Date':
                td = tr[i].getElementsByTagName("td")[4];
                break;
            case 'Amount':
                td = tr[i].getElementByTagName('td')[5];
                break;
            case 'Submitted date':
                td = tr[i].getElementByTagName('td')[6];
                break;
            case 'Proposal':
                td = tr[i].getElementsByTagName("td")[7];
                break;
            case 'Comments':
                td = tr[i].getElementByTagName('td')[8];
                break;
        }

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function myFunction3() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla_cotizaciones_award");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'Code':
                td = tr[i].getElementsByTagName("a")[0];
                break;
            case 'Designated user':
                td = tr[i].getElementsByTagName("td")[1];
                break;
            case 'Type of Bid':
                td = tr[i].getElementsByTagName("td")[2];
                break;
            case 'Issue Date':
                td = tr[i].getElementsByTagName("td")[3];
                break;
            case 'End Date':
                td = tr[i].getElementsByTagName("td")[4];
                break;
            case 'Amount':
                td = tr[i].getElementByTagName('td')[5];
                break;
            case 'Award date':
                td = tr[i].getElementByTagName('td')[6];
                break;
            case 'Proposal':
                td = tr[i].getElementsByTagName("td")[7];
                break;
            case 'Comments':
                td = tr[i].getElementByTagName('td')[8];
                break;
        }

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function myFunction4() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("tabla_usuarios");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'First names':
                td = tr[i].getElementsByTagName("td")[0];
                break;
            case 'Last names':
                td = tr[i].getElementsByTagName("td")[1];
                break;
        }

        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function myFunction5(){
  var input, filter, table, tr, td, i, select;
  select = document.getElementById("tipo");
  var tipo = select.options[select.selectedIndex].value;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("tabla_cotizaciones_no_bid");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
      switch (tipo) {
          case 'Code':
              td = tr[i].getElementsByTagName("a")[0];
              break;
          case 'Designated user':
              td = tr[i].getElementsByTagName("td")[1];
              break;
          case 'Type of Bid':
              td = tr[i].getElementsByTagName("td")[2];
              break;
          case 'Issue Date':
              td = tr[i].getElementsByTagName("td")[3];
              break;
          case 'End Date':
              td = tr[i].getElementsByTagName("td")[4];
              break;
          case 'Proposal':
              td = tr[i].getElementsByTagName("td")[5];
              break;
          case 'Comments':
              td = tr[i].getElementByTagName('td')[6];
              break;
      }

      if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
          } else {
              tr[i].style.display = "none";
          }
      }
  }
}

function myFunction6(){
  var input, filter, table, tr, td, i, select;
  select = document.getElementById("tipo_extra");
  var tipo = select.options[select.selectedIndex].value;
  input = document.getElementById("myInputadmin");
  filter = input.value.toUpperCase();
  table = document.getElementById("tabla_extra");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
      switch (tipo) {
          case 'Rfq id':
              td = tr[i].getElementsByTagName("td")[1];
              break;
          case 'User':
              td = tr[i].getElementsByTagName("td")[0];
              break;
      }

      if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
          } else {
              tr[i].style.display = "none";
          }
      }
  }
}

function myFunction7(){
  var input, filter, table, tr, td, i, select;
  select = document.getElementById("tipo");
  var tipo = select.options[select.selectedIndex].value;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("tabla_todas_cotizaciones_award");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
      switch (tipo) {
          case 'Proposal':
              td = tr[i].getElementsByTagName("td")[7];
              break;
          case 'Code':
              td = tr[i].getElementsByTagName("a")[0];
              break;
      }

      if (td) {
          if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
              tr[i].style.display = "";
          } else {
              tr[i].style.display = "none";
          }
      }
  }
}

$(document).ready(function () {
  $('#sidebar_collapse').on('click', function(){
    $('#footer_item').toggleClass('footer_item1');
  });

  $('#completed_date').datepicker();
  $('#expiration_date').datepicker();

  $('#tabla_submitted').DataTable();

    $('#issue_date').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
    $('#end_date').inputmask("datetime", {
        mask: "2/1/y h:s",
        placeholder: "mm/dd/yyyy hh:mm",
        leapday: "02/29/",
        separator: "/",
        alias: "mm/dd/yyyy"
    });

    $('#date_milestone').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});

    //REALIZAR CALCULO DE LA TABLA DE ITEMS
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


    var time = setInterval(function(){
      var total_additional = 0;
      var payment_terms = 0;
      if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
          payment_terms = 1.0215;
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
      $('#additional').val(additional);
      $('#additional_subitems').val(additional_subitems);
      $('#unit_prices').val(unit_prices);
      $('#unit_prices_subitems').val(unit_prices_subitems);
      $('#partes_total_price').val(partes_total_price);
      $('#partes_total_price_subitems').val(partes_total_price_subitems);
      total1 = total1.toFixed(2);
      total2 = total2.toFixed(2);
      $('#total_cost').val(total1);
      $('#total_price').val(total2);
      $('#total1').html('$ ' + total1);
      $('#total2').html('$ ' + total2);
      $('#total_quantity').html(total_quantity);
      $('#total_additional').html('$ ' + total_additional);
    }, 500);



    $('#form_edited_quote').submit(function () {
      var total_additional = 0;
      var payment_terms = 0;
      if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
          payment_terms = 1.0215;
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
      $('#additional').val(additional);
      $('#additional_subitems').val(additional_subitems);
      $('#unit_prices').val(unit_prices);
      $('#unit_prices_subitems').val(unit_prices_subitems);
      $('#partes_total_price').val(partes_total_price);
      $('#partes_total_price_subitems').val(partes_total_price_subitems);
      total1 = total1.toFixed(2);
      total2 = total2.toFixed(2);
      $('#total_cost').val(total1);
      $('#total_price').val(total2);
      $('#total1').html('$ ' + total1);
      $('#total2').html('$ ' + total2);
      $('#total_quantity').html(total_quantity);
      $('#total_additional').html('$ ' + total_additional);
    });
});
