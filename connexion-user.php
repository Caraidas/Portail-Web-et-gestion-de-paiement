<?php
session_start();
include_once('Class/Database.php');

//initialisations des variables de sécurisation
if(!isset($_SESSION['tentative'])){
    $_SESSION['tentative'] = 1; //
    $_SESSION['timestamp_limite'] = time() + 60*10;
}

if (isset($_POST['id']) && isset($_POST['psw'])){
    $db = Database::getPDO();
    $role = "";
    //si le client existe et que le nombre de tentatives est inférieur à 3 ou la limite de temps avant un autre mot de passe est passée
    if ($role = Database::getRole($_POST['id'], $_POST['psw'], $db) && ($_SESSION['tentative'] < 3 || $_SESSION['timestamp_limite'] < time())){

        //initialisation des variables utilisables dans d'autres pages
        $_SESSION['tentative'] = 0;
        $_SESSION['id'] = $_POST['id']; //login
        $_SESSION['role'] = $role; //role
        $_SESSION['warning-display'] = "style='display: none'";

        header('Location: page.php');

    } else {// sinon (erreur)... -> Il n'a pas de compte correspondant
        $_SESSION['warning-display'] = "";
        $_SESSION['timestamp_limite'] = time() + 60*10; //reset le temps avant nouvel essai

        $_SESSION['tentative']++;

        header('Location: login.php');
    }
}



?>