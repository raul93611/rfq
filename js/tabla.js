$(document).ready(function () {
    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

function validar_form(){
    var brand = document.forms['form_equipo']['brand'].value;
    var part_number = document.forms['form_equipo']['part_number'].value;
    var description = document.forms['form_equipo']['description'].value;
    var quantity = document.forms['form_equipo']['quantity'].value;
    var unit_price = document.forms['form_equipo']['unit_price'].value;
    
    if(brand == '' || part_number == '' || description == '' || quantity == '' || unit_price == ''){
        alert('Form must be fill out.');
        return false;
    }
}