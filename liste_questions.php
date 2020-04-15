<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/liste_questions.css">
    <title>Document</title>
</head>
<body>
<div >
    <form action="" method="post" class="titre_entete">
        <h3 class="titre_question">Nbre de question/jeu</h3>
        <input type="number" class="champ_nbre_questions" name="nombre_question" value="<?php if(isset($_SESSION['T'][0]))
        {echo $_SESSION['T'][0] ;} ?>">
        <input class="button_ok" type="submit" value="OK">
    </form>
    <div class="affiche_reponse">
    <?php
    if(isset($_POST['nombre_question']) && is_numeric($_POST['nombre_question'])){
        if(empty($_POST['nombre_question'])){
            echo 'Nombre question vide';
        }
        else{
            $_SESSION['T'] = array();
            $_SESSION['T'][] = $_POST['nombre_question'];
        }
    }

    $questions = file_get_contents('questions.json');
    $question_json = json_decode($questions);

    //Pagination


    if(isset($_GET['pages'])){
        $_GET['pages'] = intval($_GET['pages']);
        $currentPage= $_GET['pages'];
    }
    else{
        $currentPage = 1;
    }
    $count = count($question_json);
    $perPage = 1;
    $pages = ceil($count/$perPage);
    $offeset = $perPage * ($currentPage - 1);
    $question_json = array_slice($question_json,$offeset, $perPage);

    for ($i=0; $i<count($question_json); $i++){
        $x = $i+1;
        if (is_array($question_json[$i])){
            $affich_question = $x.'.'.$question_json[$i][0]->{'questions'};
            echo "<span class='afficher_question'>$affich_question</span><br/>";
            if(is_object($question_json[$i][0]->{'reponse'})){
                for ($j=0; $j<sizeof($question_json[$i][0]->{'reponse'}->{'bonne_reponse'});$j++){
                    echo '<input type="checkbox"/><span class="affich_reponse">'.$question_json[$i][0]->{'reponse'}->{'bonne_reponse'}[$j].'</span><br/>';
                }
                for ($k=0;$k<count($question_json[$i][0]->{'reponse'}->{'fausse_reponse'});$k++){
                    echo '<input type="checkbox"/><span class="affich_reponse">'.$question_json[$i][0]->{'reponse'}->{'fausse_reponse'}[$k].'</span><br/>';
                }
            }
            else{
                //echo '<span class="affich_reponse">'.$question_json[$i][0]->{'reponse'}.'</span><br/>';
                echo '<span class="affich_reponse"><input placeholder="REPONSE" name="reponse'.$i.'" style="width: 49%; height: 26px;margin-left: 6%;border-radius: 8px;"/></span><br/>';
            }
        }
        else{
        if ($question_json[$i]){
            $affich_question = $x.'.'.$question_json[$i]->{'questions'} ;
            echo "<span class='afficher_question'>$affich_question</span><br/>";
            if (is_object($question_json[$i]->{'reponse'})) {
                for ($j=0; $j<sizeof($question_json[$i]->{'reponse'}->{'bonne_reponse'});$j++){
                    echo '<input type="checkbox"/><span class="affich_reponse">'.$question_json[$i]->{'reponse'}->{'bonne_reponse'}[$j].'</span><br/>';
                }
                for ($k=0;$k<count($question_json[$i]->{'reponse'}->{'fausse_reponse'});$k++){
                    echo '<input type="checkbox"/><span class="affich_reponse">'.$question_json[$i]->{'reponse'}->{'fausse_reponse'}[$k].'</span><br/>';
                }
            }
            else{
                echo '<span class="affich_reponse"><input placeholder="REPONSE" name="reponse'.$i.'" style="width: 49%; height: 26px;margin-left: 6%;border-radius: 8px;"/></span><br/>';
              //  echo '<span class="affich_reponse">'.$question_json[$i][0]->{'reponse'}.'</span><br/>';
            }
        }
        }
    }

  echo '</div><div class="pagination">';

    $precedent = $currentPage-1;
    $suivant = $currentPage + 1;

    if($currentPage>1){
        $link ='home_admin.php?page=liste_question';
        if($currentPage>2){
            $link .= '&pages='.$precedent;
        }
        echo ' <a href="'.$link.'"><button class="boutton_precendent">&laquo;Precedent</button></a> ';
    }
    if($currentPage<$pages){
        echo ' <a href="home_admin.php?page=liste_question&pages='.$suivant.'"><button class="button_suivant">Suivant &raquo;</button></a> ';
    }

?>
    </div>
</div>
</body>
</html>
