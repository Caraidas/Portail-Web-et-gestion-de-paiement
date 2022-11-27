<?php

include_once 'Database.php';

/**
 *CLasse utilsé pour extraires les informations de la base de donnée Mysql
 */
class SQLData
{

    /**
     * Renvoie la liste des tresoreries sous forme de PDOStatment
     *
     * @param $db : la connexion à la base de donnée
     * @param $date : l'id de l'entreprise à étudier (optionnel)
     * @param $id : la date que l'on veut verifier (optionnel)
     * @return mixed
     */
    public static function getTresorerie($db,$order,$field, $date=null, $id=null){

        //création de la syntaxe de la requette
        $query = "
        SELECT B_Client.NumSiren AS 'Siren',
                B_Client.RaisonSociale AS 'RaisonSociale',
                B_Client.Devise AS 'Devise',
                SUM(MontantTotalRemise) AS 'MontantTotal',
                SUM(NombreTransactionTMP) AS 'NombreTransaction'
                FROM B_Client,(
                        SELECT
                        B_Client.NumSiren AS 'client',
                        B_Remise.NumRemise AS 'NumeroRemise',
                        B_Remise.DateTraitement AS 'DateTraitement',
                        COUNT(B_Transaction.NumAutorisation) AS 'NombretransactionTmp' , 
                        (TableMontantPositif.montant - TableMontantNegatif.montant) AS 'MontantTotalRemise'
                        FROM B_Remise
                        LEFT JOIN B_Client ON B_Client.NumSiren = B_Remise.NumSiren
                        LEFT JOIN B_Transaction ON B_Transaction.NumRemise = B_Remise.NumRemise
                        LEFT JOIN TableMontantPositif ON TableMontantPositif.NumRemise = B_Remise.NumRemise
                        LEFT JOIN TableMontantNegatif ON TableMontantNegatif.NumRemise = B_Remise.NumRemise
                        
        ";
        if($date !== null){
            $query.=" WHERE B_Remise.DateTraitement < :date";
        }

        $query .= " GROUP BY B_Remise.NumRemise) Remises 
        WHERE Remises.client = B_Client.NumSiren";
        if($id !== null){
            $query.=" AND B_Client.NumSiren = :id";
        }
        $query.=" GROUP BY B_Client.NumSiren";
        if ((($order=="ASC"||$order="DESC")&&($field=="Siren"||$field=="MontantTotal"))){
            $query.=" ORDER BY $field $order;";
        }
        
        //securisation de la requette
        $query = $db->prepare($query);
        if($date !== null){
            $query->bindParam('date',$date,PDO::PARAM_STR);
        }
        if($id !== null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }
        //$query->bindParam(':field',$field,PDO::PARAM_STR);
        //$query->bindParam(':order', $order,PDO::PARAM_STR);

        $query->execute();
        return $query;
    }

    /**
     * Renvoie la liste des impayé sous forme de PDOStatment
     *
     * @param $db : la connexion à la base de donnée
     * @param $id : l'id de l'entreprise à étudier (optionnel)
     * @return mixed
     */
    public static function getImpaye($db,$order,$field,$id=null){
        if ($field=="MontantTotal"){
            $field="Montant";
        }
        //création de la syntax de la requette
        $query = "
        SELECT B_Client.NumSiren AS Siren,
               B_Remise.DateTraitement AS DateRemise,
               B_Transaction.DateVente AS DateVente,
               B_Transaction.NumCarte,
               B_Transaction.Reseau,
               B_Impaye.NumDossier AS NumeroDossier,  
               B_Transaction.Devise,
               B_Transaction.Montant,
               B_TypeImpaye.LibelleImpaye
        FROM B_Client 
             NATURAL JOIN B_Remise
             NATURAL JOIN B_Transaction
             NATURAL JOIN B_Impaye
             NATURAL JOIN B_TypeImpaye
        ";

        if( $id !== null){
            $query.=" AND B_Client.NumSiren = :id";
        }

        if ((($order=="ASC"||$order="DESC")&&($field=="Siren"||$field=="Montant"))){
            $query.=" ORDER BY $field $order;";
        }

        //securisation de la requette
        $query = $db->prepare($query);
        if( $id !== null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

        $query->execute();
        return $query;
    }

    /**
     * Permet d'avoir la remise par clients
     *
     * @param $db : la connexion a la base de donnée
     * @param $siren : le numéro de siren de l'entreprise (optionnel)
     * @param $raison : la raison sociale de l'entreprise (optionnel)
     * @param $dateDebut : la date de début de la recherche (optionnel)
     * @param $dateFin : la date de fin de la recherche (optionnel)
     * @return mixed
     */
    public static function getRemise($db,$order,$field, $siren = null, $raison = null, $dateDebut = null, $dateFin = null){
        $numWhere = 0;
        $req = "SELECT B_Client.NumSiren AS 'Siren',
                B_Client.RaisonSociale AS 'RaisonSociale',
                B_Client.Devise AS 'Devise',
                B_Remise.NumRemise AS 'NumeroRemise',
                B_Remise.DateTraitement AS 'DateTraitement',
                COUNT(B_Transaction.NumAutorisation) AS 'Nombretransaction' , 
                ABS(TableMontantPositif.montant - TableMontantNegatif.montant) AS 'MontantTotal',
                CHAR(44 - SIGN(TableMontantPositif.montant - TableMontantNegatif.montant)) AS 'Sens'
            FROM B_Remise
                LEFT JOIN B_Client ON B_Remise.NumSiren = B_Client.NumSiren
                LEFT JOIN B_Transaction ON B_Transaction.NumRemise = B_Remise.NumRemise
                LEFT JOIN TableMontantPositif ON TableMontantPositif.NumRemise = B_Remise.NumRemise
                LEFT JOIN TableMontantNegatif ON TableMontantNegatif.NumRemise = B_Remise.NumRemise
            ";

        if ($siren!==null){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.=" B_Client.NumSiren = :siren";
        }
        if ( $raison !==null){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.=" B_Client.RaisonSociale = :raison";
        }
        if ($dateDebut !== null){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.= " B_Remise.DateTraitement >= :dateDebut";
        }
        if ($dateFin !== null){
            if ($numWhere ==0){
                $req.=" WHERE";
            }else{
                $req.=" AND";
            }
            $numWhere++;
            $req.= " B_Remise.DateTraitement <= :dateFin";
        }

        $req.=" GROUP BY B_Remise.NumRemise";

        if ((($order=="ASC"||$order="DESC")&&($field=="Siren"||$field=="MontantTotal"))){
            $req.=" ORDER BY $field $order;";
        }

        $query = $db->prepare($req);
        if ($raison!==null ){
            $query->bindParam(":raison",$raison,PDO::PARAM_STR);
        }
        if ($siren!==null){
            $query->bindParam(":siren",$siren,PDO::PARAM_INT);
        }
        if ($raison!==null){
            $query->bindParam(":dateDebut",$dateDebut,PDO::PARAM_STR);
        }
        if ($raison!==null){
            $query->bindParam(":dateFin",$dateFin,PDO::PARAM_STR);
        }

        $query->execute();
        return $query;
    }

    /**
     * Permet d'avoir les détails sur une remise d'un client
     *
     * @param $db :  la connexion a la base de donnée
     * @param $remise : la remise à examiné
     * @return mixed
     */
    public static function getDetails($db, $remise){
        $req = "SELECT B_Client.NumSiren AS 'Siren',
            B_Transaction.DateVente AS 'DateVente',
            B_Transaction.NumCarte AS 'NumeroCarte',
            B_Transaction.Reseau AS 'Reseau',
            B_Transaction.Montant AS 'Montant',
            B_Transaction.NumAutorisation,
            B_Transaction.Devise,
            B_Transaction.Sens AS 'Sens'
            FROM B_Client
            NATURAL JOIN B_Remise
            NATURAL JOIN B_Transaction
            WHERE B_Remise.NumRemise = :remise";

            $query= $db->prepare($req);
            $query->bindParam(":remise",$remise,PDO::PARAM_INT);
            $query->execute();
            return $query;
    }

    /**
     * Permet d'afficher toutes les informations des clients de la base de donnée
     *
     * @param $db : la connexion a la base de donnée
     * @param $role : le rôle du client actuellement connecté
     * @return mixed|void
     */
    public static function getLogin($db, $role){
        if ($role == 'Admin'){

            $req = "SELECT L.Login AS Login, L.Role AS Role, C.NumSiren AS Siren, C.RaisonSociale AS 'Raison Sociale' FROM B_Login L LEFT JOIN B_Client C ON C.Login=L.Login";

            return $db->query($req);
        }
    }

    /**
     * Permet de supprimer un utilisateur de la base de donnée
     *
     * @param $db : la connexion a la base de donnée
     * @param $Login : le Login de la personne à supprimer
     * @return void
     */
    public static function deleteUser($db, $Login){
        $req2 = "DELETE FROM B_Login WHERE Login=$Login";
        $db->query($req2);
    }

    /**
     * permet d'ajouter un login
     * @param $db : la connexion à la base de donnée
     * @param $login : login du future utilisateur
     * @param $role : role du futur utilisateur
     * @param $mdp : mot de passe du futur utilisateur
     * @return void
     */
    public static function addLogin($db, $login, $role, $mdp){

        $query = "INSERT INTO B_Login (Login, MotDePasse, Role) VALUES ('".$login."', md5('".$mdp."'), '".$role."')";
        $db->query($query);
    }

    /**
     * Renvoie l'historique de la tresorerie sous forme de tableau
     *
     * @param $db : la connexion à la base de donnée
     * @param $id : l'id du compte dont on veut l'historique
     * @return mixed
     */
    public static function getTresorerieHistorique($db, $id){

        //création de la syntaxe de la requette
        $query = "SELECT
               SUM(B_Transaction.Montant) AS 'Montant',
               UNIX_TIMESTAMP(B_Remise.DateTraitement) AS 'Date'
        FROM B_Remise,
             B_Client,
             B_Transaction
        WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
          AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
        ";

        if($id !== null){
            $query.=" AND B_Client.NumSiren = :id";
        }
        $query.=" GROUP BY B_Remise.NumRemise ORDER BY Date ASC;";

        //securisation de la requette
        $query = $db->prepare($query);
        if($id !== null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

        $query->execute();
        $table = [];
        $somme = 0;
        while($row = $query->fetch(PDO::FETCH_ASSOC)){ 
            $somme += $row['Montant'];
            array_push($table,[$row['Date'],$somme]);
        }
        return $table;
    }

    /**
     * Renvoie la liste des libelles impayes et le nombre de fois qu'ils ont eut lieu
     *
     * @param $db : la connexion à la base de donnée
     * @param $id : l'id de l'entreprise à étudier (optionnel)
     * @return mixed
     */
    public static function getMotifImpaye($db,$id=null){

        //création de la syntax de la requette
        $query = "SELECT 
               COUNT(B_Impaye.NumDossier) AS Total,
               B_TypeImpaye.LibelleImpaye AS LibelleImpaye
                FROM B_Client 
                NATURAL JOIN B_Remise
                NATURAL JOIN B_Transaction
                NATURAL JOIN B_Impaye
                NATURAL JOIN B_TypeImpaye
        ";
        if( $id !== null){
            $query.=" WHERE B_Client.NumSiren = :id";
        }
        $query.=" GROUP BY LibelleImpaye";

        //securisation de la requette
        $query = $db->prepare($query);
        if( $id !== null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

        $table = [];
        $query->execute();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){ 
            array_push($table,[$row['LibelleImpaye'],$row['Total']]);
        }
        return $table;
    }

    /**
     * Renvoie le tableau représentant l'historique des impayés et des payés
     *
     * @param $db : la connexion à la base de donnée
     * @param $id : l'id de l'entreprise à étudier 
     * @param $dateDebut : la date de début de la période à étudier
     * @param $dateFin : la date de fin de la période à étudier
     * @return mixed
     */
    public static function getHistoriqueImpaye($db,$id,$dateDebut, $dateFin){
        $query = "SELECT UNIX_TIMESTAMP(CONCAT(YEAR(DateTraitement),'-',MONTH(DateTraitement),'-',01)) AS 'TimeStamp', SUM(Positif.MontantPositif) TotalPositif, SUM(Negatif.MontantNegatif) TotalNegatif FROM B_Remise JOIN (
            SELECT B_Remise.NumRemise NumRemise, SUM(B_Transaction.Montant) MontantPositif FROM B_Remise NATURAL JOIN B_Transaction WHERE B_Transaction.Sens='+' GROUP BY NumRemise) Positif 
            ON B_Remise.NumRemise = Positif.NumRemise JOIN(
            SELECT B_Remise.NumRemise NumRemise, SUM(B_Transaction.Montant) MontantNegatif FROM B_Remise NATURAL JOIN B_Transaction WHERE B_Transaction.Sens='-' GROUP BY NumRemise) Negatif 
            ON B_Remise.NumRemise = Negatif.NumRemise
            JOIN B_Client ON B_Client.NumSiren = B_Remise.NumSiren
            WHERE B_Client.NumSiren = :id AND B_Remise.DateTraitement BETWEEN :dateD AND :dateF GROUP BY TimeStamp
            ";

        $query = $db->prepare($query);
        $query->bindParam('id',$id,PDO::PARAM_INT);
        $query->bindParam('dateD',$dateDebut,PDO::PARAM_STR);
        $query->bindParam('dateF',$dateFin,PDO::PARAM_STR);
        $table = [];
        $query->execute();
        while($row = $query->fetch(PDO::FETCH_ASSOC)){ 
            array_push($table,[$row['TimeStamp'],$row['TotalPositif'],$row['TotalNegatif']]);
        }
        return $table;
    }

    /**
     * @param $db : la connexion a la bdd
     * @param $login : le login
     * @return mixed : le num siren correspondant au login
     */
    public static function getSirenOfCommerceant($db, $login){

        $commande = "SELECT NumSiren FROM B_Client NATURAL JOIN B_Login WHERE B_Login.Login = :Login; ";
        $query = $db->prepare($commande);
        $query->bindParam("Login",$login,PDO::PARAM_INT);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        return $row["NumSiren"];
    }

    /**
     * @param $db : la connexion a la bdd
     * @return mixed : le num siren correspondant au login
     */
    public static function getSommeImpaye($db){

        $query = "SELECT C.NumSiren, C.RaisonSociale, SUM(T.Montant) MontantImpaye FROM B_Impaye I 
        JOIN B_Transaction T ON I.NumAutorisation=T.NumAutorisation 
        JOIN B_Remise R ON R.NumRemise=T.NumRemise J
        OIN B_Client C ON C.NumSiren = R.NumSiren 
        GROUP By NumSiren";
        $query = $db->prepare($query);
        $query->execute();
        return $query;
    }


}