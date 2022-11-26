Highcharts.chart('bar-chart', {
    chart: {
        type: "column"
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [
            'Janvier',
            'Fevrier',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Aout',
            'Septembre',
            'Octobre',
            'Novembre',
            'Decembre'
        ],
        crosshair: true,
        lineColor: '#3F5071',
        lineWidth: 2
    },
    yAxis: {
        title: {
            useHTML: true,
            text: 'Montant en €'
        },
        lineColor: '#3F5071',
        lineWidth: 2
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        },
        series: {
            borderRadius: 2,
            shadow: true,
            pointWidth: 10
        }
    },
    series: [{
            name: 'Impayés par mois (€)',
            data: [13.93, 13.63, 13.73, 13.67, 14.37, 14.89, 14.56,
                14.32, 14.13, 13.93, 13.21, 12.16
            ],
            color: {
                linearGradient: {
                    x1: 0.5,
                    x2: 0.5,
                    y1: 0,
                    y2: 1
                },
                stops: [
                    [0, '#3A6DD0'],
                    [1, '#8A73E2']
                ]
            }

        },
        {
            name: 'Payé (€)',
            data: [10.93, 9.63, 3.73, 20.67, 1.37, 4.89, 5.56,
                12.32, 10.13, 14.93, 11.21, 9.16
            ],
            color: {
                linearGradient: {
                    x1: 0.5,
                    x2: 0.5,
                    y1: 0,
                    y2: 1
                },
                stops: [
                    [0, '#F7B42C'],
                    [1, '#FC7F57']
                ]
            }

        }
    ],
});

Highcharts.chart('column-chart', {
    chart: {
        type: "column"
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [
            'Janvier',
            'Fevrier',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Aout',
            'Septembre',
            'Octobre',
            'Novembre',
            'Decembre'
        ],
        crosshair: true,
        lineColor: '#3F5071',
        lineWidth: 2
    },
    yAxis: {
        title: {
            useHTML: true,
            text: 'Montant en €'
        },
        lineColor: '#3F5071',
        lineWidth: 2
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        },
        series: {
            borderRadius: 5,
            shadow: true,
            pointWidth: 30
        }
    },
    series: [{
            name: 'Impayés par mois (€)',
            data: [13.93, 13.63, 13.73, 13.67, 14.37, 14.89, 14.56,
                14.32, 14.13, 13.93, 13.21, 12.16
            ],
            color: {
                linearGradient: {
                    x1: 0.5,
                    x2: 0.5,
                    y1: 0,
                    y2: 1
                },
                stops: [
                    [0, '#3A6DD0'],
                    [1, '#8A73E2']
                ]
            }

        },
        {
            name: 'Payé (€)',
            data: [10.93, 9.63, 3.73, 20.67, 1.37, 4.89, 5.56,
                12.32, 10.13, 14.93, 11.21, 9.16
            ],
            color: {
                linearGradient: {
                    x1: 0.5,
                    x2: 0.5,
                    y1: 0,
                    y2: 1
                },
                stops: [
                    [0, '#F7B42C'],
                    [1, '#FC7F57']
                ]
            }

        }
    ],
});