<?php
    require_once 'Class/Database.php';
    require_once 'Class/SQLData.php';
    require_once  'Class/GenerateHTML.php';
    $db = Database::getPDO(); //database pour toute la page
    $sirenCo = null;
    include 'header.php';
    if(isset($_SESSION['role'])){
        $role = $_SESSION['role'];
        switch($role){
            case 'Commerçant' :
                $sirenCo = SQLData::getSirenOfCommerceant($db,$_SESSION["id"]);
                break;
            case 'Admin' :
                header('Location: adminChoice.html');
                break;
            case 'PO' :
                break;
            default :
                header('Location: login.php');
        }

    }else{
        header('Location: login.php');
    }

    if(isset($_POST['search'])&& !empty($_POST['search'])){
        $research = $_POST['search'];
    }else {
        $research = null;
    }

    if(isset($_POST['date'])&& (!empty($_POST['date']))){
        $date = $_POST['date'];
    }else {
        $date = null;
    }

    if(isset($_POST['dateFin'])&& (!empty($_POST['dateFin']))){
        $dateFin = $_POST['dateFin'];
    }else {
        $dateFin = null;
    }

    if(isset($_POST['dateDebut'])&& (!empty($_POST['dateDebut']))){
        $dateDebut = $_POST['dateDebut'];
    }else {
        $dateDebut = null;
    }
?>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <script>

        const setTab = function(tab) {
            localStorage.setItem("selected_tab", tab);
        };

        window.onload = function() {
            if ("selected_tab" in localStorage) {
                document.getElementById(localStorage.getItem("selected_tab")).checked = true;
            } else {
                document.getElementById("tab-tresorerie").checked = true;
            }
        };
    </script>
    
    <div class="mytabs">
        <input type="radio" name="mytabs" id="tab-tresorerie" onclick="setTab('tab-tresorerie')" checked>
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
                  //public static function getTresorerie($db,$order,$field,$sirenCo=null, $date=null, $siren=null, $raison=null){
                    $gTresorerie = GenerateHTML::generateTresorerieTab($db,$order,$field,$sirenCo,$d,$research,$research);
                   $txtTresorerie = $gTresorerie[0];
                   echo "$txtTresorerie";
                   $datasTresorerie = $gTresorerie[1];
                ?>
                </tbody>
            </table>
            <div class="export">
            <form action='Class/Export.php' method='post'>
                <input type="radio" id="csv" name="export-tresorerie" value="0">
                <label class="radio-btn" for="csv">.csv</label><br>
                <input type="radio" id="xls" name="export-tresorerie" value="1">
                <label class="radio-btn" for="xls">.xls</label><br>
                <input type="radio" id="pdf" name="export-tresorerie" value="2">
                <label class="radio-btn" for="pdf">.pdf</label>
                <?php
                    $_SESSION["data-tresorerie"]=$datasTresorerie;
                    echo "<input name='txtTresorerie' type='hidden' value='$txtTresorerie'>";
                ?>
                <input class='btn' type="submit" value="Submit">        
            </form>
        </div>
        </div>

        <input type="radio" name="mytabs" id="tab-remise" onclick="setTab('tab-remise')">
        <label class="label-style" for="tab-remise">Remise</label>
        <div class="tab">
        <form action="page.php" method="post">
            <label for="dateDebut">Date de début:</label>
            <input type="date" id="dateDebut" name="dateDebut">

            <label for="dateFin">Date de fin:</label>
            <input type="date" id="dateFin" name="dateFin">
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
                    if(isset($_POST['order']) && isset($_POST['tab-field'])){
                            $order = $_POST['order'];
                            $field = $_POST['tab-field'];
                    }else {
                        $order = 'Siren';
                        $field = 'ASC';
                    }

                    $gRemise = GenerateHTML::generateRemiseTab($db,$order,$field,$sirenCo, $research, $research, $research, $dateDebut, $dateFin);
                    $txtRemise = $gRemise[0];
                    echo $txtRemise;
                    $count = $gRemise[1];
                    $list_remise = $gRemise[2];
                    $datasRemise = $gRemise[3];
                    $_SESSION["data-remise"]=$datasRemise;
                    $_SESSION["txt-remise"]=$txtRemise;
                ?>
                </tbody>
            </table>
            <div class="export">
                <form action='Class/Export.php' method='post'>
                    <input type="radio" id="csv" name="export-remise" value="0">
                    <label class="radio-btn" for="csv">.csv</label><br>
                    <input type="radio" id="xls" name="export-remise" value="1">
                    <label class="radio-btn" for="xls">.xls</label><br>
                    <input type="radio" id="pdf" name="export-remise" value="2">
                    <label class="radio-btn" for="pdf">.pdf</label>
                    
                    <input class='btn' type="submit" value="Submit">        
                </form>
            </div>
        </div>

        <input type="radio" name="mytabs" id="tab-impaye" onclick="setTab('tab-impaye')">
        <label class="label-last label-style" for="tab-impaye">Impayés</label>
        <div class="tab">
            <form action="page.php" method="post">
            <label for="dateDebut">Date de début:</label>
            <input type="date" id="dateDebut" name="dateDebut">

            <label for="dateFin">Date de fin:</label>
            <input type="date" id="dateFin" name="dateFin">
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
                <?php
                if(isset($_POST['order']) && isset($_POST['tab-field'])){
                    $order = $_POST['order'];
                    $field = $_POST['tab-field'];
                }else {
                    $order = 'Siren';
                    $field = 'ASC';
                }
                $gImpaye = GenerateHTML::generateImpayeTab($db,$order,$field,$sirenCo,$dateDebut,$dateFin,$research,$research,$research);
                $txtImpaye = $gImpaye[0];
                $datasImpaye = $gImpaye[1];
                echo $txtImpaye;
                ?>
                </tbody>
            </table>
            <div class="export">
                <form action='Class/Export.php' method='post'>
                    <input type="radio" id="csv" name="export-impaye" value="0">
                    <label class="radio-btn" for="csv">.csv</label><br>
                    <input type="radio" id="xls" name="export-impaye" value="1">
                    <label class="radio-btn" for="xls">.xls</label><br>
                    <input type="radio" id="pdf" name="export-impaye" value="2">
                    <label class="radio-btn" for="pdf">.pdf</label>
                    <?php
                    $_SESSION["data-impaye"]=$datasImpaye;
                    echo "<input name='txtImpaye' type='hidden' value='$txtImpaye'>";
                ?>
                    <input class='btn' type="submit" value="Submit">        
                </form>
            </div>
    </div>
    <div class="fixedContainer">
        <form action='page.php' method='post'>
            <label for="search">Rechercher</label>
            <input type="search" id="search" name="search">
            <button type="submit">Search</button>
        </form>
    </div>
    </div>
</div>
<?php
for ($i = 0; $i < $count - 1; $i++) {
    $j = $i + 1;
    echo "<div id='content$j'>
            <table class='classic-table'>
                <thead>
                    <tr>
                        <th>N° SIREN</th>
                        <th>Date vente</th>
                        <th>N° carte</th>
                        <th>Réseau</th>
                        <th>N° Autorisation</th>
                        <th>Devise</th>
                        <th>Montant</th>
                        <th>Sens</th>
                    </tr>
                </thead>
                <tbody>";
    $retour =  GenerateHTML::generateDetailsTab($db,$list_remise[$i],$i);
    echo $retour[0];
    $_SESSION['Detail'.$i] = $retour[1];
} 
?>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function () {
    var count = "<?php echo $count; ?>";
    for (let i = 1; i < count; i++) {
        $('#content'+i).hide();
    }
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