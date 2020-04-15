<?php
include 'header.php';
if(isset($_POST['prenom'], $_POST['nom'], $_POST['login'], $_POST['password'], $_POST['confirm_password'])){
if (empty($_POST['prenom']) || empty($_POST['nom']) || empty($_POST['login']) ||
    empty($_POST['password']) || empty($_POST['confirm_password'])){
    if(empty($_POST['prenom']) || preg_match("#[^a-zA-Z]#", $_POST['prenom'])){
        $msg = 'Saisir un prenom correct';
    }
    elseif(empty($_POST['nom']) || preg_match("#[^a-zA-Z]#", $_POST['nom'])){
        $msg = 'Saisir un nom';
    }
    elseif(empty($_POST['login'])){
        $msg = 'Saisir un login';
    }
    elseif (empty($_POST['password'])){
        $msg = 'Saisir un mot de pass';
    }
    else{
        $msg = 'Veuillez confirmer votre mot de pass';
    }
}
else{
    $prenom =  $_POST['prenom'];
    $nom = $_POST['nom'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if($password != $confirm_password){
        echo 'Veuillez entrer deux mots de pass identiques';
    }else{
        $users = array();

        $users['prenom'] = $prenom;
        $users['nom'] = $nom;
        $users['login'] = $login;
        $users['password'] = $password;
        $users['confirm_password'] = $confirm_password;
        $users['score'] = array();


        if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])){
            $tailleMax = 2097152;
            $extentionsValides = array('jpeg', 'jpg', 'gif', 'png');
            if($_FILES['avatar']['size'] <= $tailleMax){
                $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                if(in_array($extensionUpload, $extentionsValides)){
                    $_SESSION['extensionUpload'] = $extensionUpload;
                    $chemin = "membres/avatars/".$users['login'].".".$_SESSION['extensionUpload'];
                    $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                    if($resultat){
                        $users['photo'] = $users['login'].".".$_SESSION['extensionUpload'];
                        $_SESSION['photo'] = $users['photo'];
                    }
                    else{
                        $msg = 'Erreur durant l\'importation de l\'image';
                    }
                }
                else{
                    $msg = 'Votre photo avatar doit etre au format jpeg, jpg, png ou gif';
                }
            }
            else{
                $msg = 'Votre avatar ne doit pas depasser 2Mo';
            }
        }

        $js = file_get_contents('users.json');
        $js= json_decode($js, true);

        $js_admin = file_get_contents('admin.json');
        $js_admin= json_decode($js_admin , true);
        $msg = '';
        if(!(isset($js))){
            $js[] = $users;
            $_SESSION['connecte_joueur'] = 1;
            header('Location:home_joueur.php');
        }
        else{
            for ($i=0;$i<count($js_admin);$i++){
                $ver_admin = in_array($users['login'], $js_admin[$i]);
                if($ver_admin == true){
                    break;
                }
            }
            for ($i=0;$i<count($js);$i++){
                $ver = in_array($users['login'],$js[$i]);
                if($ver == true){
                    break;
                }
            }

            if(($ver == true) || ($ver_admin==true)){
                $msg = '<h3 style="color: red">Login existe déja</h3>';
            }
            elseif(($ver == false) && ($ver_admin==false)){
                $js[] = $users;
                $_SESSION['connecte_joueur'] = 1;
                header('Location:home_joueur.php');
            }
        }
        $js = json_encode($js);
        file_put_contents('users.json', $js);
    }
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
    <title></title>
</head>
<body>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/inscription.css">
    <title>Inscription</title>
</head>
<body>
<div class="form_image">
    <div>
        <div class="inscrire">
            <h1>S'INSCRIRE</h1>
            <span>Pour proposer des quiz</span>
        </div>
        <hr style="">
        <div class="formulaire">
            <?php if (isset($msg)){echo "<h4 style='color: red'>$msg</h4>";} ?>
            <form action="#" method="post" enctype="multipart/form-data">
                <label >Prénom</label><br>
                <input class="inpt" type="text" name="prenom" value="<?php if(isset($prenom)){echo $prenom;} ?>"><br>
                <label>Nom</label><br>
                <input class="inpt" type="text" name="nom" value="<?php if(isset($nom)){echo $nom;} ?>"><br>
                <label>Login</label><br>
                <input class="inpt" type="text" name="login" value="<?php if(isset($login)){echo $login;} ?>"><br>
                <label>Password</label><br>
                <input class="inpt" type="password" name="password" value="<?php if(isset($password)){echo $password;} ?>"><br>
                <label>Confirm password</label><br>
                <input class="inpt" type="password" name="confirm_password" value="<?php if(isset($confirm_password)){echo $confirm_password;} ?>"><br>
                <div class="avatar">
                    Avatar
                    <a href="#"><input class="choix_fichier" type="file" name="avatar" value="Choisir un fichier"></a>
                </div>
                <button class="creer_compte" style="color: white">Créer compte</button>
            </form>
        </div>
    </div>
    <?php
    echo '<div class="photoavatar" style="">';
    if(isset($login)){
        echo '<img src="membres/avatars/'.$login.".".$_SESSION['extensionUpload'].'">';
    }
    echo '<span style="margin-left: 25%">Avatar admin</span> </div>';
    ?>
</div>
</body>
</html>
</body>
</html>