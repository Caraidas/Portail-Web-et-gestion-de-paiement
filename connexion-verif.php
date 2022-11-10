<?php
$db = null;
include('database.php');

session_start();

function getRole($id, $psw, $conn){
    $result = $conn->prepare("SELECT * FROM B_Login WHERE id LIKE :id AND MotDePasse LIKE :psw");
    $result->bindParam('id',$id,PDO::PARAM_INT);
    $result->bindParam('psw',md5($psw),PDO::PARAM_STR);
    $result->execute();

    while($ligne = $result->fetch()){
        return $ligne['Role'];
    }
    return false;
}

if (isset($_POST['id']) && isset($_POST['psw'])){

    if (getRole($_POST['id'], $_POST['psw'], $db)){// si le client existe

        $_SESSION["id"] = $_POST['id'];
        $_SESSION["psw"] = $_POST['psw'];
        $_SESSION["role"] = getRole( $_POST['id'], $_POST['psw'],$db);

        header('Location: ../myaccount.php');

    }else{// sinon (erreur)... -> Il n'a pas de compte correspondant
        header('Location: ../connexion.php?error=1');
    }
}


?>