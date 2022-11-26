<?php
    session_start();
    require_once 'Class/Database.php';
    require_once 'Class/SQLData.php';
    require_once  'Class/GenerateHTML.php';
    $db = Database::getPDO(); //database pour toute la page
?>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
.center {
    margin: auto;
    width: 60%;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

.hideform {
    display: none;
}
</style>
<body> 
    
    <div class="mytabs">
        <input type="radio" name="mytabs" id="tab-tresorerie" checked="checked">
        <label class="label-first label-style" for="tab-tresorerie">Trésorerie</label>
        <div class="tab">
        <form action="page.php" method="get">
            <label for="date">Annonces du:</label>
            <input type="date" id="date" name="date">
            <input type="submit">
        </form>
        <form class="show-radio" action="page.php" method="post">
            <label for="tab-field">Trier le tableau par: </label>
            <select name="tab-field" id="tab-field">
                <option value="">--Choisissez une option--</option>
                <option value="Siren">Siren</option>
                <option value="MontantTotal">Montant</option>
            </select>
            <input type="radio" id="croissant" name="order" value="ASC">
            <label for="croissant">croissant</label>
            <input type="radio" id="decroissant" name="order" value="DESC">
            <label for="decroissant">décroissant</label>
            <input type="submit">
        </form>
        <table class="table-fill">
                <thead>
                    <tr>
                        <th>N°SIREN</th>
                        <th>Raison sociale</th>
                        <th>Nombre de transactions</th>
                        <th>Devise</th>
                        <th>Montant total</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <?php
                   //L'ordre du tableau 
                   if(isset($_POST['order']) && isset($_POST['tab-field'])){
                        $order = $_POST['order'];
                        $field = $_POST['tab-field'];
                   }else {
                       $order = 'Siren';
                       $field = 'ASC';
                   }
                   if(isset($_GET['date']) && $_GET['date']!=''){
                       $d = $_GET['date'];//aaaa-mm-jj;
                   }else{
                       $d = null;
                   }
                   echo GenerateHTML::generateTresorerieTab($db,$order,$field,$d)

                ?>  
                </tbody>
            </table>
        </div>

        <input type="radio" name="mytabs" id="tab-remise">
        <label class="label-style" for="tab-remise">Remise</label>
        <div class="tab">
        <table class="table-fill">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>N°SIREN</th>
                        <th>Raison sociale</th>
                        <th>N° Remise</th>
                        <th>Date traitement</th>
                        <th>Nbr Transactions</th>
                        <th>Devise</th>
                        <th>Montant total</th>
                        <th>Sens + ou -</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <?php
                    $retour = GenerateHTML::generateRemiseTab($db);
                    echo $retour[0];
                    $count = $retour[1];
                    $list_remise = $retour[2];
                ?>
                </tbody>
            </table>
        </div>

        <input type="radio" name="mytabs" id="tab-impaye">
        <label class="label-last label-style" for="tab-impaye">Impayés</label>
        <div class="tab">
        <table class="table-fill">
                <thead>
                    <tr>
                        <th>N°SIREN</th>
                        <th>Date vente</th>
                        <th>Date remise</th>
                        <th>N° Carte</th>
                        <th>Réseau</th>
                        <th>N° dossier impayés</th>
                        <th>Devise</th>
                        <th>Montant</th>
                        <th>Libellé impayés</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <?= GenerateHTML::generateImpayeTab($db) ?>
                </tbody>
            </table>
    </div>
    <div class="fixedContainer">
        <form action='Class/Export.php' method='post'>
            <input type="radio" id="csv" name="type" value="0">
            <label class="radio-btn" for="csv">.csv</label><br>
            <input type="radio" id="xls" name="type" value="1">
            <label class="radio-btn" for="xls">.xls</label><br>
            <input type="radio" id="pdf" name="type" value="2">
            <label class="radio-btn" for="pdf">.pdf</label>
            <input class='btn' type="submit" value="Submit">
        </form>
    </div>
</div>
<?= GenerateHTML::generateDetailsTab($db,$count,$list_remise)?>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function () {
    $('#content1').hide();
    $('#content2').hide();
    $('#content3').hide();
    $('#content4').hide();
    $('#content5').hide();
    $("input").click(function () {
        if ($('tr#' + $(this).data("href")).is(":visible")) {
            $('tr#' + $(this).data("href")).remove();
        } else {
            $(this).closest('tr').after('<tr class="normal-tr" id="' + $(this).data("href") + '"><td colspan="5">' + $('#' + $(this).data("href")).html() + '</td></tr>');
        }                       
    });

});
</script>
</html>