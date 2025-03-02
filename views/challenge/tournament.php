<?php
// Debug
error_log("Début de la vue tournament.php");

if (!isset($_GET["id"])) {
    header("Location:?page=challenge");
}

function getMatchesByTour($challenge_id) {
    global $db;
    try {
        // Récupérer le challenge pour avoir le tour actuel
        $challenge = challenge($challenge_id);
        error_log("Challenge ID: " . $challenge_id . ", Tour actuel: " . $challenge->tour);

        // Récupérer tous les matchs du challenge
        $q = $db->prepare("
            SELECT m.*, 
                   p1.prenom as p1_prenom, p1.nom as p1_nom,
                   p2.prenom as p2_prenom, p2.nom as p2_nom,
                   m.tour,
                   COALESCE(m.updated_at, m.created_at) as match_date
            FROM matches m 
            LEFT JOIN participant p1 ON m.id_part1 = p1.id
            LEFT JOIN participant p2 ON m.id_part2 = p2.id
            WHERE m.challenge_id = :challenge_id
            ORDER BY m.tour ASC, m.id ASC
        ");
        
        $q->execute(["challenge_id" => $challenge_id]);
        $matches = $q->fetchAll(PDO::FETCH_OBJ);
        
        error_log("Nombre total de matchs trouvés : " . count($matches));

        // Organiser les matchs par tour
        $matchesByTour = [];
        foreach ($matches as $match) {
            $tour = $match->tour;
            if (!isset($matchesByTour[$tour])) {
                $matchesByTour[$tour] = [];
            }
            $matchesByTour[$tour][] = $match;
            error_log("Match ajouté au tour " . $tour . ": " . $match->p1_prenom . " vs " . ($match->p2_prenom ?? 'qualifié directement'));
        }

        return $matchesByTour;
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des matchs par tour : " . $e->getMessage());
        return [];
    }
}

// Récupérer les matchs
$challenge_id = $_GET["id"];
$matchesByTour = getMatchesByTour($challenge_id);
$maxTours = count($matchesByTour) > 0 ? max(array_keys($matchesByTour)) : 1;

error_log("Nombre de tours trouvés : " . $maxTours);
?>

<!-- Inclure le CSS -->
<link rel="stylesheet" href="assets/css/tournament.css">

<div class="container-fluid mt-4" style="margin-top: 90px!important;">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><?= niveauChallenge($_GET["id"]) ?></h3>
                <a href="?page=match&challenge=<?= $_GET["id"] ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux matchs
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="tournament-container">
                <?php for ($tour = 1; $tour <= $maxTours; $tour++) : ?>
                    <div class="tournament-round">
                        <div class="round-title">
                            Tour <?= $tour ?>
                        </div>
                        
                        <?php if (isset($matchesByTour[$tour])) : ?>
                            <div class="row">
                            <?php foreach ($matchesByTour[$tour] as $match) : ?>
                                <div class="match-wrapper col-md-3 p-2">
                                    <div class="match-container">
                                        <?php 
                                        $winner_id = $match->gagnant_id;
                                        $part1 = participant($match->id_part1);
                                        $part2 = $match->id_part2 ? participant($match->id_part2) : null;
                                        ?>
                                        <div class="match-player <?= $winner_id == $match->id_part1 ? 'winner' : '' ?>">
                                            <?= $part1->prenom ?> <?= $part1->nom ?>
                                            <?php if ($winner_id == $match->id_part1) : ?>
                                                <i class="fas fa-trophy text-warning ms-2"></i>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($match->id_part2): ?>
                                        <div class="match-vs">VS</div>
                                        <?php endif; ?>
                                        <?php if ($part2) : ?>
                                            <div class="match-player <?= $winner_id == $match->id_part2 ? 'winner' : '' ?>">
                                                <?= $part2->prenom ?> <?= $part2->nom ?>
                                                <?php if ($winner_id == $match->id_part2) : ?>
                                                    <i class="fas fa-trophy text-warning ms-2"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php else : ?>
                                            <div class="match-player text-muted">
                                                <em>Qualifié(e) directement</em>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="match-connector"></div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div>

<?php error_log("Fin de la vue tournament.php"); ?>
