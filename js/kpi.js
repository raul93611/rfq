$(document).ready(function () {
  const idRfq = $('input[name="id_rfq"]').val();
  $.ajax({
    url: `/rfq/kpi/${idRfq}`,
    dataType: 'json',
    contentType: "application/json; charset=utf-8",
    method: "GET",
    success: function (data) {
      // General chart
      const general = data.general;
      let chartdata = {
        labels: ['Total Price', 'Total Cost', 'Profit'],
        datasets: [
          {
            label: 'Quote',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: general.quote
          },
          {
            label: 'Re-Quote',
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: general.reQuote
          },
          {
            label: 'Fulfillment',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: general.fulfillment
          },
        ]
      };
      let box = $("#general-chart");
      var chart = new Chart(box, {
        type: 'bar',
        data: chartdata,
        options: {
          maintainAspectRatio: false,
          skipNull: true,
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              filter: function (tooltipItem) {
                return tooltipItem.raw === null ? false : true; 
              },
            }
          }
        },
      });

      //RFQ chart
      const rfq = data.rfq;
      chartdata = {
        labels: ['Total Price', 'Total Cost', 'Profit'],
        datasets: [
          {
            label: 'Quote',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: rfq.quote
          },
          {
            label: 'Re-Quote',
            backgroundColor: '#ffc107',
            borderColor: '#ffc107',
            data: rfq.reQuote
          },
          {
            label: 'Fulfillment',
            backgroundColor: '#28a745',
            borderColor: '#28a745',
            data: rfq.fulfillment
          },
        ]
      };
      box = $("#rfq-chart");
      var chart = new Chart(box, {
        type: 'bar',
        data: chartdata,
        options: {
          maintainAspectRatio: false,
          skipNull: true,
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              filter: function (tooltipItem) {
                return tooltipItem.raw === null ? false : true; 
              },
            }
          }
        },
      });

      //RFP chart
      const rfp = data.rfp;
      chartdata = {
        labels: ['Total Price', 'Total Cost', 'Profit'],
        datasets: [
          {
            label: 'Services',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: rfp
          }
        ]
      };
      box = $("#rfp-chart");
      var chart = new Chart(box, {
        type: 'bar',
        data: chartdata,
        options: {
          maintainAspectRatio: false,
          skipNull: true,
          scales: {
            y: {
              beginAtZero: true
            }
          },
          plugins: {
            tooltip: {
              filter: function (tooltipItem) {
                return tooltipItem.raw === null ? false : true; 
              },
            }
          }
        },
      });
    },
    error: function (data) {
      console.error(data);
    }
  });
});
