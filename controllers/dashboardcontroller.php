<?php 
declare(strict_types=1);

require_once("models/Stats.php");

class DashboardController {
    private $stats;

    public function __construct() {
        $this->stats = new Stats();
    }

    public function index() {
        if (!isset($_SESSION["user"])) {
            header("Location:?page=home");
            exit();
        }

        $basicStats = $this->stats->getBasicStats();
        $detailedStats = $this->stats->getDetailedStats();

        // Préparer les données pour les graphiques
        $chartData = [
            'userGrowth' => $this->prepareUserGrowthData(),
            'loginAttempts' => $this->prepareLoginAttemptsData(),
            'userActivity' => $this->prepareUserActivityData()
        ];

        require_once("views/includes/entete.php");
        require_once("views/includes/navbar.php");
        require_once("views/dashboard.php");
        require_once("views/includes/footer.php");
    }

    private function prepareUserGrowthData(): array {
        // Données pour le graphique de croissance des utilisateurs
        $dates = [];
        $counts = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $dates[] = $date;
            $counts[] = rand(10, 100); // À remplacer par les vraies données
        }
        return ['dates' => $dates, 'counts' => $counts];
    }

    private function prepareLoginAttemptsData(): array {
        // Données pour le graphique des tentatives de connexion
        $hours = [];
        $successful = [];
        $failed = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = date('H', strtotime("-$i hours"));
            $hours[] = $hour;
            $successful[] = rand(5, 30); // À remplacer par les vraies données
            $failed[] = rand(0, 10); // À remplacer par les vraies données
        }
        return [
            'hours' => $hours,
            'successful' => $successful,
            'failed' => $failed
        ];
    }

    private function prepareUserActivityData(): array {
        // Données pour le graphique d'activité des utilisateurs
        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $activity = array_map(function() {
            return rand(50, 200);
        }, $days);
        return ['days' => $days, 'activity' => $activity];
    }
}

// Instancier et exécuter le contrôleur
$controller = new DashboardController();
$controller->index();