<?php
// controller/NutritionController.php — CRUD Nutrition (structure réelle: idNutrition, id_user, id_regime, date, poids, calories, proteines, glucides, lipides)
require_once __DIR__ . '/../model/Nutrition.php';
require_once __DIR__ . '/../config/config.php';

class NutritionController {

    /**
     * READ — Récupérer toutes les fiches nutrition
     */
    public function listAllNutrition(): array {
        // Essai avec jointure utilisateur
        try {
            $db = config::getConnexion();
            // D'abord vérifier les colonnes disponibles
            $cols = $db->query("SHOW COLUMNS FROM nutrition")->fetchAll(PDO::FETCH_COLUMN);
            
            $hasIdUser = in_array('id_user', $cols);
            
            if ($hasIdUser) {
                $sql = "SELECT n.*, u.nom as nom_user, u.prenom as prenom_user
                        FROM nutrition n
                        LEFT JOIN utilisateur u ON n.id_user = u.idUser
                        ORDER BY n.idNutrition DESC";
            } else {
                $sql = "SELECT * FROM nutrition ORDER BY idNutrition DESC";
            }
            
            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * READ — Récupérer une fiche nutrition par ID
     */
    public function getNutritionById(int $id) {
        $sql = "SELECT * FROM nutrition WHERE idNutrition = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * UPDATE — Mettre à jour une fiche nutrition
     */
    public function updateNutrition(int $id, $poids, $calories, $proteines, $glucides, $lipides): array {
        $errors = [];

        if (trim($calories) === '' || !is_numeric($calories) || $calories < 0)
            $errors[] = "Les calories doivent être un nombre positif.";
        if (trim($proteines) === '' || !is_numeric($proteines) || $proteines < 0)
            $errors[] = "Les protéines doivent être un nombre positif.";
        if (trim($glucides) === '' || !is_numeric($glucides) || $glucides < 0)
            $errors[] = "Les glucides doivent être un nombre positif.";
        if (trim($lipides) === '' || !is_numeric($lipides) || $lipides < 0)
            $errors[] = "Les lipides doivent être un nombre positif.";

        if (!empty($errors)) return ['success' => false, 'errors' => $errors];

        $sql = "UPDATE nutrition SET poids = :poids, calories = :calories, proteines = :proteines,
                    glucides = :glucides, lipides = :lipides WHERE idNutrition = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':poids', floatval($poids));
            $stmt->bindValue(':calories', floatval($calories));
            $stmt->bindValue(':proteines', floatval($proteines));
            $stmt->bindValue(':glucides', floatval($glucides));
            $stmt->bindValue(':lipides', floatval($lipides));
            $stmt->execute();
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'errors' => ["Erreur PDO: " . $e->getMessage()]];
        }
    }

    /**
     * DELETE — Supprimer une fiche nutrition
     */
    public function deleteNutrition(int $id): bool {
        $sql = "DELETE FROM nutrition WHERE idNutrition = :id";
        try {
            $db = config::getConnexion();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    /**
     * STATS — Moyennes globales (colonnes détectées dynamiquement)
     */
    public function getStats(): array {
        try {
            $db   = config::getConnexion();
            $cols = $db->query("SHOW COLUMNS FROM nutrition")->fetchAll(PDO::FETCH_COLUMN);

            $selects = ['COUNT(*) as total'];
            if (in_array('calories',  $cols)) $selects[] = 'AVG(calories)  as avg_cals';
            if (in_array('proteines', $cols)) $selects[] = 'AVG(proteines) as avg_prot';
            if (in_array('glucides',  $cols)) $selects[] = 'AVG(glucides)  as avg_gluc';
            if (in_array('lipides',   $cols)) $selects[] = 'AVG(lipides)   as avg_lip';

            $sql  = "SELECT " . implode(', ', $selects) . " FROM nutrition";
            $row  = $db->query($sql)->fetch();
            return array_merge(['total'=>0,'avg_cals'=>0,'avg_prot'=>0,'avg_gluc'=>0,'avg_lip'=>0], $row ?: []);
        } catch (Exception $e) {
            return ['total' => 0, 'avg_cals' => 0, 'avg_prot' => 0, 'avg_gluc' => 0, 'avg_lip' => 0];
        }
    }
}

// ============================================================
// ROUTING
// ============================================================

$controller = new NutritionController();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'update':
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['idNutrition'] ?? 0);
            $result = $controller->updateNutrition(
                $id,
                $_POST['poids']     ?? 0,
                $_POST['calories']  ?? '',
                $_POST['proteines'] ?? '',
                $_POST['glucides']  ?? '',
                $_POST['lipides']   ?? ''
            );
            if ($result['success']) {
                header('Location: ../../../index.php?action=nutrition_admin&tab=nutrition&success=updated');
            } else {
                $_SESSION['nutrition_errors'] = $result['errors'];
                header('Location: ../../../index.php?action=nutrition_admin&tab=nutrition&id=' . $id . '&edit=1');
            }
            exit;
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $controller->deleteNutrition($id);
            header('Location: ../../../index.php?action=nutrition_admin&tab=nutrition&success=deleted');
            exit;
        }
        break;
}
?>
