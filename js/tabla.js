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

$(document).ready(function () {
  $('#sidebar_collapse').on('click', function(){
    $('#footer_item').toggleClass('footer_item1');
  });



    $('#issue_date').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
    $('#end_date').inputmask("datetime", {
        mask: "2/1/y h:s",
        placeholder: "mm/dd/yyyy hh:mm",
        leapday: "02/29/",
        separator: "/",
        alias: "mm/dd/yyyy"
    });
    $('#completed_date').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
    $('#expiration_date').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
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


    var payment_terms = 0;
    if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
        payment_terms = 1.0215;
    } else {
        payment_terms = 1;
    }
    var taxes = $('#taxes').val();
    var profit = $('#profit').val();
    if($('#additional_general').val() == ''){
      var additional_general = 0;
    }else{
      var additional_general = $('#additional_general').val();
    }

    var shipping_cost = $('#shipping_cost').val();

    var i = 0;
    var j = 1;
    var total1 = 0;
    var total2 = 0 + parseFloat(shipping_cost);
    var partes_total_price = '';
    var unit_prices = '';
    var additional = '';
    $('#items tr').each(function () {
        var add_cost = $('#add_cost' + j).val();
        if (i === 0) {
            additional = additional + add_cost;
        } else {
            additional = additional + ',' + add_cost;
        }
        var resul_taxes = parseFloat(additional_general) + parseFloat(add_cost) + ((1 + (taxes / 100)) * monto[i] * payment_terms);
        resul_taxes = resul_taxes.toFixed(2);
        $(this).find('td').eq(8).html('$ ' + resul_taxes);
        if (profit !== 0) {
            var resul_profit = (1 + (profit / 100)) * resul_taxes;
            resul_profit = resul_profit.toFixed(2);
            $(this).find('td').eq(10).html('$ ' + resul_profit);
            if (i === 0) {
                unit_prices = unit_prices + resul_profit;
            } else {
                unit_prices = unit_prices + ',' + resul_profit;
            }
        } else {
            $(this).find('td').eq(10).html('$ ' + resul_taxes);
            if (i === 0) {
                unit_prices = unit_prices + resul_taxes;
            } else {
                unit_prices = unit_prices + ',' + resul_taxes;
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

        if (i === 0) {
            partes_total_price = partes_total_price + total_price;
        } else {
            partes_total_price = partes_total_price + ',' + total_price;
        }
        i++;
        j++;
    });
    $('#additional').val(additional);
    $('#unit_prices').val(unit_prices);
    $('#partes_total_price').val(partes_total_price);
    total1 = total1.toFixed(2);
    total2 = total2.toFixed(2);
    $('#total_cost').val(total1);
    $('#total_price').val(total2);
    $('#total1').html('$ ' + total1);
    $('#total2').html('$ ' + total2);

    $('#calculate').click(function () {
        var payment_terms = 0;
        if ($('input:radio[name=payment_terms]:checked').val() === 'Net 30/CC') {
            payment_terms = 1.0215;
        } else {
            payment_terms = 1;
        }
        var taxes = $('#taxes').val();
        var profit = $('#profit').val();
        if($('#additional_general').val() == ''){
          var additional_general = 0;
        }else{
          var additional_general = $('#additional_general').val();
        }
        var shipping_cost = $('#shipping_cost').val();

        var i = 0;
        var j = 1;
        var total1 = 0;
        var total2 = 0 + parseFloat(shipping_cost);
        var partes_total_price = '';
        var unit_prices = '';
        var additional = '';
        $('#items tr').each(function () {
          if($('#add_cost' + j).val() == ''){
            var add_cost = 0;
          }else{
            var add_cost = $('#add_cost' + j).val();
          }
            if (i === 0) {
                additional = additional + add_cost;
            } else {
                additional = additional + ',' + add_cost;
            }
            var resul_taxes = parseFloat(additional_general) + parseFloat(add_cost) + ((1 + (taxes / 100)) * monto[i] * payment_terms);
            resul_taxes = resul_taxes.toFixed(2);
            $(this).find('td').eq(8).html('$ ' + resul_taxes);
            if (profit !== 0) {
                var resul_profit = (1 + (profit / 100)) * resul_taxes;
                resul_profit = resul_profit.toFixed(2);
                $(this).find('td').eq(10).html('$ ' + resul_profit);
                if (i === 0) {
                    unit_prices = unit_prices + resul_profit;
                } else {
                    unit_prices = unit_prices + ',' + resul_profit;
                }
            } else {
                $(this).find('td').eq(10).html('$ ' + resul_taxes);
                if (i === 0) {
                    unit_prices = unit_prices + resul_taxes;
                } else {
                    unit_prices = unit_prices + ',' + resul_taxes;
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

            if (i === 0) {
                partes_total_price = partes_total_price + total_price;
            } else {
                partes_total_price = partes_total_price + ',' + total_price;
            }
            i++;
            j++;
        });
        $('#additional').val(additional);
        $('#unit_prices').val(unit_prices);
        $('#partes_total_price').val(partes_total_price);
        total1 = total1.toFixed(2);
        total2 = total2.toFixed(2);
        $('#total_cost').val(total1);
        $('#total_price').val(total2);
        $('#total1').html('$ ' + total1);
        $('#total2').html('$ ' + total2);
    });
});
