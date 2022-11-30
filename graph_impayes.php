<?php
require_once 'Class/Database.php';
require_once 'Class/SQLData.php';
include 'header.php';

if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
} else {
    header("Location: login.php");
}

$db = Database::getPDO(); //database pour toute la page
$sirenCo = SQLData::getSirenOfCommerceant($db, $_SESSION["id"]);

if (isset($_SESSION['style_dates']) && isset($_SESSION['style_four']) && isset($_SESSION['style_twelve'])) {
  $style_dates = $_SESSION['style_dates'];
  $style_four = $_SESSION['style_four'];
  $style_twelve = $_SESSION['style_twelve'];
} else {
  $style_dates = "class='selected'";
  $style_four = "";
  $style_twelve = "";
}

if (isset($_POST['fin'])) {
    $fin = $_POST['fin'];
} else {
    $fin = "2022-12-31";
}

if (isset($_POST['debut'])) {
  $debut = $_POST['debut'];
} else {
    if ($style_four == "class='selected'") {
        $lst_fin = explode('-', $fin);
        $int_month = intval($lst_fin[1]) - 4;
        $int_year = intval($lst_fin[0]);
        if ($int_month < 1) {
            $int_month = 12 + $int_month;
            $int_year = intval($lst_fin[0]) - 1;
        }
        $new_arr = array(strval($int_year), strval($int_month), $lst_fin[2]);
        $debut = join('-', $new_arr);

    } else if ($style_twelve == "class='selected'") {
        $lst_fin = explode('-', $fin);
        $int_year = intval($lst_fin[0]) - 1;
        $new_arr = array(strval($int_year), $lst_fin[1], $lst_fin[2]);
        $debut = join('-', $new_arr);
    } else {
        $debut = "2015-08-18";
    }
}

$cnx = Database::getPDO();
$stats2= SQLData::getMotifImpaye($cnx, intval($sirenCo));
$stats3 = SQLData::getHistoriqueImpaye($cnx, intval($sirenCo), $debut, $fin);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style-graphiques.css">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <title>Graphique</title>
</head>

<body>
<script>
    let i = 2;
    let name = "column";

    const changeGraph = function() {
        const graph = localStorage.getItem("graph");
        if (graph == "spline") {
            localStorage.setItem("graph", "column");
        } else {
            localStorage.setItem("graph", "spline");
        }

        window.location.reload();
    };
</script>
  <div class="site-container">
    <div class="retour-tableau">
      <a href="page.php">Revenir aux tableaux</a>
    </div>
    <ul class="buttons">
      <a href="chose_date_selection.php?selected=dates">
        <?php
        echo "<li $style_dates>Par dates</li>";
        ?>
      </a>
      <a href="chose_date_selection.php?selected=four_months">
        <?php
        echo "<li $style_four>4 mois</li>";
        ?>
      </a>
      <a href="chose_date_selection.php?selected=twelve_months">
        <?php
        echo "<li $style_twelve>12 mois</li>";
        ?>
      </a>
    </ul>
    <div class='variables'>
      <div>
        <div class='exclam'></div>
        <p>
          Entrez la date de début :
        </p>
      </div>
      <form action="graph_impayes.php" method="POST">
          <?php
            if ($style_dates == "class='selected'") {
                echo "
                    <div>
                      <label for='debut'>Début</label>
                      <input type='date' name='debut' id='debut'>
                    </div>
                ";
            }
          ?>
        <div>
          <label for="fin">Fin</label>
          <input type="date" name="fin" id="fin">
        </div>
        <input type="submit" name="submit">
      </form>
      <?php
          if (isset($_POST['debut'])) {
            $_SESSION['debut'] = $_POST['debut'];
          }

          if (isset($_POST['fin'])) {
              $_SESSION['fin'] = $_POST['fin'];
          }
      ?>
    </div>
    <div class="title">
      <h2>Graphiques :</h2>
    </div>
      <button id="button" onclick="changeGraph();" style="width: 150px; height: 25px;">Changer de Graph</button>
    <div id="historique_impaye" style="width: 95%; max-width: 1000px;min-height:600px;"></div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const chart = Highcharts.chart('historique_impaye', {
          chart: {
            type: ("graph" in localStorage ? localStorage.getItem("graph") : "column")
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
            type: 'datetime',
            tickInterval: 30 * 24 * 3600 * 1000,
            labels: {
              formatter: function() {
                return Highcharts.dateFormat('%b %Y', this.value);

              },
            },
          },
          yAxis: {
            title: {
              useHTML: true,
              text: ''
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
                    foreach ($stats3 as $stat) {
                      echo "[" . $stat[0] * 1000 . ",$stat[2]],";
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
                    foreach ($stats3 as $stat) {
                      echo "[" . $stat[0] * 1000 . ",$stat[1]],";
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
      <div id="impaye_par_categorie" style="width: 95%; max-width: 800px;min-height:600px; margin-top: 50px;"></div>
      <script>
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
          });
      </script>
  </div>
  </div>
</body>

</html>