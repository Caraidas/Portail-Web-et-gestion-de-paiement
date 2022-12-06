<?php
session_start();
if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
} else {
    header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        :root {
            --gradient : linear-gradient(45deg, rgba(58,109,208,1) 0%, rgba(138,115,226,1) 100%);
            --bleu : #3F5071
        }
        
        .menu {    
            display:flex;
            flex-direction: row;
            align-items: center;
            padding:20px;  
            position:relative;
            box-shadow: 0 4px 2px -2px gray;
            font-weight:600;
            font-size:32px;  
        }

        .menu a{
            margin-left: auto;
            margin-right: 0;
            text-decoration: none;
            background: var(--gradient);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo {
            font-weight:600;
            font-size:32px;
            background: var(--gradient);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media all and (max-width: 480px) {
        .menu {
            font-weight:300;
            font-size: 13px;
        }

        .logo {
            font-size: 1.25em;
        }
        }
    </style>
</head>
<body>
<?php
    echo "
        <div class='menu'>
            <div class='logo'><a href='index.php'>FROG Bank</a></div>
            <a href='destroy.php' style='font-size: 0.75em;'>$id (déconnexion)</a>
        </div>
    ";
    ?>

</body>
</html>