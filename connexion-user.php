<?php
include_once('Database.php');

if (isset($_POST['id']) && isset($_POST['psw'])){

    $db = Database::getPDO();

    if (getRole($_POST['id'], $_POST['psw'], $db)){// si le client existe

        // A VIRER C'EST PAS SECUR
        $_SESSION["id"] = $_POST['id'];
        $_SESSION["psw"] = $_POST['psw'];
        $_SESSION["role"] = getRole( $_POST['id'], $_POST['psw'],$db);

        header('Location: ../myaccount.php');

    }else{// sinon (erreur)... -> Il n'a pas de compte correspondant
        echo 'Login incorrect';
    }
}



?>

<form id="Form" method="POST" action="">
    <!-- Name input-->


    <input id="name" name='id' type="text" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="id">Identifiant</label>

    <input id="name" name="psw" type="password" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="psw">Mot de passe</label>


    <!-- Submit Button-->
    <button id="Button" type="submit">Connexion</button>
</form>