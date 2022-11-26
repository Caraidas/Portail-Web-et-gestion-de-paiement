<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
</head>
<body>
    <?php
        require_once 'Class/Database.php';
        require_once 'Class/SQLData.php';
        $cnx = Database::getPDO();
        $stats = SQLData::getTresorerieHistorique($cnx,615888425);
        $stats2= SQLData::getMotifImpaye($cnx,615888425);
        $stats3=SQLData::getHistoriqueImpaye($cnx,615888425,"2015-08-18","2022-12-31");
    ?>
<div id="historique_tresorerie" style="width:100%; height:400px;"></div>
<?php
print_r($stats);
?>
<div id="impaye_par_categorie" style="width:100%; height:400px;"></div>
<?php
print_r($stats2);
?>

<div id="historique_impaye" style="width:100%; height:400px;"></div>
<?php
print_r($stats3);
?>
yey

</body>
</html>


<script>
/*
Highcharts.theme = {
    colors: ['#3a6dd0','#506fd5','#536fd6','#566fd6','#6270d9','#6570da','#7471dd','#7e72df','#8473e1','#8a73e2']
};
Highcharts.setOptions(Highcharts.theme);
*/ // Tentative de couleur dans notre theme pour les graphes
Highcharts.setOptions({
    lang: {
            loading: 'Chargement...',
            months: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
            weekdays: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
            shortMonths: ['jan', 'fév', 'mar', 'avr', 'mai', 'juin', 'juil', 'aoû', 'sep', 'oct', 'nov', 'déc'],
            exportButtonTitle: "Exporter",
            printButtonTitle: "Imprimer",
            rangeSelectorFrom: "Du",
            rangeSelectorTo: "au",
            rangeSelectorZoom: "Période",
            downloadPNG: 'Télécharger en PNG',
            downloadJPEG: 'Télécharger en JPEG',
            downloadPDF: 'Télécharger en PDF',
            downloadSVG: 'Télécharger en SVG',
            resetZoom: "Réinitialiser le zoom",
            resetZoomTitle: "Réinitialiser le zoom",
            thousandsSep: " ",
            decimalPoint: ',' ,
            printChart:'Imprimer le graphique',
            viewFullscreen: 'Voir en plein écran'
        }
});

document.addEventListener('DOMContentLoaded',function(){
    const chart=Highcharts.chart('historique_tresorerie',{
        chart:{
            type: 'spline'
        },
        title: {
            text: 'Historique de trésorerie'
        },
        plotOptions: {
            series: {
                color: 'purple'
            }
        },
        xAxis: {
            title:{
                text : 'Date'
            },
            type:'datetime',
            tickInterval:30 * 24 * 3600 * 1000
            },
            labels: 
                {
                    formatter: function ( ){
                            return Highcharts.dateFormat('%b %Y', this.value);

                    },
                },
        yAxis:{
            title:{
                text : 'Trésorerie totale'
            }
        },
        series :[{
            data:<?php
            echo "[";
            foreach($stats as $stat) {
                echo "[".$stat[0]*1000 .",$stat[1]],";
            }
            echo "]";
            ?>,
            name: 'Historique',
            marker: {
                lineWidth: 3,
                lineColor: 'purple',
                fillColor: 'white'
            }
        }
        ]
    })
}
);  

document.addEventListener('DOMContentLoaded',function(){
    const chart=Highcharts.chart('impaye_par_categorie',{
        chart:{
            type: 'pie'
        },
        title: {
            text: 'Nombre d\'impayés triés par motif'
        },
        plotOptions: {
            series: {
                color: 'purple'
            }
        },
        series :[{
            data:<?php
            echo "[";
            foreach($stats2 as $stat) {
                echo "[\"$stat[0]\",$stat[1]],";
            }
            echo "]";
            ?>,
            name: 'Historique',
            marker: {
                lineWidth: 3,
                lineColor: 'purple',
                fillColor: 'white'
            }
        }
        ]
    })
}
);  
document.addEventListener('DOMContentLoaded',function(){
    const chart=Highcharts.chart('historique_impaye', {
    chart: {
        type: "column"
    },
    title: {
        text: ''
    },
    xAxis: {
        startOnTick: true,
        endOnTick: true,
        crosshair: true,
        lineColor: '#3F5071',
        lineWidth: 2,
        type:'datetime',
        tickInterval:30 * 24 * 3600 * 1000,
        labels: 
        {
            formatter: function ( ){
                    return Highcharts.dateFormat('%b %Y', this.value);

            },
        },
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
            borderWidth: 0,
        },
        series: {
            borderRadius: 5,
            shadow: true,
            pointWidth: 30,
            centerInCategory: true,
            groupPadding: 0.35
        }
    },
    series: [{
            name: 'Impayés par mois (€)',
            data: <?php
                    echo "[";
                    foreach($stats3 as $stat) {
                        echo "[".$stat[0]*1000 .",$stat[2]],";
                    }
                    echo "]";
            ?>,
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
            data: <?php
                    echo "[";
                    foreach($stats3 as $stat) {
                        echo "[".$stat[0]*1000 .",$stat[1]],";
                    }
                    echo "]";
            ?>,
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
})
});
</script>

          
