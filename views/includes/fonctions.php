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

function allMatchesTermines($matches) {
    foreach ($matches as $match) {
        if ($match->statut == 0) {
            return false;
        }
    }
    return count($matches) > 0;
}

function niveauChallenge($idchallenges){
    global $db;
    $challenge = challenge($idchallenges);
    $tour = isset($challenge->tour) ? $challenge->tour : null;
    
    // Si le tour est défini dans la base de données, l'utiliser en priorité
    if ($tour) {
        $titre = match($tour) {
            1 => "Premier tour",
            2 => "Deuxième tour",
            3 => "Quarts de finale ",
            4 => "Demi-finales ",
            5 => "Finale ",
            default => "Tour " . $tour
        };
        return $titre;
    }
    
    try {
        // Compter d'abord le nombre total de matchs
        $q = $db->prepare("SELECT COUNT(*) as total FROM matches WHERE challenge_id = :challenge_id");
        $q->execute(["challenge_id" => $idchallenges]);
        $result = $q->fetch();
        $totalMatches = $result->total;
        
        // Compter les matchs actifs (non terminés)
        $q = $db->prepare("SELECT COUNT(*) as active FROM matches WHERE challenge_id = :challenge_id AND statut = 0");
        $q->execute(["challenge_id" => $idchallenges]);
        $result = $q->fetch();
        $activeMatches = $result->active;
        
        // Ajouter un log pour le débogage
        error_log("Challenge ID: " . $idchallenges . ", Total matchs: " . $totalMatches . ", Matchs actifs: " . $activeMatches);
        
        // Déterminer le niveau en fonction du nombre total de matchs
        return match(true) {
            $totalMatches === 1 => "Finale ",
            $totalMatches === 2 => "Demi-finales ",
            $totalMatches === 3 || $totalMatches === 4 => "Quarts de finale ",
            $totalMatches >= 5 && $totalMatches <= 8 => "Huitièmes de finale",
            $totalMatches >= 9 && $totalMatches <= 16 => "Seizièmes de finale",
            default => "Tour préliminaire"
        };
    } catch (PDOException $th) {
        error_log("Erreur dans niveauChallenge: " . $th->getMessage());
        return "Tour en cours";
    }
}