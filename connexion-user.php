<?php
if (isset($_GET["error"]) && $_GET["error"]=='1'){
    echo '<div class="alert alert-danger" role="alert" style="width:100%;">
                Login incorrect
              </div>';
}

?>

<form id="Form" method="POST" action="php/clientconnect.php">
    <!-- Name input-->


    <input id="name" name='id' type="text" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="id">Identifiant</label>

    <input id="name" name="psw" type="password" placeholder="Enter your name..." data-sb-validations="required" />
    <label for="psw">Mot de passe</label>


    <!-- Submit Button-->
    <button id="Button" type="submit">Connexion</button>
</form>