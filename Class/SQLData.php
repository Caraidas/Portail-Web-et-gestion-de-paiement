<?php

include_once 'Database';

class SQLData
{

    /*
     * Renvoie la liste des tresoreries sous forme de PDOStatment
     *
     * $db : la connexion à la base de donnée
     * (optionnel) $id : l'id de l'entreprise à étudier
     * (optionnel) $date : la date que l'on veut verifier
     *
     */
    public static function getTresorerie($db,$date=null,$id=null){

        //création de la syntaxe de la requette
        $query = "
        SELECT B_Client.NumSiren,
               RaisonSociale,
               B_Client.Devise,
               COUNT(B_Transaction.Montant) AS 'nombre de transactions',
               SUM(B_Transaction.Montant) AS 'Montant total'
        FROM B_Remise,
             B_Client,
             B_Transaction
        WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
          AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
        ";

        if(!$date === null){
            $query.=" AND B_Transaction.DateVente = :date";
        }
        if(!$id === null){
            $query.=" AND B_Client.NumSiren = :id";
        }
        $query.=" GROUP BY NumSiren;";

        //securisation de la requette
        $query = $db->prepare($query);
        if(!$date === null){
            $query->bindParam('date',$date,PDO::PARAM_STR);
        }
        if(!$id === null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

        $query->exec();
        return $query;
    }

    /*
     * Renvoie la liste des impayé sous forme de PDOStatment
     *
     * $db : la connexion à la base de donnée
     * (optionnel) $id : l'id de l'entreprise à étudier
     *
     */
    public static function getImpaye($db,$id=null){

        //création de la syntax de la requette
        $query = "
        SELECT B_Client.NumSiren,
               B_Remise.DateTraitement 'Date Remise',
               B_Transaction.NumCarte,
               B_Transaction.Reseau,
               B_Transaction.NumImpaye 'Numero de dossier impaye',  
               B_Transaction.Devise,
               B_Transaction.Montant,
               B_Transaction.LibelleImpaye
        FROM B_Client 
             NATURAL JOIN B_Remise
             NATURAL JOIN B_Transaction
        WHERE B_Transaction.NumImpaye IS NOT NULL;
        ";

        if(! $id === null){
            $query.=" AND B_Client.NumSiren = :id";
        }

        //securisation de la requette
        $query = $db->prepare($query);
        if(! $id === null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

        $query->exec();
        return $query;
    }

    public static function getRemise($db, $siren = null, $raison = null, $dateDebut = null, $dateFin = null){
        $numWhere = 0;
        $req = "
            SELECT B_Client.NumSiren,
                B_Client.RaisonSociale,
                B_Client.Devise,
                B_Remise.NumRemise,
                B_Remise.DateTraitement,
                COUNT(B_Transaction.NumAutorisation) AS 'Nombre de transaction' , 
                ABS(TableMontantPositif.montant - TableMontantNegatif.montant) AS 'Montant Total',
                CHAR(44 - SIGN(TableMontantPositif.montant - TableMontantNegatif.montant)) AS 'sens'
            FROM B_Remise
                LEFT JOIN B_Client ON B_Remise.NumSiren = B_Client.NumSiren
                LEFT JOIN B_Transaction ON B_Transaction.NumRemise = B_Remise.NumRemise
                LEFT JOIN TableMontantPositif ON TableMontantPositif.NumRemise = B_Remise.NumRemise
                LEFT JOIN TableMontantNegatif ON TableMontantNegatif.NumRemise = B_Remise.NumRemise
            ";

        if (!($siren===null)){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.=" B_Client.NumSiren = :siren";
        }
        if (! $raison ===null){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.=" B_Client.RaisonSociale = :raison";
        }
        if (!($dateDebut === null)){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.= " B_Remise.DateTraitement >= :dateDebut";
        }
        if (!($dateFin === null)){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.= " B_Remise.DateTraitement <= :dateFin";
        }

        $req.=" GROUP BY B_Remise.NumRemise";

        $query = prepare($req);
        if (!($raison===null )){
            $query->bindParam(":raison",$raison,PDO::PARAM_STR);
        }
        if (!($siren===null )){
            $query->bindParam(":siren",$siren,PDO::PARAM_INT);
        }
        if (!($raison===null )){
            $query->bindParam(":dateDebut",$dateDebut,PDO::PARAM_STR);
        }
        if (!($raison===null )){
            $query->bindParam(":dateFin",$dateFin,PDO::PARAM_STR);
        }

        $query->exec();
        return $query;
    }

    public static function getDetails($cnx, $remise){
        $req = "SELECT B_Client.NumSiren,
            B_Transaction.DateVente,
            B_Transaction.NumCarte,
            B_Transaction.Reseau,
            B_Transaction.Montant,
            B_Transaction.Sens
            FROM B_Client
            NATURAL JOIN B_Remise
            NATURAL JOIN B_Transaction
            WHERE B_Remise.NumRemise = :remise";

            $query=prepare($req);
            $query->bindParam(":remise",$remise,PDO::PARAM_INT);
            $query->exec();
            return $query;
    }



}