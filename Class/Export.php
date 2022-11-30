<?php
include_once "Database.php";
include_once "SQLData.php";
include_once "GenerateHTML.php";
include_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
use \Spipu\Html2Pdf\Html2Pdf;

session_start();
$db = Database::getPDO();
if (isset($_POST['export-tresorerie']) && isset($_SESSION['data-tresorerie']) && isset($_POST['txtTresorerie'])){
    $type= $_POST['export-tresorerie'];
    if ($type==0 || $type ==1){
        $datas = $_SESSION['data-tresorerie'];
        Export::export_tresorerie_to_csv_xls($datas, $type);
    }else if ($type == 2){
        Export::export_tresorerie_to_PDF($_POST['txtTresorerie']);
    }
}else if (isset($_POST['export-remise']) && isset($_SESSION['data-remise']) && isset($_SESSION["txt-remise"])){
    $type= $_POST['export-remise'];
    if ($type==0 || $type ==1){
        $datas = $_SESSION['data-remise'];
        Export::export_remise_to_csv_xls($datas, $type);
    }else if ($type == 2){
        Export::export_remise_to_PDF($_SESSION["txt-remise"]);
    }
}if (isset($_POST['export-impaye']) && isset($_SESSION['data-impaye']) && isset($_POST['txtImpaye'])){
    $type= $_POST['export-impaye'];
    if ($type==0 || $type ==1){
        $datas = $_SESSION['data-impaye'];
        Export::export_impayes_to_csv_xls($datas, $type);
    }else if ($type == 2){
        Export::export_impaye_to_PDF($_POST['txtImpaye']);
    }
}

/**
 * Classe qui s'occupe de faire les différentes exportations des tableaux
 */
class Export{

    /**
     * Fonction qui génère les différents fichiers d'exportations pour le tableau de trésorerie.
     *
     * @param $requestResult : le PDOStatment contenant les informations nécéssaires
     * @param $fileType : le type de fichiers de retour voulu (CSV,xls..)
     * @return void
     */
    public static function export_tresorerie_to_csv_xls($requestResult, $fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
        if($fileType==0){
            $fileName = ".csv";
            $contentType = "text/csv";
            $endCase = ";";
            $endLine = "\n";
        }else if($fileType==1){
            $fileName = ".xls";
            $contentType = "application/vnd.ms-excel";
            $endCase = "\t";
            $endLine = "\n";
        }

        $excel = "";
        $excel .=  "Numero de Siren".$endCase."RaisonSociale".$endCase."Devise".$endCase."Nombres de Transaction".$endCase."Montant Total".$endLine;
        foreach($requestResult as $row) {
            $excel .= "$row[Siren]".$endCase."$row[RaisonSociale]".$endCase."$row[Devise]".$endCase."$row[NombreTransaction]".$endCase."$row[MontantTotal]$endLine";
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=TRESORERIE-".date('y/m/d')."$fileName");
        print $excel;
        exit;
    }

    /**
     * Fonction qui génère les différents fichiers d'exportations pour le tableau de remise.
     *
     * @param $requestResult : le PDOStatment contenant les informations nécéssaires
     * @param $fileType : le type de fichiers de retour voulu (CSV,xls..)
     * @return void
     */
    public static function export_remise_to_csv_xls($requestResult, $fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
        if($fileType==0){
            $fileName = ".csv";
            $contentType = "text/csv";
            $endCase = ";";
            $endLine = "\n";
        }else if($fileType==1){
            $fileName = ".xls";
            $contentType = "application/vnd.ms-excel";
            $endCase = "\t";
            $endLine = "\n";
        }

        $excel = "";
        $excel .=  "Numero de Siren".$endCase."RaisonSociale".$endCase."Devise".$endCase."Numero de Remise".$endCase."Date de Traitement".$endCase."Nombres de Transactions".$endCase."Montant Total".$endCase."Sens".$endLine;
        foreach($requestResult as $row) {
            $excel .= "$row[Siren]".$endCase."$row[RaisonSociale]".$endCase."$row[Devise]".$endCase."$row[NumeroRemise]".$endCase."$row[DateTraitement]".$endCase."$row[Nombretransaction]".$endCase."$row[MontantTotal]".$endCase."$row[Sens]".$endLine;
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=REMISES-".date('y/m/d')."$fileName");
        print $excel;
        exit;
    }

    /**
     * Fonction qui génère les différents fichiers d'exportations pour le tableau des détails des remises.
     *
     * @param $requestResult : le PDOStatment contenant les informations nécéssaires
     * @param $fileType : le type de fichiers de retour voulu (CSV,xls..)
     * @return void
     */
    public static function export_remise_details_to_csv_xls($requestResult, $fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
        if($fileType==0){
            $fileName = ".csv";
            $contentType = "text/csv";
            $endCase = ";";
            $endLine = "\n";
        }else if($fileType==1){
            $fileName = ".xls";
            $contentType = "application/vnd.ms-excel";
            $endCase = "\t";
            $endLine = "\n";
        }

        $excel = "";
        $excel .=  "Numero de Siren".$endCase."Date de Vente".$endCase."Numero de Carte".$endCase."Reseau".$endCase."Montant".$endCase."Sens".$endLine;
        foreach($requestResult as $row) {
            $excel .= "$row[Siren]".$endCase."$row[DateVente]".$endCase."$row[NumeroCarte]".$endCase."$row[Reseau]".$endCase."$row[Montant]".$endCase."$row[Sens]".$endLine;
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=REMISE-DETAILS".date('y/m/d')."$fileName");
        print $excel;
        exit;
    }

    /**
     * Fonction qui génère les différents fichiers d'exportations pour le tableau des impayes.
     *
     * @param $requestResult : le PDOStatment contenant les informations nécéssaires
     * @param $fileType : le type de fichiers de retour voulu (CSV,xls..)
     * @return void
     */
    public static function export_impayes_to_csv_xls($requestResult, $fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
        if($fileType==0){
            $fileName = ".csv";
            $contentType = "text/csv";
            $endCase = ";";
            $endLine = "\n";
        }else if($fileType==1){
            $fileName = ".xls";
            $contentType = "application/vnd.ms-excel";
            $endCase = "\t";
            $endLine = "\n";
        }

        $excel = "";
        $excel .=  "Numero de Siren".$endCase."Date de Remise".$endCase."Date de Vente".$endCase."N° de Carte".$endCase."Réseau".$endCase."N° de Dossier".$endCase."Devise".$endCase."Montant".$endCase."Libelle Impaye".$endLine;
        foreach($requestResult as $row) {
            $excel .= "$row[Siren]".$endCase."$row[DateRemise]".$endCase."$row[DateVente]".$endCase."$row[NumCarte]".$endCase."$row[Reseau]".$endCase."$row[NumeroDossier]".$endCase."$row[Devise]".$endCase."$row[Montant]".$endCase."$row[LibelleImpaye]".$endLine;
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=IMPAYES-".date('y/m/d')."$fileName");
        print $excel;
        exit;
    }

    /**
     * Fait télécharger à l'utilisateur le tableau des trésorerie sous format pdf
     *
     * @param $db : la connexion à la base de donnée
     * @param $date : la date choisie dans le tableau des trésorerie
     *
     */
    public static function export_tresorerie_to_PDF($texte){

        $txt = "
        Date d'extraction :".date("y/m/d")."<br>
        <table>
            <thead>
                <tr>
                    <th>N°SIREN</th>
                    <th>Raison sociale</th>
                    <th>Nombre de transactions</th>
                    <th>Devise</th>
                    <th>Montant total </th>
                </tr>
            </thead>
            <tbody>".$texte."</tbody>
            </table>
             <style>
                td{
                    border:black solid 1px;
                    border-collapse: collapse 
                }
                table{
                    border:black solid 1px;
                    border-collapse: collapse 
                }
                thead{
                    border:grey solid 1px;
                    border-collapse: collapse 
                }
            </style>";

        $html2pdf = new Html2Pdf('L','A4','fr');
        $html2pdf->writeHTML($txt);
        ob_end_clean();
        $html2pdf->output('TRESORERIE'.date("y/m/d").'.pdf','D'); //
    }

    /**
     * Fait télécharger à l'utilisateur le tableau des remises sous format pdf
     *
     * @param $db : la connexion à la base de donnée
     */
    public static function  export_remise_to_PDF($texte){
        $txt = "
        Date d'extraction :".date("y/m/d")."<br><table>
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
            <tbody>". $texte. "</tbody>
        </table> 
        <style>
            td{
                border:black solid 1px;
                border-collapse: collapse 
            }
            table{
                border:black solid 1px;
                border-collapse: collapse 
            }
            thead{
                border:grey solid 1px;
                border-collapse: collapse 
            }
        </style>";

        $html2pdf = new Html2Pdf('L','A4','fr');
        $html2pdf->writeHTML($txt);
        ob_end_clean();
        $html2pdf->output('REMISE'.date("y/m/d").'.pdf','D');
    }

    /**
     * Fait télécharger à l'utilisateur le tableau des remises sous format pdf
     *
     * @param $db : la connexion à la base de donnée
     *
     */
    public static function export_impaye_to_PDF($texte){
        $txt ="
        Date d'extraction :".date("y/m/d"). "<table>
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
            <tbody>". $texte. "</tbody>
        </table> 
        <style>
            td{
                border:black solid 1px;
                border-collapse: collapse 
            }
            table{
                border:black solid 1px;
                border-collapse: collapse 
            }
            thead{
                border:grey solid 1px;
                border-collapse: collapse 
            }
        </style>";

        $html2pdf = new Html2Pdf('L','A4','fr');
        $html2pdf->writeHTML($txt);
        ob_end_clean();
        $html2pdf->output('REMISES'.date("y/m/d").'.pdf','D');
    }

    /**
     * Fait télécharger à l'utilisateur un tableau de détail sous format pdf
     *
     * @param $db : la connexion à la base de donnée
     * @param $id : l'indentifiant dont on veut le détail
     *
     */
    public static function export_detail_to_PDF($db, $id){

        $txt="
        Date d'extraction :".date("y/m/d")."<table>
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

        $details = SQLData::getDetails($db, $id)[0];
        while ($row2 = $details->fetch(PDO::FETCH_ASSOC)) {
            $txt.= "<tr>
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

        $txt.="</tbody></table> <style>
            td{
                border:black solid 1px;
                border-collapse: collapse 
            }
            table{
                border:black solid 1px;
                border-collapse: collapse 
            }
            thead{
                border:grey solid 1px;
                border-collapse: collapse 
            }
        </style>";
        $html2pdf = new Html2Pdf('L','A4','fr');
        $html2pdf->writeHTML($txt);
        ob_end_clean();
        $html2pdf->output('DETAIL'.date("y/m/d").'.pdf','D');
    }
}

?>