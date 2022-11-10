<?php

class Database{


    private $db_host='mysql:host=sqletud.u-pem.fr;';
    private $db_user = 'lucas.leveque';
    private $db_pass ='Lucas_2003';
    private $db_name ='dbname=lucas.leveque_db';
    private $pdo;
    
    
  public function getPDO(){
    try {
        if($this->pdo === null){
          $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES utf8mb4');
          $cnx = new PDO($this->db_host.$this->db_name,$this->db_user,$this->db_pass, $options);
          $this->pdo = $cnx;
        }
        if($this->pdo === null){
          echo "L'objet est null";
      }
    }
    catch (PDOException $e) {
        echo "ERREUR : La connexion a échouée";

    }
    return $this->pdo;
  }


  public function query($statement){
    $quer = $this->getPDO()->query($statement);
    $data = $quer->fetchAll(PDO::FETCH_OBJ);
    return $data;
  }


    
}

?>