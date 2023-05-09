$(document).ready(function () {
  let chartQuote;
  let chartRequote;
  let chartFulfillment;
  $('#reports_charts_form').submit(function () {
    $.post('/rfq/reports_charts/', $(this).serialize(), function (data) {

      chartQuote = generateProfitChart('quote', chartQuote, data.quote_amounts);
      chartRequote = generateProfitChart('requote', chartRequote, data.re_quotes_amounts);
      chartFulfillment = generateProfitChart('fulfillment', chartFulfillment, data.fulfillment_amounts);
    });
    return false;
  });

  const generateProfitChart = (id, chartType, amounts) => {
    const totalCost = amounts.map(obj => obj.total_cost);
    const totalPrice = amounts.map(obj => obj.total_price);
    const profit = amounts.map(obj => obj.profit);
    let chartQuoteData = {
      labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          label: 'Total Cost',
          backgroundColor: '#13A8F0',
          borderColor: '#13A8F0',
          data: totalCost
        },
        {
          label: 'Total Price',
          backgroundColor: '#485566',
          borderColor: '#485566',
          data: totalPrice
        },
        {
          label: 'Profit',
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          data: profit
        }
      ]
    };
    if (chartType) chartType.destroy();
    chartType = new Chart($('#'+id), {
      type: 'bar',
      data: chartQuoteData,
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
            text: id.toUpperCase(),
          }
        }
      },
    });

    return chartType;
  };

  const footer = (tooltipItems) => {
    const profitPercentage = tooltipItems[1].parsed.y ? (tooltipItems[2].parsed.y / tooltipItems[1].parsed.y) * 100 : 0;
    return 'Profit (%): ' + profitPercentage.toFixed(2);
  };
});
