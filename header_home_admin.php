<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/header_home.css">

</head>
<?php
require 'auth.php';
redirecting_login();
if(est_connecte()){
    echo '
<div class="entete">
<p class="titre_quizz">CRÉER ET PARAMÉTRER VOS QUIZZ</p>
<a href="logout.php"><button class="logout">Déconnexion</button></a>
</div>';
}
?>
<body>
</body>
</html>
