
<div class="card container col-md-10 mt-5 mb-5" style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">
        <h5 class="card-title col-md-8">Tirage au sort des matchs</h5>
        <div class="col-md-4 text-end">
            <a href="?page=challenge&type=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ajouter un challenge</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>

    <!-- Formulaire de sélection de tournoi -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form action="" method="post" class="form-inline">
                <div class="input-group">
                    <select name="tournoi" id="tournoi" class="form-control">
                        <option value="">Sélectionner un tournoi</option>
                        <?php foreach($challenges as $c): ?>
                            <option value="<?= $c->id ?>" <?= isset($_POST['tournoi']) && $_POST['tournoi'] == $c->id ? 'selected' : '' ?>>
                                <?= $c->nom ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="afficher" class="btn btn-primary">Afficher</button>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($_POST['afficher']) && !empty($_POST['tournoi'])): 
        // Récupérer les matchs du tournoi sélectionné
        $tournoi_id = $_POST['tournoi'];
        $matchs = matches($tournoi_id);
        $challenge = challenge($tournoi_id);
    ?>
        <h4 class="text-center mb-4"><?= $challenge->nom ?></h4>
        
        <?php if(empty($matchs)): ?>
            <div class="alert alert-info">Aucun match trouvé pour ce tournoi.</div>
        <?php else: ?>
            <div class="container bracket">
                <?php
                // Déterminer le nombre de rounds nécessaires
                $totalMatches = count($matchs);
                $rounds = 1;
                $matchesInFirstRound = $totalMatches;
                
                // Si nous avons plus de 2 matchs, nous avons besoin de plusieurs rounds
                if ($totalMatches > 2) {
                    $rounds = ceil(log($totalMatches, 2)) + 1;
                    $matchesInFirstRound = pow(2, $rounds - 1) / 2;
                }
                
                // Créer les rounds
                for ($i = 1; $i <= $rounds; $i++):
                ?>
                    <div class="round">
                        <?php if ($i == 1): // Premier round avec tous les matchs initiaux ?>
                            <?php foreach ($matchs as $index => $match): 
                                $p1 = participant($match->id_part1);
                                $p2 = participant($match->id_part2);
                                $p1Name = $p1->prenom . ' ' . $p1->nom;
                                $p2Name = $p2 ? $p2->prenom . ' ' . $p2->nom : 'À déterminer';
                                $winner = '';
                                
                                if ($match->statut == 1 && $match->gagnant_id) {
                                    $gagnant = participant($match->gagnant_id);
                                    $winner = ' - Gagnant: ' . $gagnant->prenom . ' ' . $gagnant->nom;
                                }
                                
                                $matchClass = $match->statut == 1 ? 'match completed' : 'match pending';
                            ?>
                                <div class="<?= $matchClass ?>">
                                    <div class="match-info">
                                        <div class="participant <?= ($match->statut == 1 && $match->gagnant_id == $match->id_part1) ? 'winner' : '' ?>">
                                            <?= $p1Name ?>
                                        </div>
                                        <div class="vs">VS</div>
                                        <div class="participant <?= ($match->statut == 1 && $match->gagnant_id == $match->id_part2) ? 'winner' : '' ?>">
                                            <?= $p2Name ?>
                                        </div>
                                    </div>
                                    <?php if ($match->statut == 1 && $match->gagnant_id): ?>
                                        <div class="winner-badge">
                                            <i class="fa fa-trophy"></i> <?= $gagnant->prenom . ' ' . $gagnant->nom ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($index < count($matchs) - 1 && $index % 2 == 0): ?>
                                    <div class="connector"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php elseif ($i == $rounds): // Dernier round (finale) ?>
                            <div class="match final">
                                <div class="match-title">Finale</div>
                                <div class="trophy-icon">🏆</div>
                            </div>
                        <?php else: // Rounds intermédiaires ?>
                            <?php 
                            $matchesInRound = pow(2, $rounds - $i) / 2;
                            for ($j = 0; $j < $matchesInRound; $j++): 
                            ?>
                                <div class="match future">
                                    <div class="match-title">
                                        <?= $i == $rounds - 1 ? 'Demi-finale ' . ($j + 1) : 'Quart de finale ' . ($j + 1) ?>
                                    </div>
                                </div>
                                <?php if ($j < $matchesInRound - 1): ?>
                                    <div class="connector"></div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<style>
    .bracket {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 50px;
        overflow-x: auto;
        padding: 20px 0;
    }
    
    .round {
        display: flex;
        flex-direction: column;
        gap: 30px;
        min-width: 250px;
    }
    
    .match {
        border: 2px solid #0d6efd;
        border-radius: 10px;
        background-color: #f8f9fa;
        padding: 15px;
        position: relative;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .match.completed {
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .match.pending {
        border-color: #ffc107;
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .match.future {
        border-color: #6c757d;
        background-color: rgba(108, 117, 125, 0.1);
        border-style: dashed;
    }
    
    .match.final {
        border-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
        min-height: 150px;
    }
    
    .match-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .participant {
        padding: 5px;
        border-radius: 5px;
    }
    
    .participant.winner {
        background-color: rgba(40, 167, 69, 0.2);
        font-weight: bold;
    }
    
    .vs {
        font-weight: bold;
        color: #6c757d;
        text-align: center;
        margin: 5px 0;
    }
    
    .winner-badge {
        margin-top: 10px;
        text-align: center;
        color: #28a745;
        font-weight: bold;
    }
    
    .match-title {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
        color: #0d6efd;
    }
    
    .trophy-icon {
        font-size: 3rem;
        color: gold;
        text-align: center;
        margin-top: 10px;
    }
    
    .connector {
        height: 30px;
        position: relative;
    }
    
    .connector::before {
        content: "";
        position: absolute;
        top: 0;
        left: 50%;
        height: 100%;
        width: 2px;
        background-color: #0d6efd;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .bracket {
            flex-direction: column;
            align-items: center;
        }
        
        .round {
            width: 100%;
        }
    }
</style>