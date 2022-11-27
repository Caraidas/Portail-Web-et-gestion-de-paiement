<?php
session_start();
if (isset($_GET['selected'])) {
    echo "salut";
    $selected = $_GET['selected'];
    if ($selected == "dates") {
        echo "1";
        $_SESSION['style_dates'] = "class='selected'";
        $_SESSION['style_four'] = "";
        $_SESSION['style_twelve'] = "";
    }

    if ($selected == "four_months") {
        echo "2";
        $_SESSION['style_dates'] = "";
        $_SESSION['style_four'] = "class='selected'";
        $_SESSION['style_twelve'] = "";
    }

    if ($selected == "twelve_months") {
        echo "3";
        $_SESSION['style_dates'] = "";
        $_SESSION['style_four'] = "";
        $_SESSION['style_twelve'] = "class='selected'";
    }
}

header('Location: graph_impayes.php');

