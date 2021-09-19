$(document).ready(function () {
  $('#report_select').change(function() {
    if($(this).find('[value="fulfillment_pending"]').is(':selected')){
      $('select[name="month"]').attr('disabled', true);
      $('select[name="year"]').attr('disabled', true);
    }else{
      $('select[name="month"]').attr('disabled', false);
      $('select[name="year"]').attr('disabled', false);
    }
  })
});
