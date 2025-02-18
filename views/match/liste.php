<?php if (isset($matches)): ?>
    <?php if (count($matches) > 0): ?>
    <div class="card container mb-5" >
        <div class="card-body">
            <div class="row mb-3">
                <!-- <h5 class="card-title col-md-8"> <?php //echo (count($matches) == 1 && count($etat) == 0) ? '🏆 Vainqueur 🏆' : "Liste des Matches à venir" ?></h5> -->
                <h1 class="card-title col-md-8"><?= niveauChallenge($_GET["challenge"]) ?></h1>
                <?php if (count($etat) == 0 && count($matches) > 1): ?>
                <div class="col-md-4 text-end">
                    <a href="?page=match&challenge=<?= $_GET['challenge'] ?>&next" class="btn btn-info btn-sm">Passer au prochain tour <i class="fa fa-arrow-right"></i> </a>
                </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <?php foreach($matches as $m): ?>
                    <div class="col-md-3 p-2">
                        <div class="card <?php if(count($matches) == 1 && $m->statut == 1){ echo 'bg-info text-white';}else if($m->statut == 1){ echo 'bg-dark text-white';}else{ echo '';}  ?>">
                            <div class="card-body text-center">
                                <p>
                                    <?= $m->statut == 0 ? afficheParticipant(participant($m->id_part1)) : afficheParticipant(participant($m->gagnant_id)) ?>
                                    <?php if ($m->statut == 0): ?>
                                    <br><a href="?page=match&challenge=<?= $_GET['challenge'] ?>&match=<?= $m->id ?>&gagnant=<?= $m->id_part1 ?>" class="btn btn-success btn-sm rounded-pill"><i class="fa fa-check" title="gagner"></i></a>
                                    <?php endif; ?>
                                </p>
                                <?php if ($m->id_part2 && $m->statut == 0): ?>
                                        <h5>Vs</h5>
                                        <p>
                                            <?= afficheParticipant(participant($m->id_part2)) ?>
                                            <?php if ($m->statut == 0): ?>
                                                <br><a href="?page=match&challenge=<?= $_GET['challenge'] ?>&match=<?= $m->id ?>&gagnant=<?= $m->id_part2 ?>" class="btn btn-success btn-sm rounded-pill"><i class="fa fa-check" title="gagner"></i></a>
                                            <?php endif; ?>
                                        </p>
                                <?php elseif(count($matches) == 1 && $m->statut == 1): ?>
                                    <h2 class="text-dark">Félicitations 🏆</h2>
                                <?php elseif($m->id_part2 && $m->statut == 1): ?>
                                        <span class="text-success">qualifié(e)</span>
                                <?php else: ?>
                                    <span class="text-success">Dèjà qualifié(e)</span>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div
        class="alert alert-info container"
        role="alert"
    >
        <strong>Aucun match pour le moment !</strong>
    </div>
    
<?php endif; ?>
<?php endif; ?>