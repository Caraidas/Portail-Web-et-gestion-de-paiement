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
    public static function getTresorerie($db, $date=null, $id=null){

        //création de la syntaxe de la requette
        $query = "
        SELECT B_Client.NumSiren AS 'Siren',
               RaisonSociale AS 'RaisonSociale',
               B_Client.Devise AS 'Devise',
               COUNT(B_Transaction.Montant) AS 'NombreTransactions',
               SUM(B_Transaction.Montant) AS 'MontantTotal'
        FROM B_Remise,
             B_Client,
             B_Transaction
        WHERE B_Client.NumSiren LIKE B_Remise.NumSiren
          AND B_Remise.NumRemise LIKE B_Transaction.NumRemise
        ";

        if($date !== null){
            $query.=" AND B_Transaction.DateVente = :date";
        }
        if($id !== null){
            $query.=" AND B_Client.NumSiren = :id";
        }
        $query.=" GROUP BY Siren;";

        //securisation de la requette
        $query = $db->prepare($query);
        if($date !== null){
            $query->bindParam('date',$date,PDO::PARAM_STR);
        }
        if($id !== null){
            $query->bindParam('id',$id,PDO::PARAM_INT);
        }

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
    public static function getImpaye($db,$id=null){

        //création de la syntax de la requette
        $query = "
        SELECT B_Client.NumSiren,
               B_Remise.DateTraitement AS DateRemise,
               B_Transaction.DateVente AS DateVente,
               B_Transaction.NumCarte,
               B_Transaction.Reseau,
               B_Transaction.NumImpaye AS NumeroDossier,  
               B_Transaction.Devise,
               B_Transaction.Montant,
               B_Transaction.LibelleImpaye
        FROM B_Client 
             NATURAL JOIN B_Remise
             NATURAL JOIN B_Transaction
        WHERE B_Transaction.NumImpaye IS NOT NULL;
        ";

        if( $id !== null){
            $query.=" AND B_Client.NumSiren = :id";
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
    public static function getRemise($db, $siren = null, $raison = null, $dateDebut = null, $dateFin = null){
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
            B_Transaction.Sens AS 'Sens'
            FROM B_Client
            NATURAL JOIN B_Remise
            NATURAL JOIN B_Transaction
            WHERE B_Remise.NumRemise = :remise";

            $query= $db->prepare($req);
            $query->bindParam(":remise",$remise,PDO::PARAM_INT);
            $query->exec();
            return $query;
    }

    /**
     * Permet d'afficher toutes les informations des clients de la base de donnée
     *
     * @param $db : la connexion a la base de donnée
     * @param $role : le rôle du client actuellement connecté
     * @return mixed|void
     */
    public static function getClients($db, $role){
        if ($role == 'Admin'){

            $req = "SELECT NumSiren AS 'Siren',
                    RaisonSociale AS 'Raison',
                    Devise AS 'Devise',
                    NumeroCarte AS 'NumCarte',
                    Login AS 'Login'  FROM B_Client";

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
    public static function deleteUser($db, $Siren, $Login){
        $req2 = "DELETE FROM B_Login WHERE Login LIKE ".$Login;
        $db->query($req2);
    }
}