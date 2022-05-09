$(document).ready(function () {
  /****************************************************************************/
  const net30Checkbox = $('#net30_cc');
  const fulfillmentPage = $('#fulfillment_page');
  let net30Fulfillment;
  fulfillmentPage.on('change', '#net30_cc', function(){
    if($(this).is(':checked')){
      net30Fulfillment = 1;
    }else{
      net30Fulfillment = 0;
    }
    $.ajax({
      url: '/rfq/save_net_30/',
      data: {
        id_rfq: $(this).attr('data'),
        value: net30Fulfillment
      },
      type: 'POST',
      success: function(res){
        $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
      }
    });
  });
  /***********************************TOOLTIP INITIALIZATION******************/
  $('body').tooltip({
    selector: '[data-toggle="tooltip"]',
    trigger: 'click',
    placement: 'left',
  });
  /***********************************FULFILLMENT AUDIT TRAILS******************/
  const btnAuditTrail = $('#fulfillment_audit_trails_button');
  const auditTrailModal = $('#fulfillment_audit_trails_modal');
  const auditTrailModalBody = $('#fulfillment_audit_trails_modal .modal-body');
  const auditTrailLink = $('.audit_trail_link');

  btnAuditTrail.click(function(){
    auditTrailModalBody.load('/rfq/load_fulfillment_audit_trails', {id_rfq: $(this).attr('data')}, function(){
      auditTrailModal.modal();
    });
  });

  auditTrailModal.on('click', '.audit_trail_link', function(){
    auditTrailModal.modal('hide');
    const id = $(this).attr('data');
    $(id).addClass('highlight');
    setTimeout(function() {
      $(id).removeClass('highlight');
    }, 5000);
  });

  /***********************************FULFILLMENT SHIPPING******************/
  let counterShipping = $('#edit_fulfillment_shipping_modal form').attr('data');

  $('#fulfillment_page').on('click', '#edit_fulfillment_shipping', function(){
    $('#edit_fulfillment_shipping_modal form').load('/rfq/load_fulfillment_shipping/' + $(this).attr('data'), function(){
      if(!+counterShipping)$('.remove_shipping').attr('disabled', 'disabled');
      $('#edit_fulfillment_shipping_modal').modal();
    });
  });

  $('#edit_fulfillment_shipping_form').submit(function(){
    $.post('/rfq/update_fulfillment_shipping', $(this).serialize(), function(res){
      $('#edit_fulfillment_shipping_form')[0].reset();
      $('#edit_fulfillment_shipping_modal').modal('hide');
      $('#fulfillment_page').load('/rfq/load_fulfillment_page/' + res.id_rfq);
    });
    return false;
  });



  $('#edit_fulfillment_shipping_modal form').on('click', '.add_shipping', function(){
    counterShipping++;
    $('input[name="shipping_counter"]').val(counterShipping);
    const shippingFields = `
    <div class="shipping${counterShipping}">
      <div class="form-group">
      <label for="fulfillment_shipping${counterShipping}">Description:</label>
      <input type="hidden" name="fulfillment_shipping_original${counterShipping}" value="">
      <input type="text" class="form-control form-control-sm" id="fulfillment_shipping${counterShipping}" name="fulfillment_shipping${counterShipping}" value="">
      </div>
      <div class="form-group">
      <label for="amount${counterShipping}">Amount:</label>
      <input type="hidden" name="amount_original${counterShipping}" value="">
      <input type="number" step=".01" id="amount${counterShipping}" class="form-control form-control-sm" name="amount${counterShipping}" value="">
      </div>
    </div>
    `;
    $('.shipping_container').append(shippingFields);
    if(counterShipping)$('.remove_shipping').removeAttr('disabled');
  });

  $('#edit_fulfillment_shipping_modal form').on('click', '.remove_shipping', function(){
    $(`.shipping${counterShipping}`).remove();
    counterShipping--;
    $('input[name="shipping_counter"]').val(counterShipping);
    if(!counterShipping)$('.remove_shipping').attr('disabled', 'disabled');
  });
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
