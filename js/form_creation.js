function Supprimer_element() {
    var conteneur = document.getElementById('conteneur');
    var inpt_js = document.getElementById('input_js'+i);
    var check_js = document.getElementById('check_js'+i);
    var nbChampsAjout = document.getElementById('number_reponse').value;
    for (var i = 0 ; i < nbChampsAjout; i++){
        conteneur.removeChild(conteneur.childNodes[i]);
    }
}

var nbInput = 0; //On utilise une variable globale pour éviter d'avoir des inputs avec le même nom...

function ajouterChamps(){
    var nbChampsAjout = document.getElementById('number_reponse').value;
    var DivToAdd = document.getElementById('conteneur');
    if(nbChampsAjout <= 0){alert('Veuillez indiquer le nombre de champs à ajouter');}
    else{
        tempInput = "";
        for(let i = 1 ; i <= nbChampsAjout; i++){
            nbInput++;
            tempInput+= '<input type="text" name="reponse'+i+'" placeholder="reponse'+i+'" class="inpt_js"  id="input_js"/>' +
                '<input  type="checkbox" id="c2" name="c'+i+'" id="check_js" />/<a href=""><img src="Images/Icônes/ic-supprimer.png"/></a>' +
                '<br />';
        }
        DivToAdd.innerHTML = tempInput;
    }
}

function type_questions(){
    var type_question = document.getElementById('type_question');
    var type_reponse = document.getElementById('ajout_type_reponse');
    var titre = document.getElementById("titre");
    var select_type = type_question[type_question.selectedIndex].value;
    if(select_type === '3'){
        titre.innerHTML = '<h4 style="margin-left: -85%;">Reponse</h4><textarea id="reponse_texte" name="reponse_texte" style="margin-top: -5%; margin-left: 22%;"></textarea>';
        titre.body.appendChild(titre);
    }else{
        if(select_type === '1'){
            titre.innerHTML = '<h4 style="margin-left: -85%;">Reponse</h4><input class="input_simple_js"  name="reponse_simple"/>';
            titre.body.appendChild(titre);
        }
        else{
            titre.innerHTML = '<label class="title_number_response"><span style="margin-left: 3%">NBRE<br>REPONSE</span></label>\n' +
                '        <input type="number" class="number_reponse" name="number_reponse" placeholder="Ex:3" id="number_reponse">\n' +
                '        <input type="button" class="ajouter_nbre_reponse" value="Ajouter" name="ajoutchamp" onclick="ajouterChamps()"><br>';
            titre.body.appendChild(titre);
        }
    }
}


