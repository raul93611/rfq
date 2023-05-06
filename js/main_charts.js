$(document).ready(function () {
  if($('#graficas').length != 0){
    $.ajax({
      url: "/rfq/main_charts",
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function(data) {
        $('#annual_awards_amounts .current').html('$ ' + data.annual_awards_amount);
        $('#annual_awards_amounts .past').html('$ ' + data.past_annual_awards_amount);
        $('#annual_awards .current').html(data.annual_awards);
        $('#annual_awards .past').html(data.past_annual_awards);
        
        const usernames = data.completed_quotes_by_user_and_last_current_month.map(obj => obj.user_name);
        const totalCompletedQuotes = data.completed_quotes_by_user_and_last_current_month.map(obj => obj.total_quotes);
        const totalCompletedQuotesLastMonth = data.completed_quotes_by_user_and_last_current_month.map(obj => obj.total_quotes_past_month);
        var chartdata = {
          labels: usernames,
          datasets: [
            {
              label: 'Current month',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: totalCompletedQuotes
            },
            {
              label: 'Last month',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: totalCompletedQuotesLastMonth
            }
          ]
        };
        var box = $("#completados-chart");
        var grafico = new Chart(box, {
          type: 'bar',
          data: chartdata,
          options: {
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          },
        });

        const annualAwardsAmountByMonth = data.annual_awards_amount_by_month.map(obj => obj.total_price);
        const pastAnnualAwardsAmountByMonth = data.past_annual_awards_amount_by_month.map(obj => obj.total_price);
        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              label: 'Annual awards(by amount) ',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: annualAwardsAmountByMonth
            },
            {
              label: 'Past annual awards(by amount)',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: pastAnnualAwardsAmountByMonth
            }
          ]
        };
        var box = $("#monto_ganados_anual_chart");
        var grafico = new Chart(box, {
          type: 'bar',
          data: chartdata,
          options: {
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: $.extend({
                  beginAtZero: true,
                  callback: function (value, index, values) {
                    if (value >= 1000) {
                      value /= 1000;
                      value += 'k';
                    }
                    return '$' + value;
                  }
                }, {fontColor: '#39485A'})
              }
            }
          },
        });

        const totalAwardQuotes = data.award_quotes_by_user_and_last_current_month.map(obj => obj.total_quotes);
        const totalAwardQuotesLastMonth = data.award_quotes_by_user_and_last_current_month.map(obj => obj.total_quotes_past_month);
        var chartdata = {
          labels: usernames,
          datasets: [
            {
              label: 'Current month',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: totalAwardQuotes
            },
            {
              label: 'Last month',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: totalAwardQuotesLastMonth
            }
          ]
        };
        var box = $("#ganadas-chart");
        var grafico = new Chart(box, {
          type: 'bar',
          data: chartdata,
          options: {
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          },
        });

        const annualAwardsByMonth = data.annual_awards_by_month.map(obj => obj.total_quotes);
        const pastAnnualAwardsByMonth = data.past_annual_awards_by_month.map(obj => obj.total_quotes);
        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              label: 'Annual awards',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: annualAwardsByMonth
            },
            {
              label: 'Past annual awards',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: pastAnnualAwardsByMonth
            }
          ]
        };
        var box = $("#ganados_anuales_chart");
        var grafico = new Chart(box, {
          type: 'bar',
          data: chartdata,
          options: {
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true
              }
            }
          },
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  }
});
