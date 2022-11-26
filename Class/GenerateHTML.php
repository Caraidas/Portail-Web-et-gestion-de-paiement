<?php
include_once 'SQLData.php';

class GenerateHTML
{

    /**
     * Fonction pour générer le code HTML du tableau de tresorerie
     *
     * @param $db : connexion à la base de donnée
     * @param $d  : (optionnelle) la date pour la trésorerie
     * @return string : le code html de l'interieur du tableau
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
                                <td>".$row['NombreTransactions']."</td>
                                <td>".$row['Devise']."</td>
                                <td>".$row['MontantTotal']."</td>
                           </tr>";
            }
        }else{
            $retour.="Pas de résultats pour cet utilisateur";
        }

        return $retour;
    }


}