<div class="card container col-md-10 mt-5 mb-5" style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">La liste des cohortes</h5>
        <div class="col-md-4 text-end">
            <a href="?page=cohorte&type=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ajouter</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>

    <table class="table table-striped table-bordered align-middle mb-0 bg-white">
        <thead class="bg-light text-center">
            <tr>
            <th>Nom</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach($cohortes as $c): ?>
            <tr>
                <td>
                    <?= $c->nom ?>
                </td>
                <td>
                <a href="?page=cohorte&type=edit&id=<?= $c->id ?>" class="btn btn-info btn-sm rounded-pill"><i class="fa fa-eye"></i></a>
                <a href="" class="btn btn-danger btn-sm rounded-pill"><i class="fa fa-trash"></i></a>
                    
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
</div>