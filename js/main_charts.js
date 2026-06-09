/* =========================================================================
   Dashboard · Charts tab (/rfq/perfil/charts)
   The two ANNUAL AWARDS cards compare THREE years (current + 2 prior) as
   grouped monthly columns with a three-row legend. Recency reads as
   saturation: newest year = brand blue, older years progressively muted.
   The Completed / Awards (by-user) cards compare current vs last month and hide
   users with no activity in either month, filtered independently per card.
   ========================================================================= */

const MONTHS_SHORT = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
const MONTHS_LONG = ['January', 'February', 'March', 'April', 'May', 'June',
  'July', 'August', 'September', 'October', 'November', 'December'];

/* recency ramp — index 0 = oldest … last = current year */
const AWARD_RAMP = ['#aebccb', '#5e83a4', '#13A8F0'];
const rampColor = (i) => AWARD_RAMP[i] || AWARD_RAMP[AWARD_RAMP.length - 1];

/* ---- formatters (match the Annual Awards design) ----------------------- */
function fmtCount(n) { return Math.round(n).toLocaleString('en-US'); }
function fmtMoneyFull(n) {
  return '$' + Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function fmtAxisMoney(v) {
  if (v >= 1e6) return '$' + (v / 1e6).toFixed(1) + 'M';
  if (v >= 1e3) return '$' + Math.round(v / 1e3) + 'k';
  return '$' + Math.round(v);
}

/* Zip a user's last-month + current-month series into one row per user and drop
   anyone with no activity in either month. SQL SUM(...) values arrive as strings
   over JSON, so coerce with Number() before the zero check. Each chart filters
   independently — a user can show in Awards while hidden in Completed. */
function activeUserSeries(pastArr, currentArr) {
  return pastArr
    .map((row, i) => ({
      name: row.user_name,
      current: currentArr[i].total_quotes,
      past: row.total_quotes
    }))
    .filter(u => Number(u.current) !== 0 || Number(u.past) !== 0);
}

function initCharts() {
  if ($('#graficas').length > 0) {
    $.ajax({
      url: "/rfq/main_charts",
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function (data) {
        // Helper for the unchanged per-user bar charts (current vs last month)
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

        /* ---- Annual Awards: three-year grouped columns ------------------- */
        function createAnnualChart(elementId, years, isMoney) {
          const datasets = years.map((y, i) => ({
            label: String(y.year),
            backgroundColor: rampColor(i),
            borderColor: rampColor(i),
            borderRadius: 2,
            borderSkipped: false,
            maxBarThickness: 22,
            data: y.by_month.map(m => isMoney ? m.total_price : m.total_quotes)
          }));

          new Chart($(elementId), {
            type: 'bar',
            data: { labels: MONTHS_SHORT, datasets },
            options: {
              maintainAspectRatio: false,
              // One tooltip per hovered bar (a single year), not the shared index tooltip
              interaction: { mode: 'nearest', intersect: true },
              plugins: {
                legend: { display: false },
                tooltip: {
                  mode: 'nearest',
                  intersect: true,
                  callbacks: {
                    title: (items) => items.length
                      ? `${MONTHS_LONG[items[0].dataIndex]} ${items[0].dataset.label}` : '',
                    label: (item) => isMoney
                      ? fmtMoneyFull(item.raw)
                      : `${fmtCount(item.raw)} award${item.raw === 1 ? '' : 's'}`
                  }
                }
              },
              scales: {
                x: { grid: { display: false } },
                y: {
                  beginAtZero: true,
                  ticks: { callback: (v) => isMoney ? fmtAxisMoney(v) : fmtCount(v) }
                }
              }
            }
          });
        }

        /* ---- three-row legend (newest year first), totals from the data -- */
        function renderAnnualLegend(containerId, years, isMoney) {
          const $c = $(containerId).empty();
          years.slice().reverse().forEach((y) => {
            const i = years.findIndex(x => x.year === y.year);
            const total = isMoney ? fmtMoneyFull(y.total_price) : fmtCount(y.total_quotes);
            $c.append(
              '<div class="chart-legend-item">' +
              '<span class="chart-legend-dot" style="background:' + rampColor(i) + '"></span>' +
              '<span class="chart-legend-text">' + y.year + '</span>' +
              '<span class="chart-legend-value">' + total + '</span>' +
              '</div>'
            );
          });
        }

        const annualYears = data.annual_awards_years || [];

        // Completed Quotes Chart — hide users with no activity in either month
        const completedUsers = activeUserSeries(
          data.completed_quotes_by_user_past_month,
          data.completed_quotes_by_user_current_month
        );

        createBarChart("#completados-chart", completedUsers.map(u => u.name), [
          {
            label: 'Current month',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: completedUsers.map(u => u.current)
          },
          {
            label: 'Last month',
            backgroundColor: '#39485A',
            borderColor: '#39485A',
            data: completedUsers.map(u => u.past)
          }
        ]);

        // Annual Awards by Amount (3-year)
        createAnnualChart("#monto_ganados_anual_chart", annualYears, true);
        renderAnnualLegend("#annual_awards_amounts_legend", annualYears, true);

        // Award Quotes Chart — hide users with no activity in either month
        const awardedUsers = activeUserSeries(
          data.award_quotes_by_user_past_month,
          data.award_quotes_by_user_current_month
        );

        createBarChart("#ganadas-chart", awardedUsers.map(u => u.name), [
          {
            label: 'Current month',
            backgroundColor: '#13A8F0',
            borderColor: '#13A8F0',
            data: awardedUsers.map(u => u.current)
          },
          {
            label: 'Last month',
            backgroundColor: '#39485A',
            borderColor: '#39485A',
            data: awardedUsers.map(u => u.past)
          }
        ]);

        // Annual Awards by Count (3-year)
        createAnnualChart("#ganados_anuales_chart", annualYears, false);
        renderAnnualLegend("#annual_awards_legend", annualYears, false);
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data:", { xhr, status, error });
      }
    });
  }
}

/* Browser entry point — guarded so this file can be required by Node unit tests
   (tests/js/charts_filter.test.js) without a jQuery/DOM environment. */
if (typeof $ !== 'undefined') {
  $(document).ready(initCharts);
}

if (typeof module !== 'undefined' && module.exports) {
  module.exports = { activeUserSeries };
}
