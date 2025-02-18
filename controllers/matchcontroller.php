<?php 

if (!isset($_SESSION["user"])) {
    return header("Location:?page=home");
}

if (isset($_POST["afficher"])) {
    extract($_POST);

    if (notEmpty([$challenge])) {
        return header("Location:?page=match&challenge=".$challenge);
    }else{
        setmessage("Veuillez selectionner un challenge", "danger");
        return header("Location:?page=match");
    }
}

if (isset($_GET["gagnant"]) && isset($_GET["match"])) {
    if (gagner($_GET["match"], $_GET["gagnant"])) {
        $p = participant($_GET["gagnant"]);
        setmessage("Félicitations à {$p->prenom} {$p->nom} de la {$p->nomcohorte}");
        return header("Location:?page=match&challenge=".$_GET["challenge"]);
    }
}

if (isset($_GET["next"])) {
    $matches = matches($_GET["challenge"]);
    $challenge = challenge($_GET["challenge"]);

    
    if ($challenge) {
        if (changerStatut($challenge->id, 2)) {
            $nom = count($matches) == 2 ? $challenge->nom."_final" : $challenge->nom."_1";
            $parent_id = $challenge->parent_id ?:$challenge->id;
            if (ajouterChallenge($nom, $challenge->debut, 1)) {
                $last = challenges()[0];
                foreach ($matches as $m) {
                    $p = participant($m->gagnant_id);
                    ajouterParticipant($p->prenom, $p->nom, $p->cohorte_id, $last->id);
                }
    
                setmessage("{$last->nom} a été créé pour le tour suivant");
                return header("Location:?page=challenge&type=edit&id=$last->id");
            }
        }
        
    }
}

$challenges = challenges();

if (isset($_GET["challenge"])) {
    $matches = matches($_GET["challenge"]);
    $etat = verifierChallenge($_GET["challenge"]);

}


require_once("views/includes/entete.php");
require_once("views/includes/navbar.php");

if (isset($_GET["type"])) {
    if (isset($_GET["id"])) {
        $c = challenge($_GET["id"]);
    }
    require_once("views/challenge/add.php");
}else{
    require_once("views/match/match.php");
}
require_once("views/includes/footer.php");



