$(function () {
    'use strict';

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

    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    };

    var mode = 'index';
    var intersect = true;

    var $completadosChart = $('#completados-chart');
    var completadosChart = new Chart($completadosChart, {
        type: 'bar',
        data: {
            labels: nombres_usuario,
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: cotizaciones_completadas
                },
                {
                    backgroundColor: '#ced4da',
                    borderColor: '#ced4da',
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

    var $ganadasChart = $('#ganadas-chart');
    var ganadasChart = new Chart($ganadasChart, {
        type: 'bar',
        data: {
            labels: nombres_usuario,
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: cotizaciones_ganadas
                },
                {
                    backgroundColor: '#ced4da',
                    borderColor: '#ced4da',
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
                            stepSize: 1,
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

    var $sometidasChart = $('#sometidas-chart');
    var sometidasChart = new Chart($sometidasChart, {
        type: 'bar',
        data: {
            labels: nombres_usuario,
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: cotizaciones_no_sometidas
                },
                {
                    backgroundColor: '#ced4da',
                    borderColor: '#ced4da',
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
    var $ganadosAnualesChart = $('#ganados_anuales_chart');
    var ganadosAnualesChart = new Chart($ganadosAnualesChart, {
        type: 'bar',
        data: {
            labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
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
                            stepSize: 1,
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
                    backgroundColor: '#007bff',
                    borderColor: '#007bff',
                    data: monto_cotizaciones_mes/*[1,2,3,4,5,6,7,8,9,1,2,0]*/
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
                    backgroundColor: ["#ff2e00", "#edcf0e", "#0cd63f", "#0c8bd6", "#dc0fe0"],
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

    var $visitorsChart = $('#visitors-chart');
    var visitorsChart = new Chart($visitorsChart, {
        data: {
            labels: ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
            datasets: [{
                    type: 'line',
                    data: [100, 120, 170, 167, 180, 177, 160],
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    pointBorderColor: '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill: false
                            // pointHoverBackgroundColor: '#007bff',
                            // pointHoverBorderColor    : '#007bff'
                },
                {
                    type: 'line',
                    data: [60, 80, 70, 67, 80, 77, 100],
                    backgroundColor: 'tansparent',
                    borderColor: '#ced4da',
                    pointBorderColor: '#ced4da',
                    pointBackgroundColor: '#ced4da',
                    fill: false
                            // pointHoverBackgroundColor: '#ced4da',
                            // pointHoverBorderColor    : '#ced4da'
                }]
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
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            suggestedMax: 200
                        }, ticksStyle)
                    }],
                xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        },
                        ticks: ticksStyle
                    }]
            }
        }
    });
});
