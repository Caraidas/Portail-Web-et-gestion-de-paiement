
// catgories : liste des titre de chaque colonne
// title : Le titre du graphique
// data : liste des valeurs associées à chaque valeurs de categorie

Highcharts.chart("container",
        {
        chart:
            {
            type: column,
            },
        title:
            {
            text: "title"
            },
        subtitle:
            {
            text: 'Source : ' +
                '<a href="google.com" ' +
                'target="_blank">Lucas le pd</a>'
            },
        xAxis:
            {
                categories:
                    [
                    '2010',
                    '2011',
                    '2012',
                    '2013',
                    '2014',
                    '2015',
                    '2016',
                    '2017',
                    '2018',
                    '2019',
                    '2010',
                    '2021'
                    ],
                crosshair: true
        },
        yAxis:
            {
            series:
                [
                    {
                    name: 'Montant des impayés',
                    data: [13.93, 13.63, 13.73, 13.67, 14.37, 14.89, 14.56,
                        14.32, 14.13, 13.93, 13.21, 12.16]
                    }
                ]
            }
        })