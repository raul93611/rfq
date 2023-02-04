$(document).ready(function () {
  let chart;
  $('#reports_charts_form').submit(function () {
    $.post('/rfq/reports_charts/', $(this).serialize(), function (data) {
      let chartdata = {
        labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
        datasets: [
          {
            label: 'Total Cost',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: data[0]
          },
          {
            label: 'Total Price',
            backgroundColor: '#485566',
            borderColor: '#485566',
            data: data[1]
          },
          {
            label: 'Profit',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: data[2]
          }
        ]
      };
      let box = $("#chart");
      if(chart) chart.destroy();
      chart = new Chart(box, {
        type: 'bar',
        data: chartdata,
        options: {
          maintainAspectRatio: false,
          skipNull: true,
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (value, index, values) {
                  if (value >= 1000) {
                    value /= 1000;
                    value += 'k';
                  }
                  return '$' + value;
                }
              }
            }
          },
          plugins: {
            tooltip: {
              filter: function (tooltipItem) {
                return tooltipItem.raw === null ? false : true;
              },
              callbacks: {
                footer: footer,
              },
              intersect: false
            },
            title: {
              display: true,
              text: 'Awards',
            }
          }
        },
      });
    });
    return false;
  });

  const footer = (tooltipItems) => {
    const profitPercentage = tooltipItems[1].parsed.y ? (tooltipItems[2].parsed.y/tooltipItems[1].parsed.y) * 100 : 0;
    return 'Profit (%): ' + profitPercentage.toFixed(2);
  };
});
