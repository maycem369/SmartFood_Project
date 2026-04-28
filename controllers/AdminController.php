<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/StatsModel.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Profil.php';

class AdminController {
    private $stats;
    private $user;
    private $profil;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->stats = new StatsModel($this->db);
        $this->user  = new User($this->db);
        $this->profil = new Profil($this->db);
    }

    // ── Vérification accès admin ──────────────────────────────────────────────
    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function dashboard(): void {
        $this->requireAdmin();
        $totalUsers          = $this->stats->getTotalUsers();
        $totalRecettes       = $this->stats->getTotalRecettes();
        $totalIngredients    = $this->stats->getTotalIngredients();
        $totalSuggestions    = $this->stats->getTotalSuggestionsIA();
        $recettesParMois     = $this->stats->getRecettesParMois();
        $caloriesParMois     = $this->stats->getCaloriesMoyennesParMois();
        $derniersUtilisateurs = $this->stats->getDerniersUtilisateurs(5);
        $dernieresRecettes   = $this->stats->getDernieresRecettes(5);
        include __DIR__ . '/../views/Backoffice/dashboard_admin.php';
    }

    // ── Liste des utilisateurs ────────────────────────────────────────────────
    public function usersList(): void {
        $this->requireAdmin();
        $users = $this->user->readAll();
        include __DIR__ . '/../views/Backoffice/users_list.php';
    }

    // ── Détails d'un utilisateur ──────────────────────────────────────────────
    public function userDetails(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->user->readOneWithProfil($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        include __DIR__ . '/../views/Backoffice/user_details.php';
    }

    // ── Formulaire modification ───────────────────────────────────────────────
    public function editUser(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->user->readOne($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        include __DIR__ . '/../views/Backoffice/edit_user.php';
    }

    // ── Confirmation suppression ──────────────────────────────────────────────
    public function deleteUser(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->user->readOne($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            header("Location: index.php?action=users_list");
            exit();
        }
        include __DIR__ . '/../views/Backoffice/delete_user.php';
    }

    // ── Formulaire ajout (GET) ────────────────────────────────────────────────
    public function showAddUser(): void {
        $this->requireAdmin();
        include __DIR__ . '/../views/Backoffice/add_user.php';
    }

    // ── Exécution suppression ─────────────────────────────────────────────────
    public function deleteUserConfirm(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
        } elseif ($this->user->delete($id)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header("Location: index.php?action=users_list");
        exit();
    }
}
?>