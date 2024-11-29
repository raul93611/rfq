$(document).ready(function () {
  const idRfq = $('input[name="id_rfq"]').val();

  // Helper function to create bar charts
  function createBarChart(elementId, labels, datasets) {
    const chartElement = $(elementId);
    new Chart(chartElement, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: datasets
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        },
        plugins: {
          tooltip: {
            filter: tooltipItem => tooltipItem.raw !== null
          }
        }
      }
    });
  }

  $.ajax({
    url: `/rfq/kpi/${idRfq}`,
    dataType: 'json',
    contentType: "application/json; charset=utf-8",
    method: "GET",
    success: function (data) {
      // General Chart
      createBarChart("#general-chart",
        ['Total Price', 'Total Cost', 'Profit'],
        [
          {
            label: 'Quote',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: data.general.quote
          },
          {
            label: 'Re-Quote',
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: data.general.reQuote
          },
          {
            label: 'Fulfillment',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: data.general.fulfillment
          }
        ]
      );

      // RFQ Chart
      createBarChart("#rfq-chart",
        ['Total Price', 'Total Cost', 'Profit'],
        [
          {
            label: 'Quote',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: data.rfq.quote
          },
          {
            label: 'Re-Quote',
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: data.rfq.reQuote
          },
          {
            label: 'Fulfillment',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: data.rfq.fulfillment
          }
        ]
      );

      // RFP Chart
      createBarChart("#rfp-chart",
        ['Total Price', 'Total Cost', 'Profit'],
        [
          {
            label: 'Services',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: data.rfp
          }
        ]
      );
    },
    error: function (xhr, status, error) {
      console.error("Error fetching KPI data:", {
        xhr: xhr,
        status: status,
        error: error
      });
    }
  });
});