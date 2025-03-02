<?php
// Récupérer tous les challenges liés au tournoi actuel
$tournamentChallenges = [];
if (isset($challenge) && $challenge) {
    // Récupérer le challenge parent (racine du tournoi)
    $rootChallenge = $challenge;
    if ($challenge->parent_id) {
        try {
            $stmt = $db->prepare("SELECT * FROM challenges WHERE id = ?");
            $stmt->execute([$challenge->parent_id]);
            $rootChallenge = $stmt->fetch();
        } catch (PDOException $e) {
            // Ignorer l'erreur
        }
    }
    
    // Récupérer tous les challenges liés à ce tournoi
    try {
        $stmt = $db->prepare("SELECT * FROM challenges WHERE id = ? OR parent_id = ? ORDER BY id ASC");
        $stmt->execute([$rootChallenge->id, $rootChallenge->id]);
        $tournamentChallenges = $stmt->fetchAll();
    } catch (PDOException $e) {
        // Ignorer l'erreur
    }
}
?>

<div class="card container col-md-12 mt-5 mb-5">
    <div class="card-body">
        <h5 class="card-title">Arbre du tournoi</h5>
        
        <?php if (empty($tournamentChallenges)): ?>
            <div class="alert alert-info">
                Sélectionnez un challenge pour voir l'arbre du tournoi.
            </div>
        <?php else: ?>
            <div class="tournament-tree-container">
                <div class="tournament-tree">
                    <?php 
                    // Organiser les challenges par niveau
                    $levels = [];
                    foreach ($tournamentChallenges as $c) {
                        $level = 0;
                        if (strpos($c->nom, '_final') !== false) {
                            $level = 999; // Finale
                        } elseif (preg_match('/_(\d+)$/', $c->nom, $matches)) {
                            $level = (int)$matches[1];
                        }
                        if (!isset($levels[$level])) {
                            $levels[$level] = [];
                        }
                        $levels[$level][] = $c;
                    }
                    
                    // Trier les niveaux
                    ksort($levels);
                    
                    // Afficher l'arbre du tournoi
                    foreach ($levels as $level => $levelChallenges): 
                        $levelName = $level === 999 ? "Finale" : "Tour " . $level;
                    ?>
                        <div class="tournament-level">
                            <h6 class="level-title"><?= $levelName ?></h6>
                            <?php foreach ($levelChallenges as $c): 
                                // Récupérer les matchs de ce challenge
                                $challengeMatches = [];
                                try {
                                    $stmt = $db->prepare("SELECT * FROM matches WHERE challenge_id = ?");
                                    $stmt->execute([$c->id]);
                                    $challengeMatches = $stmt->fetchAll();
                                } catch (PDOException $e) {
                                    // Ignorer l'erreur
                                }
                            ?>
                                <div class="tournament-matches">
                                    <?php foreach ($challengeMatches as $match): 
                                        $participant1 = participant($match->id_part1);
                                        $participant2 = $match->id_part2 ? participant($match->id_part2) : null;
                                        $winner = $match->gagnant_id ? participant($match->gagnant_id) : null;
                                        
                                        $p1Name = $participant1 ? $participant1->prenom . ' ' . $participant1->nom : 'TBD';
                                        $p2Name = $participant2 ? $participant2->prenom . ' ' . $participant2->nom : 'TBD';
                                        
                                        $matchClass = 'match-pending';
                                        if ($match->statut == 1) {
                                            $matchClass = 'match-completed';
                                        }
                                    ?>
                                        <div class="tournament-match <?= $matchClass ?>">
                                            <div class="match-participant <?= ($winner && $winner->id == $match->id_part1) ? 'match-winner' : '' ?>">
                                                <?= $p1Name ?>
                                            </div>
                                            <div class="match-vs">VS</div>
                                            <div class="match-participant <?= ($winner && $winner->id == $match->id_part2) ? 'match-winner' : '' ?>">
                                                <?= $p2Name ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.tournament-tree-container {
    overflow-x: auto;
    margin: 20px 0;
}

.tournament-tree {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    min-width: 100%;
}

.tournament-level {
    display: flex;
    flex-direction: column;
    margin-right: 40px;
    min-width: 250px;
}

.level-title {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
    background-color: #f8f9fa;
    padding: 8px;
    border-radius: 4px;
}

.tournament-matches {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.tournament-match {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    position: relative;
}

.tournament-match::after {
    content: '';
    position: absolute;
    right: -40px;
    top: 50%;
    width: 40px;
    height: 2px;
    background-color: #dee2e6;
}

.tournament-level:last-child .tournament-match::after {
    display: none;
}

.match-participant {
    padding: 5px;
    border-radius: 4px;
}

.match-winner {
    background-color: #d4edda;
    color: #155724;
    font-weight: bold;
}

.match-vs {
    text-align: center;
    font-weight: bold;
    margin: 5px 0;
    color: #6c757d;
}

.match-completed {
    border-color: #28a745;
}

.match-pending {
    border-color: #ffc107;
}
</style>
