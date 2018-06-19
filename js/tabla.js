function myFunction() {
    var input, filter, table, tr, td, i, select;
    select = document.getElementById("tipo");
    var tipo = select.options[select.selectedIndex].value;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        switch (tipo) {
            case 'E-mail Code':
                td = tr[i].getElementsByTagName("button")[0];
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
            case 'Status':
                td = tr[i].getElementsByTagName("td")[5];
                break;
            case 'Amount':
                td = tr[i].getElementsByTagName("td")[6];
                break;
            case 'Completed date':
                td = tr[i].getElementsByTagName("td")[7];
                break;
            case 'Proposal':
                td = tr[i].getElementsByTagName("td")[8];
                break;
            case 'Comments':
                td = tr[i].getElementsByTagName("td")[9];
                break;
            case 'Award':
                td = tr[i].getElementsByTagName("td")[10];
                break;
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

function validar_form() {
    var brand = document.forms['form_equipo']['brand'].value;
    var part_number = document.forms['form_equipo']['part_number'].value;
    var description = document.forms['form_equipo']['description'].value;
    var quantity = document.forms['form_equipo']['quantity'].value;
    var unit_price = document.forms['form_equipo']['unit_price'].value;

    if (brand == '' || part_number == '' || description == '' || quantity == '' || unit_price == '') {
        alert('Form must be fill out.');
        return false;
    }
}

$(document).ready(function () {
    $('#issue_date').inputmask('mm/dd/yyyy', {'placeholder': 'mm/dd/yyyy'});
    $('#end_date').inputmask("datetime", {
        mask: "2/1/y h:s",
        placeholder: "mm/dd/yyyy hh:mm",
        leapday: "02/29/",
        separator: "/",
        alias: "mm/dd/yyyy"
    });

    //REALIZAR CALCULO DE LA TABLA DE ITEMS
    var monto = [];
    var quantity = [];

    $('#items tr').each(function () {
        quantity.push($(this).find('td').eq(4).text());
        if(!isNaN($(this).find('td').eq(6).text().split(' ')[1])){
            monto.push($(this).find('td').eq(6).text().split(' ')[1]);
        }else{
            monto.push(0);
        }
    });

    var taxes = $('#taxes').val();
    var profit = $('#profit').val();
    var i = 0;
    var total1 = 0;
    var total2 = 0;
    $('#items tr').each(function () {
        var resul_taxes = (1 + (taxes / 100)) * monto[i];
        resul_taxes = resul_taxes.toFixed(2);
        $(this).find('td').eq(6).html('$ ' + resul_taxes);
        if (profit !== 0) {
            var resul_profit = (1 + (profit / 100)) * resul_taxes;
            resul_profit = resul_profit.toFixed(2);
            $(this).find('td').eq(8).html('$ ' + resul_profit);
        } else {
            $(this).find('td').eq(8).html('$ ' + resul_taxes);
        }
        var total_cost = resul_taxes * quantity[i];
        total_cost = total_cost.toFixed(2);

        if (!isNaN(total_cost)) {
            total1 = total1 + parseInt(total_cost);
        }

        $(this).find('td').eq(7).html('$ ' + total_cost);
        var total_price = resul_profit * quantity[i];
        total_price = total_price.toFixed(2);

        if (!isNaN(total_price)) {
            total2 = total2 + parseInt(total_price);
        }

        $(this).find('td').eq(9).html('$ ' + total_price);
        i++;
    });
    $('#total1').html('$ ' + total1);
    $('#total2').html('$ ' + total2);

    $('#calculate').click(function () {
        var taxes = $('#taxes').val();
        var profit = $('#profit').val();
        var i = 0;
        var total1 = 0;
        var total2 = 0;
        $('#items tr').each(function () {
            var resul_taxes = (1 + (taxes / 100)) * monto[i];
            resul_taxes = resul_taxes.toFixed(2);
            $(this).find('td').eq(6).html('$ ' + resul_taxes);
            if (profit !== 0) {
                var resul_profit = (1 + (profit / 100)) * resul_taxes;
                resul_profit = resul_profit.toFixed(2);
                $(this).find('td').eq(8).html('$ ' + resul_profit);
            } else {
                $(this).find('td').eq(8).html('$ ' + resul_taxes);
            }
            var total_cost = resul_taxes * quantity[i];
            total_cost = total_cost.toFixed(2);

            if (!isNaN(total_cost)) {
                total1 = total1 + parseFloat(total_cost);
            }

            $(this).find('td').eq(7).html('$ ' + total_cost);
            var total_price = resul_profit * quantity[i];
            total_price = total_price.toFixed(2);

            if (!isNaN(total_price)) {
                total2 = total2 + parseFloat(total_price);
            }

            $(this).find('td').eq(9).html('$ ' + total_price);
            i++;
        });
        $('#total1').html('$ ' + total1);
        $('#total2').html('$ ' + total2);
    });
});
    