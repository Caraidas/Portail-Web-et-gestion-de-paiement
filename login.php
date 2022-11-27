<?php

session_start();
if (isset($_SESSION['warning-display'])) {
    $warning_display = $_SESSION['warning-display'];
} else {
    $warning_display = "style='display: none'";
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
        <div class='warning' $warning_display>
            <div class='exclam'></div>
            <p>
                <?php
                if(isset($_SESSION['tentative']) && $_SESSION['timestamp_limite']) {
                    $tentative = 3 - $_SESSION['tentative'];
                    if ($_SESSION['tentative'] < 3)
                        echo '<mark>il vous reste ' . $tentative . ' tentatives.</mark>';
                    elseif ($_SESSION['timestamp_limite'] > time())
                        echo 'attendez 10 min avant un nouvel essai.';
                }
                ?>
            </p>
        </div>
        <form method='POST' action='connexion-user.php' class='formulaire'>
            <div>
                <label for='id'>Login :</label>
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