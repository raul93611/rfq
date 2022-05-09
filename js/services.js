$(document).ready(function () {
  $('#services_table').on('click', '.edit_service', function(){
    $('#edit_service_modal form').load('/rfq/load_service/' + $(this).attr('data'), function(){
      $('#edit_service_modal').modal();
    });
  });
  $('#add_service').click(function(){
    $('#add_service_modal').modal();
  });
});
