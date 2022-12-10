<?php
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Acceuil</title>
</head>
<body>
    <h2 class="title-admin">Bonjour ! Que souhaitez vous faire aujourd'hui?</h2>

    <div class="box-flex">
        <div class="box">
            <a href="addAccount.php"><img src="images/plus.png"></a>
            <strong><p>Créer un nouveau compte</p></strong>
        </div>
        <div class="box">
            <a href="delAccount.php"><img src="images/account.png"></a>
            <strong><p>Voir la liste des <br> comptes</p></strong>
        </div>
    </div>
</body>
</html>