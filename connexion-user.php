<?php
if (isset($_GET["error"]) && $_GET["error"]=='1'){
    echo 'Login incorrect';
}

?>

<form id="Form" method="POST" action="connexion-verif.php">
    <!-- Name input-->


    <input id="name" name='id' type="text" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="id">Identifiant</label>

    <input id="name" name="psw" type="password" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="psw">Mot de passe</label>


    <!-- Submit Button-->
    <button id="Button" type="submit">Connexion</button>
</form>