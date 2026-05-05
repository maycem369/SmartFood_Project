<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/FaceId.php';
require_once __DIR__ . '/../models/Profil.php';

class FaceIdController {
    private $db;

    public function __construct() {
        $database   = new Database();
        $this->db   = $database->getConnection();
    }

    // ═══════════════════════════════════════════════════════
    // HELPERS PRIVÉS
    // ═══════════════════════════════════════════════════════

    private function requireAuth(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonError('Non authentifié.', 401);
        }
    }

    private function jsonOk(array $data): void {
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => true] + $data);
        exit;
    }

    private function jsonError(string $message, int $code = 400): void {
        ob_end_clean();
        header('Content-Type: application/json');
        http_response_code($code);
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }

    // ── Distance euclidienne entre deux descripteurs 128D ──
    private function euclideanDistance(array $d1, array $d2): float {
        if (count($d1) !== count($d2)) return 999.0;
        $sum = 0.0;
        foreach ($d1 as $i => $v) {
            $diff  = $v - $d2[$i];
            $sum  += $diff * $diff;
        }
        return sqrt($sum);
    }

    // ═══════════════════════════════════════════════════════
    // MÉTHODES BDD — utilisent FaceId comme DTO
    // ═══════════════════════════════════════════════════════

    // Sauvegarde ou mise à jour du descripteur (UPSERT)
    private function saveDescriptor(int $userId, string $jsonDescriptor): bool {
        $faceId = new FaceId($this->db);
        $faceId->setIdUser($userId);
        $faceId->setDescriptor($jsonDescriptor);

        $query = "INSERT INTO " . $faceId->getTable() . " (id_user, descriptor)
                  VALUES (:id_user, :descriptor)
                  ON DUPLICATE KEY UPDATE descriptor = :descriptor2, updated_at = NOW()";
        $stmt  = $this->db->prepare($query);
        $stmt->bindParam(':id_user',     $faceId->getIdUser(), PDO::PARAM_INT);
        $stmt->bindParam(':descriptor',  $faceId->getDescriptor());
        $stmt->bindParam(':descriptor2', $faceId->getDescriptor());
        return $stmt->execute();
    }

    // Vérifie si l'utilisateur a un Face ID enregistré
    private function hasFaceId(int $userId): bool {
        $faceId = new FaceId($this->db);
        $query  = "SELECT id FROM " . $faceId->getTable() . " WHERE id_user = :id_user";
        $stmt   = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Récupère le descripteur d'un utilisateur
    private function getDescriptorByUserId(int $userId): ?FaceId {
        $faceId = new FaceId($this->db);
        $query  = "SELECT * FROM " . $faceId->getTable() . " WHERE id_user = :id_user";
        $stmt   = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $faceId->setId($row['id']);
        $faceId->setIdUser($row['id_user']);
        $faceId->setDescriptor($row['descriptor']);
        $faceId->setCreatedAt($row['created_at']);
        $faceId->setUpdatedAt($row['updated_at']);
        return $faceId;
    }

    // Récupère tous les descripteurs (pour comparaison login)
    private function getAllDescriptors(): array {
        $faceId = new FaceId($this->db);
        $query  = "SELECT fd.id_user, fd.descriptor,
                          u.nom, u.prenom, u.email, u.role
                   FROM " . $faceId->getTable() . " fd
                   JOIN utilisateur u ON fd.id_user = u.idUser
                   WHERE u.statut = 'actif'";
        $stmt   = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Supprime le Face ID d'un utilisateur
    private function deleteDescriptor(int $userId): bool {
        $faceId = new FaceId($this->db);
        $query  = "DELETE FROM " . $faceId->getTable() . " WHERE id_user = :id_user";
        $stmt   = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Trouve l'utilisateur dont le descripteur est le plus proche
    private function findMatchingUser(array $inputDescriptor, float $threshold = 0.5): ?array {
        $allRows    = $this->getAllDescriptors();
        $bestMatch  = null;
        $bestDist   = $threshold;

        foreach ($allRows as $row) {
            $stored = json_decode($row['descriptor'], true);
            if (!$stored || count($stored) !== 128) continue;
            $dist = $this->euclideanDistance($inputDescriptor, $stored);
            if ($dist < $bestDist) {
                $bestDist  = $dist;
                $bestMatch = $row;
            }
        }
        return $bestMatch;
    }

    // ═══════════════════════════════════════════════════════
    // ACTIONS PUBLIQUES (appelées par index.php)
    // ═══════════════════════════════════════════════════════

    // GET — page d'enregistrement du visage
    public function showRegister(): void {
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        $hasFaceId = $this->hasFaceId((int)$_SESSION['user_id']);
        include __DIR__ . '/../views/Frontoffice/register_face.php';
    }

    // POST JSON — enregistrement du descripteur facial
    public function register(): void {
        $this->requireAuth();

        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body['descriptor']) || !is_array($body['descriptor'])) {
            $this->jsonError('Descripteur facial manquant ou invalide.');
        }
        if (count($body['descriptor']) !== 128) {
            $this->jsonError('Descripteur invalide : 128 valeurs attendues.');
        }

        // Normalise en tableau de floats puis encode en JSON
        $descriptorArray = array_map('floatval', $body['descriptor']);
        $jsonDescriptor  = json_encode($descriptorArray);

        if ($this->saveDescriptor((int)$_SESSION['user_id'], $jsonDescriptor)) {
            $this->jsonOk(['message' => 'Face ID enregistré avec succès !']);
        } else {
            $this->jsonError('Erreur lors de la sauvegarde.');
        }
    }

    // POST JSON — authentification par reconnaissance faciale
    public function login(): void {
        ob_start(); // Capture toute sortie HTML parasite (erreurs PHP)

        $body = json_decode(file_get_contents('php://input'), true);

        if (!isset($body['descriptor']) || !is_array($body['descriptor'])) {
            $this->jsonError('Descripteur facial manquant.');
        }
        if (count($body['descriptor']) !== 128) {
            $this->jsonError('Descripteur invalide.');
        }

        $inputDescriptor = array_map('floatval', $body['descriptor']);
        $match           = $this->findMatchingUser($inputDescriptor, 0.6);

        if (!$match) {
            $this->jsonError('Visage non reconnu. Utilisez votre mot de passe.', 401);
        }

        // Ouvre la session
        $_SESSION['user_id']     = $match['id_user'];
        $_SESSION['user_role']   = $match['role'];
        $_SESSION['user_prenom'] = $match['prenom'];
        $_SESSION['user_nom']    = $match['nom'];
        $_SESSION['user_email']  = $match['email'];

        // Photo de profil — requête directe (Profil n'a pas de méthode getByUserId)
        $stmtPhoto = $this->db->prepare(
            "SELECT photo FROM profil WHERE id_user = :id LIMIT 1"
        );
        $stmtPhoto->bindParam(':id', $match['id_user'], PDO::PARAM_INT);
        $stmtPhoto->execute();
        $photoRow = $stmtPhoto->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_photo'] = ($photoRow && $photoRow['photo'])
            ? $photoRow['photo']
            : 'default-avatar.png';

        $redirect = ($match['role'] === 'admin') ? 'admin_dashboard' : 'dashboard_user';
        $this->jsonOk([
            'message'  => 'Authentification réussie !',
            'redirect' => 'index.php?action=' . $redirect,
            'prenom'   => $match['prenom'],
        ]);
    }

    // POST JSON — suppression du Face ID
    public function delete(): void {
        $this->requireAuth();
        if ($this->deleteDescriptor((int)$_SESSION['user_id'])) {
            $this->jsonOk(['message' => 'Face ID supprimé.']);
        } else {
            $this->jsonError('Erreur lors de la suppression.');
        }
    }

    // GET JSON — statut Face ID de l'utilisateur connecté
    public function status(): void {
        $this->requireAuth();
        $registered = $this->hasFaceId((int)$_SESSION['user_id']);
        $this->jsonOk(['registered' => $registered]);
    }
}
?>