<?php
require_once 'Class/Database.php';
require_once 'Class/SQLData.php';
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['role'])){

    echo 'Votre login est : '.$_SESSION['id'].'<br> Votre role est : ' .$_SESSION['role'];
    if ($_SESSION['role'] == 'Admin'){
        $db = Database::getPDO();
        if (isset($_POST['Siren']) && isset($_POST['Login'])) {
            SQLData::deleteUser($db, $_POST['Siren'], $_POST['Login']);
            echo "<br>l'utilisateur " . $_POST['Siren'] . "a bien été supprimé<br>";
        }


        $clients = SQLData::getClients($db,$_SESSION['role']);
        while($row = $clients->fetch(PDO::FETCH_ASSOC)){
            echo "<br><tr><td>".$row['Siren']." <br></td>
            <td>".$row['Raison']." <br> </td>
            <td>".$row['Devise']." <br> </td>
            <td>".$row['NumCarte']." <br> </td>
            <td>".$row['Login']." <br> </td></tr>";
        }
        echo '<br> Pour supprimer un Client, entrez son Siren ainsi que son Login si-dessous';
        echo'<form id="Form" method="POST" action="">
             <input id="name" name="Siren" type="text" placeholder="Entrez Siren" data-sb-validations="required" />
             <label for="Siren">Siren</label>
             <input id="name" name="Login" type="text" placeholder="Entrez Login" data-sb-validations="required" />
             <label for="Login">Siren</label>
             <button id="Button" type="submit">supprimer</button>';
    }


}else{// pas encore connecté
    header('Location: connexion-user.php');
}
