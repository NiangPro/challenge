<?php 

if (!isset($_SESSION["user"])) {
    return header("Location:?page=home");
}

if (isset($_POST["ajouter"])) {
    extract($_POST);

    if (notEmpty([$nom, $debut])) {
        if (ajouterChallenge($nom, $debut)) {
            setmessage("Ajout challenge avec succès");
            return header("Location:?page=challenge");
        }
    }else{
        setmessage("Veuillez remplir tous les champs", "danger");
        return header("Location:?page=challenge&type=add");
    }

}

if (isset($_POST["ajouterParticipant"])) {
    extract($_POST);

    if (notEmpty([$prenom, $nom, $cohorte_id])) {
        if (ajouterParticipant($prenom, $nom, $cohorte_id, $_GET["id"])) {
            setmessage("Ajout participant avec succès");
            return header("Location:?page=challenge&type=edit&id=".$_GET["id"]);
        }
    }else{
        setmessage("Veuillez remplir tous les champs", "danger");
        return header("Location:?page=challenge&type=edit&id=".$_GET["id"]."&sous=add");
    }

}

if (isset($_GET["statut"])) {
    if (isset($_GET["id"]) && $_GET["id"]) {
        if ($_GET["statut"] == "valider") {
            if (changerStatut($_GET["id"], 1)) {
                setmessage("Challenge validé avec succès");
                return header("Location:?page=challenge");
            }
        }elseif($_GET["statut"] == "terminer"){
            if (changerStatut($_GET["id"], 2)) {
                setmessage("Challenge terminé");
                return header("Location:?page=challenge");
            }
        }
    }
    
}

if (isset($_GET["delete"])) {
    if ($_GET["delete"]) {
        if (supprimerChallenge($_GET["delete"])) {
            supprimerMatches($_GET["delete"]);
            setmessage("Challenge supprimé avec succès");
        }
    }
    return header("Location:?page=challenge");
}

if (isset($_GET["tirer"])) {
    $mats = matches($_GET["id"]);

    if (count($mats) > 0) {
        setmessage("Il y a des matches en cours", "warning");
    }else{
        $teams = participants($_GET["id"]);

        if (count($teams) > 0) {
            // Mélanger aléatoirement les équipes
            shuffle($teams);
            
            /// Vérifier si le nombre d'équipes est impair
            $bye_team = null;
            if (count($teams) % 2 !== 0) {
                // Sélectionner une équipe aléatoire qui passe directement au tour suivant
                $bye_team = array_pop($teams);
            }

            // Former les matchs
            $matches = array_chunk($teams, 2);
            // print_r($bye_team);
            // die();


            foreach ($matches as $match) {
                ajouterMatch($match[0]->id, $match[1]->id, $_GET["id"]);
            }

            // Afficher l'équipe qui passe directement au tour suivant
            if ($bye_team) {
                ajouterMatch($bye_team->id, null, $_GET["id"], $bye_team->id, 1);

                setmessage("Tirage effectué, {$bye_team->prenom} {$bye_team->nom} est automatiquement qualifié(e) pour le prochain tour.");

            }else{
                setmessage("Tirage effectué");
            }
        }else{
            setmessage("Aucun participant pour le moment", "danger");
        }
    
        
    }
    

    return header("Location:?page=challenge");

}

if (isset($_GET["idgagnant"])) {
    $games = monParcours($_GET["idgagnant"]);
}


$challenges = challenges();
$cohortes = cohortes();

require_once("views/includes/entete.php");
require_once("views/includes/navbar.php");

if (isset($_GET["type"])) {
    if (isset($_GET["id"])) {
        $c = challenge($_GET["id"]);
        $participants = participants($c->id);
        $last = dernierChallenge($_GET["id"]);
        if ($last) {
           $matches = matches($last->id);
           if (count($matches) == 1) {
            if ($matches[0]->statut == 1) {
                $gagnant = participant($matches[0]->gagnant_id);
            }
           }
        }
       
    }
    require_once("views/challenge/add.php");
}else{
    require_once("views/challenge/challenge.php");
}
require_once("views/includes/footer.php");



