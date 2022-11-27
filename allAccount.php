<?php
    require_once 'Class/Database.php';
    require_once 'Class/SQLData.php';
    require_once  'Class/GenerateHTML.php';
    $db = Database::getPDO(); //database pour toute la page

    include 'header.php';
    if(isset($_SESSION['role'])){
        $role = $_SESSION['role'];
        switch($role){
            case 'Commerçant' :
                header("Location: page.php");
                break;
            case 'Admin' :
                break;
            case 'PO' :
                header("Location: page.php");
                break;
            default :
                header("Location: login.php");
                break;
        }

    }else{
        header("Location: login.php");
    }
    
?>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Table Style</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body> 
    
    <div class="mytabs">
        <input type="radio" name="mytabs" id="tab-tresorerie" checked>
        <label class="label-first label-style" for="tab-tresorerie">Comptes</label>
        <div class="tab">
        <p style="font-family:bold; font-size:25px"> Liste des comptes</p>
        <table class="table-fill">
                <thead>
                    <tr>
                        <th>Login</th>
                        <th>Role</th>
                        <th>Siren</th>
                        <th>Raison sociale</th>
                    </tr>
                </thead>
                <tbody class="table-hover">
                <?php
                   //L'ordre du tableau 
                   $clients = SQLData::getLogin($db,$_SESSION['role']);
                    while($row = $clients->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>
                        <td>".$row['Login']."</td>
                        <td>".$row['Role']."</td>";
                    if ($row['Siren']!==null &&$row['Raison Sociale']!==null){
                        echo"<td>".$row['Siren']."</td>
                        <td>".$row['Raison Sociale']."</td>";
                    }    else{
                        echo"<td style=\"font-size:25px;\">"."X"."</td>
                        <td style=\"font-size:25px;\">"."X"."</td>";
                    }
                        
                   echo "</tr>";

                    }

                ?>
                </tbody>
        </table>
        <p> Pour supprimer un Client, entrer son Login indiqué ci-dessus </p>
        <form id="Form" method="POST" action="">
             <input id="name" name="Login" type="text" placeholder="Entrez Login" data-sb-validations="required" />
             <label for="Login">Login :</label>
             <button id="Button" type="submit">Supprimer</button>
             <input type="checkbox" id="checkbox" name="checkbox" value="1">
             <label for="checkbox"> Avez-vous l'accord du Product Owner ?</label>
        </form>
        <?php 
            if (isset($_POST['Login']) && !empty($_POST['Login'])) {
                if(isset($_POST['checkbox'])){
                    SQLData::deleteUser($db, $_POST['Login']);
                    echo "L'utilisateur " . $_POST['Login'] . " a bien été supprimé<br>";
                }
                else
                    echo" <p style=\"color:red; font-size:20px;\">Il vous faut l'accord du Product Owner pour supprimer un utilisateur </p>";
            }
            ?>
        </div>

</div>
  </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</html>