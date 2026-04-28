<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Profil.php';
require_once __DIR__ . '/../models/User.php';

class ProfilController {
    private $profil;
    private $user;
    private $db;

    public function __construct() {
        $database     = new Database();
        $this->db     = $database->getConnection();
        $this->profil = new Profil($this->db);
        $this->user   = new User($this->db);
    }

    // ── Guard ─────────────────────────────────────────────────────────────────

    private function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
    }

    // =========================================================================
    // PROFIL — DB OPERATIONS
    // =========================================================================

    /** Fetch profil + user name/email by user id. */
    private function fetchProfilByUserId(int $id_user): ?array {
        $query = "SELECT p.*, u.nom, u.prenom, u.email
                  FROM profil p
                  JOIN utilisateur u ON p.id_user = u.idUser
                  WHERE p.id_user = :id_user
                  LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Fetch a single user row by primary key. */
    private function fetchUser(int $id): ?array {
        $query = "SELECT * FROM utilisateur WHERE idUser = :id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // =========================================================================
    // BUSINESS LOGIC — IMC
    // =========================================================================

    /** Calculate BMI from weight (kg) and height (cm). */
    private function calculIMC(float $poids, float $taille): ?float {
        if (!$poids || !$taille || $taille == 0) return null;
        $tailleM = $taille / 100;
        return round($poids / ($tailleM * $tailleM), 1);
    }

    /** Return the French label for a given BMI value. */
    private function getImcLabel(float $imc): string {
        if      ($imc < 18.5) return "Insuffisance pondérale";
        elseif  ($imc < 25)   return "Normal";
        elseif  ($imc < 30)   return "Surpoids";
        else                  return "Obésité";
    }

    // =========================================================================
    // ACTIONS
    // =========================================================================

    public function showProfil(): void {
        $this->requireAuth();
        $profilData = $this->fetchProfilByUserId($_SESSION['user_id']);
        $userData   = $this->fetchUser($_SESSION['user_id']);
        $imc        = null;
        $imcLabel   = null;
        if (!empty($profilData['poids']) && !empty($profilData['taille'])) {
            $imc      = $this->calculIMC($profilData['poids'], $profilData['taille']);
            $imcLabel = $this->getImcLabel($imc);
        }
        include __DIR__ . '/../views/Frontoffice/profil.php';
    }

    public function showEditProfil(): void {
        $this->requireAuth();
        $profilData = $this->fetchProfilByUserId($_SESSION['user_id']);
        $userData   = $this->fetchUser($_SESSION['user_id']);
        include __DIR__ . '/../views/Frontoffice/edit_profile.php';
    }

    public function showChangePassword(): void {
        $this->requireAuth();
        include __DIR__ . '/../views/Frontoffice/change_password.php';
    }
}
?>