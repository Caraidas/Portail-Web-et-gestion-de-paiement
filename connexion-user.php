<?php
session_start();
include_once('Class/Database.php');

if(!isset($_SESSION['tentative'])){
    $_SESSION['tentative'] = 1;
    $_SESSION['timestamp_limite'] = time() + 60*10;
}

if (isset($_POST['id']) && isset($_POST['psw'])){
    $db = Database::getPDO();
    $role = "";
    if ($role = Database::getRole($_POST['id'], $_POST['psw'], $db) && ($_SESSION['tentative'] < 3 || $_SESSION['timestamp_limite'] < time())){// si le client existe

        // A VIRER C'EST PAS SECUR
        $_SESSION['tentative'] = 0;
        $_SESSION['id'] = $_POST['id'];
        $_SESSION['role'] = $role;
        $_SESSION['warning-display'] = "style='display: none'";

        header('Location: page.php');

    } else {// sinon (erreur)... -> Il n'a pas de compte correspondant
        $_SESSION['warning-display'] = "";
        $_SESSION['timestamp_limite'] = time() + 60*10;

        $_SESSION['tentative']++;

        header('Location: login.php');
    }
}



?>