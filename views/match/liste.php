<?php if (isset($_GET["challenge"])) : ?>
    <?php
    $challenge = challenge($_GET["challenge"]);
    $matchs = matches($_GET["challenge"]);
    $matchsTermines = matchesHistorique($_GET["challenge"]);
    ?>
    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <?= niveauChallenge($_GET["challenge"]) ?>
                                <small class="text-muted ms-2">Tour <?= $challenge->tour ?></small>
                            </h3>
                            <a href="?page=challenge" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="matches-container">
                            <!-- Matchs en cours -->
                            <div class="mb-5">
                                <h4 class="tour-title">Matchs en Cours</h4>
                                <div class="row">
                                    <?php if (count($matchs) > 0) : ?>
                                        <?php foreach ($matchs as $match) : ?>
                                    <?php if ($match->statut == 0) : ?>

                                            <?php
                                            $part1 = participant($match->id_part1);
                                            $part2 = $match->id_part2 ? participant($match->id_part2) : null;
                                            ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="match-card position-relative">
                                                    <div class="match-header">
                                                        Match #<?= $match->id ?>
                                                    </div>
                                                    <div class="match-status status-ongoing">
                                                        En cours
                                                    </div>
                                                    <div class="match-body">
                                                        <div class="match-versus">
                                                            <div class="match-player">
                                                                <div class="match-player-name">
                                                                    <?= $part1->prenom ?> <?= $part1->nom ?>
                                                                </div>
                                                                <div class="match-player-cohorte">
                                                                    <?= $part1->nomcohorte ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if ($part2) : ?>
                                                                <div class="match-vs">VS</div>
                                                                <div class="match-player">
                                                                    <div class="match-player-name">
                                                                        <?= $part2->prenom ?> <?= $part2->nom ?>
                                                                    </div>
                                                                    <div class="match-player-cohorte">
                                                                        <?= $part2->nomcohorte ?>
                                                                    </div>
                                                                </div>
                                                            <?php else : ?>
                                                                <div class="match-player">
                                                                    <div class="match-player-name text-muted">
                                                                        <em>En attente...</em>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="match-actions">
                                                            <a href="?page=match&gagnant=<?= $match->id_part1 ?>&match=<?= $match->id ?>&challenge=<?= $_GET["challenge"] ?>" 
                                                               class="btn btn-winner">
                                                                <i class="fas fa-trophy"></i>
                                                                <?= $part1->prenom ?>
                                                            </a>
                                                            <?php if ($part2) : ?>
                                                                <a href="?page=match&gagnant=<?= $match->id_part2 ?>&match=<?= $match->id ?>&challenge=<?= $_GET["challenge"] ?>" 
                                                                   class="btn btn-winner">
                                                                    <i class="fas fa-trophy"></i>
                                                                    <?= $part2->prenom ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <div class="col-12">
                                            <div class="alert alert-info text-center">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Aucun match en cours
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Matchs terminés -->
                            <?php 
                            $matchsTourActuel = array_filter($matchsTermines, function($m) use ($challenge) {
                                return $m->tour == $challenge->tour;
                            });
                            
                            if (count($matchsTourActuel) > 0) : 
                            ?>
                                <div>
                                    <h4 class="tour-title">Matchs Terminés</h4>
                                    <div class="row">
                                        <?php foreach ($matchsTourActuel as $match) : ?>
                                            <?php
                                            $part1 = participant($match->id_part1);
                                            $part2 = $match->id_part2 ? participant($match->id_part2) : null;
                                            $gagnant = participant($match->gagnant_id);
                                            ?>
                                            <div class="col-md-6 col-lg-4">
                                                <div class="match-card position-relative">
                                                    <div class="match-header">
                                                        Match #<?= $match->id ?>
                                                    </div>
                                                    <div class="match-status status-completed">
                                                        Terminé
                                                    </div>
                                                    <div class="match-body">
                                                        <div class="match-versus">
                                                            <div class="match-player">
                                                                <div class="match-player-name <?= $match->gagnant_id == $part1->id ? 'text-success fw-bold' : '' ?>">
                                                                    <?= $part1->prenom ?> <?= $part1->nom ?>
                                                                </div>
                                                                <div class="match-player-cohorte">
                                                                    <?= $part1->nomcohorte ?>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if ($part2) : ?>
                                                                <div class="match-vs">VS</div>
                                                                <div class="match-player">
                                                                    <div class="match-player-name <?= $match->gagnant_id == $part2->id ? 'text-success fw-bold' : '' ?>">
                                                                        <?= $part2->prenom ?> <?= $part2->nom ?>
                                                                    </div>
                                                                    <div class="match-player-cohorte">
                                                                        <?= $part2->nomcohorte ?>
                                                                    </div>
                                                                </div>
                                                            <?php else : ?>
                                                                <div class="match-player">
                                                                    <div class="match-player-name text-muted">
                                                                        <em>Qualifié(e) directement</em>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="text-center mt-3">
                                                            <div class="winner-badge">
                                                                <i class="fas fa-trophy"></i>
                                                                Gagnant : <?= $gagnant->prenom ?> <?= $gagnant->nom ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php 
                            $matchsEnCours = count($matchs);
                            $matchsTerminesTourActuel = count($matchsTourActuel);
                            $totalMatchsTourActuel = $matchsEnCours + $matchsTerminesTourActuel;
                            
                            if ($totalMatchsTourActuel > 0 && $matchsEnCours == 0) : 
                            ?>
                                <div class="text-center mt-4">
                                    <a href="?page=match&next&challenge=<?= $_GET["challenge"] ?>" 
                                       class="next-tour-btn">
                                        <i class="fas fa-forward me-2"></i>
                                        Passer au tour suivant
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>