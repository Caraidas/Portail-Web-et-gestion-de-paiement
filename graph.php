<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <?php
        require_once 'Class/Database.php';
        require_once 'Class/SQLData.php';
        $cnx = Database::getPDO();
        $stats = SQLData::getTresorerieHistorique($cnx,615888425);
    ?>
<div id="container" style="width:100%; height:400px;"></div>
yey
<?php
print_r($stats);
?>
</body>
</html>


<script>
document.addEventListener('DOMContentLoaded',function(){
    const chart=Highcharts.chart('container',{
        chart:{
            type: 'spline'
        },
        title: {
            text: 'Thèses en ligne'
        },
        plotOptions: {
            series: {
                color: 'purple'
            }
        },
        xAxis: {
                type: 'datetime',
                dateTimeLabelFormats: {
                    day: '%y-%m-%d'
                }
            },
        series :[{
            data:<?php
            echo "[";
            foreach($stats as $stat) {
                echo "[\"$stat[0]\",$stat[1]],";
            }
            echo "]";
            //[[0,2],[1,8],[2,15]]
            ?>,
            name: 'Average',
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
</script>

          
