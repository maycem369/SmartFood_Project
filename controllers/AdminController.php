<?php
require_once __DIR__ . '/../config/Database.php';

class AdminController {
    private $db;

    public function __construct() {
        $database     = new Database();
        $this->db     = $database->getConnection();
    }

    // ── Guard ─────────────────────────────────────────────────────────────────

    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // =========================================================================
    // STATS — DB OPERATIONS
    // =========================================================================

    private function getTotalUsers(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateur");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    private function getTotalRecettes(): int {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM recette");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    private function getTotalIngredients(): int {
        return 320; // valeur exemple — remplacer par une vraie requête si nécessaire
    }

    private function getTotalSuggestionsIA(): int {
        return 1200; // valeur exemple
    }

    private function getRecettesParMois(): array {
        $query = "SELECT DATE_FORMAT(date_creation, '%Y-%m') as mois, COUNT(*) as nombre
                  FROM recette
                  WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY mois
                  ORDER BY mois ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCaloriesMoyennesParMois(): array {
        $query = "SELECT DATE_FORMAT(date_creation, '%Y-%m') as mois,
                         AVG(caloriesTotales) as moyenne_calories
                  FROM recette
                  WHERE date_creation >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY mois
                  ORDER BY mois ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getDerniersUtilisateurs(int $limit = 5): array {
        $query = "SELECT idUser, nom, prenom, email, role, date_creation
                  FROM utilisateur
                  ORDER BY date_creation DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getDernieresRecettes(int $limit = 5): array {
        $query = "SELECT r.idRecette, r.nom, r.caloriesTotales, r.date_creation,
                         u.nom as auteur_nom, u.prenom as auteur_prenom
                  FROM recette r
                  LEFT JOIN utilisateur u ON r.id_user = u.idUser
                  ORDER BY r.date_creation DESC
                  LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getGoalDistribution(): array {
        $query = "SELECT objectif, COUNT(*) as nb FROM profil GROUP BY objectif";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentActivities(int $limit = 10): array {
        $query = "SELECT * FROM activity_log ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================================
    // USER — DB OPERATIONS
    // =========================================================================

    private function fetchUser(int $id): ?array {
        $query = "SELECT * FROM utilisateur WHERE idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    private function fetchAllUsers(): array {
        $query = "SELECT * FROM utilisateur ORDER BY idUser DESC";
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchUserWithProfil(int $id): ?array {
        $query = "SELECT u.*, p.age, p.sexe, p.poids, p.taille,
                         p.objectif, p.niveau_activite, p.allergies
                  FROM utilisateur u
                  LEFT JOIN profil p ON u.idUser = p.id_user
                  WHERE u.idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // 🔁 Renommée pour éviter le conflit de nom
    private function deleteUserById(int $id): bool {
        $query = "DELETE FROM utilisateur WHERE idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // =========================================================================
    // ACTIONS
    // =========================================================================

    public function dashboard(): void {
        $this->requireAdmin();
        $totalUsers           = $this->getTotalUsers();
        $totalRecettes        = $this->getTotalRecettes();
        $totalIngredients     = $this->getTotalIngredients();
        $totalSuggestions     = $this->getTotalSuggestionsIA();
        $recettesParMois      = $this->getRecettesParMois();
        $caloriesParMois      = $this->getCaloriesMoyennesParMois();
        $derniersUtilisateurs = $this->getDerniersUtilisateurs(5);
        $dernieresRecettes    = $this->getDernieresRecettes(5);
        $goalDistribution     = $this->getGoalDistribution();
        $recentActivities     = $this->getRecentActivities(8);
        include __DIR__ . '/../views/Backoffice/dashboard_admin.php';
    }

    public function usersList(): void {
        $this->requireAdmin();
        $users = $this->fetchAllUsers();
        include __DIR__ . '/../views/Backoffice/users_list.php';
    }

    public function userDetails(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->fetchUserWithProfil($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        include __DIR__ . '/../views/Backoffice/user_details.php';
    }

    public function editUser(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->fetchUser($id);
        if (!$user) {
            $_SESSION['error'] = "Utilisateur non trouvé.";
            header("Location: index.php?action=users_list");
            exit();
        }
        include __DIR__ . '/../views/Backoffice/edit_user.php';
    }

    public function deleteUser(): void {
        $this->requireAdmin();
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->fetchUser($id);
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

    public function showAddUser(): void {
        $this->requireAdmin();
        include __DIR__ . '/../views/Backoffice/add_user.php';
    }

    public function configuration(): void {
        $this->requireAdmin();
        $id = (int)$_SESSION['user_id'];
        $user = $this->fetchUserWithProfil($id);
        $hasFaceId = !empty($user['face_descriptor']);
        include __DIR__ . '/../views/Backoffice/configuration.php';
    }

    public function deleteUserConfirm(): void {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
        } else {
            if ($this->deleteUserById($id)) {
                $_SESSION['success'] = "Utilisateur supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression.";
            }
        }
        header("Location: index.php?action=users_list");
        exit();
    }
}
?>