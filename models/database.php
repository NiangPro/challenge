<?php 

$dsn = 'mysql:host=localhost;dbname=duel_app;charset=utf8';
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

function seconnecter($email){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM users WHERE email =:email");
        $q->execute(["email" => $email]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function participants($idchallenge){
    global $db;

    try {
        $q = $db->prepare("SELECT prenom, p.nom as nom, p.id as id, c.nom as nomcohorte
         FROM participant p, cohortes c WHERE p.cohorte_id = c.id AND p.challenge_id = :idchallenge ORDER BY p.id DESC");
        $q->execute(["idchallenge" => $idchallenge]);

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function participant($id){
    global $db;

    try {
        $q = $db->prepare("SELECT prenom, p.nom as nom, p.id as id, c.nom as nomcohorte, cohorte_id, existant
         FROM participant p, cohortes c WHERE p.cohorte_id = c.id AND p.id = :id ORDER BY p.id DESC");
        $q->execute(["id" => $id]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function ajouterParticipant($prenom, $nom, $cohorte_id, $challenge_id){
    global $db;

    try {
        $q = $db->prepare("INSERT INTO participant(prenom, nom, cohorte_id, challenge_id) VALUES(:prenom, :nom, :cohorte_id, :challenge_id)");
        return $q->execute([
            "prenom" => ucfirst($prenom),
            "nom" => ucfirst($nom),
            "cohorte_id" => $cohorte_id,
            "challenge_id" => $challenge_id,
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function supprimerMatches($challenge_id){
    global $db;

    try {
        $q = $db->prepare("DELETE FROM matches WHERE challenge_id =:challenge_id");
        return $q->execute(["challenge_id" => $challenge_id]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function matches($idchallenge){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM matches WHERE challenge_id = :idchallenge");
        $q->execute(["idchallenge" => $idchallenge]);

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function parcours($idpart){
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
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function cursus($idpart){
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
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function ajouterMatch($id_part1, $id_part2, $challenge_id, $gagnant = null, $statut = 0){
    global $db;

    try {
        $q = $db->prepare("INSERT INTO matches(id_part1, id_part2, challenge_id, gagnant_id, statut) VALUES(:id_part1, :id_part2, :challenge_id, :gagnant, :statut)");
        return $q->execute([
            "id_part1" => $id_part1,
            "id_part2" => $id_part2,
            "challenge_id" => $challenge_id,
            "gagnant" => $gagnant,
            "statut" => $statut,
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function gagner($id, $gagnant){
    global $db;

    try {
        $q = $db->prepare("UPDATE matches SET gagnant_id =:gagnant, statut =:statut WHERE id =:id");
        return $q->execute([
            "id" => $id,
            "gagnant" => $gagnant,
            "statut" => 1,
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function ajouterCohorte($nom){
    global $db;

    try {
        $q = $db->prepare("INSERT INTO cohortes(nom) VALUES(:nom)");
        return $q->execute([
            "nom" => ucfirst($nom),
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function cohortes(){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM cohortes ORDER BY id DESC");
        $q->execute();

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function cohorte($id){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM cohortes WHERE id =:id");
        $q->execute([
            "id" => $id,
        ]);

        return $q->fetch();

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function challenges(){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges ORDER BY id DESC");
        $q->execute();

        return $q->fetchAll();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function dernierChallenge($parent_id){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges WHERE parent_id=:parent_id ORDER BY id DESC");
        $q->execute(["parent_id" => $parent_id]);

        return $q->fetch();
    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function supprimerChallenge($id){
    global $db;

    try {
        $q = $db->prepare("DELETE FROM challenges WHERE id =:id");
        return $q->execute(["id" => $id]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function ajouterChallenge($nom, $debut, $statut = 0){
    global $db;

    try {
        $q = $db->prepare("INSERT INTO challenges(nom, debut, statut) VALUES(:nom, :debut, :statut)");
        return $q->execute([
            "nom" => ucfirst($nom),
            "debut" => $debut,
            "statut" => $statut,
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}


function changerStatut($id, $statut){
    global $db;

    try {
        $q = $db->prepare("UPDATE challenges SET statut =:statut WHERE id =:id");
        return $q->execute([
            "statut" => $statut,
            "id" => $id,
        ]);

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function challenge($id){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM challenges WHERE id =:id");
        $q->execute([
            "id" => $id,
        ]);

        return $q->fetch();

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}

function verifierChallenge($id){
    global $db;

    try {
        $q = $db->prepare("SELECT * FROM matches WHERE challenge_id =:id AND statut = :statut");
        $q->execute([
            "id" => $id,
            "statut" => 0,
        ]);

        return $q->fetchALL();

    } catch (PDOException $th) {
        setmessage("Erreur: ".$th->getMessage()." a la ligne: ".__LINE__, "danger");
    }
}
