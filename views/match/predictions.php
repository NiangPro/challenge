<?php
// Récupérer les matchs à venir (statut = 0)
$upcomingMatches = [];
try {
    $sql = "SELECT 
                m.id, m.challenge_id,
                c.nom as challenge_nom,
                p1.id as p1_id, p1.prenom as p1_prenom, p1.nom as p1_nom, 
                p2.id as p2_id, p2.prenom as p2_prenom, p2.nom as p2_nom,
                co1.nom as p1_cohorte, co2.nom as p2_cohorte
            FROM matches m
            JOIN challenges c ON m.challenge_id = c.id
            JOIN participants p1 ON m.id_part1 = p1.id
            LEFT JOIN participants p2 ON m.id_part2 = p2.id
            LEFT JOIN cohortes co1 ON p1.cohorte_id = co1.id
            LEFT JOIN cohortes co2 ON p2.cohorte_id = co2.id
            WHERE m.statut = 0 AND m.id_part2 IS NOT NULL
            ORDER BY m.id ASC";
    $upcomingMatches = $db->query($sql)->fetchAll();
} catch (PDOException $e) {
    // Ignorer l'erreur
}

// Gérer les prédictions
$userPredictions = [];
$predictionSuccess = false;
$predictionError = false;

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']->id;
    
    // Récupérer les prédictions existantes de l'utilisateur
    try {
        $sql = "SELECT match_id, predicted_winner FROM predictions WHERE user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $predictions = $stmt->fetchAll();
        
        foreach ($predictions as $prediction) {
            $userPredictions[$prediction->match_id] = $prediction->predicted_winner;
        }
    } catch (PDOException $e) {
        // La table n'existe peut-être pas encore
    }
    
    // Traiter le formulaire de prédiction
    if (isset($_POST['submit_prediction'])) {
        $matchId = $_POST['match_id'] ?? 0;
        $predictedWinner = $_POST['predicted_winner'] ?? 0;
        
        if ($matchId && $predictedWinner) {
            try {
                // Vérifier si la table existe
                $db->query("SELECT 1 FROM predictions LIMIT 1");
            } catch (PDOException $e) {
                // Créer la table si elle n'existe pas
                $db->exec("CREATE TABLE IF NOT EXISTS predictions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    match_id INT NOT NULL,
                    predicted_winner INT NOT NULL,
                    prediction_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    UNIQUE KEY user_match (user_id, match_id)
                )");
            }
            
            try {
                // Vérifier si une prédiction existe déjà
                $sql = "SELECT id FROM predictions WHERE user_id = ? AND match_id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$userId, $matchId]);
                $existingPrediction = $stmt->fetch();
                
                if ($existingPrediction) {
                    // Mettre à jour la prédiction existante
                    $sql = "UPDATE predictions SET predicted_winner = ? WHERE id = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$predictedWinner, $existingPrediction->id]);
                } else {
                    // Insérer une nouvelle prédiction
                    $sql = "INSERT INTO predictions (user_id, match_id, predicted_winner) VALUES (?, ?, ?)";
                    $stmt = $db->prepare($sql);
                    $stmt->execute([$userId, $matchId, $predictedWinner]);
                }
                
                $userPredictions[$matchId] = $predictedWinner;
                $predictionSuccess = true;
                
                // Créer une notification pour la prédiction
                $matchInfo = null;
                foreach ($upcomingMatches as $match) {
                    if ($match->id == $matchId) {
                        $matchInfo = $match;
                        break;
                    }
                }
                
                if ($matchInfo) {
                    $winnerName = "";
                    if ($predictedWinner == $matchInfo->p1_id) {
                        $winnerName = $matchInfo->p1_prenom . " " . $matchInfo->p1_nom;
                    } else if ($predictedWinner == $matchInfo->p2_id) {
                        $winnerName = $matchInfo->p2_prenom . " " . $matchInfo->p2_nom;
                    }
                    
                    $message = "Vous avez prédit que {$winnerName} remportera le match dans le tournoi {$matchInfo->challenge_nom}";
                    createNotification($_SESSION['user']->id, $message, "info", "?page=match&view=predictions");
                }
            } catch (PDOException $e) {
                $predictionError = true;
            }
        }
    }
}
?>

<div class="card container col-md-12 mt-5 mb-5">
    <div class="card-body">
        <h5 class="card-title">Prédictions des matchs</h5>
        
        <?php if ($predictionSuccess): ?>
            <div class="alert alert-success">
                Votre prédiction a été enregistrée avec succès !
            </div>
        <?php endif; ?>
        
        <?php if ($predictionError): ?>
            <div class="alert alert-danger">
                Une erreur s'est produite lors de l'enregistrement de votre prédiction.
            </div>
        <?php endif; ?>
        
        <?php if (!isset($_SESSION['user'])): ?>
            <div class="alert alert-warning">
                Connectez-vous pour faire des prédictions et gagner des points !
            </div>
        <?php endif; ?>
        
        <?php if (empty($upcomingMatches)): ?>
            <div class="alert alert-info">
                Aucun match à venir pour le moment.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($upcomingMatches as $match): 
                    $p1Name = $match->p1_prenom . ' ' . $match->p1_nom;
                    $p2Name = $match->p2_prenom . ' ' . $match->p2_nom;
                    $userPrediction = $userPredictions[$match->id] ?? null;
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <?= $match->challenge_nom ?>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-5 text-center">
                                        <div class="participant-avatar">
                                            <i class="fa fa-user-circle fa-3x"></i>
                                        </div>
                                        <p class="mt-2"><?= $p1Name ?></p>
                                        <small class="text-muted"><?= $match->p1_cohorte ?></small>
                                    </div>
                                    <div class="col-2 text-center">
                                        <span class="vs-badge">VS</span>
                                    </div>
                                    <div class="col-5 text-center">
                                        <div class="participant-avatar">
                                            <i class="fa fa-user-circle fa-3x"></i>
                                        </div>
                                        <p class="mt-2"><?= $p2Name ?></p>
                                        <small class="text-muted"><?= $match->p2_cohorte ?></small>
                                    </div>
                                </div>
                                
                                <?php if (isset($_SESSION['user'])): ?>
                                    <form action="" method="post" class="mt-3">
                                        <input type="hidden" name="match_id" value="<?= $match->id ?>">
                                        <div class="form-group">
                                            <label>Votre prédiction :</label>
                                            <div class="btn-group w-100" role="group">
                                                <button type="submit" name="predicted_winner" value="<?= $match->p1_id ?>" class="btn <?= $userPrediction == $match->p1_id ? 'btn-success' : 'btn-outline-primary' ?>">
                                                    <?= $p1Name ?>
                                                </button>
                                                <button type="submit" name="predicted_winner" value="<?= $match->p2_id ?>" class="btn <?= $userPrediction == $match->p2_id ? 'btn-success' : 'btn-outline-primary' ?>">
                                                    <?= $p2Name ?>
                                                </button>
                                            </div>
                                            <input type="hidden" name="submit_prediction" value="1">
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-light mt-3 text-center">
                                        <a href="?page=login">Connectez-vous</a> pour prédire
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.participant-avatar {
    margin-bottom: 10px;
    color: #007bff;
}

.vs-badge {
    display: inline-block;
    background-color: #f8f9fa;
    color: #6c757d;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 50%;
    border: 1px solid #dee2e6;
}
</style>
