<div class="card container col-md-10 mt-5 mb-5" style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">Formulaire d'<?= isset($c) ? "edition" : "ajout" ?> challenges</h5>
        <div class="col-md-4 text-end">
            <a href="?page=challenge" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Retour</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>
    <form class="" method="post">
        <div class="row">
            <div class="col-md-6">
 <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" value="<?= getInputData('nom', isset($c) ? $c : '') ?>" name="nom" id="form1Example1" class="form-control" />
                    <label class="form-label" for="form1Example1">Nom</label>
                </div>
            </div>
            <div class="col-md-6">
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="datetime-local"  value="<?= getInputData('debut', isset($c) ? $c : '')  ?>" id="form1Example2"  name="debut" class="form-control" />
                    <label class="form-label" for="form1Example2">Date</label>
                </div>
            </div>
            
        </div>
        <?php if(isset($c)): ?>
            <button data-mdb-ripple-init name="modifier" type="submit" class="btn btn-warning">Modifier</button>
        <?php else: ?>
            <button data-mdb-ripple-init name="ajouter" type="submit" class="btn btn-success">Ajouter</button>
        <?php endif; ?>
    </form>
  </div>
</div>

<?php if(isset($games) && count($games) > 0): ?>
    <?php require_once("views/challenge/parcours.php"); ?>
<?php else: ?>

    <?php if(isset($gagnant) && $gagnant): ?>
        <div class="d-flex justify-content-center">
        <div class="card col-md-3">
        <div class="bg-image hover-overlay" data-mdb-ripple-init data-mdb-ripple-color="light">
            <img src="https://th.bing.com/th/id/OIP.d8VGCnGQN6BDnjHNgjffhwHaHa?pid=ImgDet&w=199&h=199&c=7&dpr=1.5" width="100%" style="height: 200px;" class="img-fluid"/>
            <a href="#!">
            <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
            </a>
        </div>
        <div class="card-body text-center">
            <h5 class="card-title"><?= $gagnant->prenom ?> <?= $gagnant->nom ?></h5>
            <p class="card-text ">(<?= $gagnant->nomcohorte ?>)</p>
            <p class="text-success">Félicitations 🏆</p>
            <a href="?page=challenge&type=edit&id=<?= $_GET["id"] ?>&idgagnant=<?= $gagnant->existant ?>" class="btn btn-info" data-mdb-ripple-init>Voir son parcours</a>
        </div>
        </div>
        </div>
    <?php endif; ?>

    <?php if(isset($c)): ?>
        <?php if(isset($_GET["sous"])): ?>
            <?php require_once("views/challenge/addParticipant.php"); ?>
            <?php else: ?>
        <div class="card container col-md-10 mt-5 mb-5">
            <div class="card-body">
                <div class="row mb-3">

                    <h5 class="card-title col-md-8">La liste des des participants (<span class="text-info"><?= count($participants) ?></span>)</h5>
                    <div class="col-md-4 text-end">
                        <a href="?page=challenge&type=edit&id=<?= $_GET["id"] ?>&sous=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ajouter</a>
                    </div>
                </div>

                <table class="table table-bordered align-middle mb-0 bg-white">
                    <thead class="bg-light text-center">
                        <tr>
                        <th>Prenom</th>
                        <th>Nom</th>
                        <th>Cohorte</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php foreach($participants as $p): ?>
                        <tr>
                            <td>
                                <?= $p->prenom ?>
                            </td>
                            <td>
                                <?= $p->nom ?>
                            </td>
                            <td>
                            <?= $p->nomcohorte ?>
                            </td>
                            
                            <td>
                            <a href="?page=challenge&type=edit&id=<?= $p->id ?>" class="btn btn-info btn-sm rounded-pill"><i class="fa fa-eye"></i></a>
                            <a href="" class="btn btn-danger btn-sm rounded-pill"><i class="fa fa-trash"></i></a>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>