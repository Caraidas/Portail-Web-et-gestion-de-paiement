<?php

session_start();
if (isset($_SESSION['warning-display'])) {
    $warning_display = $_SESSION['warning-display'];
} else {
    $warning_display = "style='display: none'";
}

echo "
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='style-connexion.css'>
    <title>Frog Bank - Veuillez vous connecter</title>
</head>

<body>
    
    <div class='container'>
        <h1>Frog Bank.</h1>
        <div class='warning' $warning_display>
            <div class='exclam'></div>
            <p>
                Votre identifiant ou mot de passe est incorrect, réessayez.
            </p>
        </div>
        <form method='POST' action='connexion-user.php' class='formulaire'>
            <div>
                <label for='id'>Login :</label>
                <input type='text' class='input' name='id'>
            </div>

            <div>
                <label for='psw'>Mot de passe :</label>
                <input type='password' class='input' name='psw'>
            </div>

            <input type='submit' value='Se connecter' class='submit'>
            <a href='destroy.php'>destroy session</a>
        </form>
    </div>
</body>
</html>";
?>