<div class="container-fluid py-4" style="margin-top: 90px!important;">
    <div class="row">
        <div class="col-12">
            <h1 class="display-4">Tableau de bord</h1>
            <p class="lead">Bienvenue sur le tableau de bord de l'application. Voici un aperçu des statistiques et des performances.</p>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mt-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Utilisateurs totaux</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['total_users'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sessions actives</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['active_users'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-desktop fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tentatives de connexion échouées</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['failed_logins'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Durée moyenne de session</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['average_session_duration'] ?? 0 ?> min</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des matchs -->
    <div class="row mt-4">
        <div class="col-12">
            <h2 class="h3 mb-3">Statistiques des matchs</h2>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des matchs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['match_stats']['total'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gamepad fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Matchs terminés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['match_stats']['completed'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Matchs en attente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $basicStats['match_stats']['pending'] ?? 0 ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Taux de complétion</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= $basicStats['match_stats']['total'] > 0 ? round(($basicStats['match_stats']['completed'] / $basicStats['match_stats']['total']) * 100) : 0 ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mt-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Croissance des utilisateurs</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Distribution des matchs</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="matchDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tentatives de connexion (24h)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="loginAttemptsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Activité par jour</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Challenges et Gagnants -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Challenges</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Challenge</th>
                                    <th>Nombre de matchs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($detailedStats['match_stats']['top_challenges'])): ?>
                                    <tr>
                                        <td colspan="2" class="text-center">Aucun challenge trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($detailedStats['match_stats']['top_challenges'] as $challenge): ?>
                                        <tr>
                                            <td><?= $challenge['nom'] ?></td>
                                            <td><?= $challenge['match_count'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Gagnants</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Participant</th>
                                    <th>Victoires</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($detailedStats['match_stats']['top_winners'])): ?>
                                    <tr>
                                        <td colspan="2" class="text-center">Aucun gagnant trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($detailedStats['match_stats']['top_winners'] as $winner): ?>
                                        <tr>
                                            <td><?= $winner['participant'] ?></td>
                                            <td><?= $winner['wins'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matchs récents -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Matchs récents</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Challenge</th>
                                    <th>Participant 1</th>
                                    <th>Participant 2</th>
                                    <th>Gagnant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($detailedStats['match_stats']['recent_matches'])): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun match récent trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($detailedStats['match_stats']['recent_matches'] as $match): 
                                        $p1Name = $match['p1_prenom'] . ' ' . $match['p1_nom'];
                                        $p2Name = isset($match['p2_prenom']) ? $match['p2_prenom'] . ' ' . $match['p2_nom'] : 'N/A';
                                        $statusClass = $match['statut'] == 1 ? 'bg-success' : 'bg-warning';
                                        $statusText = $match['statut'] == 1 ? 'Terminé' : 'En attente';
                                    ?>
                                        <tr>
                                            <td><?= $match['id'] ?></td>
                                            <td><?= $match['challenge'] ?></td>
                                            <td><?= $p1Name ?></td>
                                            <td><?= $p2Name ?></td>
                                            <td><?= $match['gagnant'] ?></td>
                                            <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique de croissance des utilisateurs
    var userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    var userGrowthChart = new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($chartData['userGrowth']['dates']) ?>,
            datasets: [{
                label: 'Nouveaux utilisateurs',
                data: <?= json_encode($chartData['userGrowth']['counts']) ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des tentatives de connexion
    var loginAttemptsCtx = document.getElementById('loginAttemptsChart').getContext('2d');
    var loginAttemptsChart = new Chart(loginAttemptsCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartData['loginAttempts']['hours']) ?>,
            datasets: [
                {
                    label: 'Réussies',
                    data: <?= json_encode($chartData['loginAttempts']['successful']) ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)'
                },
                {
                    label: 'Échouées',
                    data: <?= json_encode($chartData['loginAttempts']['failed']) ?>,
                    backgroundColor: 'rgba(220, 53, 69, 0.8)'
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique d'activité des utilisateurs
    var userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    var userActivityChart = new Chart(userActivityCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartData['userActivity']['days']) ?>,
            datasets: [{
                label: 'Activité',
                data: <?= json_encode($chartData['userActivity']['activity']) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.8)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique de distribution des matchs
    var matchDistributionCtx = document.getElementById('matchDistributionChart').getContext('2d');
    var matchDistributionData = {
        labels: ['Terminés', 'En attente'],
        datasets: [{
            data: [<?= $basicStats['match_stats']['completed'] ?? 0 ?>, <?= $basicStats['match_stats']['pending'] ?? 0 ?>],
            backgroundColor: ['rgba(40, 167, 69, 0.8)', 'rgba(255, 193, 7, 0.8)']
        }]
    };
    var matchDistributionChart = new Chart(matchDistributionCtx, {
        type: 'doughnut',
        data: matchDistributionData,
        options: {
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>