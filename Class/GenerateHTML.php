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
    public static function generateTresorerieTab($db,$order,$field, $d=null,$id=null){

        $retour = '';
        $tresorerie = SQLData::getTresorerie($db,$order,$field,$d,$id);

        if($tresorerie->rowCount() > 0){
            echo "".$tresorerie->rowCount()." résultats trouvés";
            while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){
                $retour.= " <tr>
                                <td>".$row['Siren']."</td>
                                <td>".$row['RaisonSociale']."</td>
                                <td>".$row['NombreTransaction']."</td>
                                <td>".$row['Devise']."</td>
                                <td>".$row['MontantTotal']."</td>
                           </tr>";
            }
        }else{
            $retour.="Pas de résultats pour cet utilisateur";
        }

        $retourTab = array($retour,$tresorerie);

        return $retourTab;
    }

    /**
     * Fonction pour générer le code html du tableau des remises
     *
     * @param $db : la connexion à la base de donnée
     * @return array : le code html de l'interieur du tableau,le compteur pour les impaye,la liste des utilisateur,
     *                 la requette utilisée pour générer le tableau
     */
    public static function generateRemiseTab($db,$order,$field){

        $retour = '';
        $tresorerie = SQLData::getRemise($db,$order,$field);

        if($tresorerie->rowCount() > 0){
            echo "".$tresorerie->rowCount()." résultats trouvés";
            $count = 1;
            $list_remise = array();
            while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){
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
                                        <td>".$row['MontantTotal']."</td>
                                        <td>".$row['Sens']."</td>
                                   </tr>";
                echo $count;
                $count++;
                $list_remise[] = $row['NumeroRemise'];
            }
        }else{
            $retour.= "Pas de résultats pour cet utilisateur";
        }

        $retourarray = array(
            $retour,
            $count,
            $list_remise,
            $tresorerie
        );

        return $retourarray;
    }


    /**
     * Fonction pour générer le code html du tableau des impayes
     *
     * @param $db : la connexion à la base de donnée
     * @return array : le code html de l'interieur du tableau, et la requette utilisé pour le générer
     */
    public static function generateImpayeTab($db,$order,$field){
        $retour = "";
        $tresorerie = SQLData::getImpaye($db,$order,$field);

        if($tresorerie->rowCount() > 0){
            echo "".$tresorerie->rowCount()." résultats trouvés";
            while($row = $tresorerie->fetch(PDO::FETCH_ASSOC)){
                $retour.= " <tr>
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
        }else{
            $retour.= "Pas de résultats pour cet utilisateur";
        }

        $retourTab = array($retour,$tresorerie);

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
        $details = SQLData::getDetails($db, $id);
        while ($row2 = $details->fetch(PDO::FETCH_ASSOC)) {
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
        $retour .= "</tbody></table></div>";


        $retouTab = array($retour,$details);
        return $retour;

    }
}