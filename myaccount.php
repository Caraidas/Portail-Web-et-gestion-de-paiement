<?php
require_once 'Class/Database.php';
require_once 'Class/SQLData.php';
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['psw']) && isset($_SESSION['role'])){

    echo ' votre login est : '.$_SESSION['id'].' et le mot de passe = '.$_SESSION['psw'].'<br> Votre role est : ' .$_SESSION['role'];
    if ($_SESSION['role'] == 'Admin'){
        $db = Database::getPDO();
        if (isset($_POST['Siren']) && isset($_POST['Login'])) {
            SQLData::getClients($db, $_SESSION['role']);
            echo "<br>l'utilisateur " . $_POST['Siren'] . "a bien été supprimé<br>";
        }


        $clients = SQLData::getClients($db,$_SESSION['role']);
        while($row = $clients->fetch(PDO::FETCH_ASSOC)){
            echo "<td>".$row['Siren']." </td>
            <td id=\"name\" name='id'>".$row['Raison']." </td>
            <td>".$row['Devise']." </td>
            <td>".$row['NumCarte']." </td>
            <td>".$row['Login']." </td>";
        }
        echo '<br> Pour supprimer un Client, son Siren ainsi que son Login si-dessous';
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
