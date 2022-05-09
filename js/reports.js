$(document).ready(function () {
  const quarterSelect = $('select[name="quarter"]');
  const monthSelect = $('select[name="month"]');
  const yearSelect = $('select[name="year"]');
  const monthlyOption = $('#monthly');
  const quarterlyOption = $('#quarterly');
  const yearlyOption = $('#yearly');
  const reportSelect = $('#report_select');
  const typeInput = $('input[name="type"]');

  function checkFulfillmentReport(){
    let isSelected = false;
    if(reportSelect.find('[value="fulfillment_pending"]').is(':selected')){
      monthSelect.attr('disabled', true);
      yearSelect.attr('disabled', true);
      quarterSelect.attr('disabled', true);
      monthSelect.show();
      quarterSelect.hide();
      isSelected = true;
    }
    return isSelected;
  }

  function monthly(){
    monthSelect.attr('disabled', false);
    quarterSelect.attr('disabled', false);
    yearSelect.attr('disabled', false);
    monthSelect.show();
    quarterSelect.hide();
    typeInput.val('monthly');
  }

  function quarterly(){
    monthSelect.attr('disabled', false);
    quarterSelect.attr('disabled', false);
    yearSelect.attr('disabled', false);
    monthSelect.hide();
    quarterSelect.show();
    typeInput.val('quarterly');
  }

  function yearly(){
    monthSelect.attr('disabled', true);
    quarterSelect.attr('disabled', true);
    yearSelect.attr('disabled', false);
    monthSelect.show();
    quarterSelect.hide();
    typeInput.val('yearly');
  }

  function checkReportType(){
    let isSelected = checkFulfillmentReport();
    if(!isSelected){
      if(monthlyOption.hasClass('active')){
        monthly();
      } else if (quarterlyOption.hasClass('active')){
        quarterly();
      } else if(yearlyOption.hasClass('active')){
        yearly();
      }
    }
  }

  checkReportType();

  monthlyOption.click(function(){
    let isSelected = checkFulfillmentReport();
    if(!isSelected){
      monthly();
    }
  });

  quarterlyOption.click(function() {
    let isSelected = checkFulfillmentReport();
    if(!isSelected){
      quarterly();
    }
  });

  yearlyOption.click(function() {
    let isSelected = checkFulfillmentReport();
    if(!isSelected){
      yearly();
    }
  });

  reportSelect.change(function() {
    checkReportType();
  });
});
