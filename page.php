<?php require_once 'Class/Database.php';?>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body> 
    <?php include_once 'header.html'; ?>
    <div class="mytabs">
        <input type="radio" name="mytabs" id="tab-tresorerie" checked="checked">
        <label class="label-first" for="tab-tresorerie">Trésorerie</label>
        <div class="tab">
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
                   /* $db = new Database();
                    $select_qry = $db->getPDO()->prepare("SELECT B_Client.NumSiren,
                                                                 RaisonSociale,
                                                                 B_Client.Devise,
                                                                 COUNT(B_Transaction.Montant) AS 'nombre de transactions',
                                                                 SUM(B_Transaction.Montant) AS 'Montant total'
                                                        FROM B_Remise,
                                                            B_Client,
                                                            B_Transaction
                                                        WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
                                                        AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
                                                        AND B_Client.NumSiren = 615888425
                                                        GROUP BY NumSiren;") or die('Error: Could not execute mysql query MAIN');

                    if($select_qry->rowCount() > 0){
                        while($row = $select_qry->fetch(PDO::FETCH_ASSOC)){
                            echo " <tr>
                                        <td>".$row['NumSiren']."</td>
                                        <td>".$row['RaisonSociale']."</td>
                                        <td>".$row['nombre de transactions']."</td>
                                        <td>".$row['Devise']."</td>
                                        <td>".$row['Montant total']."</td>
                                   </tr>";
                        }
                    }else echo "Pas de résultats pour cet utilisateur";*/
                ?>
                    
                </tbody>
            </table>
        </div>

        <input type="radio" name="mytabs" id="tab-remise">
        <label for="tab-remise">Remise</label>
        <div class="tab">
           <p> lorem ipsum dolor sit amet, consectetur adipis ac ante et
            lorem ipsum dolor sit amet, consectetur adipis ac ante e
            lorem ipsum dolor sit amet, consectetur adipis ac ante et
            lorem ipsum dolor sit amet, consectetur adipis ac ante et</p>
        </div>

        <input type="radio" name="mytabs" id="tab-impaye">
        <label class="label-last" for="tab-impaye">Impayés</label>
        <div class="tab">
           <p> lorem ipsum dolor sit amet, consectetur adipis ac ante et
            l
            lorem ipsum dolor sit amet, consectetur adipis ac ante et</p>
        </div>
    </div>
    
   
    

  </body>
</html>