<?php

session_start();
if (isset($_SESSION['warning-display'])) {
    $warning_display = $_SESSION['warning-display'];
} else {
    $warning_display = "style='display: none;'";
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='style-connexion.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <title>Frog Bank - Veuillez vous connecter</title>
</head>

<body>

    <div class='container'>
        <h1>Frog Bank.</h1>
        <?php
        echo "
        <div class='warning' $warning_display>";
        ?>
            <div class='exclam'></div>
                <?php
                if(isset($_SESSION['tentative']) && $_SESSION['timestamp_limite']) {

                    $temps_restant = $_SESSION['timestamp_limite'] - time();
                    if ($temps_restant < 0)
                        $temps_restant = "Vous pouvez réessayer.";
                    $tentative = 3 - $_SESSION['tentative'];
                    if ($_SESSION['tentative'] == 2)
                        echo '<p class="red-text">Mot de passe ou login incorrect. Attention ! C\'est votre dernière tentative !</p>';
                    elseif ($_SESSION['tentative'] < 3)
                        echo '<p>Mot de passe ou login incorrect. Il vous reste ' . $tentative . ' tentatives.</p>';


                    elseif ($_SESSION['timestamp_limite'] > time())
                        echo '<p>Vous avez entré un mot de passe érroné trop de fois, veuillez attendre '.$temps_restant.' secondes avant de réessayer ...</p>';
                }
                ?>
        </div>
        <form method='POST' action='connexion-user.php' class='formulaire'>
            <div>
                <label for='id'>Identifiant :</label>
                <input type='text' class='input' name='id'>
            </div>

            <div>
                <label for='psw' class="pswrd">Mot de passe :</label>
                <div>
                    <input type="password" name="psw" autocomplete="current-password" required="" id="id_password" class="input">
                    <i class="far fa-eye" id="togglePassword" style="position: absolute; margin: 19px -40px; cursor: pointer; color: var(--bleu);"></i>
                </div>
                <script>
                    const togglePassword = document.querySelector('#togglePassword');
                    const password = document.querySelector('#id_password');

                    togglePassword.addEventListener('click', function(e) {
                        // toggle the type attribute
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        password.setAttribute('type', type);
                        // toggle the eye slash icon
                        this.classList.toggle('fa-eye-slash');
                    });
                </script>
            </div>

            <input type='submit' value='Se connecter' class='submit'>
            <a href='destroy.php'>destroy session</a>
        </form>
    </div>
</body>

</html>