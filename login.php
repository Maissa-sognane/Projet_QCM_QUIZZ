<?php
session_start();
include 'header.php';
if(isset($_POST['login'], $_POST['password'])){
    if (!(empty($_POST['login'])) && !(empty($_POST['password']))) {
        require 'auth.php';
        $msg = '';
        $login = $_POST['login'];
        $password = $_POST['password'];

        $json = file_get_contents('admin.json');
        $parsed_json = json_decode($json);

        $_SESSION['parsed_json'] = $parsed_json;

        $json_use = file_get_contents('users.json');
        $parse_joueur = json_decode($json_use);



//Se connecter en tant que admin

        for ($i = 0; $i < count($parsed_json); $i++){
            if(isset($parsed_json[$i]->{'login'}, $parsed_json[$i]->{'password'}, $parsed_json[$i]->{'prenom'},
                $parsed_json[$i]->{'nom'})){
                $login_json = $parsed_json[$i]->{'login'};
                $password_json = $parsed_json[$i]->{'password'};
                $prenom = $parsed_json[$i]->{'prenom'};
                $nom = $parsed_json[$i]->{'nom'};
                if(isset($parsed_json[$i]->{'photo'})){
                    $photo = $parsed_json[$i]->{'photo'};
                }
            }
            if (($login == $login_json) && ($password == $password_json)) {
                $_SESSION['connecte'] = 1;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['login'] = $login_json;
                $_SESSION['photo'] = $photo;
                $_SESSION['nom'] = $nom;
                header('Location: home_admin.php');
            }
            if (($login == $login_json) && ($password != $password_json)){
                    $msg = 'Password invalid';
                }
            if (($login != $login_json) && ($password == $password_json)){
                    $msg ='Login invalid';
                }
            if(($login != $login_json) && ($password != $password_json)){
                    $msg = 'Login et password invalid';
                }
        }


//CONNEXION EN TANTQUE JOUEUR
        for ($i = 0; $i < count($parse_joueur); $i++){
            if(isset($parse_joueur[$i]->{'login'}, $parse_joueur[$i]->{'password'}, $parse_joueur[$i]->{'prenom'},
                $parse_joueur[$i]->{'nom'})){
                $login_json = $parse_joueur[$i]->{'login'};
                $password_json = $parse_joueur[$i]->{'password'};
                $prenom = $parse_joueur[$i]->{'prenom'};
                $nom = $parse_joueur[$i]->{'nom'};
            }
            if(isset($parse_joueur[$i]->{'photo'})){
                $photo = $parse_joueur[$i]->{'photo'};
            }
            if(isset($parse_joueur[$i]->{'score'})){
                $score = $parse_joueur[$i]->{'score'};
            }
            if(isset($password_json[$i]->{'photo'})){
                $photo = $parse_joueur[$i]->{'photo'};
            }
            if (($login == $login_json) && ($password == $password_json)) {
                $_SESSION['connecte_joueur'] = 1;
                $_SESSION['prenom_joueur'] = $prenom;
                $_SESSION['login_joueur'] = $login_json;
                $_SESSION['photo_joueur'] = $photo;
                $_SESSION['nom_joueur'] = $nom;
                $_SESSION['tab_score'] = $score;
                header('Location: home_joueur.php');
            }
            elseif (($login == $login_json) && ($password != $password_json)) {
                    $msg ='Password invalid';
                }
            if (($login != $login_json) && ($password == $password_json)){
                    $msg = 'Login invalid';
                }
        }


/*
       //Fonction pour se connecter
               function logging($parse, $log, $pass,$prenom,$nom,$photo,$lien,$login,$score){
                   for ($i = 0; $i < count($parse); $i++){
                           $login_json = $parse[$i]->{'login'};
                           $password_json = $parse[$i]->{'password'};
                       if (($log == $login_json) && ($pass == $password_json)) {
                           session_start();
                           $_SESSION['connecte'] = 1;
                           if(isset($prenom,$nom,$photo)){
                               $login = $log;
                               $prenom = $parse[$i]->{'prenom'};
                               $nom = $parse[$i]->{'nom'};
                               $photo = $parse[$i]->{'photo'};
                           }

                           header($lien);
                       }  elseif (($log == $login_json) && ($pass != $password_json)){
                           echo '<h4>Password invalid</h4>';
                       } elseif (($log != $login_json) && ($pass == $password_json)) {

                           echo '<h4>Login invalid</h4>';
                       } else {
                          echo '<h4>Login et password invalid</h4>';
                       }
                   }
               }

               //se connecter en tant que admin
              logging($parsed_json, $login, $password,$_SESSION['prenom'],$_SESSION['nom'],$_SESSION['photo'], 'Location: home_admin.php',$_SESSION['login'],null);

               //se connecter en tant que user
               //logging($parse_joueur, $login, $password, 'Location: home_joueur.php');
        */
    }
    else{
        $msg = 'Champs Obligatoires';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<?php if(isset($msg)){echo "<h2 style='color: red; text-align: center'>$msg</h2>"; }?>
<div class="formulaire">
    <form action="#" method="post">
    <div class="titre_formulaire"><p class="log_form">Login form</p></div>
        <input class="input" type="text" name="login" placeholder="Login" value="<?php
        if(isset($_POST['login'])){echo $_POST['login'];}
        ?>">
        <img class="img_log" src="Images/Icônes/ic-login.png" alt="">
        <br>
        <input class="input" type="password" name="password" placeholder="Password" value="<?php
        if(isset($_POST['password'])){echo $_POST['password'];}
        ?>">
        <img class="img_log"   src="Images/Icônes/icone-password.png" alt="" style="height: 20px"><br>
        <input class="button" type="submit" value="Connexion">
        <a href="inscription.php">S'inscrire pour jouer?</a>

    </form>
</div>
</body>
</html>