$(document).ready(function () {
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'left',
  });
  /*********************************TRACKINGS*******************************/
  $('#tracking_box').on('click', '.add_tracking_button', function(){
    var id_item = $(this).attr('name');
    $('#new_tracking #id_item').val(id_item);
    $('#new_tracking').modal();
  });
  $('#delivery_date, #due_date').daterangepicker({
    singleDatePicker: true
  });
  $('#tracking_box').on('click', '.edit_tracking', function(){
    $('#edit_tracking_modal form').load('/rfq/tracking/load_tracking/' + $(this).attr('data'), function(){
      $('.date').daterangepicker({
        singleDatePicker: true
      });
      $('#edit_tracking_modal').modal();
    });
    return false;
  });

  $('#edit_tracking_form').submit(function(){
    $.post('/rfq/tracking/save_edit_tracking', $(this).serialize(), function(res){
      $('#edit_tracking_modal').modal('hide');
      $('#tracking_box').load('/rfq/tracking/load_tracking_box/' + res.id_rfq);
    });
    return false;
  });

  $('#tracking_box').on('click', '.add_tracking_subitem_button', function(){
    var id_subitem = $(this).attr('name');
    $('#new_tracking_subitem #id_subitem').val(id_subitem);
    $('#new_tracking_subitem').modal();
  });
  $('#delivery_date_subitem').daterangepicker({
    singleDatePicker: true
  });
  $('#tracking_box').on('click', '.edit_tracking_subitem', function(){
    $('#edit_tracking_subitem_modal form').load('/rfq/tracking/load_tracking_subitem/' + $(this).attr('data'), function(){
      $('.date').daterangepicker({
        singleDatePicker: true
      });
      $('#edit_tracking_subitem_modal').modal();
    });
    return false;
  });
  $('#edit_tracking_subitem_form').submit(function(){
    $.post('/rfq/tracking/save_edit_tracking_subitem', $(this).serialize(), function(res){
      $('#edit_tracking_subitem_modal').modal('hide');
      $('#tracking_box').load('/rfq/tracking/load_tracking_box/' + res.id_rfq);
    });
    return false;
  });
});
