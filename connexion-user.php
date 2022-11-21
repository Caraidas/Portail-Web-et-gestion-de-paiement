<?php
session_start();
include_once('Class/Database.php');

if (isset($_POST['id']) && isset($_POST['psw'])){

    $db = Database::getPDO();
    $role = "";
    if (($role = Database::getRole($_POST['id'], $_POST['psw'], $db))){// si le client existe

        // A VIRER C'EST PAS SECUR
        $_SESSION['id'] = $_POST['id'];
        $_SESSION['psw'] = $_POST['psw'];
        $_SESSION['role'] = $role;

        header('Location: page.php');

    } else {// sinon (erreur)... -> Il n'a pas de compte correspondant
        $_SESSION['warning-display'] = "";
        header('Location: login.php');
    }
}



?>