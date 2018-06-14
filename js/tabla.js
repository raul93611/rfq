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
});
    