<?php

/**
 *Gère la connexion à la base de donnée et diverses informations relatives cette-dernière
 */
class Database{

    /**
     * Fonction qui génère l'objet PDO
     *
     * @return PDO|null
     */
    public static function getPDO(){
      $db_host='mysql:host=sqletud.u-pem.fr;';
      $db_user = 'lucas.leveque';
      $db_pass ='Lucas_2003';
      $db_name ='dbname=lucas.leveque_db';
      $pdo = null;

    try {
        if($pdo === null){
          $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8mb4');
          $cnx = new PDO($db_host.$db_name,$db_user,$db_pass, $options);
          $pdo = $cnx;
        }
        if($pdo === null){
          echo "L'objet est null";
      }
    }
    catch (PDOException $e) {
        echo "ERREUR : La connexion a échouée";

    }
    return $pdo;
  }

    /**
     * Fonctions qui récupère le role d'un utilisateur
     *
     * @param $id : l'id de l'utilisateur
     * @param $psw : son mot de passe
     * @param $conn : la connexion à la base de donnée
     * @return mixed
     */
    public static function getRole($id, $psw, $conn){
        $result = $conn->prepare("SELECT * FROM B_Login WHERE Login LIKE :id AND MotDePasse LIKE :psw");
        $result->bindParam('id',$id,PDO::PARAM_INT);
        $result->bindParam('psw',md5($psw),PDO::PARAM_STR);
        $result->execute();

        while($ligne = $result->fetch()){
            return $ligne['Role'];
        }
        return false;
  }
}

?>