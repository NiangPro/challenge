<?php 

function setmessage($msg, $type="success"){
    $_SESSION["msg"]["content"] = $msg; 
    $_SESSION["msg"]["type"] = $type; 
}

function saveInputData(){
    if (isset($_POST)) {
        $_SESSION["input"] = $_POST;
    }
}

function getInputData($nom, $obj = null){

    if ($obj) {
        return $obj->$nom;
    }else if(isset($_SESSION["input"][$nom]) && $_SESSION["input"][$nom]){

        return  $_SESSION["input"][$nom];
    }else{
        return null;
    }
}

function notEmpty($data =[]){
    foreach ($data as $value) {
        if (empty($value)) {
            return false;
        }
    }

    return true;
}

function afficheParticipant($p){
    return "{$p->prenom} {$p->nom} <br> ({$p->nomcohorte})";
}

function monParcours($idgagnant){
    $tab = cursus($idgagnant);

    $matches = [];
    foreach ($tab as $p) {
        $m = parcours($p->id);
        if ($m) {
            $matches[] = $m;
        }
    }

    return $matches;
}

function niveauChallenge($idchallenges){
    $matches = matches($idchallenges);

    if (count($matches) == 1) {
        return "🏆 Finale 🏆";
    }elseif (count($matches) == 2){
        return "1/2 Finale";
    }elseif (count($matches) <= 4){
        return "1/4 Finale";
    }elseif (count($matches) <= 8){
        return "1/8 Finale";
    }elseif (count($matches) <= 16){
        return "1/16 Finale";
    }else{
        return "Tour préliminaire";
    }
}