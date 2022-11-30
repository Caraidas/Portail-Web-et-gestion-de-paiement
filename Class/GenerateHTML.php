<?php
include_once 'SQLData.php';

class GenerateHTML
{

    /**
     * Fonction pour générer le code html du tableau de tresorerie
     *
     * @param $db : connexion à la base de donnée
     * @param $d  : (optionnelle) la date pour la trésorerie
     * @return array : le code html de l'interieur du tableau et la requette nécéssaire pour le générer
     */
    public static function generateTresorerieTab($db,$order,$field,$sirenCo =null, $d=null,$siren=null, $raison=null){

        $retour = '';
        $tresorerie = SQLData::getTresorerie($db,$order,$field,$sirenCo,$d,$siren,$raison);
        if($tresorerie->rowCount() > 0){

            echo "".$tresorerie->rowCount()." résultats trouvés";
            $fetch = $tresorerie->fetchAll();
            foreach($fetch as $row){
                if ($row['MontantTotal'] < 0)
                    $solde = "<p style=\"color:#FF0000\">".$row['MontantTotal']."</p>";
                else
                    $solde = $row['MontantTotal'];
                $retour.= " <tr>
                                <td>".$row['Siren']."</td>
                                <td>".$row['RaisonSociale']."</td>
                                <td>".$row['NombreTransaction']."</td>
                                <td>".$row['Devise']."</td>
                                <td>".$solde."</td>
                           </tr>";
            }
        }else{
            $retour.="Pas de résultats pour cet utilisateur";
            $fetch = [];
        }
        $retourTab = array($retour,$fetch);

        return $retourTab;
    }

    /**
     * Fonction pour générer le code html du tableau des remises
     *
     * @param $db : la connexion à la base de donnée
     * @return array : le code html de l'interieur du tableau,le compteur pour les impaye,la liste des utilisateur,
     *                 la requette utilisée pour générer le tableau
     */
    public static function generateRemiseTab($db,$order,$field,$sirenCo, $siren, $raison, $numRemise, $dateDebut, $dateFin){

        $retour = '';
        $tresorerie = SQLData::getRemise($db,$order,$field,$sirenCo, $siren, $raison, $numRemise, $dateDebut, $dateFin);
        $count = 1;
        $list_remise = array();


        if($tresorerie->rowCount() > 0){
            echo "".$tresorerie->rowCount()." résultats trouvés";
            $fetch = $tresorerie->fetchAll();
            foreach($fetch as $row){
                if ($row['Sens'] == "-")
                    $negatif = "<p style=\"color:#FF0000\">".$row['MontantTotal']."</p>";
                else
                    $negatif = $row['MontantTotal'];
                $retour.= " <tr>
                                        <td>
                                            <input type='button' name='' value='Détails' data-href='content$count'>
                                        </td>
                                        <td>".$row['Siren']."</td>
                                        <td>".$row['RaisonSociale']."</td>
                                        <td>".$row['NumeroRemise']."</td>
                                        <td>".$row['DateTraitement']."</td>
                                        <td>".$row['Nombretransaction']."</td>
                                        <td>".$row['Devise']."</td>
                                        <td>".$negatif."</td>
                                        <td>".$row['Sens']."</td>
                                   </tr>";
                echo $count;
                $count++;
                $list_remise[] = $row['NumeroRemise'];
            }
        }else{
            $retour.= "Pas de résultats pour cet utilisateur";
            $fetch = [];
        }

        $retourarray = array(
            $retour,
            $count,
            $list_remise,
            $fetch
        );

        return $retourarray;
    }


    /**
     * Fonction pour générer le code html du tableau des impayes
     *
     * @param $db : la connexion à la base de donnée
     * @return array : le code html de l'interieur du tableau, et la requette utilisé pour le générer
     */
    public static function generateImpayeTab($db,$order,$field,$sirenCo,$dateDebut,$dateFin,$siren,$raison,$dossier){
        $retour = "";
        $tresorerie = SQLData::getImpaye($db,$order,$field,$sirenCo,$dateDebut,$dateFin,$siren,$raison,$dossier);

        
        if($tresorerie->rowCount() > 0){
            echo "".$tresorerie->rowCount()." résultats trouvés";
            $fetch = $tresorerie->fetchAll();
            foreach($fetch as $row){
                $retour.= " <tr>
                                        <td>".$row['Siren']."</td>
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
        }else{
            $retour.= "Pas de résultats pour cet utilisateur";
            $fetch = [];
        }

        $retourTab = array($retour,$fetch);

        return $retourTab;
    }

    /**
     * Fonction pour générer le code html des détails des impayés
     *
     * @param $db : la connexion à la base de donnée
     * @param $count : le nombre d'impayé à détailler
     * @param $list_remise : la liste des remises à détailler
     * @return string : le code HTML de l'interieur du tableau
     */
    public static function generateDetailsTab($db,$id)
    {

        $retour = "";
        $sauvegarde = "";
        $details = SQLData::getDetails($db, $id);
        while ($row2 = $details->fetch(PDO::FETCH_ASSOC)) {
            $sauvegarde .= "<tr>
                <td>" . $row2['Siren'] . "</td>
                <td>" . $row2['DateVente'] . "</td>
                <td>" . $row2['NumeroCarte'] . "</td>
                <td>" . $row2['Reseau'] . "</td>
                <td>" . $row2['NumAutorisation'] . "</td>
                <td>" . $row2['Devise'] . "</td>
                <td>" . $row2['Montant'] . "</td>
                <td>" . $row2['Sens'] . "</td>
            </tr>";
            $retour .= "<tr>
                <td>" . $row2['Siren'] . "</td>
                <td>" . $row2['DateVente'] . "</td>
                <td>" . $row2['NumeroCarte'] . "</td>
                <td>" . $row2['Reseau'] . "</td>
                <td>" . $row2['NumAutorisation'] . "</td>
                <td>" . $row2['Devise'] . "</td>
                <td>" . $row2['Montant'] . "</td>
                <td>" . $row2['Sens'] . "</td>
            </tr>";
        }
        $retour .= "</tbody></table><div class='export'>
                <form action='Class/Export.php' method='post'>
                    <div>exporter : </div>
                    <input type='radio' id='csv' name='export-detail' value='0'>
                    <label class='radio-btn' for='csv'>.csv</label><br>
                    <input type='radio' id='xls' name='export-detail' value='1'>
                    <label class='radio-btn' for='xls'>.xls</label><br>
                    <input type='radio' id='pdf' name='export-detail' value='2'>
                    <label class='radio-btn' for='pdf'>.pdf</label>
                    <input name='txtImpaye' type='hidden' value='$sauvegarde'>
                    <input class='btn' type='submit' value='Submit'>
                </form>
            </div>
            </div>";


        $retourTab = array($retour,$details);
        return $retourTab;

    }
}