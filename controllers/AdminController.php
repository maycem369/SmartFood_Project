<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/StatsModel.php';

class AdminController {
    private $stats;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->stats = new StatsModel($this->db);
    }

    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
        $totalUsers = $this->stats->getTotalUsers();
        $totalRecettes = $this->stats->getTotalRecettes();
        $totalIngredients = $this->stats->getTotalIngredients();
        $totalSuggestions = $this->stats->getTotalSuggestionsIA();
        $recettesParMois = $this->stats->getRecettesParMois();
        $caloriesParMois = $this->stats->getCaloriesMoyennesParMois();
        $derniersUtilisateurs = $this->stats->getDerniersUtilisateurs(5);
        $dernieresRecettes = $this->stats->getDernieresRecettes(5);
        include __DIR__ . '/../views/Backoffice/dashboard_admin.php';
    }
}
?>