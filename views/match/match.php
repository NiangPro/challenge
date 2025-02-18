<div class="card container col-md-8 mt-5 mb-5"  style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">Matches</h5>
       
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>
    <form class="" method="post">
                <div data-mdb-input-init class="form-group mb-4">
                    <label  for="inlineFormSelectPref">Challenges</label>
                    <select data-mdb-select-init name="challenge" class="form-control select">
                        <option value="">Veuillez choisir un challenge</option>    
                        <?php foreach($challenges as $co): ?>
                            <option value="<?= $co->id ?>" <?= (isset($_GET["challenge"]) && $_GET["challenge"] == $co->id) ? "selected" : "" ?> ><?= $co->nom ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
           <button data-mdb-ripple-init name="afficher" type="submit" class="btn btn-success">Selectionner</button>
    </form>
  </div>
</div>
<?php require_once("views/match/liste.php"); ?>
