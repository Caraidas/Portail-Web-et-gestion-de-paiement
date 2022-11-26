<?php
include_once "Database.php";
include_once "SQLData.php";
include_once "GenerateHTML.php";
include_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
use \Spipu\Html2Pdf\Html2Pdf;

$db = Database::getPDO();
Export::export_tresorerie_to_PDF($db);


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
            $excel .= "$row[Siren]".$endCase."$row[RaisonSociale]".$endCase."$row[Devise]".$endCase."$row[NombreTransactions]".$endCase."$row[MontantTotal]$endLine";
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=Tresorerie$fileName");
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
            $excel .= "$row[Siren]".$endCase."$row[RaisonSociale]".$endCase."$row[Devise]".$endCase."$row[NumeroRemise]".$endCase."$row[DateTraitement]".$endCase."$row[NombreTransaction]".$endCase."$row[MontantTotal]".$endCase."$row[Sens]".$endLine;
        }
        
        header("Content-type: application/$contentType");
        header("Content-disposition: attachment; filename=Remises$fileName");
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
        header("Content-disposition: attachment; filename=RemiseDetails$fileName");
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
    public static function export_tresorerie_to_PDF($db, $date=null){
        $html2pdf = new Html2Pdf('p','A4','fr');
        $txt = "
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
            <tbody>".GenerateHTML::generateTresorerieTab($db,$date)."</tbody>
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

        $html2pdf->writeHTML($txt);
        ob_end_clean();
        $html2pdf->output('Tresorerie'.date("y-m-d").'.pdf','D'); //
    }
}

?>