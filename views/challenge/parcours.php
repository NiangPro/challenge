<div class="card container mb-5" >
        <div class="card-body">
            <div class="row mb-3">
                <h5 class="card-title col-md-8"> 🏆 Parcours du Vainqueur 🏆</h5>
                <div class="col-md-4 text-end">
                    <a href="?page=challenge&type=edit&id=<?= $_GET['id'] ?>" class="btn btn-info btn-sm">Retour <i class="fa fa-arrow-right"></i> </a>
                </div>
            </div>
            <div class="row">
                <?php foreach($games as $m): ?>
                    <div class="col-md-3 p-2">
                        <div class="card bg-dark">
                            <div class="card-body text-center">
                                <h3 class="text-white"><?= niveauChallenge($m->challenge_id) ?></h3>
                                <p class="<?= $m->id_part1 == $m->gagnant_id ? 'text-success' : 'text-danger' ?>">
                                    <?= afficheParticipant(participant($m->id_part1)) ?>
                                </p>
                                <?php if ($m->id_part2): ?>
                                        <h5>Vs</h5>
                                        <p class="<?= $m->id_part2 == $m->gagnant_id ? 'text-success' : 'text-danger' ?>">
                                            <?= afficheParticipant(participant($m->id_part2)) ?>
                                            
                                        </p>
                                <?php else: ?>
                                    <span class="text-success">Au tirage</span>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                
                <?php endforeach; ?>
            </div>
        </div>
    </div>