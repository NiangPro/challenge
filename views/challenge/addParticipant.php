<div class="card container col-md-10 mt-5 mb-5">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">Formulaire d'<?= isset($c) ? "edition" : "ajout" ?> Participant</h5>
        <div class="col-md-4 text-end">
            <a href="?page=challenge&type=edit&id=<?= $_GET["id"] ?>" class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Retour</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>
    <form class="" method="post">
        <div class="row">
            <div class="col-md-6">
 <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" value="<?= getInputData('prenom', isset($p) ? $p : '') ?>" name="prenom" id="form1Example1" class="form-control" />
                    <label class="form-label" for="form1Example1">Prenom</label>
                </div>
            </div>
            <div class="col-md-6">
            <div data-mdb-input-init class="form-outline mb-4">
                    <input type="text" value="<?= getInputData('nom', isset($p) ? $p : '') ?>" name="nom" id="form1Example1" class="form-control" />
                    <label class="form-label" for="form1Example1">Nom</label>
                </div>
            </div>
            <div data-mdb-input-init class="form-group mb-4">
                <label  for="inlineFormSelectPref">Cohorte</label>
                <select data-mdb-select-init name="cohorte_id" class="form-control select">
                    <option value="">Veuillez choisir la cohorte</option>    
                    <?php foreach($cohortes as $co): ?>
                        <option value="<?= $co->id ?>"><?= $co->nom ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
        </div>
        <?php if(isset($p)): ?>
            <button data-mdb-ripple-init name="modifier" type="submit" class="btn btn-warning">Modifier</button>
        <?php else: ?>
            <button data-mdb-ripple-init name="ajouterParticipant" type="submit" class="btn btn-success">Ajouter</button>
        <?php endif; ?>
    </form>
  </div>
</div>

