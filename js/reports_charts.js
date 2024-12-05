$(document).ready(function () {
  let chartQuote, chartRequote, chartFulfillment;

  // Submit event for generating charts
  $('#reports_charts_form').submit(function (e) {
    e.preventDefault();
    $.post('/rfq/reports_charts/', $(this).serialize(), function (data) {
      chartQuote = generateProfitChart('quote', chartQuote, data.quote_amounts);
      chartRequote = generateProfitChart('requote', chartRequote, data.re_quotes_amounts);
      chartFulfillment = generateProfitChart('fulfillment', chartFulfillment, data.fulfillment_amounts);
    });
  });

  // Function to generate profit chart
  const generateProfitChart = (id, existingChart, amounts) => {
    const totalCost = amounts.map(obj => obj.total_cost || 0);
    const totalPrice = amounts.map(obj => obj.total_price || 0);
    const profit = amounts.map(obj => obj.profit || 0);

    const chartData = {
      labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          label: 'Total Cost',
          backgroundColor: '#13A8F0',
          borderColor: '#13A8F0',
          data: totalCost,
        },
        {
          label: 'Total Price',
          backgroundColor: '#485566',
          borderColor: '#485566',
          data: totalPrice,
        },
        {
          label: 'Profit',
          backgroundColor: '#28a745',
          borderColor: '#28a745',
          data: profit,
        },
      ],
    };

    if (existingChart) existingChart.destroy();

    return new Chart($(`#${id}`), {
      type: 'bar',
      data: chartData,
      options: {
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: (value) => {
                if (value >= 1000) value = (value / 1000) + 'k';
                return `$${value}`;
              },
            },
          },
        },
        plugins: {
          tooltip: {
            filter: (tooltipItem) => tooltipItem.raw !== null,
            callbacks: {
              footer: tooltipFooterCallback,
            },
            intersect: false,
          },
          title: {
            display: true,
            text: id.toUpperCase(),
          },
        },
      },
    });
  };

  // Tooltip footer callback
  const tooltipFooterCallback = (tooltipItems) => {
    const totalPrice = tooltipItems[1]?.parsed.y || 0;
    const profit = tooltipItems[2]?.parsed.y || 0;
    const profitPercentage = totalPrice ? (profit / totalPrice) * 100 : 0;
    return `Profit (%): ${profitPercentage.toFixed(2)}%`;
  };
});