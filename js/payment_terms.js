$(document).ready(function () {
  let payment_terms_table = $('#payment_terms_table').DataTable({
    ajax: '/rfq/load_payment_terms_table/',
    "columnDefs": [
      { className: "text-center", "targets": [1] }
    ]
  });

  $('#add_payment_term').click(function (){
    $('#add_payment_term_modal').modal();
  });

  $('#add_payment_term_form').submit(function(){
    $.post('/rfq/save_payment_term', $(this).serialize(), function(res){
      if(res.result === false){
        $('.error_message').show();
      }else{
        $('#add_payment_term_form')[0].reset();
        $('#add_payment_term_modal').modal('hide');
        $('.error_message').hide();
        payment_terms_table.ajax.reload(null, false);
      }
    });

    return false;
  });

  $('#payment_terms_table').on('click', '.edit_button', function(){
    $('#edit_payment_term_modal form').load('/rfq/load_payment_term/' + $(this).attr('data'), function(){
      $('#edit_payment_term_modal').modal();
    });
    return false;
  });

  $('#edit_payment_term_form').submit(function(){
    $.post('/rfq/update_payment_term', $(this).serialize(), function(res){
      if(res.result === false){
        $('.error_message').show();
      }else{
        $('#edit_payment_term_form')[0].reset();
        $('#edit_payment_term_modal').modal('hide');
        $('.error_message').hide();
        payment_terms_table.ajax.reload(null, false);
      }
    });
    return false;
  });

  $('#payment_terms_table').on('click', '.delete_button', function(){
    $('#continue_button').attr('data', $(this).attr('data'));
    $('#alert_delete_system').modal();
  });

  $('#continue_button').click(function(){
    $.ajax({
      url: '/rfq/delete_payment_term/',
      data: {
        id_payment_term: $(this).attr('data')
      },
      type: 'POST',
      success: function(res){
        payment_terms_table.ajax.reload(null, false);
        $('#alert_delete_system').modal('hide');
      }
    });
    return false;
  });
});
