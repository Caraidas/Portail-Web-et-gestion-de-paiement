<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['psw']) && isset($_SESSION['role'])){
    echo ' votre login est : '.$_SESSION['id'].' et le mot de passe = '.$_SESSION['psw'].'<br> Votre role est : ' .$_SESSION['role'];



}else{// pas encore connecté
    header('Location: connexion-user.php');
}
