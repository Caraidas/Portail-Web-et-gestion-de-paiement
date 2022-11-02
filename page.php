<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body> 

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
                    <!-- DEBUT DE LA BOUCLE -->
                    <tr>
                        <td >362 521 879</td>
                        <td >Renault SAS</td>
                        <td >16</td>
                        <td >EUR</td>
                        <td> 100.0</td>
                    </tr>
                    <tr>
                    <td >357 521 879</td>
                        <td >Laura Leroy SA</td>
                        <td >4</td>
                        <td >DOL</td>
                        <td >102.50</td>
                    </tr>
                    <tr>
                    <td >682 521 879</td>
                        <td >Cloud KICKS</td>
                        <td >25</td>
                        <td >EUR</td>
                        <td >54.20</td>
                    </tr>
                    <tr>
                        <td >124 521 879</td>
                        <td >Patrick Legrand SA</td>
                        <td >2</td>
                        <td >EUR</td>
                        <td >150.0</td>
                    </tr>
                    <tr>
                        <td >242 521 879</td>
                        <td >FlashDesign SARL</td>
                        <td >254</td>
                        <td >EUR</td>
                        <td >50 000.0</td>
                    </tr>
                    <tr>
                        <td >942 521 879</td>
                        <td >Pokemon SARL</td>
                        <td >14</td>
                        <td >DIR</td>
                        <td >10.0</td>
                    </tr>
                    
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
<?php








?>