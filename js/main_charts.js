$(document).ready(function () {
  if($('#graficas').length != 0){
    $.ajax({
      url: "/rfq/main_charts",
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function(data) {
        var usernames = data.usernames;
        var chartdata = {
          labels: usernames,
          datasets: [
            {
              label: 'Current month',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: data.completed_quotes
            },
            {
              label: 'Last month',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: data.past_completed_quotes
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

        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              label: 'Annual awards(by amount)',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: data.monthly_price_awards
            },
            {
              label: 'Past annual awards(by amount)',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: data.past_monthly_price_awards
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

        var chartdata = {
          labels: usernames,
          datasets: [
            {
              label: 'Current month',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: data.award_quotes
            },
            {
              label: 'Last month',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: data.past_award_quotes
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

        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
            {
              label: 'Annual awards',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: data.monthly_awards
            },
            {
              label: 'Past annual awards',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: data.past_monthly_awards
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

        var dataSets = [];
        usernames.forEach((username, i) => {
          var color = '#'+Math.floor(Math.random()*16777215).toString(16);
          dataSets.push(
            {
              label: username,
              data: data.monthly_completed_quotes_by_user[i],
              backgroundColor: color,
              borderColor: color,
            }
          );
        });

        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: dataSets
        };
        var box = $("#user_by_month_completed");
        var grafico = new Chart(box, {
          type: 'line',
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

        var dataSets = [];
        usernames.forEach((username, i) => {
          var color = '#'+Math.floor(Math.random()*16777215).toString(16);
          dataSets.push(
            {
              label: username,
              data: data.monthly_awards_quotes_by_user[i],
              backgroundColor: color,
              borderColor: color,
            }
          );
        });

        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: dataSets
        };
        var box = $("#user_by_month_award");
        var grafico = new Chart(box, {
          type: 'line',
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

        var dataSets = [];
        usernames.forEach((username, i) => {
          var color = '#'+Math.floor(Math.random()*16777215).toString(16);
          dataSets.push(
            {
              label: username,
              data: data.monthly_price_awards_quotes_by_user[i],
              backgroundColor: color,
              borderColor: color,
            }
          );
        });

        var chartdata = {
          labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: dataSets
        };
        var box = $("#user_by_month_award_amount");
        var grafico = new Chart(box, {
          type: 'line',
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
