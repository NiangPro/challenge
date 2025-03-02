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
    } else {
        setmessage("Veuillez remplir tous les champs", "danger");
        return header("Location:?page=challenge&type=add");
    }
}

if (isset($_POST["ajouterParticipant"])) {
    extract($_POST);

    if (notEmpty([$prenom, $nom, $cohorte_id])) {
        if (ajouterParticipant($prenom, $nom, $cohorte_id, $_GET["id"], null)) {
            setmessage("Ajout participant avec succès");
            return header("Location:?page=challenge&type=edit&id=" . $_GET["id"]);
        }
    } else {
        setmessage("Veuillez remplir tous les champs", "danger");
        return header("Location:?page=challenge&type=edit&id=" . $_GET["id"] . "&sous=add");
    }
}

if (isset($_GET["statut"])) {
    if (isset($_GET["id"]) && $_GET["id"]) {
        if ($_GET["statut"] == "valider") {
            if (changerStatut($_GET["id"], 1)) {
                setmessage("Challenge validé avec succès");
                return header("Location:?page=challenge");
            }
        } elseif ($_GET["statut"] == "terminer") {
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
    // Vérifier si le challenge est en cours (statut = 1)
    $challenge = challenge($_GET["id"]);
    if ($challenge->statut != 1) {
        setmessage("Le challenge doit être en cours pour effectuer un tirage", "warning");
        return header("Location:?page=challenge");
    }

    // Vérifier s'il y a des matchs non joués
    $mats = matches($_GET["id"]);
    $unplayedMatches = false;
    foreach ($mats as $match) {
        if ($match->statut == 0) {
            $unplayedMatches = true;
            break;
        }
    }

    if ($unplayedMatches) {
        setmessage("Il y a des matches en cours", "warning");
        return header("Location:?page=match&challenge=" . $_GET["id"]);
    }

    try {
        global $db;
        $db->beginTransaction();

        // Récupérer le tour actuel du challenge
        $currentTour = $challenge->tour ?? 1;
        error_log("Tirage pour le tour " . $currentTour);

        $teams = participants($_GET["id"]);
        if (count($teams) > 0) {
            // Mélanger aléatoirement les équipes
            shuffle($teams);

            // Vérifier si le nombre d'équipes est impair
            $bye_team = null;
            if (count($teams) % 2 !== 0) {
                $bye_team = array_pop($teams);
            }

            // Former les paires pour les matchs
            for ($i = 0; $i < count($teams); $i += 2) {
                if (isset($teams[$i + 1])) {
                    // Créer un match entre deux participants
                    $stmt = $db->prepare("
                        INSERT INTO matches(id_part1, id_part2, challenge_id, statut, tour) 
                        VALUES(?, ?, ?, 0, ?)
                    ");
                    $stmt->execute([
                        $teams[$i]->id,
                        $teams[$i + 1]->id,
                        $_GET["id"],
                        $currentTour
                    ]);
                    error_log("Match créé : {$teams[$i]->prenom} vs {$teams[$i + 1]->prenom} pour le tour {$currentTour}");
                }
            }

            // Gérer le participant qui passe directement au tour suivant (cas impair)
            if ($bye_team) {
                $stmt = $db->prepare("
                    INSERT INTO matches(id_part1, id_part2, challenge_id, gagnant_id, statut, tour) 
                    VALUES(?, NULL, ?, ?, 1, ?)
                ");
                $stmt->execute([
                    $bye_team->id,
                    $_GET["id"],
                    $bye_team->id,
                    $currentTour
                ]);
                error_log("Match créé pour {$bye_team->prenom} (qualifié directement) pour le tour {$currentTour}");

                // Créer une notification
                createNotification(
                    0, 
                    "{$bye_team->prenom} {$bye_team->nom} est automatiquement qualifié(e) pour le prochain tour du tournoi.", 
                    "info", 
                    "?page=match&challenge=" . $_GET["id"]
                );
            }

            $db->commit();
            setmessage(
                $bye_team 
                    ? "Tirage effectué. {$bye_team->prenom} {$bye_team->nom} est automatiquement qualifié(e)." 
                    : "Tirage effectué avec succès"
            );
        } else {
            setmessage("Aucun participant pour le moment", "danger");
        }
    } catch (Exception $e) {
        $db->rollBack();
        error_log("Erreur lors du tirage : " . $e->getMessage());
        setmessage("Une erreur est survenue lors du tirage : " . $e->getMessage(), "danger");
    }
    
    return header("Location:?page=match&challenge=" . $_GET["id"]);
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
    
    if ($_GET["type"] === "tournament" && isset($_GET["id"])) {
        // Debug
        error_log("Affichage de l'arbre du tournoi pour le challenge " . $_GET["id"]);
        require_once("views/challenge/tournament.php");
    } else {
        require_once("views/challenge/add.php");
    }
} else {
    require_once("views/challenge/challenge.php");
}

require_once("views/includes/footer.php");
