<?php
$msg = '';
if(isset($_POST['prenom'], $_POST['nom'], $_POST['login'], $_POST['password'], $_POST['confirm_password'], $_FILES['avatar']['name'])){
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
        //$photo = $_FILES['avatar']['name'];
         if($password != $confirm_password){
            $msg = 'Veuillez entrer deux mots de pass identiques';
         }
         else{
             $admin = array();

             $admin['prenom'] = $prenom;
             $admin['nom'] = $nom;
             $admin['login'] = $login;
             $admin['password'] = $password;
             $admin['confirm_password'] = $confirm_password;
            // $admin['photo'] = $photo;

             $_SESSION['login_admin_ajouter'] = $admin['login'];

               if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])){
                 $tailleMax = 2097152;
                 $extentionsValides = array('jpeg', 'jpg', 'gif', 'png');
                 if($_FILES['avatar']['size'] <= $tailleMax)
                 {
                     $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
                     if(in_array($extensionUpload, $extentionsValides)){
                         $_SESSION['extensionUpload'] = $extensionUpload;
                        $chemin = "membres/avatars/".$login.".".$_SESSION['extensionUpload'];
                        $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                        if($resultat){
                            $admin['photo'] = $login.".".$_SESSION['extensionUpload'];
                            $_SESSION['photo'] = $admin['photo'];
                        }
                        else{
                            $msg =  'Erreur durant l\'importation de l\'image';
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
             $js = file_get_contents('admin.json');
             $js= json_decode($js, true);

             $js_users = file_get_contents('users.json');
             $js_users = json_decode($js_users, true);

             if(!(isset($js))){
                 $js[] = $admin;
                 header('Location:home_admin.php');
             }
             else{
                 for ($i=0;$i<count($js_users);$i++){
                     $ver_users = in_array($admin['login'], $js_users[$i]);
                     if($ver_users == true){
                         break;
                     }
                 }

                 for ($i=0;$i<count($js);$i++){
                     $ver = in_array($admin['login'],$js[$i]);
                     if($ver == true){
                         break;
                     }
                 }
                 if(($ver == true) || ($ver_users==true)){
                    $msg = 'Login existe déja';
                 }
                 elseif(($ver == false) && ($ver_users==false)){
                     $js[] = $admin;
                    // header('Location:home_admin.php');
                 }

             }
             $js = json_encode($js);

             file_put_contents('admin.json', $js);
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
    <link rel="stylesheet" type="text/css" href="css/formulaire_creation_admin.css">
    <title>Document</title>
</head>
<body>
<div class="form_image">
  <div>
      <div class="inscrire">
          <h3>S'INSCRIRE</h3>
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
              <input class="choix_fichier" type="file" name="avatar" >
          </div>
          <button class="creer_compte" style="color: black">Créer compte</button>
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