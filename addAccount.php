<?php
    require_once 'Class/Database.php';
    require_once 'Class/SQLData.php';

    include 'header.php';
    if(isset($_SESSION['role'])){
        $role = $_SESSION['role'];
        switch($role){
            case 'Commerçant' :
                header('Location: index.php');
                break;
            case 'Admin' :
                break;
            case 'PO' :
                header('Location: index.php');
                break;
            default :
                header('Location: login.php');
        }

    }else{
        header('Location: login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/style-connexion.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <title>Ajouter un compte</title>
</head>
<body>

    <div class="retour">
        <a href="index.php"><img src="images/fleche-gauche.png"></a>
        <p>Retour</p>
    </div>

    <?php
        echo '<div class="container" style="justify-content:normal !important;"><h1 id="title">Ajouter un utilisateur</h1>';
        if (isset($_SESSION['id']) && isset($_SESSION['role'])){
            //vérification qu'il s'agit bien de l'admin
            if ($_SESSION['role'] == 'Admin'){
                $db = Database::getPDO();

                //vérification que les champs pour l'ajout d'un login sont bien entré
                if (isset($_POST["AjoutMDP"]) && ($_POST["AjoutMDP"] === $_POST["ConfirmationMDP"]) && !empty($_POST["AjoutMDP"])) {
                    if (isset($_POST["AjoutLogin"]) && !empty($_POST["AjoutLogin"])){
                        if(isset($_POST['checkbox'])){
                            SQLData::addLogin($db, $_POST["AjoutLogin"], $_POST["AjoutRole"], $_POST["AjoutMDP"]);
                            echo "<div class='success'><p>L'utilisateur " . $_POST['AjoutLogin'] . " a bien été ajouté</p></div>";
                        }
                    }
                }


                $clients = SQLData::getLogin($db,$_SESSION['role']);

                
                echo '
                    <form class="center" id="Form" method="POST" action="addAccount.php">
                    <div>
                        <label for="AjoutLogin">Identifiant</label>
                        <input class="input" id="identifiant" name="AjoutLogin" type="text" placeholder="Entrez Login" required/>
                    </div>
                    <div>
                        <label for="AjoutRole">Rôle</label>
                        <select class="input" id="role" name="AjoutRole">
                            <option valeur="Admin">Admin</option>
                            <option valeur="PO">Product Owner</option>
                            <option valeur="Commerçant">Commerçant</option>
                        </select>
                    </div>

                    <div>
                        <label class="pswrd" for="AjoutMDP">Mot de passe</label>
                        <div>
                            <input class="input" id="id_password_first" name="AjoutMDP" type="password" placeholder="Entrez le mot de passe" required />
                            <i class="far fa-eye" id="togglePasswordFirst" style="position: absolute; margin: 19px -40px; cursor: pointer; color: var(--bleu);"></i>
                        </div>
                        <script>
                            const togglePassword = document.querySelector("#togglePasswordFirst");
                            const password = document.querySelector("#id_password_first");

                            togglePassword.addEventListener("click", function(e) {
                                // toggle the type attribute
                                const type = password.getAttribute("type") === "password" ? "text" : "password";
                                password.setAttribute("type", type);
                                // toggle the eye slash icon
                                this.classList.toggle("fa-eye-slash");
                            });
                        </script>
                    </div>
                    <div>
                        <label class="pswrd" for="ConfirmationMDP">Confirmation</label>
                        <div>
                            <input class="input" id="id_password_second" name="ConfirmationMDP" type="password" placeholder="Confirmer le mot de passe" required/>
                            <i class="far fa-eye" id="togglePasswordSecond" style="position: absolute; margin: 19px -40px; cursor: pointer; color: var(--bleu);"></i>
                        </div>
                        <script>
                            const togglePassword2 = document.querySelector("#togglePasswordSecond");
                            const password2 = document.querySelector("#id_password_second");

                            togglePassword2.addEventListener("click", function(e) {
                                // toggle the type attribute
                                const type2 = password2.getAttribute("type") === "password" ? "text" : "password";
                                password2.setAttribute("type", type2);
                                // toggle the eye slash icon
                                this.classList.toggle("fa-eye-slash");
                            });
                        </script>
                    </div>
                    <div>
                        <label style="color:black; font-size:14px;padding-left:0px" for="checkbox"> accord du PO</label>
                        <input type="checkbox" id="checkbox" name="checkbox" value="1" required>
                    </div>
                    <button class="submit" id="Button" type="submit">Créer</button></form></div>';
            }


        }else{// pas encore connecté
            header('Location: connexion-user.php');
        }
    ?>

</body>
</html>