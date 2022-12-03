<?php
require_once 'Class/Database.php';
require_once 'Class/SQLData.php';
include 'header.php';

if (isset($_SESSION["id"]) && isset($_SESSION["role"])) {
    $id = $_SESSION["id"];
    $role = $_SESSION["role"];
    echo $role;
    echo "<br>";
    echo $id;
} else {
    header("Location: login.php");
}

$db = Database::getPDO(); //database pour toute la page
$sirenCo = SQLData::getSirenOfCommerceant($db, $id);

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
if ($role === "Commerçant"){
  $stats2= SQLData::getMotifImpaye($cnx, intval($sirenCo));
}else{
  $stats2= SQLData::getMotifImpaye($cnx, null);
}

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
      <a href="index.php">Revenir aux tableaux</a>
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
          Entrez la date de Fin :
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
    <?php if ($role="Commerçant"){
      echo "<div id=\"impaye_par_categorie\" style=\"width: 95%; max-width: 800px;min-height:600px; margin-top: 50px;\"></div>";
    }

    ?>
      
      
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