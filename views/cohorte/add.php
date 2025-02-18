<div class="card container col-md-10 mt-5 mb-5" style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">Formulaire d'<?= isset($c) ? "edition" : "ajout" ?> cohorte</h5>
        <div class="col-md-4 text-end">
            <a href="?page=cohorte" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"></i> Retour</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>
    <form class="" method="post">
        <div class="row">
            <div class="col-md-12">
 <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" value="<?= getInputData('nom', isset($c) ? $c : '') ?>" name="nom" id="form1Example1" class="form-control" />
                    <label class="form-label" for="form1Example1">Nom</label>
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

