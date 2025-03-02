<?php

if (!isset($_SESSION["user"])) {
    return header("Location:?page=home");
}

if (isset($_POST["afficher"])) {
    extract($_POST);

    if (notEmpty([$challenge])) {
        return header("Location:?page=match&challenge=" . $challenge);
    } else {
        setmessage("Veuillez selectionner un challenge", "danger");
        return header("Location:?page=match");
    }
}

if (isset($_GET["gagnant"]) && isset($_GET["match"])) {
    if (gagner($_GET["match"], $_GET["gagnant"])) {
        $p = participant($_GET["gagnant"]);
        setmessage("Félicitations à {$p->prenom} {$p->nom} de la {$p->nomcohorte}");
        
        // Créer une notification pour tous les utilisateurs
        createNotification(0, "Félicitations à {$p->prenom} {$p->nom} de la {$p->nomcohorte} qui a remporté son match!", "success", "?page=match&challenge=" . $_GET["challenge"]);
        
        return header("Location:?page=match&challenge=" . $_GET["challenge"]);
    }
}

if (isset($_GET["next"])) {
    $challenge = challenge($_GET["challenge"]);
    $tourActuel = isset($challenge->tour) ? $challenge->tour : 1;
    $matches = matches($_GET["challenge"]);

    if ($challenge) {
        // Vérifier si tous les matchs sont terminés
        $allMatchesCompleted = true;
        $winners = [];
        
        foreach ($matches as $match) {
            if ($match->statut != 1 || !$match->gagnant_id) {
                $allMatchesCompleted = false;
                break;
            }
            $winners[] = $match->gagnant_id;
        }
        
        if ($allMatchesCompleted && count($winners) >= 2) {
            // Commencer une transaction pour s'assurer que toutes les opérations sont effectuées
            $db->beginTransaction();
            
            try {
                // Archiver les matchs du tour actuel
                archiverMatchesPrecedents($challenge->id, $tourActuel);
                
                // Créer les matchs du prochain tour
                $numWinners = count($winners);
                $numMatches = floor($numWinners / 2);
                $remainingWinner = ($numWinners % 2 == 1) ? $winners[$numWinners - 1] : null;
                
                // Mélanger les gagnants pour des matchs aléatoires
                shuffle($winners);
                
                // Créer les nouveaux matchs
                for ($i = 0; $i < $numMatches; $i++) {
                    $id_part1 = $winners[$i * 2];
                    $id_part2 = $winners[($i * 2) + 1];
                    ajouterMatch($id_part1, $id_part2, $challenge->id, null, 0, $tourActuel + 1);
                }
                
                // Gérer le participant restant si nombre impair
                if ($remainingWinner) {
                    ajouterMatch($remainingWinner, null, $challenge->id, null, 0, $tourActuel + 1);
                }
                
                // Mettre à jour le nom et le tour du challenge
                $nouveauTour = $tourActuel + 1;
                $nouveauNom = preg_replace('/_tour\d+$/', '', $challenge->nom) . "_tour" . $nouveauTour;
                mettreAJourChallenge($challenge->id, $nouveauNom, $nouveauTour);
                
                // Déterminer le nom du tour pour la notification
                $nomTour = match($nouveauTour) {
                    1 => "Premier tour",
                    2 => "Deuxième tour",
                    3 => "Quarts de finale",
                    4 => "Demi-finales",
                    5 => "Finale",
                    default => "Tour " . $nouveauTour
                };
                
                // Créer une notification pour le nouveau tour
                createNotification(
                    0, 
                    "Le {$nomTour} du tournoi vient de commencer ! " . 
                    count($winners) . " participants qualifiés s'affronteront dans " . 
                    ($numMatches + ($remainingWinner ? 1 : 0)) . " matchs passionnants !",
                    "info",
                    "?page=match&challenge=" . $challenge->id
                );
                
                // Valider toutes les opérations
                $db->commit();
                
                setmessage("Passage au {$nomTour} effectué avec succès !", "success");
            } catch (Exception $e) {
                // En cas d'erreur, annuler toutes les opérations
                $db->rollBack();
                error_log("Erreur lors du passage au tour suivant : " . $e->getMessage());
                setmessage("Une erreur est survenue lors du passage au tour suivant.", "danger");
            }
        } else {
            setmessage("Tous les matchs doivent être terminés avant de passer au tour suivant.", "warning");
        }
    }
    
    return header("Location:?page=match&challenge=" . $_GET["challenge"]);
}

// Traitement des notifications
if (isset($_GET['mark_read']) && isset($_GET['notification_id'])) {
    markNotificationAsRead($_GET['notification_id']);
    
    // Rediriger vers la page précédente ou vers la page spécifiée dans le lien de la notification
    if (isset($_GET['redirect'])) {
        return header("Location:" . urldecode($_GET['redirect']));
    } else {
        return header("Location:" . $_SERVER['HTTP_REFERER']);
    }
}

if (isset($_GET['mark_all_read']) && isset($_SESSION['user'])) {
    markAllNotificationsAsRead($_SESSION['user']->id);
    
    // Rediriger vers la page précédente
    return header("Location:" . $_SERVER['HTTP_REFERER']);
}

$challenges = challenges();

if (isset($_GET["challenge"])) {
    $matches = matches($_GET["challenge"]);
    $etat = verifierChallenge($_GET["challenge"]);
    $challenge = challenge($_GET["challenge"]);
}

require_once("views/includes/entete.php");
require_once("views/includes/navbar.php");

// Déterminer quelle vue afficher
$view = 'match';
if (isset($_GET['view'])) {
    switch ($_GET['view']) {
        case 'tree':
            $view = 'tournament-tree';
            break;
        case 'history':
            $view = 'history';
            break;
        case 'predictions':
            $view = 'predictions';
            break;
        default:
            $view = 'match';
            break;
    }
}

if (isset($_GET["type"])) {
    if (isset($_GET["id"])) {
        $c = challenge($_GET["id"]);
    }
    require_once("views/challenge/add.php");
} else {
    // Afficher les onglets de navigation
    ?>
    <div class="container mt-5" style="margin-top: 90px!important;">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= ($view == 'match') ? 'active' : '' ?>" href="?page=match<?= isset($_GET['challenge']) ? '&challenge='.$_GET['challenge'] : '' ?>">
                    <i class="fa fa-gamepad"></i> Matchs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($view == 'tournament-tree') ? 'active' : '' ?>" href="?page=match&view=tree<?= isset($_GET['challenge']) ? '&challenge='.$_GET['challenge'] : '' ?>">
                    <i class="fa fa-sitemap"></i> Arbre du tournoi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($view == 'history') ? 'active' : '' ?>" href="?page=match&view=history<?= isset($_GET['challenge']) ? '&challenge='.$_GET['challenge'] : '' ?>">
                    <i class="fa fa-history"></i> Historique
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($view == 'predictions') ? 'active' : '' ?>" href="?page=match&view=predictions<?= isset($_GET['challenge']) ? '&challenge='.$_GET['challenge'] : '' ?>">
                    <i class="fa fa-trophy"></i> Prédictions
                </a>
            </li>
        </ul>
    </div>
    <?php
    
    // Inclure la vue correspondante
    require_once("views/match/{$view}.php");
}

require_once("views/includes/footer.php");
