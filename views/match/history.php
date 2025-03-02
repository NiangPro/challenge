<?php
// Récupérer l'historique des matchs
$matchHistory = [];
try {
    $sql = "SELECT 
                m.id, m.challenge_id, m.statut, m.gagnant_id,
                c.nom as challenge_nom,
                p1.prenom as p1_prenom, p1.nom as p1_nom, 
                p2.prenom as p2_prenom, p2.nom as p2_nom,
                co1.nom as p1_cohorte, co2.nom as p2_cohorte
            FROM matches m
            JOIN challenges c ON m.challenge_id = c.id
            JOIN participants p1 ON m.id_part1 = p1.id
            LEFT JOIN participants p2 ON m.id_part2 = p2.id
            LEFT JOIN cohortes co1 ON p1.cohorte_id = co1.id
            LEFT JOIN cohortes co2 ON p2.cohorte_id = co2.id
            ORDER BY m.id DESC
            LIMIT 50";
    $matchHistory = $db->query($sql)->fetchAll();
} catch (PDOException $e) {
    // Ignorer l'erreur
}
?>

<?php if (isset($_GET["challenge"])) : ?>
    <?php
    $challenge = challenge($_GET["challenge"]);
    $historique = matchesHistorique($_GET["challenge"]);
    ?>
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center">
                    Historique des Matchs - <?= $challenge->nom ?>
                </h3>
                <hr>
                <?php if (count($historique) > 0) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tour</th>
                                    <th>Participant 1</th>
                                    <th class="text-center">VS</th>
                                    <th>Participant 2</th>
                                    <th>Gagnant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historique as $match) : ?>
                                    <?php
                                    $part1 = participant($match->id_part1);
                                    $part2 = $match->id_part2 ? participant($match->id_part2) : null;
                                    $gagnant = participant($match->gagnant_id);
                                    $nomTour = match($match->tour) {
                                        1 => "Premier tour",
                                        2 => "Deuxième tour",
                                        3 => "Quarts de finale",
                                        4 => "Demi-finales",
                                        5 => "Finale",
                                        default => "Tour " . $match->tour
                                    };
                                    ?>
                                    <tr>
                                        <td class="align-middle"><?= $nomTour ?></td>
                                        <td>
                                            <?= $part1->prenom ?> <?= $part1->nom ?><br>
                                            <small class="text-muted"><?= $part1->nomcohorte ?></small>
                                        </td>
                                        <td class="text-center align-middle">VS</td>
                                        <td>
                                            <?php if ($part2) : ?>
                                                <?= $part2->prenom ?> <?= $part2->nom ?><br>
                                                <small class="text-muted"><?= $part2->nomcohorte ?></small>
                                            <?php else : ?>
                                                <span class="text-muted">Qualifié(e) directement</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <span class="text-success">
                                                <i class="fas fa-trophy"></i>
                                                <?= $gagnant->prenom ?> <?= $gagnant->nom ?>
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <?= isset($match->match_date) ? date('d/m/Y H:i', strtotime($match->match_date)) : 'Date non disponible' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <div class="alert alert-info">
                        Aucun match terminé pour ce challenge.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card container col-md-12 mt-5 mb-5">
    <div class="card-body">
        <h5 class="card-title">Historique des matchs</h5>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Challenge</th>
                        <th>Participant 1</th>
                        <th>Participant 2</th>
                        <th>Gagnant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($matchHistory)): ?>
                        <tr>
                            <td colspan="7" class="text-center">Aucun match trouvé</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($matchHistory as $match): 
                            $p1Name = $match->p1_prenom . ' ' . $match->p1_nom . ' (' . $match->p1_cohorte . ')';
                            $p2Name = $match->p2_prenom ? $match->p2_prenom . ' ' . $match->p2_nom . ' (' . $match->p2_cohorte . ')' : 'N/A';
                            
                            $gagnantName = 'En attente';
                            if ($match->statut == 1) {
                                if ($match->gagnant_id == $match->id_part1) {
                                    $gagnantName = $p1Name;
                                } else if ($match->id_part2 && $match->gagnant_id == $match->id_part2) {
                                    $gagnantName = $p2Name;
                                }
                            }
                            
                            $statusClass = $match->statut == 1 ? 'bg-success' : 'bg-warning';
                            $statusText = $match->statut == 1 ? 'Terminé' : 'En attente';
                        ?>
                            <tr>
                                <td><?= $match->id ?></td>
                                <td><?= $match->challenge_nom ?></td>
                                <td><?= $p1Name ?></td>
                                <td><?= $p2Name ?></td>
                                <td><?= $gagnantName ?></td>
                                <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                <td>
                                    <a href="?page=match&challenge=<?= $match->challenge_id ?>" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
