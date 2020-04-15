<?php
function est_connecte (){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    return !empty($_SESSION['connecte']);
}

function est_connecte_joueur (){
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }
    return !empty($_SESSION['connecte_joueur']);
}


function  redirecting_login(){
    if(!(est_connecte())){
        header('location: login.php');
        exit();
    }
}
function redirection(){
    if(!(est_connecte_joueur())){
        header('location: login.php');
        exit();
    }
}
?>
