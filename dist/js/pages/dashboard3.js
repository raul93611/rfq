$(function () {
  'use strict'
  
  var nombres_usuario = document.getElementById('nombres_usuario').value;
  var cotizaciones_completadas = document.getElementById('cotizaciones_completadas').value;
  var cotizaciones_completadas_pasadas = document.getElementById('cotizaciones_completadas_pasadas').value;
  var cotizaciones_ganadas = document.getElementById('cotizaciones_ganadas').value;
  var cotizaciones_ganadas_pasadas = document.getElementById('cotizaciones_ganadas_pasadas').value;
  var cotizaciones_no_sometidas = document.getElementById('cotizaciones_no_sometidas').value;
  var cotizaciones_no_sometidas_pasadas = document.getElementById('cotizaciones_no_sometidas_pasadas').value;
  var cotizaciones_mes = document.getElementById('cotizaciones_mes').value;
  var monto_cotizaciones_mes = document.getElementById('monto_cotizaciones_mes').value;
  
  nombres_usuario = jQuery.parseJSON(nombres_usuario);
  cotizaciones_completadas = jQuery.parseJSON(cotizaciones_completadas);
  cotizaciones_completadas_pasadas = jQuery.parseJSON(cotizaciones_completadas_pasadas);
  cotizaciones_ganadas = jQuery.parseJSON(cotizaciones_ganadas);
  cotizaciones_ganadas_pasadas = jQuery.parseJSON(cotizaciones_ganadas_pasadas);
  cotizaciones_no_sometidas = jQuery.parseJSON(cotizaciones_no_sometidas);
  cotizaciones_no_sometidas_pasadas = jQuery.parseJSON(cotizaciones_no_sometidas_pasadas);
  cotizaciones_mes = jQuery.parseJSON(cotizaciones_mes);
  monto_cotizaciones_mes = jQuery.parseJSON(monto_cotizaciones_mes);
  
  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode      = 'index'
  var intersect = true

  var $completadosChart = $('#completados-chart')
  var completadosChart  = new Chart($completadosChart, {
    type   : 'bar',
    data   : {
      labels  : nombres_usuario,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : cotizaciones_completadas
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : cotizaciones_completadas_pasadas
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
  
  var $ganadasChart = $('#ganadas-chart')
  var ganadasChart  = new Chart($ganadasChart, {
    type   : 'bar',
    data   : {
      labels  : nombres_usuario,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : cotizaciones_ganadas
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : cotizaciones_ganadas_pasadas
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
  
  var $sometidasChart = $('#sometidas-chart')
  var sometidasChart  = new Chart($sometidasChart, {
    type   : 'bar',
    data   : {
      labels  : nombres_usuario,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : cotizaciones_no_sometidas
        },
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : cotizaciones_no_sometidas_pasadas
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
  var $ganadosAnualesChart = $('#ganados_anuales_chart')
  var ganadosAnualesChart  = new Chart($ganadosAnualesChart, {
    type   : 'bar',
    data   : {
      labels  : ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
        }/*,
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : cotizaciones_completadas_pasadas
        }*/
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })

var $montoganadosAnualesChart = $('#monto_ganados_anual_chart')
  var montoganadosAnualesChart  = new Chart($montoganadosAnualesChart, {
    type   : 'bar',
    data   : {
      labels  : ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor    : '#007bff',
          data           : monto_cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
        }/*,
        {
          backgroundColor: '#ced4da',
          borderColor    : '#ced4da',
          data           : cotizaciones_completadas_pasadas
        }*/
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value, index, values) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }
              return '$' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })  

  var $visitorsChart = $('#visitors-chart')
  var visitorsChart  = new Chart($visitorsChart, {
    data   : {
      labels  : ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
      datasets: [{
        type                : 'line',
        data                : [100, 120, 170, 167, 180, 177, 160],
        backgroundColor     : 'transparent',
        borderColor         : '#007bff',
        pointBorderColor    : '#007bff',
        pointBackgroundColor: '#007bff',
        fill                : false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
        {
          type                : 'line',
          data                : [60, 80, 70, 67, 80, 77, 100],
          backgroundColor     : 'tansparent',
          borderColor         : '#ced4da',
          pointBorderColor    : '#ced4da',
          pointBackgroundColor: '#ced4da',
          fill                : false
          // pointHoverBackgroundColor: '#ced4da',
          // pointHoverBorderColor    : '#ced4da'
        }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips           : {
        mode     : mode,
        intersect: intersect
      },
      hover              : {
        mode     : mode,
        intersect: intersect
      },
      legend             : {
        display: false
      },
      scales             : {
        yAxes: [{
          // display: false,
          gridLines: {
            display      : true,
            lineWidth    : '4px',
            color        : 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks    : $.extend({
            beginAtZero : true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display  : true,
          gridLines: {
            display: false
          },
          ticks    : ticksStyle
        }]
      }
    }
  })
})
