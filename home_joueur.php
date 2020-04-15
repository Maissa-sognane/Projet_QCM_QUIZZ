<?php
session_start();
?>
<!doctype html>
<?php
include 'header.php';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/home_joueur.css">
    <title>Home_Joueur</title>
</head>
<body>
<?php
require 'auth.php';
redirection();
if(est_connecte_joueur()){
    echo '<div class="entete">';
echo '<div class="image">';
$json = file_get_contents('users.json');
$parsed_json = json_decode($json);
if(isset($_SESSION['photo_joueur'])){
$photo = $_SESSION['photo_joueur'];
echo '<img src="membres/avatars/'.$_SESSION['photo_joueur'].'">';

$users = array();
if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])){
    $tailleMax = 2097152;
    $extentionsValides = array('jpeg', 'jpg', 'gif', 'png');
    if($_FILES['avatar']['size'] <= $tailleMax){
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if(in_array($extensionUpload, $extentionsValides)){
            $_SESSION['extensionUpload'] = $extensionUpload;
            $chemin = "membres/avatars/".$_SESSION['login_joueur'].".".$_SESSION['extensionUpload'];
            $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
            if($resultat){
                $users['photo'] = $_SESSION['login_joueur'].".".$_SESSION['extensionUpload'];
                $_SESSION['photo_joueur'] = $users['photo'];
            }
            else{
                echo 'Erreur durant l\'importation de l\'image';
            }
        }
        else{
            echo 'Votre photo avatar doit etre au format jpeg, jpg, png ou gif';
        }
    }
    else{
        echo 'Votre avatar ne doit pas depasser 2Mo';
    }

    $js = file_get_contents('users.json');

    $js= json_decode($js, true);

    $js[] = $users;


    $js = json_encode($js);

    file_put_contents('users.json', $js);
}
?>
<form action="#" method="post" enctype="multipart/form-data">
    <div class="avatar">
        <input class="choixfichier" type="file" name="avatar" value="changer photo" onChange="this.form.submit();">
        <!--<input  style="color: white;
    background: #026c7b;
    border-color: #026c7b;
    width: 107px;
    height: 40px;" value="changer" type="submit">-->
    </div>
</form>
</div>
<div class="pseudo">
    <?php
    if(isset($_SESSION['prenom_joueur'], $_SESSION['nom_joueur'])){
        echo  "<span class='prenom'>";
        echo $_SESSION['prenom_joueur'];
        echo '</span> ';
        echo  "<span class='nom'>";
        echo $_SESSION['nom_joueur'];
        echo '</span>';
    }
    }
echo '</div>';
echo '<p class="titre_quizz">BIENVENUE SUR LA PLATEFORME DE JEU QUIZZ<br>
JOUER ET TESTER VOTRE NIVEAU DE CULTURE GÉNÉRALE
</p>';
echo '<a href="logout_joueur.php"><button class="logout">Déconnexion</button></a>
</div>';
}
    $questions = file_get_contents('questions.json');
    $question_json = json_decode($questions);
   shuffle($question_json);
    //$question_json[] = $question_json;
?>
<div class="centre">
    <div class="para_gauche">
        <div class="reponse">
            <div class="question">
                <?php

                echo '<form action="';
                echo '" method="post" id="myForm">';
                $question_afficher = '';
                $n=1;
                $_SESSION['n'] = 1;
                    echo '<h1 style="text-align: center;text-decoration: underline;">Question '.$_SESSION['n'].'/' . $_SESSION['T'][0] . '</h1>';

                $size = count($question_json);
               // var_dump($question_json);
                $_SESSION['nbrpts'] = 0;
               $question_json_key = array_rand($question_json,$_SESSION['T'][0]);
             // echo count($question_json_key);
             // var_dump($question_json_key);
                //var_dump($question_json[$question_json_key[1]]);
                //var_dump($question_json[$question_json_key[3]]);

                if (isset($_GET['pages'])) {
                    $_GET['pages'] = intval($_GET['pages']);
                    $currentPage = $_GET['pages'];
                } else {
                    $currentPage = 1;
                }

                $pos = rand(0, $size);
                $count = $_SESSION['T'][0];
                $perPage = 1;
                $pages = ceil($count / $perPage);
                $offeset = $perPage * ($currentPage - 1);
                $question_json = array_slice($question_json, $offeset, $perPage);



                for ($i=0; $i<count($question_json); $i++) {
                    if (is_array($question_json[$i])) {
                        $affich_question = $question_json[$i][0]->{'questions'};
                        echo "<span class='afficher_question'><h4 style='text-align: center'>$affich_question</h4></span><br/>";
                    } else {
                        if ($question_json[$i]) {
                            $affich_question =  $question_json[$i]->{'questions'};
                            echo "<span class='afficher_question'><h4 style='text-align: center'>$affich_question</h4></span><br/>";
                        }
                    }
                }
                ?>
            </div>
                <div class="scoreTag">
                    <?php
                    for ($i=0; $i<count($question_json); $i++){
                    if (is_array($question_json[$i])) {
                        echo '<span class="score_question">' . $question_json[$i][0]->{'score'} . ' pts</span><br>';
                    }
                    else {
                        if ($question_json[$i]) {
                            echo '<span class="score_question">' .  $question_json[$i]->{'score'} . ' pts</span><br>';
                        }
                    }
                    }
                    ?>
                </div>
            <div class="reponse_question">
            <?php
          //  $reponse = '';
            $users = array();
            if(!isset($_SESSION['rep']) && !empty($_SESSION['rep'])) {
                $_SESSION['rep'] = array(
                        'score'=>0
                );
            }
                for ($i=0; $i<count($question_json); $i++){
                    if (is_array($question_json[$i])){
                        $_SESSION['score'] = $question_json[$i][0]->{'score'};
                        $reponse = '';
                        if(is_object($question_json[$i][0]->{'reponse'})){
                            if(sizeof($question_json[$i][0]->{'reponse'}->{'bonne_reponse'}) == 1){
                                echo'<label class="container">'.$question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[0].
                                    '<input id= "reponse_bonne0"  value="'.$question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[0].
                                    '" name="reponse_bonne0" type="checkbox"><span class="checkmark"></span></label><br>';

                                if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                    if(isset($_POST["reponse_bonne0"])){
                                        if(isset($question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[0])){
                                            if ($question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[0] == $_POST["reponse_bonne0"]){
                                                $reponse = 1;
                                            }
                                        }
                                    }
                                }

                            }
                            if(sizeof($question_json[$i][0]->{'reponse'}->{'bonne_reponse'}) > 1){
                                for ($j=0; $j<sizeof($question_json[$i][0]->{'reponse'}->{'bonne_reponse'});$j++){
                                    echo '<label class="container">'.$question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[$j].
                                        '<input id= "reponse_bonne'.$j.'"  value="'.$question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[$j].'" name="reponse_bonne'.$j.
                                        '" type="checkbox"><span class="checkmark"></span></label>';
                                    if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                        if(isset($_POST["reponse_bonne$j"], $question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[$j])){
                                            if ($question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[$j] == $_POST["reponse_bonne$j"]){
                                                $reponse = 1;
                                            }
                                        }
                                    }
                                }

                            }

                            for ($k=0;$k<count($question_json[$i][0]->{'reponse'}->{'fausse_reponse'});$k++){
                                echo'<label class="container">'.$question_json[$i][0]->{'reponse'}->{'fausse_reponse'}[$k].
                                    '<input name="reponse'.$k.'" type="checkbox"><span class="checkmark"></span></label>';
                                if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                    if(isset($_POST["reponse$k"])){
                                        if(isset($question_json[$i][0]->{'reponse'}->{'fausse_reponse'}[$k])){
                                            if ($question_json[$i][0]->{'reponse'}->{'fausse_reponse'}[$k] == $_POST["reponse$k"]){
                                                $reponse = 2;
                                            }
                                        }
                                    }
                                }
                            }

                            if($reponse == 1){
                                $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + $_SESSION['score'];
                            }

                        }
                        else{
                            //value="'.$question_json[$i][0]->{'reponse'}.'"
                            //onChange="this.form.submit();"
                            echo '<span class="affich_reponse"><input  placeholder="REPONSE" id="reponse_joueur'.$i.'" name="reponse_texte'.$i.
                                '" style="width: 49%; height: 26px;" value="';
                            echo '"/></span><br/>';

                            if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                if (isset($_POST["reponse_texte$i"])){
                                    if(isset($question_json[$i][0]->{'reponse'})) {
                                        if ($question_json[$i][0]->{'reponse'} == $_POST["reponse_texte$i"]) {
                                            $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + $_SESSION['score'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else{
                        if ($question_json[$i]){
                            $_SESSION['score'] = $question_json[$i]->{'score'};
                            if (is_object($question_json[$i]->{'reponse'})) {
                                $reponse = '';
                               if(sizeof($question_json[$i]->{'reponse'}->{'bonne_reponse'}) == 1){
                                   echo'<label class="container">'.$question_json[$i]->{'reponse'}->{'bonne_reponse'}[0].
                                       '<input id= "reponse_bonne0"  value="'.$question_json[$i]->{'reponse'}->{'bonne_reponse'}[0].
                                       '" name="reponse_bonne0" type="checkbox"><span class="checkmark"></span></label><br>';
                                   if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                       if(isset($_POST["reponse_bonne0"])){
                                           if(isset($question_json[$i]->{'reponse'}->{'bonne_reponse'}[0])){
                                               if ($question_json[$i]->{'reponse'}->{'bonne_reponse'}[0] == $_POST["reponse_bonne0"]){
                                                   $reponse = 1;
                                               }
                                           }
                                       }
                                   }
                               }
                               if(sizeof($question_json[$i]->{'reponse'}->{'bonne_reponse'}) > 1){
                                   for ($j=0; $j<sizeof($question_json[$i]->{'reponse'}->{'bonne_reponse'});$j++){
                                       echo'<label class="container">'.$question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j].
                                           '<input id= "reponse_bonne'.$j.'"  value="'.$question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j].
                                           '" name="reponse_bonne'.$j.'" type="checkbox"><span class="checkmark"></span></label><br>';

                                       if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                           if(isset($_POST["reponse_bonne$j"])){
                                               if(isset($question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j])){
                                                   if ($question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j] == $_POST["reponse_bonne$j"]){
                                                       $reponse = 1;
                                                   }
                                               }
                                           }
                                       }
                                   }
                               }
                                for ($k=0;$k<count($question_json[$i]->{'reponse'}->{'fausse_reponse'});$k++){
                                    echo'<label class="container">'.$question_json[$i]->{'reponse'}->{'fausse_reponse'}[$k].
                                        '<input value="'.$question_json[$i]->{'reponse'}->{'fausse_reponse'}[$k].'" name="reponse'.$k.
                                        '" type="checkbox"><span class="checkmark"></span></label><br>';
                                    if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                        if(isset($_POST["reponse$k"])){
                                            if(isset($question_json[$i]->{'reponse'}->{'fausse_reponse'}[$k])){
                                                if ($question_json[$i]->{'reponse'}->{'fausse_reponse'}[$k] == $_POST["reponse$k"]){
                                                    $reponse = 2;
                                                }
                                            }
                                        }
                                    }
                                }
                                if($reponse == 1){
                                    $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + $_SESSION['score'];
                                }
                            }
                            else{
                                //'.$question_json[$i][0]->{'reponse'}.'
                                echo '<span class="affich_reponse"><input placeholder="REPONSE" id="reponse_texte'.$i.'" name="reponse_texte'.$i.
                                    '" style="width: 49%; height: 26px;" value="';
                                echo '"/></span><br/>';


                                if(isset($_POST['boutton_suivant']) || isset($_POST['button_terminer'])){
                                    if (isset($_POST["reponse_texte$i"])){
                                        if(isset($question_json[$i]->{'reponse'})){
                                            if ($question_json[$i]->{'reponse'} == $_POST["reponse_texte$i"]) {
                                                $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + $_SESSION['score'];
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }

                }


          var_dump($reponse);

           var_dump($_SESSION['rep']);
           //  var_dump($parsed_json);

            $score = array(
                $_SESSION['login_joueur']=>array()
            );

            $precedent = $currentPage-1;
            $suivant = $currentPage + 1;
            $_SESSION['pages'] = $pages;
            $_SESSION['current'] = $currentPage;
            $_SESSION['precedent'] = $precedent;
            $_SESSION['suivant'] = $suivant;
            if($_SESSION['current'] < $_SESSION['pages']){
                echo ' <a href="home_joueur.php?&pages='.$_SESSION['suivant'].'"><input type="submit" value="Suivant" onclick="msg();"  name="boutton_suivant" class="button_suivant"></a> ';
               // echo' <a href="home_joueur.php?&pages='.$_SESSION['suivant'].'"><button name="boutton_suivant" class="button_suivant">Suivant</button></a>';
            }
            if ($_SESSION['current'] == $_SESSION['pages']){
                echo ' <a><input type="submit" name="button_terminer" class="button_suivant" style="background: green; margin-top: 2%" value="Terminer"></a> ';
            }
            if($_SESSION['current']>1){
                $link ='home_joueur.php?';
                if($_SESSION['current']>2){
                    $link .= 'pages='.$_SESSION['precedent'];
                }
                echo ' <a href="'.$link.'"><input type="button" value="Precedent" name="boutton_precedent" class="boutton_precendent"></a> ';
            }
            if(isset($_POST['button_terminer'])){

                if(isset($_SESSION['login_joueur'], $_SESSION['prenom_joueur'], $_SESSION['nom_joueur'])){
                    $nbre_points = $_SESSION['rep']['score'];
                    $score[$_SESSION['login_joueur']]['prenom'] = $_SESSION['prenom_joueur'];
                    $score[$_SESSION['login_joueur']]['nom'] = $_SESSION['nom_joueur'];
                    $score[$_SESSION['login_joueur']]['login'] = $_SESSION['login_joueur'];
                    $score[$_SESSION['login_joueur']]['score'] = $nbre_points;

                    $js = file_get_contents('score.json');
                    $js= json_decode($js, true);

                    //  var_dump($js);
                    if(!(isset($js))){
                        $js[] = $score;
                    }
                    else{
                        for ($i=0; $i<count($js); $i++){
                            if(isset($js[$i][$_SESSION['login_joueur']])) {
                                foreach ($js[$i] as $key => $value) {
                                    if (key_exists($key, $js[$i])){
                                        if($js[$i][$key]['login'] ==  $_SESSION['login_joueur']){
                                            $js[$i][$key]['score'] = $score[$_SESSION['login_joueur']]['score'];
                                        }
                                        //var_dump($js[$i]);
                                    }
                                    // var_dump($key);
                                }
                            }
                            else{
                                $js[$i][$_SESSION['login_joueur']]['prenom'] = $score[$_SESSION['login_joueur']]['prenom'];
                                $js[$i][$_SESSION['login_joueur']]['nom'] = $score[$_SESSION['login_joueur']]['nom'];
                                $js[$i][$_SESSION['login_joueur']]['login'] = $score[$_SESSION['login_joueur']]['login'];
                                $js[$i][$_SESSION['login_joueur']]['score'] =  $score[$_SESSION['login_joueur']]['score'];
                            }
                            // var_dump($js[$i]);
                            //  var_dump($js[$i]['prenom']);
                        }
                    }

                    // var_dump($js);
                    //$js[] = $score;
                    //var_dump($js);
                    $js = json_encode($js);
                    file_put_contents('score.json', $js);



                }

                if($_SESSION['login_joueur']){
                    for($i=0;$i<count($parsed_json); $i++){
                        if($parsed_json[$i]->{'login'} == $_SESSION['login_joueur']){
                            $parsed_json[$i]->{'score'}[] =   $nbre_points;
                        }
                    }
                    $js_users = json_encode($parsed_json);
                    file_put_contents('users.json', $js_users);
                }
                $_SESSION['rep']['score'] = 0;
                header('Location: home_joueur.php');
            }


            echo '</form>';
                ?>
            <?php
            if(isset($_POST['boutton_suivant'])){
                header('Location:home_joueur.php?&pages='.$_SESSION['suivant'].'');
            }
            ?>
            </div>
            <div class="pagination">

            </div>
        </div>
        <div class="score">
            <h1>SUIS</h1>
            <h1>SUIS</h1>
            <h1>SUIS</h1>
            <h1>SUIS</h1>
            <h1>SUIS</h1>
        </div>
    </div>
</div>
    <script type="text/javascript">

        function msg(){

<?php
                /*
            for ($i=0; $i<count($question_json); $i++){
                if (is_array($question_json[$i])){
                    $_SESSION['score'] = $question_json[$i][0]->{'score'};
                    if(is_object($question_json[$i][0]->{'reponse'})){
                        for ($j=0; $j<sizeof($question_json[$i][0]->{'reponse'}->{'bonne_reponse'});$j++){
                                $val = $j;
                        }
                    }
                    else{

                    }
                }
                else{
                    if ($question_json[$i]){
                        $_SESSION['score'] = $question_json[$i]->{'score'};
                        if (is_object($question_json[$i]->{'reponse'})) {
                            for ($j=0; $j<sizeof($question_json[$i]->{'reponse'}->{'bonne_reponse'});$j++){
                                $val = $question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j];
                            }
                        }
                        else{

                        }
                    }
                }

            }
                */
            ?>
            /*
            let size = < ?php if(isset($size_reponse)){echo $size_reponse; }?>;
           // alert(size);
            let score = <?php //    echo $_SESSION['score'];  ?>;
            let nbrepts = 0;
            for (i=0; i<size;i++){
                let rep = document.getElementById('reponse_bonne'+i).value;
                alert(rep);
            }

            let rep_joueur = document.getElementById("reponse_joueur0").value;
            let bonne_reponse = document.getElementById("reponse_texte0").value;

            if(size === 0){
                if(rep_joueur === bonne_reponse){
                    nbrepts = nbrepts + score;
                    <?php // $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + $_SESSION['score_text']; ?>
                    alert("gagner");
                }else{
                    alert('perdu');
                }
            }else{
               <?php // $_SESSION['rep']['score'] = $_SESSION['rep']['score'] + 0; ?>
            }

            //alert(rep_joueur);
            alert(nbrepts);
*/
        }

    </script>
</body>
</html>