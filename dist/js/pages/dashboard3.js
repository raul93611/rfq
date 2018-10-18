$(function () {
    'use strict';
    if($('#graficas').length != 0){
      var nombres_usuario = document.getElementById('nombres_usuario').value;
      var cotizaciones_completadas = document.getElementById('cotizaciones_completadas').value;
      var cotizaciones_completadas_pasadas = document.getElementById('cotizaciones_completadas_pasadas').value;
      var cotizaciones_ganadas = document.getElementById('cotizaciones_ganadas').value;
      var cotizaciones_ganadas_pasadas = document.getElementById('cotizaciones_ganadas_pasadas').value;
      var cotizaciones_no_sometidas = document.getElementById('cotizaciones_no_sometidas').value;
      var cotizaciones_no_sometidas_pasadas = document.getElementById('cotizaciones_no_sometidas_pasadas').value;
      var cotizaciones_mes = document.getElementById('cotizaciones_mes').value;
      var monto_cotizaciones_mes = document.getElementById('monto_cotizaciones_mes').value;
      var no_bid = document.getElementById('no_bid').value;
      var manufacturer_in_the_bid = document.getElementById('manufacturer_in_the_bid').value;
      var expired_due_date = document.getElementById('expired_due_date').value;
      var supplier_did_not_provide_a_quote = document.getElementById('supplier_did_not_provide_a_quote').value;
      var others = document.getElementById('others').value;

      var cotizaciones_completadas_anual_usuarios = $('#cotizaciones_completadas_anual_usuarios').val();
      var cotizaciones_ganadas_anual_usuarios = $('#cotizaciones_ganadas_anual_usuarios').val();
      var cotizaciones_not_submitted_anual_usuarios = $('#cotizaciones_not_submitted_anual_usuarios').val();

      nombres_usuario = jQuery.parseJSON(nombres_usuario);
      cotizaciones_completadas = jQuery.parseJSON(cotizaciones_completadas);
      cotizaciones_completadas_pasadas = jQuery.parseJSON(cotizaciones_completadas_pasadas);
      cotizaciones_ganadas = jQuery.parseJSON(cotizaciones_ganadas);
      cotizaciones_ganadas_pasadas = jQuery.parseJSON(cotizaciones_ganadas_pasadas);
      cotizaciones_no_sometidas = jQuery.parseJSON(cotizaciones_no_sometidas);
      cotizaciones_no_sometidas_pasadas = jQuery.parseJSON(cotizaciones_no_sometidas_pasadas);
      cotizaciones_mes = jQuery.parseJSON(cotizaciones_mes);
      monto_cotizaciones_mes = jQuery.parseJSON(monto_cotizaciones_mes);
      no_bid = jQuery.parseJSON(no_bid);
      manufacturer_in_the_bid = jQuery.parseJSON(manufacturer_in_the_bid);
      expired_due_date = jQuery.parseJSON(expired_due_date);
      supplier_did_not_provide_a_quote = jQuery.parseJSON(supplier_did_not_provide_a_quote);
      others = jQuery.parseJSON(others);

      cotizaciones_completadas_anual_usuarios = jQuery.parseJSON(cotizaciones_completadas_anual_usuarios);
      cotizaciones_ganadas_anual_usuarios = jQuery.parseJSON(cotizaciones_ganadas_anual_usuarios);
      cotizaciones_not_submitted_anual_usuarios = jQuery.parseJSON(cotizaciones_not_submitted_anual_usuarios);

      var ticksStyle = {
          fontColor: '#39485A',
          fontStyle: 'bold'
      };

      var mode = 'index';
      var intersect = true;

      if($('#completados-chart').length != 0){
        var $completadosChart = $('#completados-chart');
        var completadosChart = new Chart($completadosChart, {
            type: 'bar',
            data: {
                labels: nombres_usuario,
                datasets: [
                    {
                        backgroundColor: '#13A8F0',
                        borderColor: '#13A8F0',
                        data: cotizaciones_completadas
                    },
                    {
                        backgroundColor: '#39485A',
                        borderColor: '#39485A',
                        data: cotizaciones_completadas_pasadas
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                color: 'rgba(122, 125, 130, .2)'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000) {
                                        value /= 1000;
                                        value += 'k';
                                    }
                                    return value;
                                }
                            }, ticksStyle)
                        }],
                    xAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            ticks: ticksStyle
                        }]
                },
                animation:{
                  easing: 'easeOutCirc',
                  duration: 1500
                }
            }
        });
      }

      if($('#ganadas-chart').length != 0){
        var $ganadasChart = $('#ganadas-chart');
        var ganadasChart = new Chart($ganadasChart, {
            type: 'bar',
            data: {
                labels: nombres_usuario,
                datasets: [
                    {
                        backgroundColor: '#13A8F0',
                        borderColor: '#13A8F0',
                        data: cotizaciones_ganadas
                    },
                    {
                        backgroundColor: '#39485A',
                        borderColor: '#39485A',
                        data: cotizaciones_ganadas_pasadas
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                            //display: true,
                            gridLines: {
                                display: true,
                                color: 'rgba(122, 125, 130, .2)'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000) {
                                        value /= 1000;
                                        value += 'k';
                                    }
                                    return value;
                                }
                            }, ticksStyle)
                        }],
                    xAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            ticks: ticksStyle
                        }]
                },
                animation:{
                  easing: 'easeOutCirc',
                  duration: 1500
                }
            }
        });
      }

      if($('#sometidas-chart').length != 0){
        var $sometidasChart = $('#sometidas-chart');
        var sometidasChart = new Chart($sometidasChart, {
            type: 'bar',
            data: {
                labels: nombres_usuario,
                datasets: [
                    {
                        backgroundColor: '#13A8F0',
                        borderColor: '#13A8F0',
                        data: cotizaciones_no_sometidas
                    },
                    {
                        backgroundColor: '#39485A',
                        borderColor: '#39485A',
                        data: cotizaciones_no_sometidas_pasadas
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                            // display: false,
                            gridLines: {
                                display: true,
                                color: 'rgba(122, 125, 130, .2)'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    if (value >= 1000) {
                                        value /= 1000;
                                        value += 'k';
                                    }
                                    return value;
                                }
                            }, ticksStyle)
                        }],
                    xAxes: [{
                            display: true,
                            gridLines: {
                                display: true
                            },
                            ticks: ticksStyle
                        }]
                },
                animation:{
                  easing: 'easeOutCirc',
                  duration: 1500
                }
            }
        });
      }

      var $ganadosAnualesChart = $('#ganados_anuales_chart');
      var ganadosAnualesChart = new Chart($ganadosAnualesChart, {
          type: 'bar',
          data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                  {
                      backgroundColor: '#13A8F0',
                      borderColor: '#13A8F0',
                      data: cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
                  }/*,
                   {
                   backgroundColor: '#39485A',
                   borderColor    : '#39485A',
                   data           : cotizaciones_completadas_pasadas
                   }*/
              ]
          },
          options: {
              maintainAspectRatio: false,
              tooltips: {
                  mode: mode,
                  intersect: intersect
              },
              hover: {
                  mode: mode,
                  intersect: intersect
              },
              legend: {
                  display: false
              },
              scales: {
                  yAxes: [{
                          // display: false,
                          gridLines: {
                              display: true,
                              color: 'rgba(122, 125, 130, .2)'
                          },
                          ticks: $.extend({
                              beginAtZero: true,
                              // Include a dollar sign in the ticks
                              callback: function (value, index, values) {
                                  if (value >= 1000) {
                                      value /= 1000;
                                      value += 'k';
                                  }
                                  return value;
                              }
                          }, ticksStyle)
                      }],
                  xAxes: [{
                          display: true,
                          gridLines: {
                              display: true
                          },
                          ticks: ticksStyle
                      }]
              },
              animation:{
                easing: 'easeOutCirc',
                duration: 1500
              }
          }
      });

      var $montoganadosAnualesChart = $('#monto_ganados_anual_chart');
      var montoganadosAnualesChart = new Chart($montoganadosAnualesChart, {
          type: 'bar',
          data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                  {
                      backgroundColor: '#13A8F0',
                      borderColor: '#13A8F0',
                      data: monto_cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
                  }/*,
                   {
                   backgroundColor: '#39485A',
                   borderColor    : '#39485A',
                   data           : cotizaciones_completadas_pasadas
                   }*/
              ]
          },
          options: {
              maintainAspectRatio: false,
              tooltips: {
                  mode: mode,
                  intersect: intersect,
                  callbacks: {
                    label: function(tooltipItem, data) {
                        return tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    },
                  }
              },
              hover: {
                  mode: mode,
                  intersect: intersect
              },
              legend: {
                  display: false
              },
              scales: {
                  yAxes: [{
                          display: true,
                          gridLines: {
                              display: true,
                              color: 'rgba(122, 125, 130, .2)'
                          },
                          ticks: $.extend({
                              beginAtZero: true,
                              // Include a dollar sign in the ticks
                              callback: function (value, index, values) {
                                  if (value >= 1000) {
                                      value /= 1000;
                                      value += 'k';
                                  }
                                  return '$' + value;
                              }
                          }, ticksStyle)
                      }],
                  xAxes: [{
                          display: true,
                          gridLines: {
                              display: true
                          },
                          ticks: ticksStyle
                      }]
              },
              animation:{
                easing: 'easeOutCirc',
                duration: 1500
              }
          }
      });

      new Chart(document.getElementById("pie-chart"), {
          type: 'pie',
          data: {
              labels: ["No Bid", "Manufacturer in the Bid", "Expired due date", "Supplier did not provide a quote", "Others"],
              datasets: [{
                      label: "Population (millions)",
                      backgroundColor: ["#f75a6a", "#f8d200", "#0cd63f", "#13A8F0", "#9276e2"],
                      data: [no_bid, manufacturer_in_the_bid, expired_due_date, supplier_did_not_provide_a_quote, others]
                  }]
          },
          options: {
              maintainAspectRatio: false,
              title: {
                  display: false,
                  text: 'Predicted world population (millions) in 2050'
              },
              cutoutPercentage: 3,
              animation:{
                  easing: 'easeInOutCubic',
                  duration: 1500
              }

          }
      });

      var user_by_month_completed_data = [];
      for(var i = 0; i < nombres_usuario.length; i++){
        var color = Math.floor(Math.random()*16777215).toString(16);
        user_by_month_completed_data.push('{'+
          '"label":"' + nombres_usuario[i] + '" ,' +
          '"backgroundColor": "#' + color + '",' +
          '"borderColor": "#' + color + '",' +
          '"fill": "false",' +
          '"data": [' + cotizaciones_completadas_anual_usuarios[i] + ']' +
        '}');
      }
      user_by_month_completed_data = user_by_month_completed_data.join(',');
      user_by_month_completed_data = '['+user_by_month_completed_data+']';
      user_by_month_completed_data = jQuery.parseJSON(user_by_month_completed_data);
      new Chart(document.getElementById("user_by_month_completed"), {
          type: 'line',
          data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: user_by_month_completed_data
          },
          options: {
              elements:{
                line: {
                  tension: 0.2,
                }
              },
              tooltips:{
        				mode: 'index',
        				intersect: false,
        			},
              maintainAspectRatio: false,
              title: {
                  display: false,
                  text: 'Predicted world population (millions) in 2050'
              },
              cutoutPercentage: 3,
              animation:{
                  easing: 'easeInOutCubic',
                  duration: 1500
              }

          }
      });

      var user_by_month_award_data = [];
      for(var i = 0; i < nombres_usuario.length; i++){
        var color = Math.floor(Math.random()*16777215).toString(16);
        user_by_month_award_data.push('{'+
          '"label":"' + nombres_usuario[i] + '" ,' +
          '"backgroundColor": "#' + color + '",' +
          '"borderColor": "#' + color + '",' +
          '"fill": "false",' +
          '"data": [' + cotizaciones_ganadas_anual_usuarios[i] + ']' +
        '}');
      }
      user_by_month_award_data = user_by_month_award_data.join(',');
      user_by_month_award_data = '['+user_by_month_award_data+']';
      user_by_month_award_data = jQuery.parseJSON(user_by_month_award_data);
      new Chart(document.getElementById("user_by_month_award"), {
          type: 'line',
          data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: user_by_month_award_data
          },
          options: {
              elements:{
                line: {
                  tension: 0.2,
                }
              },
              tooltips:{
        				mode: 'index',
        				intersect: false,
        			},
              maintainAspectRatio: false,
              title: {
                  display: false,
                  text: 'Predicted world population (millions) in 2050'
              },
              cutoutPercentage: 3,
              animation:{
                  easing: 'easeInOutCubic',
                  duration: 1500
              }

          }
      });

      var user_by_month_not_submitted_data = [];
      for(var i = 0; i < nombres_usuario.length; i++){
        var color = Math.floor(Math.random()*16777215).toString(16);
        user_by_month_not_submitted_data.push('{'+
          '"label":"' + nombres_usuario[i] + '" ,' +
          '"backgroundColor": "#' + color + '",' +
          '"borderColor": "#' + color + '",' +
          '"fill": "false",' +
          '"data": [' + cotizaciones_not_submitted_anual_usuarios[i] + ']' +
        '}');
      }
      user_by_month_not_submitted_data = user_by_month_not_submitted_data.join(',');
      user_by_month_not_submitted_data = '['+user_by_month_not_submitted_data+']';
      user_by_month_not_submitted_data = jQuery.parseJSON(user_by_month_not_submitted_data);
      new Chart(document.getElementById("user_by_month_not_submitted"), {
          type: 'line',
          data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: user_by_month_not_submitted_data
          },
          options: {
              elements:{
                line: {
                  tension: 0.2,
                }
              },
              tooltips:{
        				mode: 'index',
        				intersect: false,
        			},
              maintainAspectRatio: false,
              title: {
                  display: false,
                  text: 'Predicted world population (millions) in 2050'
              },
              cutoutPercentage: 3,
              animation:{
                  easing: 'easeInOutCubic',
                  duration: 1500
              }

          }
      });
    }
});
