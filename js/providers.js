$(document).ready(function () {
  let providers_table = $('#providers_table').DataTable({
    ajax: '/rfq/provider/load_providers_table/',
    "columnDefs": [
      { className: "text-center", "targets": [1] }
    ]
  });

  $('#add_provider').click(function (){
    $('#add_provider_modal').modal();
  });

  $('#add_provider_form').submit(function(){
    $.post('/rfq/provider/save_provider', $(this).serialize(), function(res){
      if(res.result === false){
        $('.error_message').show();
      }else{
        $('#add_provider_form')[0].reset();
        $('#add_provider_modal').modal('hide');
        $('.error_message').hide();
        providers_table.ajax.reload(null, false);
      }
    });

    return false;
  });

  $('#providers_table').on('click', '.edit_button', function(){
    $('#edit_provider_modal form').load('/rfq/provider/load_provider/' + $(this).attr('data'), function(){
      $('#edit_provider_modal').modal();
    });
    return false;
  });

  $('#edit_provider_form').submit(function(){
    $.post('/rfq/provider/update_provider', $(this).serialize(), function(res){
      if(res.result === false){
        $('.error_message').show();
      }else{
        $('#edit_provider_form')[0].reset();
        $('#edit_provider_modal').modal('hide');
        $('.error_message').hide();
        providers_table.ajax.reload(null, false);
      }
    });
    return false;
  });

  $('#providers_table').on('click', '.delete_button', function(){
    $('#continue_button').attr('data', $(this).attr('data'));
    $('#alert_delete_system').modal();
  });

  $('#continue_button').click(function(){
    $.ajax({
      url: '/rfq/provider/delete_provider/',
      data: {
        id_provider: $(this).attr('data')
      },
      type: 'POST',
      success: function(res){
        providers_table.ajax.reload(null, false);
        $('#alert_delete_system').modal('hide');
      }
    });
    return false;
  });
});
