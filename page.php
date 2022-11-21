<?php
session_start();
 require_once 'Class/Database.php';
    require_once 'Class/SQLData.php'
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
    <?php include_once 'header.html'; ?>
    <div class="mytabs">
        <input type="radio" name="mytabs" id="tab-tresorerie" checked="checked">
        <label class="label-first label-style" for="tab-tresorerie">Trésorerie</label>
        <div class="tab">
        <form action="page.php" method="get">
            <label for="date">Annonces du:</label>
            <input type="date" id="date" name="date">
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
                   $db = Database::getPDO();
                   if(isset($_GET['date'])){
                    if($_GET['date']!=''){
                        $d = $_GET['date'];//aaaa-mm-jj
                        $tresorerie = SQLData::getTresorerie($db,$date=$d);
                    }else $tresorerie = SQLData::getTresorerie($db); 
                   }else {
                        $tresorerie = SQLData::getTresorerie($db); 
                        echo "non";
                   }

                    if($tresorerie->rowCount() > 0){
                        while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){ 
                            echo " <tr>
                                        <td>".$row['Siren']."</td>
                                        <td>".$row['RaisonSociale']."</td>
                                        <td>".$row['NombreTransactions']."</td>
                                        <td>".$row['Devise']."</td>
                                        <td>".$row['MontantTotal']."</td>
                                   </tr>";
                        }
                    }else echo "Pas de résultats pour cet utilisateur";
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
                        <th>N°SIREN</th>
                        <th>Raison sociale</th>
                        <th>N° Remise</th>
                        <th>Date traitement</th>
                        <th>Nbr Transactions</th>
                        <th>Devise</th>
                        <th>Montant total</th>
                        <th>Sens + ou -</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <?php
                   $db = Database::getPDO();

                   $tresorerie = SQLData::getRemise($db);

                    if($tresorerie->rowCount() > 0){
                        while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){
                            echo " <tr>
                                        <td>".$row['Siren']."</td>
                                        <td>".$row['RaisonSociale']."</td>
                                        <td>".$row['NumeroRemise']."</td>
                                        <td>".$row['DateTraitement']."</td>
                                        <td>".$row['Nombretransaction']."</td>
                                        <td>".$row['Devise']."</td>
                                        <td>".$row['MontantTotal']."</td>
                                        <td>".$row['Sens']."</td>
                                        <td></td>
                                   </tr>";
                        }
                    }else echo "Pas de résultats pour cet utilisateur";
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
                <?php
                   $db = Database::getPDO();

                   $tresorerie = SQLData::getImpaye($db);

                    if($tresorerie->rowCount() > 0){
                        while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){
                            echo " <tr>
                                        <td>".$row['NumSiren']."</td>
                                        <td>".$row['DateVente']."</td>
                                        <td>".$row['DateRemise']."</td>
                                        <td>".$row['NumCarte']."</td>
                                        <td>".$row['Reseau']."</td>
                                        <td>".$row['NumeroDossier']."</td>
                                        <td>".$row['Devise']."</td>
                                        <td>".$row['Montant']."</td>
                                        <td>".$row['LibelleImpaye']."</td>
                                   </tr>";
                        }
                    }else echo "Pas de résultats pour cet utilisateur";
                ?>  
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
   
    
<div class="center hideform">
    <button id="close" style="float: right;">X</button>
    <form action="/action_page.php">
        First name:<br>
        <input type="text" name="firstname" value="Mickey">
        <br>
        Last name:<br>
        <input type="text" name="lastname" value="Mouse">
        <br><br>
        <input type="submit" value="Submit">
    </form>
</div>
<button id="show">Show form</button>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
        $(document).ready(function() {
            $('#show').on('click', function () {
                $('.center').show();
                $(this).hide();
            })

            $('#close').on('click', function () {
                $('.center').hide();
                $('#show').show();
            })
        });
</script>
</html>