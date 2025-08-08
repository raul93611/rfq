$(document).ready(function () {
  if ($('#graficas').length > 0) {
    $.ajax({
      url: "/rfq/main_charts",
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function (data) {
        // Update HTML content
        $('#annual_awards_amounts .current').text(`$ ${data.annual_awards_amount}`);
        $('#annual_awards_amounts .past').text(`$ ${data.past_annual_awards_amount}`);
        $('#annual_awards .current').text(data.annual_awards);
        $('#annual_awards .past').text(data.past_annual_awards);

        // Helper function to create bar charts
        function createBarChart(elementId, labels, datasets, callback = null) {
          const chartElement = $(elementId);
          const options = {
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                ticks: callback ? callback : {}
              }
            }
          };

          new Chart(chartElement, {
            type: 'bar',
            data: { labels, datasets },
            options
          });
        }

        // Completed Quotes Chart
        const usernames = data.completed_quotes_by_user_past_month.map(obj => obj.user_name);
        const completedQuotesCurrent = data.completed_quotes_by_user_current_month.map(obj => obj.total_quotes);
        const completedQuotesPast = data.completed_quotes_by_user_past_month.map(obj => obj.total_quotes);

        createBarChart("#completados-chart", usernames, [
          {
            label: 'Current month',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: completedQuotesCurrent
          },
          {
            label: 'Last month',
            backgroundColor: '#39485A',
            borderColor: '#39485A',
            data: completedQuotesPast
          }
        ]);

        // Annual Awards Amount by Month Chart
        const annualAwardsAmountCurrent = data.annual_awards_amount_by_month.map(obj => obj.total_price);
        const annualAwardsAmountPast = data.past_annual_awards_amount_by_month.map(obj => obj.total_price);

        createBarChart(
          "#monto_ganados_anual_chart",
          ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          [
            {
              label: 'Annual awards (by amount)',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: annualAwardsAmountCurrent
            },
            {
              label: 'Past annual awards (by amount)',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: annualAwardsAmountPast
            }
          ],
          {
            callback: function (value) {
              if (value >= 1_000_000) {
                return `$${(value / 1_000_000).toFixed(1)}M`; // Show in millions
              } else if (value >= 1000) {
                return `$${(value / 1000).toFixed(1)}k`; // Fallback to thousands
              }
              return `$${value}`;
            }
          }
        );

        // Award Quotes Chart
        const awardedQuotesCurrent = data.award_quotes_by_user_current_month.map(obj => obj.total_quotes);
        const awardedQuotesPast = data.award_quotes_by_user_past_month.map(obj => obj.total_quotes);

        createBarChart("#ganadas-chart", usernames, [
          {
            label: 'Current month',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: awardedQuotesCurrent
          },
          {
            label: 'Last month',
            backgroundColor: '#39485A',
            borderColor: '#39485A',
            data: awardedQuotesPast
          }
        ]);

        // Annual Awards Chart
        const annualAwardsCurrent = data.annual_awards_by_month.map(obj => obj.total_quotes);
        const annualAwardsPast = data.past_annual_awards_by_month.map(obj => obj.total_quotes);

        createBarChart(
          "#ganados_anuales_chart",
          ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          [
            {
              label: 'Annual awards',
              backgroundColor: '#13A8F0',
              borderColor: '#13A8F0',
              data: annualAwardsCurrent
            },
            {
              label: 'Past annual awards',
              backgroundColor: '#39485A',
              borderColor: '#39485A',
              data: annualAwardsPast
            }
          ]
        );
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data:", { xhr, status, error });
      }
    });
  }
});