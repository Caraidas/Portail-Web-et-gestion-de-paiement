<?php
require_once 'Class/Database.php';
require_once 'Class/SQLData.php';
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['role'])){

    echo 'Votre login est : '.$_SESSION['id'].'<br> Votre role est : ' .$_SESSION['role'];
    //vérification qu'il s'agit bien de l'admin
    if ($_SESSION['role'] == 'Admin'){
        $db = Database::getPDO();


        //vérification que les champs pour la suppression d'un login est bien entré

        if (isset($_POST['Login']) && !empty($_POST['Login'])) {
            if(isset($_POST['checkbox'])){
                SQLData::deleteUser($db, $_POST['Siren']);
                echo "<br>l'utilisateur " . $_POST['Siren'] . "a bien été supprimé<br>";
            }
            else
                echo"l'accord du PO n'a pas été accordé";
        }

        //vérification que les champs pour l'ajout d'un login sont bien entré
        if (isset($_POST["AjoutMDP"]) && ($_POST["AjoutMDP"] === $_POST["ConfirmationMDP"]) && !empty($_POST["AjoutMDP"])) {
            if (isset($_POST["AjoutLogin"]) && !empty($_POST["AjoutLogin"])){
                if(isset($_POST['checkbox'])){
                    SQLData::addLogin($db, $_POST["AjoutLogin"], $_POST["AjoutRole"], $_POST["AjoutMDP"]);
                    echo "<br>l'utilisateur " . $_POST['AjoutLogin'] . "a bien été ajouté<br>";
                }
               else
                   echo"l'accord du PO n'a pas été accordé";
            }
            else
                echo "champs login manquant";
        }


        $clients = SQLData::getLogin($db,$_SESSION['role']);
        while($row = $clients->fetch(PDO::FETCH_ASSOC)){
            echo "<br><tr><td>".$row['Login']." <br></td></tr>";
        }
        echo '<br> Pour supprimer un Client, entrez son Siren ainsi que son Login si-dessous';
        echo'<form id="Form" method="POST" action="">
             <input id="name" name="Login" type="text" placeholder="Entrez Login" data-sb-validations="required" />
             <label for="Login">Login</label>
             <button id="Button" type="submit">supprimer</button>';

        echo '<br> Pour ajouter un utilisateur, entrez son Login ainsi que son Role puis le mot de passe si-dessous';
        echo '<form id="Form" method="POST" action=""><br>
             <input id="name" name="AjoutLogin" type="text" placeholder="Entrez Login" data-sb-validations="required"/>
             <label for="AjoutLogin">AjoutLogin</label>
             <br>
             <select id="name" name="AjoutRole">
                <option valeur="Admin">Admin</option>
                <option valeur="PO">PO</option>
                <option valeur="Commerçant">Commerçant</option>
             </select>
             <label for="AjoutRole">Ajout Role</label>
             <br>
             <label for="AjoutMDP">mot de passe</label>
             <br>
             <input id="name" name="AjoutMDP" type="password" placeholder="Entrez mdp" data-sb-validations="required" />
             <br>
             <label for="ConfirmationMDP">confirmation mot de passe</label>
             <br>
             <input id="name" name="ConfirmationMDP" type="password" placeholder="Entrez mdp" data-sb-validations="required" />
             <br>
             <label for="addUser">Ajouter l\'utilisateur</label>
             <br>
             <button id="Button" type="submit">Créer</button>
             <input type="checkbox" id="checkbox" name="checkbox" value="1">
             <label for="checkbox"> accord du PO</label>';
    }


}else{// pas encore connecté
    header('Location: connexion-user.php');
}
