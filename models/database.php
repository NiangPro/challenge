<?php

$dsn = 'mysql:host=localhost;dbname=challenge;charset=utf8';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);
} catch (PDOException $e) {
    setmessage("Erreur de connexion : " . $e->getMessage(), "danger");
}

function seconnecter($email)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM users WHERE email =:email");
        $q->execute(["email" => $email]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function participants($idchallenge)
{
    global $db;

    try {
        $q = $db->prepare("SELECT prenom, p.nom as nom, p.id as id, c.nom as nomcohorte
         FROM participant p, cohortes c WHERE p.cohorte_id = c.id AND p.challenge_id = :idchallenge ORDER BY p.id DESC");
        $q->execute(["idchallenge" => $idchallenge]);

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function participant($id)
{
    global $db;

    try {
        $q = $db->prepare("SELECT prenom, p.nom as nom, p.id as id, c.nom as nomcohorte, cohorte_id, existant
         FROM participant p, cohortes c WHERE p.cohorte_id = c.id AND p.id = :id ORDER BY p.id DESC");
        $q->execute(["id" => $id]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function ajouterParticipant($prenom, $nom, $cohorte_id, $challenge_id, $existant)
{
    global $db;

    try {
        $q = $db->prepare("INSERT INTO participant(prenom, nom, cohorte_id, challenge_id, existant) VALUES(:prenom, :nom, :cohorte_id, :challenge_id, :existant)");
        return $q->execute([
            "prenom" => ucfirst($prenom),
            "nom" => ucfirst($nom),
            "cohorte_id" => $cohorte_id,
            "challenge_id" => $challenge_id,
            "existant" => $existant,
        ]);
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function archiverMatchesPrecedents($challenge_id, $tour_actuel) {
    global $db;
    try {
        // Archiver les matchs des tours précédents
        $q = $db->prepare("
            UPDATE matches 
            SET is_archived = TRUE 
            WHERE challenge_id = :challenge_id 
            AND tour < :tour
            AND is_archived = FALSE
        ");
        
        $success = $q->execute([
            "challenge_id" => $challenge_id,
            "tour" => $tour_actuel
        ]);
        
        if ($success) {
            $matchsArchives = $q->rowCount();
            error_log("Nombre de matchs archivés : " . $matchsArchives);
        }
        
        return $success;
    } catch (PDOException $th) {
        error_log("Erreur lors de l'archivage des matchs : " . $th->getMessage());
        setmessage("Erreur lors de l'archivage des matchs : " . $th->getMessage(), "danger");
        return false;
    }
}

function supprimerMatches($challenge_id) {
    // Cette fonction est conservée pour la compatibilité mais ne supprime plus les matchs
    return true;
}

function matchesHistorique($challenge_id) {
    global $db;
    try {
        // Récupérer tous les matchs terminés (statut = 1) du challenge, triés par tour décroissant
        $q = $db->prepare("
            SELECT m.*, c.tour as tour_actuel
            FROM matches m 
            JOIN challenges c ON m.challenge_id = c.id 
            WHERE m.challenge_id = :challenge_id 
            AND m.statut = 1
            ORDER BY m.tour DESC, m.id DESC
        ");
        
        $q->execute(["challenge_id" => $challenge_id]);
        return $q->fetchAll();
    } catch (PDOException $th) {
        error_log("Erreur lors de la récupération de l'historique : " . $th->getMessage());
        return [];
    }
}

function matches($idchallenge)
{
    global $db;

    try {
        // Récupérer le tour actuel du challenge
        $challenge = challenge($idchallenge);
        $tourActuel = isset($challenge->tour) ? $challenge->tour : 1;
        
        // Récupérer tous les matchs du challenge, triés par tour
        $q = $db->prepare("
            SELECT m.*, 
                   p1.prenom as p1_prenom, p1.nom as p1_nom,
                   p2.prenom as p2_prenom, p2.nom as p2_nom
            FROM matches m 
            LEFT JOIN participant p1 ON m.id_part1 = p1.id
            LEFT JOIN participant p2 ON m.id_part2 = p2.id
            WHERE m.challenge_id = :idchallenge 
            ORDER BY m.tour ASC, m.id ASC
        ");
        
        $q->execute(["idchallenge" => $idchallenge]);
        $matches = $q->fetchAll(PDO::FETCH_OBJ);
        
        error_log("Matchs trouvés pour le challenge " . $idchallenge . ": " . count($matches));
        foreach ($matches as $match) {
            error_log("Match {$match->id}: Tour {$match->tour}, Statut {$match->statut}");
        }
        
        return $matches;
    } catch (PDOException $th) {
        error_log("Erreur lors de la récupération des matchs: " . $th->getMessage());
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
        return [];
    }
}

function parcours($idpart)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * 
            FROM matches
         WHERE id_part1 = :idpart OR id_part2 = :idpart");
        $q->execute([
            "idpart" => $idpart,
        ]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function cursus($idpart)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * 
            FROM participant
         WHERE id = :idpart OR existant = :idpart ORDER BY id ASC");
        $q->execute([
            "idpart" => $idpart,
        ]);

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function ajouterMatch($id_part1, $id_part2, $challenge_id, $gagnant = null, $statut = 0, $tour = null) {
    global $db;

    try {
        // Si le tour n'est pas spécifié, récupérer le tour actuel du challenge
        if ($tour === null) {
            $challenge = challenge($challenge_id);
            $tour = isset($challenge->tour) ? $challenge->tour : 1;
        }

        // Ajouter un log pour le débogage
        error_log("Tentative d'ajout d'un match - Participant1: $id_part1, Participant2: " . ($id_part2 ?: "null") . ", Challenge: $challenge_id, Tour: $tour, Gagnant: " . ($gagnant ?: "null") . ", Statut: $statut");

        $q = $db->prepare("INSERT INTO matches (participant1_id, participant2_id, challenge_id, gagnant_id, statut, tour) VALUES (:id_part1, :id_part2, :challenge_id, :gagnant, :statut, :tour)");
        
        $success = $q->execute([
            "id_part1" => $id_part1,
            "id_part2" => $id_part2,
            "challenge_id" => $challenge_id,
            "gagnant" => $gagnant,
            "statut" => $statut,
            "tour" => $tour
        ]);

        if ($success) {
            $match_id = $db->lastInsertId();
            error_log("Match ajouté avec succès - ID: " . $match_id);
            return $match_id;
        }

        return false;
    } catch (PDOException $th) {
        error_log("Erreur lors de l'ajout du match: " . $th->getMessage());
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
        return false;
    }
}

function gagner($idmatch, $idgagnant)
{
    global $db;
    try {
        $q = $db->prepare("UPDATE matches SET gagnant_id = :gagnant, statut = 1, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $q->execute([
            "gagnant" => $idgagnant,
            "id" => $idmatch
        ]);
        return true;
    } catch (PDOException $th) {
        setmessage("Erreur lors de la mise à jour du match: " . $th->getMessage(), "danger");
        return false;
    }
}

function ajouterCohorte($nom)
{
    global $db;

    try {
        $q = $db->prepare("INSERT INTO cohortes(nom) VALUES(:nom)");
        return $q->execute([
            "nom" => ucfirst($nom),
        ]);
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function cohortes()
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM cohortes ORDER BY id DESC");
        $q->execute();

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function cohorte($id)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM cohortes WHERE id =:id");
        $q->execute([
            "id" => $id,
        ]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function challenges()
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges ORDER BY id DESC");
        $q->execute();

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function dernierChallenge($parent_id)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges WHERE parent_id=:parent_id ORDER BY id DESC");
        $q->execute(["parent_id" => $parent_id]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function supprimerChallenge($id)
{
    global $db;
    try {
        // Démarrer une transaction pour s'assurer que toutes les suppressions sont effectuées ou aucune
        $db->beginTransaction();

        // Supprimer d'abord les matchs associés au challenge
        $q = $db->prepare("DELETE FROM matches WHERE challenge_id = :id");
        $q->execute(["id" => $id]);

        // Supprimer les participants associés au challenge
        $q = $db->prepare("DELETE FROM participant WHERE challenge_id = :id");
        $q->execute(["id" => $id]);

        // Enfin, supprimer le challenge lui-même
        $q = $db->prepare("DELETE FROM challenges WHERE id = :id");
        $q->execute(["id" => $id]);

        // Valider toutes les suppressions
        $db->commit();
        return true;
    } catch (PDOException $th) {
        // En cas d'erreur, annuler toutes les suppressions
        $db->rollBack();
        error_log("Erreur lors de la suppression du challenge: " . $th->getMessage());
        setmessage("Erreur lors de la suppression du challenge et de ses données associées.", "danger");
        return false;
    }
}

function ajouterChallenge($nom, $debut, $statut = 0)
{
    global $db;

    try {
        $q = $db->prepare("INSERT INTO challenges(nom, debut, statut) VALUES(:nom, :debut, :statut)");
        return $q->execute([
            "nom" => ucfirst($nom),
            "debut" => $debut,
            "statut" => $statut,
        ]);
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function changerStatut($id, $statut)
{
    global $db;

    try {
        $q = $db->prepare("UPDATE challenges SET statut =:statut WHERE id =:id");
        return $q->execute([
            "statut" => $statut,
            "id" => $id,
        ]);
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function challenge($id)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges WHERE id =:id");
        $q->execute([
            "id" => $id,
        ]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function verifierChallenge($id)
{
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM matches WHERE challenge_id =:id AND statut = :statut");
        $q->execute([
            "id" => $id,
            "statut" => 0,
        ]);

        return $q->fetchALL();
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
    }
}

function mettreAJourChallenge($id, $nouveauNom, $tour)
{
    global $db;

    try {
        // Vérifier si la colonne 'tour' existe déjà
        $q = $db->prepare("SHOW COLUMNS FROM challenges LIKE 'tour'");
        $q->execute();
        $column_exists = $q->fetch();
        
        // Si la colonne n'existe pas, l'ajouter
        if (!$column_exists) {
            $q = $db->prepare("ALTER TABLE challenges ADD COLUMN tour INT DEFAULT 1");
            $q->execute();
        }
        
        // Mettre à jour le nom et le tour du challenge
        $q = $db->prepare("UPDATE challenges SET nom = :nom, tour = :tour WHERE id = :id");
        $q->execute([
            "id" => $id,
            "nom" => $nouveauNom,
            "tour" => $tour
        ]);

        return $q->rowCount() > 0;
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
        return false;
    }
}

// Fonctions pour le système de notifications

/**
 * Crée une instance de la classe Notification
 * 
 * @return Notification Instance de la classe Notification
 */
function getNotificationManager() {
    global $db;
    
    // Vérifier si la classe est déjà chargée
    if (!class_exists('Notification')) {
        require_once('models/Notification.php');
    }
    
    return new Notification($db);
}

/**
 * Crée une notification pour un utilisateur spécifique
 * 
 * @param int $user_id ID de l'utilisateur (0 pour tous les utilisateurs)
 * @param string $message Message de la notification
 * @param string $type Type de notification (info, success, warning, danger)
 * @param string $link Lien optionnel pour rediriger l'utilisateur
 * @return bool Succès de l'opération
 */
function createNotification($user_id, $message, $type = 'info', $link = null) {
    $notificationManager = getNotificationManager();
    return $notificationManager->create($user_id, $message, $type, $link);
}

/**
 * Récupère les notifications d'un utilisateur
 * 
 * @param int $user_id ID de l'utilisateur
 * @param int $limit Nombre maximum de notifications à récupérer
 * @param bool $include_read Inclure les notifications déjà lues
 * @return array Liste des notifications
 */
function getUserNotifications($user_id, $limit = 10, $include_read = false) {
    $notificationManager = getNotificationManager();
    return $notificationManager->getForUser($user_id, $limit, $include_read);
}

/**
 * Compte le nombre de notifications non lues pour un utilisateur
 * 
 * @param int $user_id ID de l'utilisateur
 * @return int Nombre de notifications non lues
 */
function countUnreadNotifications($user_id) {
    $notificationManager = getNotificationManager();
    return $notificationManager->countUnread($user_id);
}

/**
 * Marque une notification comme lue
 * 
 * @param int $id ID de la notification
 * @return bool Succès de l'opération
 */
function markNotificationAsRead($id) {
    $notificationManager = getNotificationManager();
    return $notificationManager->markAsRead($id);
}

/**
 * Marque toutes les notifications d'un utilisateur comme lues
 * 
 * @param int $user_id ID de l'utilisateur
 * @return bool Succès de l'opération
 */
function markAllNotificationsAsRead($user_id) {
    $notificationManager = getNotificationManager();
    return $notificationManager->markAllAsRead($user_id);
}

/**
 * Vérifie s'il existe des matchs non joués pour un challenge
 * 
 * @param int $challenge_id ID du challenge
 * @return bool True s'il y a des matchs non joués, false sinon
 */
function hasUnplayedMatches($challenge_id) {
    global $db;
    
    try {
        // Ne compter que les matchs avec deux participants (id_part2 IS NOT NULL) et statut = 0 (non joués)
        $q = $db->prepare("SELECT COUNT(*) as count FROM matches WHERE challenge_id = :challenge_id AND id_part2 IS NOT NULL AND statut = 0");
        $q->execute(["challenge_id" => $challenge_id]);
        $result = $q->fetch();
        
        return $result->count > 0;
    } catch (PDOException $th) {
        setmessage("Erreur: " . $th->getMessage() . " a la ligne: " . __LINE__, "danger");
        return true; // En cas d'erreur, on suppose qu'il y a des matchs non joués par sécurité
    }
}
