$(document).ready(function () {
  /***********************************FULFILLMENT SERVICES******************/
  $('#fulfillment_page').on('click', '.add_fulfillment_service_button', function(){
    var id_service = $(this).attr('name');
    $('#new_fulfillment_service_modal #id_service').val(id_service);
    $('#new_fulfillment_service_modal').modal();
  });

  $('#add_fulfillment_service_form').submit(function(){
    $.post('/rfq/save_fulfillment_service', $(this).serialize(), function(res){
      $('#add_fulfillment_service_form')[0].reset();
      $('#new_fulfillment_service_modal').modal('hide');
      console.log(res);
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_service_button', function(){
    $('#edit_fulfillment_service_modal form').load('/rfq/load_fulfillment_service/' + $(this).attr('data'), function(){
      $('#edit_fulfillment_service_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_service_form').submit(function(){
    $.post('/rfq/save_edit_fulfillment_service', $(this).serialize(), function(res){
      $('#edit_fulfillment_service_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_service_button', function(){
    $.ajax({
      url: '/rfq/delete_fulfillment_service/',
      data: {
        id_fulfillment_service: $(this).attr('data'),
        id_service: $(this).attr('id_service')
      },
      type: 'POST',
      success: function(res){
        $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
      }
    });
    return false;
  });
  /***********************************FULFILLMENT***************************/
  $('#fulfillment_page').on('click', '.add_fulfillment_item_button', function(){
    var id_item = $(this).attr('name');
    $('#new_fulfillment_item_modal #id_item').val(id_item);
    $('#new_fulfillment_item_modal').modal();
  });

  $('#add_fulfillment_item_form').submit(function(){
    $.post('/rfq/save_fulfillment_item', $(this).serialize(), function(res){
      $('#add_fulfillment_item_form')[0].reset();
      $('#new_fulfillment_item_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_item_button', function(){
    $('#edit_fulfillment_item_modal form').load('/rfq/load_fulfillment_item/' + $(this).attr('data'), function(){
      $('#edit_fulfillment_item_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_item_form').submit(function(){
    $.post('/rfq/save_edit_fulfillment_item', $(this).serialize(), function(res){
      $('#edit_fulfillment_item_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_item_button', function(){
    $.ajax({
      url: '/rfq/delete_fulfillment_item/',
      data: {
        id_fulfillment_item: $(this).attr('data'),
        id_item: $(this).attr('id_item')
      },
      type: 'POST',
      success: function(res){
        $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });

  $('#fulfillment_page').on('click', '.add_fulfillment_subitem_button', function(){
    var id_subitem = $(this).attr('name');
    $('#new_fulfillment_subitem_modal #id_subitem').val(id_subitem);
    $('#new_fulfillment_subitem_modal').modal();
  });

  $('#add_fulfillment_subitem_form').submit(function(){
    $.post('/rfq/save_fulfillment_subitem', $(this).serialize(), function(res){
      $('#add_fulfillment_subitem_form')[0].reset();
      $('#new_fulfillment_subitem_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.edit_fulfillment_subitem_button', function(){
    $('#edit_fulfillment_subitem_modal form').load('/rfq/load_fulfillment_subitem/' + $(this).attr('data'), function(){
      $('#edit_fulfillment_subitem_modal').modal();
    });
    return false;
  });

  $('#edit_fulfillment_subitem_form').submit(function(){
    $.post('/rfq/save_edit_fulfillment_subitem', $(this).serialize(), function(res){
      $('#edit_fulfillment_subitem_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });

  $('#fulfillment_page').on('click', '.delete_fulfillment_subitem_button', function(){
    $.ajax({
      url: '/rfq/delete_fulfillment_subitem/',
      data: {
        id_fulfillment_subitem: $(this).attr('data'),
        id_subitem: $(this).attr('id_subitem'),
        id_rfq: $(this).attr('id_rfq')
      },
      type: 'POST',
      success: function(res){
        $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
});