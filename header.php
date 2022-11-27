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
        .menu {    
            display:flex;
            flex-direction: row;
            padding:20px;  
            position:relative;
            height:50px;
            box-shadow: 0 4px 2px -2px gray;
            font-weight:600;
            font-size:32px;  
        }

        .menu a{
            margin-left: auto;
            margin-right: 0;
            text-decoration: none;
            color: blue
        }

        @media all and (max-width: 480px) {
        .menu {
            height:30px;
            font-weight:300;
            font-size:13px;    
        }
        }
    </style>
</head>
<body>
<?php
    echo "
        <div class='menu'>
            <div>FROG Bank</div>
            <a href='#'>$id</a>
        </div>
    ";
    ?>

</body>
</html>