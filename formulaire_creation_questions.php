<?php
if (isset($_POST['question'], $_POST['score'], $_POST['type_question'])){
    if(empty($_POST['question']) || empty($_POST['score'])){
        if(empty($_POST['question'])){
            echo '<h1>Veuillez entrer votre question</h1>';
        }
        elseif (empty($_POST['score'])){
            echo '<h1>Veuillez saisir le nombre de point de la questions</h1>';
        }
    }
    else{
        $question = $_POST['question'];
        $score = $_POST['score'];
        $type_reponse = $_POST['type_question'];

        if(($type_reponse== '1')) {
            if ((isset($_POST['reponse_simple']))) {
                if ((empty($_POST['reponse_simple']))) {
                    echo '<h1>Veuillez entrer votre reponse</h1>';
                    die();
                }
            }
        }
            if($type_reponse == '3'){
                if(isset($_POST['reponse_texte']) && empty($_POST['reponse_texte'])){
                    echo '<h1>Veuillez entrer votre reponse</h1>';
                    die();
                }
            }

        elseif($type_reponse == '2'){
            if(isset($_POST['number_reponse']) && empty($_POST['number_reponse'])){
                echo '<h1>veuillez entrer le nombre de reponse</h1>';
                die();
            }
            elseif(isset($_POST['number_reponse']) && !empty($_POST['number_reponse'])){
                for ($i=1; $i<=$_POST['number_reponse'];$i++){
                    if(isset( $_POST['reponse'.$i]) && empty( $_POST['reponse'.$i])){
                        echo '<h3>Donner la reponse '.$i.'</h3>';
                        die();
                    }
                }
            }
        }


            $questions = array();
            if(isset($_POST['reponse_simple']) && $type_reponse == '1'){
                array_push($questions, array(
                    'questions'=>$question,
                    'score'=>$score,
                    'reponse'=>$_POST['reponse_simple'],

                ));
            }
            elseif (isset($_POST['reponse_texte']) && $type_reponse == '3'){
                array_push($questions, array(
                    'questions'=>$question,
                    'score'=>$score,
                    'reponse'=>$_POST['reponse_texte']
                ));
            }
            elseif ($type_reponse == '2'){
                if(isset($_POST['number_reponse'])){
                    if (!(isset($rep)) && !(empty($rep))){
                        $rep = array(
                            'bonne_reponse'=>'',
                            'fausse_reponse'=>''
                        );
                    }

                    $bonne_reponse = '';
                    $mauvaise_response = '';
                    for($i=1; $i<=$_POST['number_reponse']; $i++){
                        if(isset($_POST['reponse'.$i])){
                            if(isset($_POST['c'.$i]))
                            {
                                $rep['bonne_reponse'][] = $_POST['reponse'.$i];
                            }
                            else{
                                $rep['fausse_reponse'][] = $_POST['reponse'.$i];
                            }
                        }
                    }

                   $questions = array(
                        'questions'=>$question,
                        'score'=>$score,
                        'reponse'=>$rep
                    );

                }
            }
            $question_js = file_get_contents('questions.json');

            $question_js = json_decode($question_js, true);
            $question_js[] = $questions;
          //  var_dump($question_js[0][0]['questions']);

        var_dump($question_js);

            $question_js = json_encode($question_js);
            file_put_contents('questions.json', $question_js);
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
    <link type="text/css" rel="stylesheet" href="css/formlaire_creation_questions.css">
    <title>Document</title>
</head>
<body>
<h1>PARAMÉTRER VOTRE QUESTION</h1>
<div class="formulaire">
    <form action="#" method="post" name="form">
        <div>
            <label class="title_tag"><span>Questions</span></label>
            <textarea name="question"></textarea><br>
        </div>
        <label class="title_score"><span class="score">Nbre de points   </span></label>
        <input class="inpt_score" type="number" name="score"><br>
        <label class="title_type" style="">Type de réponse</label>
                <select name="type_question" style="margin-left: 9%" id="type_question">
                    <option class="defaut" value="defaut">Choisir</option>
                    <option name="choix_simple" class="choix_multiple" value="1">Choix simple</option>
                    <option name="choix_multiple" class="choix_simple" value="2">Choix multiple</option>
                    <option name="choix_texte" class="choix_text" value="3">Choix texte</option>
                </select>
       <div class="ajouter_nbre_reponses"><a href="#" name="ajout_nombre_reponse"  onclick="type_questions()"><img src="Images/Icônes/ic-ajout-réponse.png" style=""></a></div>
        <br>
        <div id='ajout_type_reponse'>
            <h3 id="titre" class="titre" style="color: black"></h3>
        </div>

        <div id="conteneur" style="margin-top: -2%">
        </div>
        <p><button class="enregistrer" style=""><strong>Enrégistrer</strong></button></p>
    </form>
</div>
</body>
<script src="js/form_creation.js"></script>
</html>