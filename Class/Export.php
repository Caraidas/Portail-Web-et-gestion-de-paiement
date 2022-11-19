<?php
include "Database.php";
include "SQLData.php";

$cnx = Database::getPDO();
$req = SQLData::getTresorerie($cnx);
$result = $req->fetchAll();

$type = $_POST['type'];
Export::export_tresorerie_to_csv_xls($result,$type);

class Export{
    public static function export_tresorerie_to_csv_xls($requestResult,$fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
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

    public static function export_remise_to_csv_xls($requestResult,$fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
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

    public static function export_remise_details_to_csv_xls($requestResult,$fileType){ // Prend en paramètre le resultat de la requete après le fetchAll et le type de fichier vers lequel on exporte : 0 pour csv et 1 pour xls
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
}

?>