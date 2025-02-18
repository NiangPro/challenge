<div class="card container col-md-10 mt-5 mb-5"  style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">La liste des challenges</h5>
        <div class="col-md-4 text-end">
            <a href="?page=challenge&type=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ajouter</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>

    <table class="table table-bordered align-middle mb-0 bg-white">
        <thead class="bg-light text-center">
            <tr>
            <th>Nom</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach($challenges as $c): ?>
                <?php if(!$c->parent_id): ?>
                <tr>
                    <td>
                        <?= $c->nom ?>
                    </td>
                    <td>
                        <?= date("d/m/Y à H:i:s", strtotime($c->debut)) ?>
                    </td>
                    <td>
                        <?php if($c->statut == 1): ?>
                        <span class="badge badge-success rounded-pill d-inline">Encours</span>
                        <?php elseif($c->statut == 2): ?>
                            <span class="badge badge-danger rounded-pill d-inline">Terminé</span>
                        <?php else: ?>
                            <span class="badge badge-info rounded-pill d-inline">En attente</span>
                        <?php endif; ?>

                    </td>
                    <td>
                    <?php if($c->statut == 0): ?>
                        <a href="?page=challenge&statut=valider&id=<?= $c->id ?>" class="btn btn-success btn-sm rounded-pill"><i class="fa fa-check" title="activer"></i></a>
                        <a href="?page=challenge&tirer&id=<?= $c->id ?>" class="btn btn-primary btn-sm rounded-pill"><i class="fa fa-crosshairs" title="tirer"></i></a>
                    <?php endif; ?>

                    <?php if($c->statut == 1): ?>
                        <a href="?page=challenge&tirer&id=<?= $c->id ?>" class="btn btn-primary btn-sm rounded-pill"><i class="fa fa-crosshairs" title="tirer"></i></a>
                        <a href="?page=challenge&statut=terminer&id=<?= $c->id ?>" class="btn btn-warning btn-sm rounded-pill"><i class="fa fa-stopwatch" title="activer"></i></a>
                    <?php endif; ?>
                    <!-- <a href="?page=challenge&statut=valider&id=<?= $c->id ?>" class="btn btn-success btn-sm rounded-pill"><i class="fa fa-check" title="activer"></i></a> -->

                    <a href="?page=challenge&type=edit&id=<?= $c->id ?>" class="btn btn-info btn-sm rounded-pill"><i class="fa fa-eye"></i></a>
                    <a href=""  data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal<?= $c->id ?>" class="btn btn-danger btn-sm rounded-pill"><i class="fa fa-trash"></i></a>
                        
                    <div class="modal fade" id="exampleModal<?= $c->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
                                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Êtes-vous sûr de vouloir supprimer?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-mdb-ripple-init data-mdb-dismiss="modal">Non</button>
                                <a href="?page=challenge&delete=<?= $c->id ?>" class="btn btn-success" data-mdb-ripple-init>Oui</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    </td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
</div>